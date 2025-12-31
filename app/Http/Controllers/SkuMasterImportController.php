<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

use App\Models\ItemImportLog;
use App\Models\ItemImportDataLog;
use App\Models\ItemImportErrorLog;
use App\Models\MItem; // For checking if item exists
use App\Models\MSKU; // Changed to MSKU

class SkuMasterImportController extends Controller
{
    // Show import page
    public function index()
    {
        return view('import.sku_master_import');
    }

    public function showPreview(Request $request)
    {
        // Retrieve data from session
        $previewData = session('sku_import_preview_data', []);
        $fileName = session('sku_import_file_name', '');
        $validationErrors = session('sku_import_errors', []);
        $fileType = session('sku_import_file_type', 'excel');

        $totalRecords = session('sku_import_total_records', count($previewData));
        $errorCount   = session('sku_import_error_count', 0);

        if (empty($previewData)) {
            return redirect()->route('sku-import.index')
                ->with('error', 'No preview data found. Please upload a file first.');
        }

        // Paginate the session data
        $currentPage = $request->input('page', 1);
        $perPage = 15;
        $offset = ($currentPage - 1) * $perPage;

        $paginatedData = array_slice($previewData, $offset, $perPage);

        $previewPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedData,
            count($previewData),
            $perPage,
            $currentPage,
            ['path' => route('sku-import.preview.show'), 'query' => $request->query()]
        );

