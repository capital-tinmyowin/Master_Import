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
use App\Models\MItem;

class ItemMasterImportController extends Controller
{
    // Show import page
    public function index()
    {
        return view('import.item_master_import');
    }
    public function processPreview(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            // Read Excel file
            $path = $file->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Remove empty rows
            $rows = array_filter($rows, function ($row) {
                return !empty(array_filter($row, function ($cell) {
                    return $cell !== null && trim($cell) !== '';
                }));
            });

            // Remove header row
            $header = array_shift($rows);

            // Check headers
            $englishHeaders = ['Item_Code', 'Item_Name', 'JanCD', 'MakerName', 'Memo', 'ListPrice', 'SalePrice'];
            $japaneseHeaders = ['商品番号', '商品名', 'JANCD', 'メーカー名', '注記', '定価', '原価'];
            $headerCheck = $this->checkHeaders($header, $englishHeaders, $japaneseHeaders);

            if (!$headerCheck['valid']) {
                return redirect()->route('item-import.index')
                    ->with('error', $headerCheck['message']);
            }

            $isJapaneseFormat = $headerCheck['format'] === 'japanese';
            $previewData = [];
            $validationErrors = [];

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                // Ensure we have at least 7 columns
                $row = array_pad($row, 7, null);

                $itemData = [
                    'item_code' => $row[0] ?? null,
                    'itemname' => $row[1] ?? null,
                    'jancd' => $this->convertJancd($row[2] ?? null),
                    'makername' => $row[3] ?? null,
                    'memo' => $row[4] ?? null,
                    'listprice' => $this->cleanPrice($row[5] ?? 0),
                    'saleprice' => $this->cleanPrice($row[6] ?? 0),
                    'row_number' => $rowNumber
                ];

                // Validation rules
                $validator = Validator::make($itemData, [
                    'item_code' => [
                        'required',
                        'max:50',
                        function ($attribute, $value, $fail) {
                            if (!preg_match('/^[a-zA-Z0-9\-_.]+$/', $value)) {
                                $fail('Item Code can only contain letters, numbers, hyphen (-), underscore (_), and dot (.). No spaces or special characters allowed.');
                            }
                        },
                        function ($attribute, $value, $fail) use ($previewData, $rowIndex) {
                            if ($this->hasDuplicateItemCodesInCurrentImport($previewData, $rowIndex, $value)) {
                                $fail('Item Code "' . $value . '" appears more than once in this import file. Please remove duplicates.');
                            }
                        }
                    ],
                    'itemname'  => 'required|max:200',
                    'jancd'     => [
                        'required',
                        'digits:13',
                        function ($attribute, $value, $fail) {
                            if (!preg_match('/^\d+$/', $value)) {
                                $fail('JAN Code must contain only digits (0-9).');
                            }
                        }
                    ],
                    'makername' => 'required',
                    'listprice' => [
                        'required',
                        'numeric',
                        'min:0.01',
                        function ($attribute, $value, $fail) {
                            if (empty($value) || $value == 0) {
                                $fail('List Price cannot be blank or 0.');
                            }
                            if (!is_numeric($value)) {
                                $fail('List Price must be a valid number.');
                            }
                        }
                    ],
                    'saleprice' => [
                        'required',
                        'numeric',
                        'min:0.01',
                        function ($attribute, $value, $fail) {
                            if (empty($value) || $value == 0) {
                                $fail('Sale Price cannot be blank or 0.');
                            }
                            if (!is_numeric($value)) {
                                $fail('Sale Price must be a valid number.');
                            }
                        }
                    ],
                ], [
                    'listprice.required' => 'List Price is required.',
                    'listprice.numeric' => 'List Price must be a number.',
                    'listprice.min' => 'List Price must be greater than 0.',
                    'saleprice.required' => 'Sale Price is required.',
                    'saleprice.numeric' => 'Sale Price must be a number.',
                    'saleprice.min' => 'Sale Price must be greater than 0.',
                    'jancd.digits' => 'JAN Code must be exactly 13 digits.',
                ]);

                Session::put('import_format', $isJapaneseFormat ? 'japanese' : 'english');

                if ($validator->fails()) {
                    $validationErrors[] = [
                        'row' => $rowNumber,
                        'errors' => $validator->errors()->all(),
                        'data' => $itemData
                    ];
                }

                $previewData[] = $itemData;
            }

