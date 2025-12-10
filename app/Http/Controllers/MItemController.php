<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MItemsImport;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Exports\MItemsExport;
use Illuminate\Support\Facades\Auth;


class MItemController extends Controller
{

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $itemCode = $request->input('Item_Code');
        $itemName = $request->input('ItemName');
        $sortBy = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'asc');

        // Parse comma-separated Item Codes
        $itemCodes = [];
        if (!empty($itemCode)) {
            // Split by comma, trim whitespace, and filter out empty values
            $itemCodes = array_map('trim', explode(',', $itemCode));
            $itemCodes = array_filter($itemCodes); // Remove empty values
        }

        // Call stored procedure - need to modify to accept multiple codes
        // Option 1: Modify stored procedure to accept multiple codes
        // Option 2: Handle it in PHP after getting all data

        // For now, let's modify the stored procedure call or handle filtering in PHP
        $itemsArray = DB::select('EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?', [
            $itemCode, // Pass as-is, might need stored procedure modification
            $itemName
        ]);

        // Convert to collection
        $itemsCollection = collect($itemsArray);

        // Filter by multiple item codes if provided
        if (!empty($itemCodes)) {
            $itemsCollection = $itemsCollection->filter(function ($item) use ($itemCodes) {
                return in_array($item->Item_Code, $itemCodes);
            });
        }

        // Apply sorting if requested
        if ($sortBy && in_array($sortBy, ['ListPrice', 'SalePrice'])) {
            $itemsCollection = $sortOrder === 'desc'
                ? $itemsCollection->sortByDesc($sortBy)
                : $itemsCollection->sortBy($sortBy);

            // Reset keys after sorting
            $itemsCollection = $itemsCollection->values();
        }

        // Rest of your pagination code
        $perPage = 12;
        $currentPage = $request->input('page', 1);
        $currentItems = $itemsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedItems = new LengthAwarePaginator(
            $currentItems,
            $itemsCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('mitem.index', [
            'items' => $paginatedItems
        ]);
    }


    public function exportExcel(Request $request)
    {
        $itemCode = $request->input('Item_Code');
        $itemName = $request->input('ItemName');
        $sortBy = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'asc');

        $filename = 'items_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Generate the Excel file
        $export = new MItemsExport($itemCode, $itemName, $sortBy, $sortOrder, 'excel');

        // Get the raw content
        $content = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($content))
            ->header('Pragma', 'public')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }

    public function exportCsv(Request $request)
    {
        $itemCode = $request->input('Item_Code');
        $itemName = $request->input('ItemName');
        $sortBy = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'asc');

        $filename = 'items_' . date('Y-m-d_H-i-s') . '.csv';

        // Create a custom export class with UTF-8 BOM for CSV
        $export = new class($itemCode, $itemName, $sortBy, $sortOrder) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithMapping,
            \Maatwebsite\Excel\Concerns\WithCustomCsvSettings {

            protected $itemCode;
            protected $itemName;
            protected $sortBy;
            protected $sortOrder;

            public function __construct($itemCode, $itemName, $sortBy, $sortOrder)
            {
                $this->itemCode = $itemCode;
                $this->itemName = $itemName;
                $this->sortBy = $sortBy;
                $this->sortOrder = $sortOrder;
            }

            public function collection()
            {
                // Call stored procedure
                $itemsArray = DB::select('EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?', [
                    $this->itemCode,
                    $this->itemName
                ]);

                $itemsCollection = collect($itemsArray);

                // Apply sorting if requested
                if ($this->sortBy && in_array($this->sortBy, ['ListPrice', 'SalePrice'])) {
                    $itemsCollection = $this->sortOrder === 'desc'
                        ? $itemsCollection->sortByDesc($this->sortBy)
                        : $itemsCollection->sortBy($this->sortBy);

                    $itemsCollection = $itemsCollection->values();
                }

                return $itemsCollection;
            }

            public function headings(): array
            {
                return [
                    '商品番号',
                    '商品名',
                    'JANCD',
                    'メーカー名',
                    '注記',
                    '定価',
                    '原価',
                ];
            }

            public function map($item): array
            {
                return [
                    $item->Item_Code,
                    $item->ItemName,
                    $item->JanCD,
                    $item->MakerName,
                    $item->Memo ?? '',
                    number_format($item->ListPrice),
                    number_format($item->SalePrice),
                ];
            }

            // CRITICAL: Add UTF-8 BOM for CSV files
            public function getCsvSettings(): array
            {
                return [
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'line_ending' => PHP_EOL,
                    'use_bom' => true, // THIS IS THE KEY FIX
                    'include_separator_line' => false,
                    'excel_compatibility' => false,
                    'output_encoding' => 'UTF-8',
                ];
            }
        };

        return Excel::download(
            $export,
            $filename,
            \Maatwebsite\Excel\Excel::CSV,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
    public function checkItemCode(Request $request)
    {
        $itemCode = $request->get('item_code');

        // Debug log

        if (empty($itemCode)) {
            return response()->json(['exists' => false]);
        }

        // Check if item code exists (excluding soft deleted if you use soft deletes)
        $exists = MItem::where('Item_Code', $itemCode)->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * POST version for check item code (with CSRF token)
     */
    public function checkItemCodePost(Request $request)
    {
        $itemCode = $request->get('item_code');

        if (empty($itemCode)) {
            return response()->json(['exists' => false]);
        }

        $exists = MItem::where('Item_Code', $itemCode)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        dd($request);

        $request->merge([
            'ListPrice' => str_replace(',', '', $request->ListPrice),
            'SalePrice' => str_replace(',', '', $request->SalePrice),
        ]);
        $validated = $request->validate([
            'Item_Code'  => 'required|unique:M_Item,Item_Code',
            'JanCD'      => 'required|digits:13',
            'ItemName'   => 'required|string|max:255',
            'ListPrice'  => 'required|numeric',
            'SalePrice'  => 'required|numeric',
            'Note'       => 'nullable|string',
            'sku.Size_Name.*'  => 'required',
            'sku.Color_Name.*' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // Save M_Item
            $item = MItem::create([
                'Item_Code' => $validated['Item_Code'],
                'JanCD'     => $validated['JanCD'] ?? null,
                'MakerName' => $request->MakerName,
                'ItemName'  => $validated['ItemName'],
                'ListPrice' => $validated['ListPrice'],
                'SalePrice' => $validated['SalePrice'],
                'Memo'      => $validated['Note'] ?? null,
                'CreatedDate' => now(),
                'UpdatedDate' => now(),
                'Createdby'   => 'admin',
                'Updatedby'   => 'admin',
            ]);

            // Save SKU rows
            $this->saveSKUs($request, $validated['Item_Code']);

            // Save Images with new naming convention
            $this->saveImages($request, $validated['Item_Code']);

            DB::commit();
            return redirect()->route('mitems.index')->with('success', 'Item saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveSKUs($request, $itemCode)
    {
        // Decode JSON arrays
        $sizeNames   = json_decode($request->sku['Size_Name'], true);
        $colorNames  = json_decode($request->sku['Color_Name'], true);
        $sizeCodes   = json_decode($request->sku['Size_Code'], true);
        $colorCodes  = json_decode($request->sku['Color_Code'], true);
        $jans        = json_decode($request->sku['JanCD'], true);
        $qtys        = json_decode($request->sku['Quantity'], true);

        // Loop through decoded JSON
        for ($i = 0; $i < count($sizeNames); $i++) {
            // Format size code to 4 digits with leading zeros
            $formattedSizeCode = str_pad($sizeCodes[$i], 4, '0', STR_PAD_LEFT);
            $formattedColorCode = str_pad($colorCodes[$i], 4, '0', STR_PAD_LEFT);

            \App\Models\MSKU::create([
                'Item_Code'      => $itemCode,
                'Size_Name'     => $sizeNames[$i],
                'Color_Name'    => $colorNames[$i],
                'Size_Code'     => $formattedSizeCode,
                'Color_Code'    => $formattedColorCode,
                'JanCD'         => $jans[$i],
                'Quantity'      => $qtys[$i],
                'CreatedDate'   => now(),
                'UpdatedDate'   => now(),
                'Createdby'     => 'admin',
                'Updatedby'     => 'admin',
            ]);
        }
    }

    private function saveImages($request, $itemCode)
    {
        if ($request->hasFile('Photos')) {
            $counter = 1;

            foreach ($request->file('Photos') as $file) {
                // Create filename: itemCode_counter.extension
                $ext = $file->getClientOriginalExtension();
                $finalName = $itemCode . '_' . $counter . '.' . $ext;

                // Ensure unique filename (in case of duplicates)
                while (file_exists(public_path('uploads/items/' . $finalName))) {
                    $counter++;
                    $finalName = $itemCode . '_' . $counter . '.' . $ext;
                }

                // Store image in public/uploads/items
                $file->move(public_path('uploads/items'), $finalName);

                // Save image record
                \App\Models\MItemImage::create([
                    'Item_Code'  => $itemCode,
                    'Image_Name' => $finalName,
                    'CreatedDate' => now(),
                    'UpdatedDate' => now(),
                    'Createdby'   => 'admin',
                    'Updatedby'   => 'admin',
                ]);

                $counter++;
            }
        }
    }

    public function edit($id)
    {
        $item = MItem::findOrFail($id);
        $skus = \App\Models\MSKU::where('Item_Code', $item->Item_Code)->get();
        $existingImages = \App\Models\MItemImage::where('Item_Code', $item->Item_Code)->get();

        // dd($item);
        return view('mitem.edit', compact('item', 'skus', 'existingImages'));
    }

    public function update(Request $request, $id)
    {


        // dd([
        //     'hasFiles' => $request->hasFile('Photos'),
        //     'allFiles' => $request->allFiles(),
        //     'photoData' => $request->PhotoData,
        //     'allInput' => $request->all()
        // ]);
        $item = MItem::findOrFail($id);
        // dd($request->ListPrice);
        $request->merge([
            'ListPrice' => str_replace(',', '', $request->ListPrice),
            'SalePrice' => str_replace(',', '', $request->SalePrice),
        ]);
        $validated = $request->validate([
            // 'Item_Code'  => 'required|unique:M_Item,Item_Code,' . $id,
            'JanCD'      => 'required|digits:13',
            'ItemName'   => 'required|string|max:255',
            'ListPrice'  => 'required|numeric',
            'SalePrice'  => 'required|numeric',
            'Note'       => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update main item
            $item->update([
                // 'Item_Code' => $validated['Item_Code'],
                'JanCD'     => $validated['JanCD'] ?? null,
                'MakerName' => $request->MakerName,
                'ItemName'  => $validated['ItemName'],
                'ListPrice' => $validated['ListPrice'],
                'SalePrice' => $validated['SalePrice'],
                'Memo'      => $validated['Note'] ?? null,
                'UpdatedDate' => now(),
                'Updatedby'   => 'admin',
            ]);

            // Handle SKU updates
            $this->updateSKUs($request, $item->Item_Code);

            // Handle image updates
            $this->updateImages($request, $item->Item_Code);

            DB::commit();
            return redirect()->route('mitems.index')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function updateSKUs($request, $itemCode)
    {
        // Delete existing SKUs
        \App\Models\MSKU::where('Item_Code', $itemCode)->delete();

        // Add new SKUs (same logic as store)
        $sizeNames   = json_decode($request->sku['Size_Name'], true);
        $colorNames  = json_decode($request->sku['Color_Name'], true);
        $sizeCodes   = json_decode($request->sku['Size_Code'], true);
        $colorCodes  = json_decode($request->sku['Color_Code'], true);
        $jans        = json_decode($request->sku['JanCD'], true);
        $qtys        = json_decode($request->sku['Quantity'], true);

        for ($i = 0; $i < count($sizeNames); $i++) {
            // Format size code to 4 digits with leading zeros
            $formattedSizeCode = str_pad($sizeCodes[$i], 4, '0', STR_PAD_LEFT);

            // Format color code to 4 digits with leading zeros
            $formattedColorCode = str_pad($colorCodes[$i], 4, '0', STR_PAD_LEFT);

            \App\Models\MSKU::create([
                'Item_Code'      => $itemCode,
                'Size_Name'     => $sizeNames[$i],
                'Color_Name'    => $colorNames[$i],
                'Size_Code'     => $formattedSizeCode,
                'Color_Code'    => $formattedColorCode,
                'JanCD'         => $jans[$i],
                'Quantity'      => $qtys[$i],
                'CreatedDate'   => now(),
                'UpdatedDate'   => now(),
                'Createdby'     => 'admin',
                'Updatedby'     => 'admin',
            ]);
        }
    }

    private function updateImages($request, $itemCode)
    {
        // Handle deleted images
        if ($request->has('deleted_images') && !empty($request->deleted_images)) {
            $deletedImageIds = json_decode($request->deleted_images, true);

            if (is_array($deletedImageIds) && count($deletedImageIds) > 0) {
                $imagesToDelete = \App\Models\MItemImage::whereIn('ID', $deletedImageIds)->get();

                foreach ($imagesToDelete as $image) {
                    // Delete physical file
                    $filePath = public_path('uploads/items/' . $image->Image_Name);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    // Delete database record
                    $image->delete();
                }
            }
        }

        // Handle new image uploads - AUTOMATIC NAMING
        if ($request->hasFile('Photos')) {
            // Get existing images to determine next number
            $existingImages = \App\Models\MItemImage::where('Item_Code', $itemCode)->get();

            // Find highest existing number - FIXED LOGIC
            $maxNumber = 0;
            foreach ($existingImages as $image) {
                // Extract number from filename like "ITEM001_1.jpg", "ITEM001_10.png", etc.
                $filename = $image->Image_Name;

                // Remove the extension
                $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

                // Check if filename follows pattern: itemcode_number
                if (preg_match('/^' . preg_quote($itemCode, '/') . '_(\d+)$/', $filenameWithoutExt, $matches)) {
                    $number = (int)$matches[1];
                    $maxNumber = max($maxNumber, $number);
                }
            }

            $counter = $maxNumber + 1;

            foreach ($request->file('Photos') as $file) {
                // Check if file is valid
                if ($file->isValid()) {
                    // Create filename: itemCode_counter.extension
                    $ext = $file->getClientOriginalExtension();
                    $finalName = $itemCode . '_' . $counter . '.' . $ext;

                    // Ensure unique filename
                    while (file_exists(public_path('uploads/items/' . $finalName))) {
                        $counter++;
                        $finalName = $itemCode . '_' . $counter . '.' . $ext;
                    }

                    // Store the file
                    $file->move(public_path('uploads/items'), $finalName);

                    // Create database record
                    \App\Models\MItemImage::create([
                        'Item_Code'   => $itemCode,
                        'Image_Name'  => $finalName,
                        'CreatedDate' => now(),
                        'UpdatedDate' => now(),
                        'Createdby'   => 'admin',
                        'Updatedby'   => 'admin',
                    ]);

                    $counter++;
                }
            }
        }
    }
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $path = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Remove completely empty rows
        $rows = array_filter($rows, fn($r) => !empty(array_filter($r)));

        // Clean header row: remove empty trailing columns
        $headers = $rows[0] ?? [];
        while (!empty($headers) && ($headers[count($headers) - 1] === null || $headers[count($headers) - 1] === '')) {
            array_pop($headers);
        }

        // Slice data rows (skip header)
        $dataRows = array_slice($rows, 1);

        // Trim each data row to match header length
        foreach ($dataRows as &$r) {
            $r = array_slice($r, 0, count($headers));
        }
        unset($r);

        $validatedRows = [];
        $errors = [];

        foreach ($dataRows as $index => $row) {
            $rowErrors = [];

            $itemCode  = $row[0] ?? null;
            $itemName  = $row[1] ?? null;
            $janCD     = $row[2] ?? null;
            $makerName = $row[3] ?? null;
            $memo      = $row[4] ?? null;
            $listPrice = trim((string)($row[5] ?? ''));
            $salePrice = trim((string)($row[6] ?? ''));

            // Remove commas or currency symbols
            $listPrice = str_replace([',', '¥', '$', ' '], '', $listPrice);
            $salePrice = str_replace([',', '¥', '$', ' '], '', $salePrice);

            // Validation
            if (!$itemCode) $rowErrors[] = 'Item Code is required';
            if (!$itemName) $rowErrors[] = 'Item Name is required';
            if ($janCD && strlen((string)$janCD) != 13) $rowErrors[] = 'JanCD must be 13 digits';

            // Validate List Price
            if ($listPrice === '') {
                $rowErrors[] = 'List Price is required';
            } elseif (!is_numeric($listPrice)) {
                $rowErrors[] = 'List Price must be numeric';
            }

            // Validate Sale Price
            if ($salePrice === '') {
                $rowErrors[] = 'Sale Price is required';
            } elseif (!is_numeric($salePrice)) {
                $rowErrors[] = 'Sale Price must be numeric';
            }

            $validatedRows[] = [
                'data' => $row,
                'errors' => $rowErrors
            ];

            if (!empty($rowErrors)) {
                $errors[] = [
                    'row' => $index + 2, // Excel row number
                    'messages' => $rowErrors
                ];
            }
        }

        return response()->json([
            'headers' => $headers,
            'rows' => $validatedRows,
            'errors' => $errors
        ]);
    }



    public function import(Request $request)
    {
        Excel::import(new MItemsImport, $request->file('file'));
        return redirect()->back()->with('success', 'Excel data imported successfully!');
    }

    public function create()
    {
        return view('mitem.create');
    }
}