        return view('import.sku_preview', [
            'previewData'  => $previewPaginator,
            'fileName'     => $fileName,
            'fileType'     => $fileType,
            'totalRecords' => $totalRecords,
            'errorCount'   => $errorCount
        ]);
    }



    private function validateSkuRows(array $rows, string $fileName, string $fileType)
    {
        // Build XML
        $xml = new \SimpleXMLElement('<Skus/>');

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $row = array_pad($row, 8, null);

            $sku = $xml->addChild('Sku');
            $sku->addChild('RowNumber', $rowNumber);
            $sku->addChild('ItemAdminCode', trim($row[0] ?? ''));
            $sku->addChild('ItemCode', trim($row[1] ?? ''));
            $sku->addChild('SizeName', trim($row[2] ?? ''));
            $sku->addChild('ColorName', trim($row[3] ?? ''));
            $sku->addChild('SizeCode', trim($row[4] ?? ''));
            $sku->addChild('ColorCode', trim($row[5] ?? ''));
            $sku->addChild('JanCode', $this->cleanJanCD($row[6] ?? ''));
            $sku->addChild('Quantity', $this->cleanNumber($row[7] ?? 0));
        }

        // Call validation SP
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare('EXEC sp_Validate_M_SKU_XML ?');
        $stmt->execute([$xml->asXML()]);

        $rowsResult = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $stmt->nextRowset();
        $summary = $stmt->fetch(\PDO::FETCH_OBJ);

        // Prepare preview data
        $previewData = [];
        foreach ($rowsResult as $row) {
            $previewData[] = [
                'row_number'      => $row->RowNumber,
                'item_admin_code' => $row->Item_Admin_Code,
                'item_code'       => $row->Item_Code,
                'size_code'       => $row->Size_Code,
                'size_name'       => $row->Size_Name,
                'color_code'      => $row->Color_Code,
                'color_name'      => $row->Color_Name,
                'jancd'           => $row->JanCode,
                'quantity'        => $row->Quantity,
                'Status_Message'  => $row->Status_Message,
                'Is_Valid'        => $row->Is_Valid
            ];
        }

        // Store session (same as Excel)
        Session::put('sku_import_preview_data', $previewData);
        Session::put('sku_import_file_name', $fileName);
        Session::put('sku_import_file_type', $fileType);
        Session::put('sku_import_total_records', $summary->Total_Records ?? 0);
        Session::put('sku_import_error_count', $summary->Error_Records ?? 0);
    }


    // Process Excel preview (handle both xls and xlsx)
    public function processPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $expectedHeader = [
                'Item Admin Code',
                '商品番号',
                'サイズ名 (項目選択肢別在庫用横軸選択肢)',
                'カラー名 (項目選択肢別在庫用縦軸選択肢)',
                'サイズコード',
                'カラーコード',
                'JANコード',
                '在庫数'
            ];

            $header = array_map('trim', $rows[0] ?? []);

            if ($header !== $expectedHeader) {
                return redirect()->route('sku-import.index')
                    ->with('error', 'Invalid Excel header format. Please check the column names.');
            }

            $rows = array_filter(
                $rows,
                fn($row) =>
                !empty(array_filter($row, fn($cell) => trim((string)$cell) !== ''))
            );

            array_shift($rows); // remove header

            $this->validateSkuRows($rows, $fileName, 'excel');

            return redirect()->route('sku-import.preview.show');
        } catch (\Exception $e) {
            return redirect()->route('sku-import.index')
                ->with('error', $e->getMessage());
        }
    }

    private function normalizeCode($value)
    {
        if ($value === null) return '';

        // keep digits only
        $digits = preg_replace('/\D/', '', (string)$value);

        if ($digits === '') return '';

        // limit to 4 digits (remove excess)
        if (strlen($digits) > 4) {
            $digits = substr($digits, 0, 4);
        }

        // pad with leading zeros → always 4 digits
        return str_pad($digits, 4, '0', STR_PAD_LEFT);
    }

    private function cleanJanCDForValidation($value)
    {
        if ($value === null || trim($value) === '') return '';

        // Handle scientific notation
        if (is_numeric($value) && (stripos($value, 'e') !== false)) {
            $value = sprintf('%.0f', (float)$value);
        } elseif (is_float($value) || is_double($value)) {
            $value = sprintf('%.0f', $value);
        }

        // Remove non-digits
        return preg_replace('/[^\d]/', '', (string)$value);
    }
    // Process CSV preview
    public function processCsvPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $rows = $this->readCsvFile($file->getRealPath());

            $rows = array_filter(
                $rows,
                fn($row) =>
                !empty(array_filter($row, fn($cell) => trim((string)$cell) !== ''))
            );

            array_shift($rows); // remove header

            // SAME logic as Excel
            $this->validateSkuRows($rows, $fileName, 'csv');

            return redirect()->route('sku-import.preview.show');
        } catch (\Exception $e) {
            return redirect()->route('sku-import.index')
                ->with('error', $e->getMessage());
        }
    }

    // Confirm Import (common for both Excel and CSV)
    public function confirmImport()
    {
        $fileType = session('sku_import_file_type', 'excel');
        DB::beginTransaction();
        try {
            $previewData = session('sku_import_preview_data', []);
            $errorCount = session('sku_import_error_count', 0);
            $fileName = session('sku_import_file_name', 'Unknown');
            $importedBy = Auth::check() ? Auth::user()->name : 'system';

            if (empty($previewData)) throw new \Exception('No data found.');

            // 1) Insert import log
            $importLog = ItemImportLog::create([
                'Import_Type' => 2, // SKU Import Type
                'Record_Count' => count($previewData),
                'Error_Count' => $errorCount,
                'Imported_By' => $importedBy,
                'Imported_Date' => now()
            ]);
            $importLogId = $importLog->ImportLog_ID;

            // 2) Prepare valid XML for Item_Import_DataLog
            $dataLogXml = new \SimpleXMLElement('<Items/>');
            // 3) Prepare valid XML for M_SKU
            // $mSkuXml = new \SimpleXMLElement('<Items/>');

            $validCount = 0;
            $itemCodesToDelete = []; // Track item codes for deletion

            $mSkuXml = new \SimpleXMLElement('<Skus/>'); // match SP
            $errorXml = new \SimpleXMLElement('<Items/>');
            $validCount = 0;

            foreach ($previewData as $row) {
                if (!isset($row['Is_Valid']) || $row['Is_Valid'] != 1) {
                    // Error row → add to error XML
                    $item = $errorXml->addChild('Item');
                    $item->addChild('ImportLog_ID', $importLogId);
                    $item->addChild('Item_Code', $row['item_code'] ?? '');
                    $item->addChild('Item_Name', $row['item_name'] ?? '');
                    $item->addChild('JanCD', $row['jancd'] ?? '');
                    $item->addChild('MakerName', $row['maker_name'] ?? '');
                    $item->addChild('Memo', $row['memo'] ?? '');
                    $item->addChild('ListPrice', $row['list_price'] ?? 0);
                    $item->addChild('SalePrice', $row['sale_price'] ?? 0);
                    $item->addChild('Size_Name', $row['size_name'] ?? '');
                    $item->addChild('Color_Name', $row['color_name'] ?? '');
                    $item->addChild('Size_Code', $row['size_code'] ?? '');
                    $item->addChild('Color_Code', $row['color_code'] ?? '');
                    $item->addChild('JanCode', $row['jancd'] ?? '');
                    $item->addChild('Quantity', $row['quantity'] ?? 0);
                    $item->addChild('Error_Msg', $row['Status_Message'] ?? '');

                    $errorRows[] = $row; // add to array
                    continue;
                }

                // Valid row → add to M_SKU XML
                $mSkuItem = $mSkuXml->addChild('Item');
                $mSkuItem->addChild('RowNumber', $row['row_number'] ?? 0);
                $mSkuItem->addChild('ItemAdminCode', $row['item_admin_code'] ?? '');
                $mSkuItem->addChild('ItemCode', $row['item_code'] ?? '');
                $mSkuItem->addChild('SizeCode', $row['size_code'] ?? '');
                $mSkuItem->addChild('SizeName', $row['size_name'] ?? '');
                $mSkuItem->addChild('ColorCode', $row['color_code'] ?? '');
                $mSkuItem->addChild('ColorName', $row['color_name'] ?? '');
                $mSkuItem->addChild('JanCode', $row['jancd'] ?? '');
                $mSkuItem->addChild('Quantity', $row['quantity'] ?? 0);
                $mSkuItem->addChild('CreatedDate', now()->format('Y-m-d H:i:s'));
                $mSkuItem->addChild('UpdatedDate', now()->format('Y-m-d H:i:s'));
                $mSkuItem->addChild('Createdby', $importedBy);
                $mSkuItem->addChild('Updatedby', $importedBy);

                $dataLogItem = $dataLogXml->addChild('Item'); // Note: the SP expects '/Items/Item'
                $dataLogItem->addChild('ImportLog_ID', $importLogId); // required for your SP
                $dataLogItem->addChild('Item_Code', $row['item_code'] ?? '');
                $dataLogItem->addChild('Item_Name', $row['item_name'] ?? ''); // make sure item_name exists
                $dataLogItem->addChild('JanCD', $row['jancd'] ?? '');
                $dataLogItem->addChild('MakerName', $row['maker_name'] ?? '');
                $dataLogItem->addChild('Memo', $row['memo'] ?? '');
                $dataLogItem->addChild('ListPrice', $row['list_price'] ?? 0);
                $dataLogItem->addChild('SalePrice', $row['sale_price'] ?? 0);
                $dataLogItem->addChild('Size_Name', $row['size_name'] ?? '');
                $dataLogItem->addChild('Color_Name', $row['color_name'] ?? '');
                $dataLogItem->addChild('Size_Code', $row['size_code'] ?? '');
                $dataLogItem->addChild('Color_Code', $row['color_code'] ?? '');
                $dataLogItem->addChild('JanCode', $row['jancd'] ?? '');
                $dataLogItem->addChild('Quantity', $row['quantity'] ?? 0);

                $validCount++;
            }

            // dd($mSkuXml->asXML());
            if ($validCount > 0) {
                // dd($validCount, $mSkuXml->asXML);
                // dd('M_SKU XML: ' . $dataLogXml->asXML());
                DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_ItemImport_DataLog_XML @xml;", [$dataLogXml->asXML()]);

                // dd($validCount, $mSkuXml);
                DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_M_SKU_XML @xml;", [$mSkuXml->asXML()]);
            }

            if (count($errorRows) > 0) {
                // dd(count($errorRows),$errorXml->asXML());
                DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_ItemImport_ErrorLog_XML @xml;", [$errorXml->asXML()]);
            }

            DB::commit();

            Session::forget([
                'sku_import_preview_data',
                'sku_import_errors',
                'sku_import_file_name',
                'sku_import_file_type'
            ]);

            return redirect()->route('home')
                ->with('success', "SKU Import completed. Success: {$validCount}, Errors: " . count($errorRows) . " (File type: " . strtoupper($fileType) . ")");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SKU Import failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'SKU Import failed: ' . $e->getMessage());
        }
    }

    // Helper method to generate Item_AdminCode
    private function generateItemAdminCode($itemCode, $sizeCode, $colorCode)
    {
        return $itemCode . '-' . $sizeCode . '-' . $colorCode;
    }

    private function cleanJanCD($value)
    {
        if ($value === null || trim($value) === '') return '';

        $originalValue = $value;

        // Handle scientific notation (e.g., 1.21212E+12 or 1.21212e+12)
        if (is_numeric($value) && (stripos($value, 'e') !== false)) {
            try {
                // Convert scientific notation to full number string
                $value = sprintf('%.0f', (float)$value);
            } catch (\Exception $e) {
                return '';
            }
        } elseif (is_float($value) || is_double($value)) {
            // Handle float/double values that might have lost precision
            $value = sprintf('%.0f', $value);
        }

        // Remove any non-digit characters
        $cleaned = preg_replace('/[^\d]/', '', (string)$value);

        // Ensure it's exactly 13 digits
        if (strlen($cleaned) !== 13) {
            // If it's a valid number but wrong length, return original for error message
            return $originalValue;
        }

        return $cleaned;
    }

    // Helper method to read CSV file
    private function readCsvFile($path)
    {
        $rows = [];
        $file = fopen($path, 'r');

        if ($file) {
            // Detect delimiter
            $firstLine = fgets($file);
            rewind($file);

            $delimiter = $this->detectDelimiter($firstLine);

            while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
                // Remove BOM from first cell if present
                if (!empty($row[0])) {
                    $row[0] = $this->removeBom($row[0]);
                }
                $rows[] = $row;
            }

            fclose($file);
        }

        return $rows;
    }

    // Helper method to detect CSV delimiter
    private function detectDelimiter($firstLine)
    {
        $delimiters = [',', ';', "\t"];
        $counts = [];

        foreach ($delimiters as $delimiter) {
            $counts[$delimiter] = substr_count($firstLine, $delimiter);
        }

        $maxCount = max($counts);
        $detectedDelimiter = array_search($maxCount, $counts);

        return $detectedDelimiter ?: ',';
    }

    // Helper method to remove BOM (Byte Order Mark)
    private function removeBom($text)
    {
        $bom = pack('H*', 'EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    // Cancel preview
    public function cancelPreview()
    {
        Session::forget([
            'sku_import_preview_data',
            'sku_import_file_name',
            'sku_import_errors',
            'sku_import_file_type'
        ]);
        return redirect()->route('sku-import.index')
            ->with('info', 'Preview cancelled.');
    }

    // ---------------- Helper Methods ----------------
    private function cleanNumber($value)
    {
        if ($value === null || trim($value) === '') return 0;
        if (is_numeric($value)) return (int) $value;
        $cleaned = preg_replace('/[^\d]/', '', (string)$value);
        return $cleaned === '' ? 0 : (int)$cleaned;
    }

    // Added flexibility for Japanese headers (optional improvement)
    private function checkHeaders($actual, $expected)
    {
        if (count($actual) < count($expected)) {
            return ['valid' => false, 'message' => 'File has fewer columns than expected. Expected: ' . implode(', ', $expected)];
        }

        // Try to match headers exactly or with some flexibility for whitespace
        foreach ($expected as $i => $expectedHeader) {
            $actualHeader = trim($actual[$i] ?? '');
            $expectedHeaderTrimmed = trim($expectedHeader);

            // Remove extra spaces and check if they match
            $actualCleaned = preg_replace('/\s+/', ' ', $actualHeader);
            $expectedCleaned = preg_replace('/\s+/', ' ', $expectedHeaderTrimmed);

            if ($actualCleaned !== $expectedCleaned) {
                return ['valid' => false, 'message' => "Column " . ($i + 1) . " should be '{$expectedHeader}' but found '{$actualHeader}'"];
            }
        }
        return ['valid' => true];
    }

    // Optional: Direct import methods (without preview)
    public function importExcel(Request $request)
    {
        return $this->processPreview($request);
    }

    public function importCsv(Request $request)
    {
        return $this->processCsvPreview($request);
    }

    public function logs()
    {
        $logs = ItemImportLog::where('Import_Type', 2)
            ->orderBy('Imported_Date', 'desc')
            ->paginate(20);

        return view('sku-import.logs', compact('logs'));
    }
}