            // Store all data in session for import
            Session::put('import_preview_data', $previewData);
            Session::put('import_file_name', $fileName);
            Session::put('import_errors', $validationErrors);
            Session::put('import_file_type', 'excel');

            // Paginate preview data for display (20 records per page)
            $currentPage = $request->input('page', 1);
            $perPage = 15;
            $offset = ($currentPage - 1) * $perPage;

            // Get paginated slice of data
            $paginatedData = array_slice($previewData, $offset, $perPage);

            // Create custom paginator
            $previewPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedData,
                count($previewData),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            return redirect()->route('item-import.preview.show');
        } catch (\Exception $e) {
            return redirect()->route('item-import.index')
                ->with('error', 'Failed to process Excel file: ' . $e->getMessage());
        }
    }


    public function showPreview(Request $request)
    {
        // Retrieve data from session
        $previewData = session('import_preview_data', []);
        $fileName = session('import_file_name', '');
        $validationErrors = session('import_errors', []);
        $fileType = session('import_file_type', 'excel');

        if (empty($previewData)) {
            return redirect()->route('item-import.index')
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
            ['path' => route('item-import.preview.show'), 'query' => $request->query()]
        );

        return view('import.Item_Preview', [
            'previewData' => $previewPaginator,
            'fileName' => $fileName,
            'errors' => $validationErrors,
            'fileType' => $fileType,
            'totalRecords' => count($previewData),
            'errorCount' => count($validationErrors)
        ]);
    }

    private function convertJancd($value)
    {
        if ($value === null || trim($value) === '') {
            return '';
        }

        // Convert to string first
        $value = (string) $value;
        $value = trim($value);

        // If it's in scientific notation (like 1.23123E+12)
        if (preg_match('/^[+-]?\d+(\.\d+)?[eE][+-]?\d+$/', $value)) {
            // Convert to float then back to string without scientific notation
            $floatVal = (float) $value;
            // Use sprintf to ensure no scientific notation
            $value = sprintf('%.0f', $floatVal);
        }

        // Remove any non-digit characters (just in case)
        $value = preg_replace('/[^\d]/', '', $value);

        // Pad with leading zeros to ensure 13 digits
        $value = str_pad($value, 13, '0', STR_PAD_LEFT);

        // Trim to 13 digits if longer (shouldn't happen, but just in case)
        if (strlen($value) > 13) {
            $value = substr($value, 0, 13);
        }

        return $value;
    }
    public function processCsvPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            // Read CSV file
            $path = $file->getRealPath();
            $rows = $this->readCsvFile($path);

            // Remove empty rows
            $rows = array_filter($rows, function ($row) {
                return !empty(array_filter($row, function ($cell) {
                    return $cell !== null && trim($cell) !== '';
                }));
            });

            // Remove header row
            $header = array_shift($rows);

            // Check headers for both English and Japanese formats
            $englishHeaders = ['Item_Code', 'Item_Name', 'JanCD', 'MakerName', 'Memo', 'ListPrice', 'SalePrice'];
            $japaneseHeaders = ['商品番号', '商品名', 'JANCD', 'メーカー名', '注記', '定価', '原価'];
            $headerCheck = $this->checkHeaders($header, $englishHeaders, $japaneseHeaders);

            if (!$headerCheck['valid']) {
                return redirect()->route('item-import.index')
                    ->with('error', $headerCheck['message']);
            }

            $isJapaneseFormat = $headerCheck['format'] === 'japanese';

            $previewData = [];
            $validationErrors = [];

            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                // Ensure we have at least 7 columns
                $row = array_pad($row, 7, null);

                // TRIM ALL VALUES TO REMOVE EXTRA SPACES
                $row = array_map('trim', $row);

                // Use the convertJancd function to handle JANCD properly
                $jancdValue = $row[2] ?? '';
                $convertedJancd = $this->convertJancd($jancdValue);

                $itemData = [
                    'item_code' => $row[0] ?? null,
                    'itemname' => $row[1] ?? null,
                    'jancd' => $convertedJancd,
                    'makername' => $row[3] ?? null,
                    'memo' => $row[4] ?? null,
                    'listprice' => $this->cleanPrice($row[5] ?? 0),
                    'saleprice' => $this->cleanPrice($row[6] ?? 0),
                    'row_number' => $rowNumber
                ];

                Session::put('import_format', $isJapaneseFormat ? 'japanese' : 'english');

                // Validation rules
                $validator = Validator::make($itemData, [
                    'item_code' => [
                        'required',
                        'max:50',
                        function ($attribute, $value, $fail) {
                            if (!preg_match('/^[a-zA-Z0-9\-_.]+$/', $value)) {
                                $fail('Item Code can only contain letters, numbers, hyphen (-), underscore (_), and dot (.). No spaces or special characters allowed.');
                            }
                        },
                        function ($attribute, $value, $fail) use ($previewData, $rowIndex) {
                            if ($this->hasDuplicateItemCodesInCurrentImport($previewData, $rowIndex, $value)) {
                                $fail('Item Code "' . $value . '" appears more than once in this import file. Please remove duplicates.');
                            }
                        }
                    ],
                    'itemname'  => 'required|max:200',
                    'jancd'     => [
                        'required',
                        'digits:13',
                        function ($attribute, $value, $fail) {
                            if (!preg_match('/^\d+$/', $value)) {
                                $fail('JAN Code must contain only digits (0-9).');
                            }
                        }
                    ],
                    'makername' => 'required',
                    'listprice' => [
                        'required',
                        'numeric',
                        'min:0.01',
                        function ($attribute, $value, $fail) {
                            if (empty($value) || $value == 0) {
                                $fail('List Price cannot be blank or 0.');
                            }
                            if (is_string($value) && !is_numeric($value)) {
                                $fail('List Price must be a valid number (no letters or special characters).');
                            }
                        }
                    ],
                    'saleprice' => [
                        'required',
                        'numeric',
                        'min:0.01',
                        function ($attribute, $value, $fail) {
                            if (empty($value) || $value == 0) {
                                $fail('Sale Price cannot be blank or 0.');
                            }
                            if (is_string($value) && !is_numeric($value)) {
                                $fail('Sale Price must be a valid number (no letters or special characters).');
                            }
                        }
                    ],
                ], [
                    'listprice.required' => 'List Price is required.',
                    'listprice.numeric' => 'List Price must be a number.',
                    'listprice.min' => 'List Price must be greater than 0.',
                    'saleprice.required' => 'Sale Price is required.',
                    'saleprice.numeric' => 'Sale Price must be a number.',
                    'saleprice.min' => 'Sale Price must be greater than 0.',
                    'jancd.digits' => 'JAN Code must be exactly 13 digits.',
                ]);

                if ($validator->fails()) {
                    $validationErrors[] = [
                        'row' => $rowNumber,
                        'errors' => $validator->errors()->all(),
                        'data' => $itemData
                    ];
                }

                $previewData[] = $itemData;
            }

            // Store all data in session for import
            Session::put('import_preview_data', $previewData);
            Session::put('import_file_name', $fileName);
            Session::put('import_errors', $validationErrors);
            Session::put('import_file_type', 'csv');

            // Paginate preview data for display (20 records per page)
            $currentPage = $request->input('page', 1);
            $perPage = 15;
            $offset = ($currentPage - 1) * $perPage;

            // Get paginated slice of data
            $paginatedData = array_slice($previewData, $offset, $perPage);

            // Create custom paginator
            $previewPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedData,
                count($previewData),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return redirect()->route('item-import.preview.show');
        } catch (\Exception $e) {
            return redirect()->route('item-import.index')
                ->with('error', 'Failed to process CSV file: ' . $e->getMessage());
        }
    }
 
    private function hasDuplicateItemCodesInCurrentImport($previewData, $currentIndex, $currentItemCode)
    {
        if (empty($currentItemCode)) {
            return false;
        }

        // Check all previous rows in the preview data
        for ($i = 0; $i < $currentIndex; $i++) {
            if (
                isset($previewData[$i]['item_code']) &&
                $previewData[$i]['item_code'] === $currentItemCode
            ) {
                return true;
            }
        }

        return false;
    }
    /**
     * Check if Item Code exists in MItem table
     */
    private function doesItemCodeExist($itemCode)
    {
        if (empty($itemCode)) {
            return false;
        }

        return MItem::where('Item_Code', $itemCode)->exists();
    }
 
    private function hasDuplicateItemCodes($previewData, $currentRowNumber)
    {
        $currentItemCode = $previewData[$currentRowNumber]['item_code'] ?? '';

        if (empty($currentItemCode)) {
            return false;
        }

        // Check all previous rows for the same item code
        for ($i = 0; $i < $currentRowNumber; $i++) {
            if (
                isset($previewData[$i]['item_code']) &&
                $previewData[$i]['item_code'] === $currentItemCode
            ) {
                return true;
            }
        }

        return false;
    }
    public function confirmImport()
    {
        $fileType = session('import_file_type', 'excel');
        DB::beginTransaction();
        try {
            $previewData = session('import_preview_data', []);
            $errorRows = session('import_errors', []);
            $fileName = session('import_file_name', 'Unknown');
            $importedBy = Auth::check() ? Auth::user()->name : 'system';

            if (empty($previewData)) throw new \Exception('No data found.');

            // 1) Insert import log
            $importLog = ItemImportLog::create([
                'Import_Type' => 1,
                'Record_Count' => count($previewData),
                'Error_Count' => count($errorRows),
                'Imported_By' => $importedBy,
                'Imported_Date' => now()
            ]);
            $importLogId = $importLog->ImportLog_ID;

            // 2) Prepare valid XML for Item_Import_DataLog
            $dataLogXml = new \SimpleXMLElement('<Items/>');
            // 3) Prepare valid XML for M_Item
            $mItemXml = new \SimpleXMLElement('<Items/>');

            $validCount = 0;
            $currentDate = now()->format('Y-m-d H:i:s');

            foreach ($previewData as $row) {
                // Skip rows with errors
                $hasError = false;
                foreach ($errorRows as $err) {
                    if (($err['row'] ?? null) == ($row['row_number'] ?? null)) {
                        $hasError = true;
                        break;
                    }
                }
                if ($hasError) continue;

                $itemCode = $row['item_code'] ?? '';
                $itemExists = $this->doesItemCodeExist($itemCode);

                // Create XML for Item_Import_DataLog
                $dataLogItem = $dataLogXml->addChild('Item');
                $dataLogItem->addChild('ImportLog_ID', $importLogId);
                $dataLogItem->addChild('Item_Code', $itemCode);
                $dataLogItem->addChild('Item_Name', $row['itemname'] ?? '');
                $dataLogItem->addChild('JanCD', $row['jancd'] ?? '');
                $dataLogItem->addChild('MakerName', $row['makername'] ?? '');
                $dataLogItem->addChild('Memo', $row['memo'] ?? '');
                $dataLogItem->addChild('ListPrice', $row['listprice'] ?? 0);
                $dataLogItem->addChild('SalePrice', $row['saleprice'] ?? 0);
                $dataLogItem->addChild('Size_Name', '');
                $dataLogItem->addChild('Color_Name', '');
                $dataLogItem->addChild('Size_Code', '');
                $dataLogItem->addChild('Color_Code', '');
                $dataLogItem->addChild('JanCode', '');
                $dataLogItem->addChild('Quantity', 0);

                // Create XML for M_Item (matching mitem table structure)
                $mItem = $mItemXml->addChild('Item');
                $mItem->addChild('Item_Code', $itemCode);
                $mItem->addChild('ItemName', $row['itemname'] ?? '');
                $mItem->addChild('JanCD', $row['jancd'] ?? '');
                $mItem->addChild('MakerName', $row['makername'] ?? '');
                $mItem->addChild('Memo', $row['memo'] ?? '');
                $mItem->addChild('ListPrice', $row['listprice'] ?? 0);
                $mItem->addChild('SalePrice', $row['saleprice'] ?? 0);
                $mItem->addChild('CreatedDate', $itemExists ? null : $currentDate); // Only set CreatedDate for new items
                $mItem->addChild('UpdatedDate', $currentDate); // Always update UpdatedDate
                $mItem->addChild('Createdby', $itemExists ? null : $importedBy); 
                $mItem->addChild('Updatedby', $importedBy); 
                $mItem->addChild('IsUpdate', $itemExists ? '1' : '0'); // Flag: 1 for update, 0 for insert

                $validCount++;
            }
            // 4) Prepare error XML (for Item_Import_ErrorLog table)
            $errorXml = new \SimpleXMLElement('<Items/>');
            foreach ($errorRows as $err) {
                $data = $err['data'] ?? [];
                $item = $errorXml->addChild('Item');
                $item->addChild('ImportLog_ID', $importLogId);
                $item->addChild('Item_Code', $data['item_code'] ?? '');
                $item->addChild('Item_Name', $data['itemname'] ?? '');
                $item->addChild('JanCD', $data['jancd'] ?? '');
                $item->addChild('MakerName', $data['makername'] ?? '');
                $item->addChild('Memo', $data['memo'] ?? '');
                $item->addChild('ListPrice', $data['listprice'] ?? 0);
                $item->addChild('SalePrice', $data['saleprice'] ?? 0);
                $item->addChild('Size_Name', '');
                $item->addChild('Color_Name', '');
                $item->addChild('Size_Code', '');
                $item->addChild('Color_Code', '');
                $item->addChild('JanCode', '');
                $item->addChild('Quantity', 0);
                $item->addChild('Error_Msg', implode(', ', $err['errors'] ?? []));
            }

            // Call Stored Procedures
            if ($validCount > 0) {
                DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_ItemImport_DataLog_XML @xml;", [$dataLogXml->asXML()]);

                Log::info('M_Item XML: ' . $mItemXml->asXML());

                try {
                    DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_M_Item_XML @xml;", [$mItemXml->asXML()]);
                } catch (\Exception $e) {
                    Log::warning('Stored procedure sp_Insert_M_Item_XML not found or failed: ' . $e->getMessage());

                    // Fallback to direct insertion if stored procedure doesn't exist
                    // foreach ($mItemXml->children() as $item) {
                    //     MItem::create([
                    //         'Item_Code' => (string)$item->Item_Code,
                    //         'ItemName' => (string)$item->ItemName,
                    //         'JanCD' => (string)$item->JanCD,
                    //         'MakerName' => (string)$item->MakerName,
                    //         'Memo' => (string)$item->Memo,
                    //         'ListPrice' => (float)$item->ListPrice,
                    //         'SalePrice' => (float)$item->SalePrice,
                    //         'CreatedDate' => (string)$item->CreatedDate,
                    //         'UpdatedDate' => (string)$item->UpdatedDate,
                    //         'Createdby' => (string)$item->Createdby,
                    //         'Updatedby' => (string)$item->Updatedby,
                    //     ]);
                    // }
                }
            }

            if (count($errorRows) > 0) {
                DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_ItemImport_ErrorLog_XML @xml;", [$errorXml->asXML()]);
            }

            DB::commit();

            Session::forget(['import_preview_data', 'import_errors', 'import_file_name', 'import_file_type']);

            return redirect()->route('home')
                ->with('success', "Import completed. Success: {$validCount}, Errors: " . count($errorRows) . " (File type: " . strtoupper($fileType) . ")");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
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
        Session::forget(['import_preview_data', 'import_file_name', 'import_errors', 'import_file_type']);
        return redirect()->route('item-import.index')
            ->with('info', 'Preview cancelled.');
    }

    private function cleanPrice($value)
    {
        if ($value === null || trim($value) === '') return 0;

        // If it's already a number, return it
        if (is_numeric($value)) return (float) $value;

        // If it's a string but contains non-numeric characters (except comma, period, and minus)
        if (is_string($value)) {
            // Remove commas for thousands separators
            $value = str_replace(',', '', $value);

            // Check if it contains any letters or language characters
            if (preg_match('/[^\d.-]/', $value)) {
                return 0; // Return 0 for invalid price with letters/language characters
            }
        }

        $cleaned = preg_replace('/[^\d.-]/', '', (string)$value);
        return ($cleaned === '' || $cleaned === '-') ? 0 : (float)$cleaned;
    }


    private function checkHeaders($actual, $englishHeaders, $japaneseHeaders)
    {
        if (count($actual) < count($englishHeaders)) {
            return ['valid' => false, 'message' => 'File has fewer columns than expected. Expected: ' . implode(', ', $englishHeaders)];
        }

        // Check for Japanese format first
        $isJapanese = true;
        foreach ($japaneseHeaders as $i => $col) {
            $actualCol = trim($actual[$i] ?? '');
            if (mb_strpos($actualCol, $col) === false) {
                $isJapanese = false;
                break;
            }
        }

        if ($isJapanese) {
            return ['valid' => true, 'format' => 'japanese'];
        }

        // Check for English format
        $isEnglish = true;
        foreach ($englishHeaders as $i => $col) {
            $actualCol = trim($actual[$i] ?? '');
            if (strtolower($actualCol) !== strtolower($col)) {
                $isEnglish = false;
                break;
            }
        }

        if ($isEnglish) {
            return ['valid' => true, 'format' => 'english'];
        }

        // If neither format matches
        return [
            'valid' => false,
            'message' => "Invalid headers. Expected either:<br>English: " . implode(', ', $englishHeaders) .
                "<br>or Japanese: " . implode(', ', $japaneseHeaders) .
                "<br>Found: " . implode(', ', $actual)
        ];
    }


    private function convertScientificToDecimal($scientific)
    {
        if (!is_string($scientific)) {
            // If not string, try to convert to string and clean
            $scientific = (string) $scientific;
        }

        $scientific = trim($scientific);

        // Check if it's in scientific notation
        if (preg_match('/^[+-]?\d+(\.\d+)?[eE][+-]?\d+$/', $scientific)) {
            // Convert to float then to string without scientific notation
            $floatVal = (float) $scientific;

            // Convert to string without scientific notation
            $decimal = sprintf('%.0f', $floatVal);

            // Ensure it's 13 digits (JAN code requirement)
            $decimal = str_pad($decimal, 13, '0', STR_PAD_LEFT);

            // Truncate to 13 digits if longer
            if (strlen($decimal) > 13) {
                $decimal = substr($decimal, 0, 13);
            }

            return $decimal;
        }

        // If not scientific notation, ensure it contains only digits
        // Remove any non-digit characters
        $decimal = preg_replace('/[^\d]/', '', $scientific);

        // If empty after removing non-digits, return empty string
        if ($decimal === '') {
            return '';
        }

        return $decimal;
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
        $logs = ItemImportLog::where('Import_Type', 1)
            ->orderBy('Imported_Date', 'desc')
            ->paginate(20);

        return view('item-import.logs', compact('logs'));
    }
}
