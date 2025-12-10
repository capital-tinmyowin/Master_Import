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
            'previewData' => $previewPaginator,
            'fileName' => $fileName,
            'errors' => $validationErrors,
            'fileType' => $fileType,
            'totalRecords' => count($previewData),
            'errorCount' => count($validationErrors)
        ]);
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

            // Check if header matches expected format
            $expectedHeaders = [
                '商品番号',
                'サイズ名 (項目選択肢別在庫用横軸選択肢)',
                'カラー名 (項目選択肢別在庫用縦軸選択肢)',
                'サイズコード',
                'カラーコード',
                'JANコード',
                '在庫数'
            ];
            $headerCheck = $this->checkHeaders($header, $expectedHeaders);

            if (!$headerCheck['valid']) {
                return redirect()->route('sku-import.index')
                    ->with('error', $headerCheck['message']);
            }

            $previewData = [];
            $validationErrors = [];
            $skuCombinations = []; // Track combinations for duplicate check
            $itemCodes = []; // ADDED: Collect item codes for database validation

            // First pass: Collect all data and track combinations
            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                // Ensure have at least 7 columns
                $row = array_pad($row, 7, null);

                // Convert scientific notation to string for JanCD
                $janCD = $row[5] ?? '';

                $itemData = [
                    'item_code' => trim($row[0] ?? ''),        // 商品番号 (column 0)
                    'size_code' => trim($row[1] ?? ''),        // サイズ名 (項目選択肢別在庫用横軸選択肢) (column 1)
                    'color_code' => trim($row[2] ?? ''),       // カラー名 (項目選択肢別在庫用縦軸選択肢) (column 2)
                    'size_name' => trim($row[3] ?? ''),        // サイズコード (column 3)
                    'color_name' => trim($row[4] ?? ''),       // カラーコード (column 4)
                    'jancd' => $this->cleanJanCD($janCD),     // JANコード (column 5)
                    'quantity' => $this->cleanNumber($row[6] ?? 0),
                    'row_number' => $rowNumber,
                    'original_jancd' => $janCD // Keep original for error messages
                ];

                $previewData[] = $itemData;

                // ADDED: Collect item code for database validation
                $itemCode = trim($itemData['item_code']);
                if ($itemCode !== '') {
                    $itemCodes[$itemCode] = true;
                }

                // Track SKU combination
                $sizeCode = trim($itemData['size_code']);
                $colorCode = trim($itemData['color_code']);

                if ($itemCode !== '' && $sizeCode !== '' && $colorCode !== '') {
                    $key = strtoupper($itemCode . '-' . $sizeCode . '-' . $colorCode);

                    if (!isset($skuCombinations[$key])) {
                        $skuCombinations[$key] = [];
                    }
                    $skuCombinations[$key][] = $rowNumber;
                }
            }

            // ADDED: Check which item codes exist in database
            $existingItemCodes = [];
            if (!empty($itemCodes)) {
                $itemCodeArray = array_keys($itemCodes);
                // Check in MItem table
                $existingItems = MItem::whereIn('Item_Code', $itemCodeArray)
                    ->select('Item_Code')
                    ->get()
                    ->pluck('Item_Code')
                    ->toArray();

                // Convert to uppercase for case-insensitive comparison
                $existingItemCodes = array_map('strtoupper', $existingItems);
            }

            // Second pass: Validate and check for duplicates
            foreach ($previewData as $index => $itemData) {
                $rowNumber = $itemData['row_number'];
                $itemCode = trim($itemData['item_code']); // ADDED: Get item code

                $errors = [];

                // ADDED: Check if item exists in database
                if ($itemCode !== '') {
                    $itemCodeUpper = strtoupper($itemCode);
                    if (!in_array($itemCodeUpper, $existingItemCodes)) {
                        $errors[] = "Item Code '{$itemCode}' does not exist in the Item Master database.";
                    }
                }

                // Validation rules
                $validator = Validator::make($itemData, [
                    'item_code'  => [
                        'required',
                        'max:50',
                        function ($attribute, $value, $fail) {
                            $trimmedValue = trim($value);

                            if ($trimmedValue === '') {
                                $fail('Item Code is required.');
                                return;
                            }

                            // Check for any whitespace
                            if (preg_match('/\s/', $value)) {
                                $fail('Item Code must not contain spaces or whitespace.');
                                return;
                            }

                            // Check for Japanese characters
                            if (preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', $trimmedValue)) {
                                $fail('Item Code must not contain Japanese characters.');
                                return;
                            }

                            // Allow only uppercase letters and numbers
                            if (!preg_match('/^[A-Z0-9]+$/', $trimmedValue)) {
                                $fail('Item Code must contain only uppercase letters (A-Z) and numbers (0-9). No lowercase letters or special characters allowed.');
                            }
                        }
                    ],
                    'size_code'  => [
                        'required',
                        'max:50',
                        'regex:/^\d+$/'  // Numbers only
                    ],
                    'color_code' => [
                        'required',
                        'max:50',
                        // Allow empty string for color_code 
                        function ($attribute, $value, $fail) {
                            if ($value !== '' && !preg_match('/^\d+$/', $value)) {
                                $fail('Color Code must contain only numbers.');
                            }
                        }
                    ],
                    'size_name'  => 'required|max:100',
                    'color_name' => 'required|max:100',
                    'jancd' => [
                        'required',
                        function ($attribute, $value, $fail) use ($itemData) {
                            // Check if original JanCD was empty
                            if (trim($itemData['original_jancd']) === '') {
                                $fail('JanCD is required.');
                                return;
                            }

                            // Clean the JanCD value first
                            $cleaned = $this->cleanJanCDForValidation($itemData['original_jancd']);

                            // Check if it's exactly 13 digits
                            if (strlen($cleaned) !== 13) {
                                $fail('JanCD must be exactly 13 digits and number only. ');
                                return;
                            }

                            // Check if contains only digits
                            if (!ctype_digit($cleaned)) {
                                $fail('JanCD must contain only numbers.');
                                return;
                            }
                        }
                    ],
                    'quantity'   => [
                        'required',
                        'integer',
                        'min:0',
                        'regex:/^\d+$/'  // Numbers only
                    ],
                ], [
                    'item_code.required' => 'Item Code is required.',
                    'size_code.required' => 'Size Code is required.',
                    'size_code.regex' => 'Size Code must contain only numbers.',
                    'color_code.required' => 'Color Code is required.',
                    'size_name.required' => 'Size Name is required.',
                    'color_name.required' => 'Color Name is required.',
                    'jancd.required' => 'JanCD is required.',
                    'quantity.required' => 'Quantity is required.',
                    'quantity.integer' => 'Quantity must be a whole number.',
                    'quantity.min' => 'Quantity cannot be negative.',
                    'quantity.regex' => 'Quantity must contain only numbers.',
                ]);

                if ($validator->fails()) {
                    $errors = array_merge($errors, $validator->errors()->all());
                }

                // Check for duplicate SKU combination
                $sizeCode = trim($itemData['size_code']);
                $colorCode = trim($itemData['color_code']);

                if ($itemCode !== '' && $sizeCode !== '' && $colorCode !== '') {
                    $key = strtoupper($itemCode . '-' . $sizeCode . '-' . $colorCode);

                    if (isset($skuCombinations[$key]) && count($skuCombinations[$key]) > 1) {
                        // This is a duplicate combination
                        $duplicateRowsList = $skuCombinations[$key];
                        // Remove current row from the list to show other duplicates
                        $otherDuplicates = array_diff($duplicateRowsList, [$rowNumber]);

                        if (!empty($otherDuplicates)) {
                            $duplicateRowsText = implode(', ', $otherDuplicates);
                            $errors[] = "Duplicate SKU combination: Item Code '{$itemCode}' with Size Code '{$sizeCode}' and Color Code '{$colorCode}'. " .
                                "Duplicate rows: {$duplicateRowsText}.";
                        }
                    }
                }

                if (!empty($errors)) {
                    $validationErrors[] = [
                        'row' => $rowNumber,
                        'errors' => $errors,
                        'data' => $itemData
                    ];
                }
            }

            // Store in session
            Session::put('sku_import_preview_data', $previewData);
            Session::put('sku_import_file_name', $fileName);
            Session::put('sku_import_errors', $validationErrors);
            Session::put('sku_import_file_type', 'excel');

            return redirect()->route('sku-import.preview.show');
        } catch (\Exception $e) {
            return redirect()->route('sku-import.index')
                ->with('error', 'Failed to process Excel file: ' . $e->getMessage());
        }
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

            // Check if header matches expected format
            $expectedHeaders = [
                '商品番号',
                'サイズ名 (項目選択肢別在庫用横軸選択肢)',
                'カラー名 (項目選択肢別在庫用縦軸選択肢)',
                'サイズコード',
                'カラーコード',
                'JANコード',
                '在庫数'
            ];
            $headerCheck = $this->checkHeaders($header, $expectedHeaders);

            if (!$headerCheck['valid']) {
                return redirect()->route('sku-import.index')
                    ->with('error', $headerCheck['message']);
            }

            $previewData = [];
            $validationErrors = [];
            $skuCombinations = []; // Track combinations for duplicate check
            $itemCodes = []; // ADDED: Collect item codes for database validation

            // First pass: Collect all data and track combinations
            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;

                $row = array_pad($row, 7, null);

                $janCD = $row[5] ?? '';

                $itemData = [
                    'item_code' => trim($row[0] ?? ''),        // 商品番号 (column 0)
                    'size_code' => trim($row[1] ?? ''),        // サイズ名 (項目選択肢別在庫用横軸選択肢) (column 1)
                    'color_code' => trim($row[2] ?? ''),       // カラー名 (項目選択肢別在庫用縦軸選択肢) (column 2)
                    'size_name' => trim($row[3] ?? ''),        // サイズコード (column 3)
                    'color_name' => trim($row[4] ?? ''),       // カラーコード (column 4)
                    'jancd' => $this->cleanJanCD($janCD),     // JANコード (column 5)
                    'quantity' => $this->cleanNumber($row[6] ?? 0),
                    'row_number' => $rowNumber,
                    'original_jancd' => $janCD
                ];

                $previewData[] = $itemData;

                // ADDED: Collect item code for database validation
                $itemCode = trim($itemData['item_code']);
                if ($itemCode !== '') {
                    $itemCodes[$itemCode] = true;
                }

                // Track SKU combination
                $sizeCode = trim($itemData['size_code']);
                $colorCode = trim($itemData['color_code']);

                if ($itemCode !== '' && $sizeCode !== '' && $colorCode !== '') {
                    $key = strtoupper($itemCode . '-' . $sizeCode . '-' . $colorCode);

                    if (!isset($skuCombinations[$key])) {
                        $skuCombinations[$key] = [];
                    }
                    $skuCombinations[$key][] = $rowNumber;
                }
            }

            // ADDED: Check which item codes exist in database
            $existingItemCodes = [];
            if (!empty($itemCodes)) {
                $itemCodeArray = array_keys($itemCodes);
                // Check in MItem table
                $existingItems = MItem::whereIn('Item_Code', $itemCodeArray)
                    ->select('Item_Code')
                    ->get()
                    ->pluck('Item_Code')
                    ->toArray();

                // Convert to uppercase for case-insensitive comparison
                $existingItemCodes = array_map('strtoupper', $existingItems);
            }

            // Second pass: Validate and check for duplicates
            foreach ($previewData as $index => $itemData) {
                $rowNumber = $itemData['row_number'];
                $itemCode = trim($itemData['item_code']); // ADDED: Get item code

                $errors = [];

                // ADDED: Check if item exists in database
                if ($itemCode !== '') {
                    $itemCodeUpper = strtoupper($itemCode);
                    if (!in_array($itemCodeUpper, $existingItemCodes)) {
                        $errors[] = "Item Code '{$itemCode}' does not exist in the Item Master database.";
                    }
                }

                // Validation rules
                $validator = Validator::make($itemData, [
                    'item_code'  => [
                        'required',
                        'max:50',
                        function ($attribute, $value, $fail) {
                            $trimmedValue = trim($value);

                            if ($trimmedValue === '') {
                                $fail('Item Code is required.');
                                return;
                            }

                            // Check for any whitespace
                            if (preg_match('/\s/', $value)) {
                                $fail('Item Code must not contain spaces or whitespace.');
                                return;
                            }

                            // Check for Japanese characters
                            if (preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', $trimmedValue)) {
                                $fail('Item Code must not contain Japanese characters.');
                                return;
                            }

                            // Allow only uppercase letters and numbers
                            if (!preg_match('/^[A-Z0-9]+$/', $trimmedValue)) {
                                $fail('Item Code must contain only uppercase letters (A-Z) and numbers (0-9). No lowercase letters or special characters allowed.');
                            }
                        }
                    ],
                    'size_code'  => [
                        'required',
                        'max:50',
                        'regex:/^\d+$/'  // Numbers only
                    ],
                    'color_code' => [
                        'required',
                        'max:50',
                        function ($attribute, $value, $fail) {
                            if ($value !== '' && !preg_match('/^\d+$/', $value)) {
                                $fail('Color Code must contain only numbers.');
                            }
                        }
                    ],
                    'size_name'  => 'required|max:100',
                    'color_name' => 'required|max:100',
                    'jancd'      => [
                        'required',
                        function ($attribute, $value, $fail) use ($itemData) {
                            // Check if original JanCD was empty
                            if (trim($itemData['original_jancd']) === '') {
                                $fail('JanCD is required.');
                                return;
                            }

                            // Check length after cleaning
                            if (strlen($value) !== 13) {
                                $fail('JanCD must be exactly 13 digits and number only.');
                                return;
                            }

                            // Check if contains only digits
                            if (!ctype_digit($value)) {
                                $fail('JanCD must contain only numbers.');
                            }
                        }
                    ],
                    'quantity'   => [
                        'required',
                        'integer',
                        'min:0',
                        'regex:/^\d+$/'  // Numbers only
                    ],
                ], [
                    'item_code.required' => 'Item Code is required.',
                    'size_code.required' => 'Size Code is required.',
                    'size_code.regex' => 'Size Code must contain only numbers.',
                    'color_code.required' => 'Color Code is required.',
                    'size_name.required' => 'Size Name is required.',
                    'color_name.required' => 'Color Name is required.',
                    'jancd.required' => 'JanCD is required.',
                    'quantity.required' => 'Quantity is required.',
                    'quantity.integer' => 'Quantity must be a whole number.',
                    'quantity.min' => 'Quantity cannot be negative.',
                    'quantity.regex' => 'Quantity must contain only numbers.',
                ]);

                if ($validator->fails()) {
                    $errors = array_merge($errors, $validator->errors()->all());
                }

                // Check for duplicate SKU combination
                $sizeCode = trim($itemData['size_code']);
                $colorCode = trim($itemData['color_code']);

                if ($itemCode !== '' && $sizeCode !== '' && $colorCode !== '') {
                    $key = strtoupper($itemCode . '-' . $sizeCode . '-' . $colorCode);

                    if (isset($skuCombinations[$key]) && count($skuCombinations[$key]) > 1) {
                        // This is a duplicate combination
                        $duplicateRowsList = $skuCombinations[$key];
                        // Remove current row from the list to show other duplicates
                        $otherDuplicates = array_diff($duplicateRowsList, [$rowNumber]);

                        if (!empty($otherDuplicates)) {
                            $duplicateRowsText = implode(', ', $otherDuplicates);
                            $errors[] = "Duplicate SKU combination: Item Code '{$itemCode}' with Size Code '{$sizeCode}' and Color Code '{$colorCode}'. " .
                                "Duplicate rows: {$duplicateRowsText}.";
                        }
                    }
                }

                if (!empty($errors)) {
                    $validationErrors[] = [
                        'row' => $rowNumber,
                        'errors' => $errors,
                        'data' => $itemData
                    ];
                }
            }

            // Store in session
            Session::put('sku_import_preview_data', $previewData);
            Session::put('sku_import_file_name', $fileName);
            Session::put('sku_import_errors', $validationErrors);
            Session::put('sku_import_file_type', 'csv');

            return redirect()->route('sku-import.preview.show');
        } catch (\Exception $e) {
            return redirect()->route('sku-import.index')
                ->with('error', 'Failed to process CSV file: ' . $e->getMessage());
        }
    }
    // Confirm Import (common for both Excel and CSV)
    public function confirmImport()
    {
        $fileType = session('sku_import_file_type', 'excel');
        DB::beginTransaction();
        try {
            $previewData = session('sku_import_preview_data', []);
            $errorRows = session('sku_import_errors', []);
            $fileName = session('sku_import_file_name', 'Unknown');
            $importedBy = Auth::check() ? Auth::user()->name : 'system';

            if (empty($previewData)) throw new \Exception('No data found.');

            // 1) Insert import log
            $importLog = ItemImportLog::create([
                'Import_Type' => 2, // SKU Import Type
                'Record_Count' => count($previewData),
                'Error_Count' => count($errorRows),
                'Imported_By' => $importedBy,
                'Imported_Date' => now()
            ]);
            $importLogId = $importLog->ImportLog_ID;

            // 2) Prepare valid XML for Item_Import_DataLog
            $dataLogXml = new \SimpleXMLElement('<Items/>');
            // 3) Prepare valid XML for M_SKU
            $mSkuXml = new \SimpleXMLElement('<Items/>');

            $validCount = 0;
            $itemCodesToDelete = []; // Track item codes for deletion

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

                // Collect unique item codes for deletion
                $itemCode = trim($row['item_code'] ?? '');
                if ($itemCode !== '') {
                    $itemCodesToDelete[$itemCode] = true;
                }

                // Generate Item_AdminCode
                $itemAdminCode = $this->generateItemAdminCode($row['item_code'], $row['size_code'], $row['color_code']);

                // A) Create XML for Item_Import_DataLog
                $dataLogItem = $dataLogXml->addChild('Item');
                $dataLogItem->addChild('ImportLog_ID', $importLogId);
                $dataLogItem->addChild('Item_Code', $row['item_code'] ?? '');
                $dataLogItem->addChild('Item_Name', ''); // Empty for SKU import
                $dataLogItem->addChild('JanCD', $row['jancd'] ?? '');
                $dataLogItem->addChild('MakerName', ''); // Empty for SKU import
                $dataLogItem->addChild('Memo', ''); // Empty for SKU import
                $dataLogItem->addChild('ListPrice', 0); // Default 0
                $dataLogItem->addChild('SalePrice', 0); // Default 0
                $dataLogItem->addChild('Size_Name', $row['size_name'] ?? '');
                $dataLogItem->addChild('Color_Name', $row['color_name'] ?? '');
                $dataLogItem->addChild('Size_Code', $row['size_code'] ?? '');
                $dataLogItem->addChild('Color_Code', $row['color_code'] ?? '');
                $dataLogItem->addChild('JanCode', ''); // Different from JanCD
                $dataLogItem->addChild('Quantity', $row['quantity'] ?? 0);

                // B) Create XML for M_SKU
                $mSkuItem = $mSkuXml->addChild('Item');
                $mSkuItem->addChild('Item_AdminCode', $itemAdminCode);
                $mSkuItem->addChild('Item_Code', $row['item_code'] ?? '');
                $mSkuItem->addChild('Size_Code', $row['size_code'] ?? '');
                $mSkuItem->addChild('Color_Code', $row['color_code'] ?? '');
                $mSkuItem->addChild('Size_Name', $row['size_name'] ?? '');
                $mSkuItem->addChild('Color_Name', $row['color_name'] ?? '');
                $mSkuItem->addChild('JanCD', $row['jancd'] ?? '');
                $mSkuItem->addChild('Quantity', $row['quantity'] ?? 0);
                $mSkuItem->addChild('CreatedDate', now()->format('Y-m-d H:i:s'));
                $mSkuItem->addChild('UpdatedDate', now()->format('Y-m-d H:i:s'));
                $mSkuItem->addChild('Createdby', $importedBy);
                $mSkuItem->addChild('Updatedby', $importedBy);

                $validCount++;
            }

            // 4) Prepare error XML (for Item_Import_ErrorLog table)
            $errorXml = new \SimpleXMLElement('<Items/>');
            foreach ($errorRows as $err) {
                $data = $err['data'] ?? [];
                // $itemAdminCode = $this->generateItemAdminCode($data['item_code'] ?? '', $data['size_code'] ?? '', $data['color_code'] ?? '');

                $item = $errorXml->addChild('Item');
                $item->addChild('ImportLog_ID', $importLogId);
                $item->addChild('Item_Code', $data['item_code'] ?? '');
                $item->addChild('Item_Name', ''); // Empty for SKU import
                $item->addChild('JanCD', $data['jancd'] ?? '');
                $item->addChild('MakerName', ''); // Empty for SKU import
                $item->addChild('Memo', ''); // Empty for SKU import

                $listPrice = $data['list_price'] ?? $data['ListPrice'] ?? '0';
                $salePrice = $data['sale_price'] ?? $data['SalePrice'] ?? '0';
                $quantity = $data['quantity'] ?? '0';

                $item->addChild('ListPrice', (string)$listPrice);
                $item->addChild('SalePrice', (string)$salePrice);
                $item->addChild('Size_Name', $data['size_name'] ?? '');
                $item->addChild('Color_Name', $data['color_name'] ?? '');
                $item->addChild('Size_Code', $data['size_code'] ?? '');
                $item->addChild('Color_Code', $data['color_code'] ?? '');
                $item->addChild('JanCode', '');
                $item->addChild('Quantity', (string)$quantity);
                $item->addChild('Error_Msg', implode(', ', $err['errors'] ?? []));
            }

            // 5) Call Stored Procedures
            if ($validCount > 0) {
                // A) First, delete existing records from M_SKU for these item codes
                if (!empty($itemCodesToDelete)) {
                    $itemCodesArray = array_keys($itemCodesToDelete);
                    Log::info('Deleting existing M_SKU records for item codes: ' . implode(', ', $itemCodesArray));

                    // MSKU::whereIn('Item_Code', $itemCodesArray)->delete();

                    DB::table('M_SKU')->whereIn('Item_Code', $itemCodesArray)->delete();
                }

                // B) Insert into Item_Import_DataLog
                DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_ItemImport_DataLog_XML @xml;", [$dataLogXml->asXML()]);

                // C) Insert into M_SKU using stored procedure
                Log::info('M_SKU XML: ' . $mSkuXml->asXML());
                DB::statement("DECLARE @xml XML = ?; EXEC sp_Insert_M_SKU_XML @xml;", [$mSkuXml->asXML()]);
            }

            if (count($errorRows) > 0) {
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
