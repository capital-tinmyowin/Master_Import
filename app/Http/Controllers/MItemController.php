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
use Illuminate\Support\Facades\Log;
use App\Models\MItemImage;
use App\Models\MSKU;

use function Laravel\Prompts\alert;

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
        // dd($request->has('use_like_search'));

        // // Parse comma-separated Item Codes
        // $itemCodes = [];
        // if (!empty($itemCode)) {
        //     // Split by comma, trim whitespace, and filter out empty values
        //     $itemCodes = array_map('trim', explode(',', $itemCode));
        //     $itemCodes = array_filter($itemCodes); // Remove empty values
        // }

        $useLikeSearch = $request->has('use_like_search') ? 1 : 0;
        $itemsArray = DB::select(
            'EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?, @UseLikeSearch = ?',
            [$itemCode, $itemName, $useLikeSearch]
        );

        // Convert to collection
        $itemsCollection = collect($itemsArray);
        // dd($itemsCollection);
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
        $itemCode  = $request->input('Item_Code');
        $itemName  = $request->input('ItemName');
        $sortBy    = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'asc');
        $type      = $request->input('excel_type', 'item'); // item | sku
        $useLikeSearch = (int) $request->input('use_like_search', 0);

        if ($type === 'sku') {

            // dd($itemCode, $useLikeSearch);
            $skusArray = DB::select(
                'EXEC Get_MSKUs_for_export @Item_Code = ?, @UseLikeSearch = ?',
                [$itemCode, $useLikeSearch]
            );

            $collection = collect($skusArray);

            if (empty($skusArray)) {
                return redirect()->back()
                    ->with('error', 'No SKU records found to export.');
            }


            $filename = 'sku_' . date('Y-m-d_H-i-s') . '.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\MSKUExport($collection),
                $filename,
                \Maatwebsite\Excel\Excel::XLSX
            );
        }

        $itemsArray = DB::select('EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?, @UseLikeSearch = ?', [$itemCode, $itemName, $useLikeSearch]);
        $collection = collect($itemsArray);
        // dd($itemsArray);
        if ($collection->isEmpty()) {
            return redirect()->back()->with('error', 'No item records found to export.');
        }

        $filename = 'items_' . date('Y-m-d_H-i-s') . '.xlsx';

        $export = new MItemsExport($itemCode, $itemName, $sortBy, $sortOrder, 'excel', $useLikeSearch);

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
        $itemCode  = $request->input('Item_Code');
        $itemName  = $request->input('ItemName');
        $sortBy    = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'asc');
        $type      = $request->input('excel_type', 'item'); // item | sku
        $useLikeSearch = (int) $request->input('use_like_search', 0);
        /* =======================
       SKU CSV (same as item)
        ========================*/
        if ($type === 'sku') {
            $skusArray = DB::select(
                'EXEC Get_MSKUs_for_export @Item_Code = ?, @UseLikeSearch = ?',
                [$itemCode, $useLikeSearch]
            );

            $collection = collect($skusArray);

            if ($collection->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'No SKU records found to export.');
            }

            $filename = 'sku_' . now()->format('Y-m-d_H-i-s') . '.csv';

            return Excel::download(
                new \App\Exports\MSKUExport($collection),
                $filename,
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        // $itemsArray = DB::select(
        //     'EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?, @UseLikeSearch = ?',
        //     [$itemCode, $itemName, $useLikeSearch]
        // );
        // $collection = collect($itemsArray);

        // if ($collection->isEmpty()) {
        //     return redirect()->back()->with('error', 'No item records found to export.');
        // }

        $filename = 'items_' . date('Y-m-d_H-i-s') . '.csv';

        $export = new class($itemCode, $itemName, $sortBy, $sortOrder, $useLikeSearch) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithMapping,
            \Maatwebsite\Excel\Concerns\WithCustomCsvSettings {

            protected $itemCode;
            protected $itemName;
            protected $sortBy;
            protected $sortOrder;
            protected $useLikeSearch;

            public function __construct($itemCode, $itemName, $sortBy, $sortOrder, $useLikeSearch)
            {
                $this->itemCode = $itemCode;
                $this->itemName = $itemName;
                $this->sortBy = $sortBy;
                $this->sortOrder = $sortOrder;
                $this->useLikeSearch = $useLikeSearch;
            }

            public function collection()
            {
                $itemsArray = DB::select(
                    'EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?, @UseLikeSearch = ?',
                    [$this->itemCode, $this->itemName, $this->useLikeSearch]
                );

                // dd($itemsArray, $this->useLikeSearch);
                $items = collect($itemsArray);

                if ($this->sortBy && in_array($this->sortBy, ['ListPrice', 'SalePrice'])) {
                    $items = $this->sortOrder === 'desc'
                        ? $items->sortByDesc($this->sortBy)
                        : $items->sortBy($this->sortBy);

                    $items = $items->values();
                }

                return $items;
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

            public function getCsvSettings(): array
            {
                return [
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'line_ending' => PHP_EOL,
                    'use_bom' => true,
                    'output_encoding' => 'UTF-8',
                ];
            }
        };

        return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::CSV);
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
                'Createdby'   => Auth::user()->name ?? 'system',
                'Updatedby'   => Auth::user()->name ?? 'system',
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
                'Item_Code'      => "商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号商品番号",
                'Size_Name'     => $sizeNames[$i],
                'Color_Name'    => $colorNames[$i],
                'Size_Code'     => $formattedSizeCode,
                'Color_Code'    => $formattedColorCode,
                'JanCD'         => $jans[$i],
                'Quantity'      => "aaaaaaaaaaaaaaaaaaaaaaaaa",
                'CreatedDate'   => now(),
                'UpdatedDate'   => now(),
                'Createdby'     => Auth::user()->name ?? 'system',
                'Updatedby'     => Auth::user()->name ?? 'system',
            ]);
        }
    }

    private function saveImages($request, $itemCode)
    {
        // if (!$request->hasFile('Photos')) {
        //     return;
        // }

        $uploadedFiles = $request->file('Photos');
        if (!$uploadedFiles || count($uploadedFiles) === 0) {
            return;
        }

        // Validate file count
        if (count($uploadedFiles) > 5) {
            throw new \Exception('Maximum 5 photos allowed.');
        }

        $counter = 1;

        foreach ($uploadedFiles as $file) {
            // Validate file
            if (!$file->isValid()) {
                throw new \Exception('Invalid file uploaded');
            }

            // Validate file type
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                throw new \Exception('Invalid file type: ' . $file->getMimeType() . '. Allowed: JPG, PNG, WEBP, GIF');
            }

            // Validate file size (max 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('File too large: ' . $file->getClientOriginalName() . '. Maximum 5MB');
            }

            // Create filename: itemCode_counter.extension
            $ext = strtolower($file->getClientOriginalExtension());
            $finalName = $itemCode . '_' . $counter . '.' . $ext;

            // Ensure unique filename
            while (file_exists(public_path('uploads/items/' . $finalName))) {
                $counter++;
                $finalName = $itemCode . '_' . $counter . '.' . $ext;
            }

            // Create directory if it doesn't exist
            $uploadPath = public_path('uploads/items');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Store image in public/uploads/items
            $file->move($uploadPath, $finalName);

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
    public function edit($id)
    {
        $item = MItem::findOrFail($id);
        $skus = \App\Models\MSKU::where('Item_Code', $item->Item_Code)->get();
        $existingImages = \App\Models\MItemImage::where('Item_Code', $item->Item_Code)->get();

        // dd($item);
        return view('mitem.edit', compact('item', 'skus', 'existingImages'));
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:M_Item,ID'
            ]);

            foreach ($request->ids as $id) {
                DB::beginTransaction();
                try {
                    $item = MItem::findOrFail($id);
                    $itemCode = $item->Item_Code;

                    // Delete associated images
                    $images = MItemImage::where('Item_Code', $itemCode)->get();
                    foreach ($images as $image) {
                        $filePath = public_path('uploads/items/' . $image->Image_Name);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }

                    // Delete database records
                    MItemImage::where('Item_Code', $itemCode)->delete();
                    MSKU::where('Item_Code', $itemCode)->delete();
                    $item->delete();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    continue;
                }
            }

            // Get remaining total items
            $totalItems = MItem::count();

            return response()->json([
                'success' => true,
                'totalItems' => $totalItems
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $item = MItem::findOrFail($id);
            $itemCode = $item->Item_Code;

            // Delete associated images (physical files)
            $images = MItemImage::where('Item_Code', $itemCode)->get();
            foreach ($images as $image) {
                $filePath = public_path('uploads/items/' . $image->Image_Name);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete database records
            MItemImage::where('Item_Code', $itemCode)->delete();
            MSKU::where('Item_Code', $itemCode)->delete();
            $item->delete();

            DB::commit();

            return redirect()->route('mitems.index')
                ->with('success', 'Item deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mitems.index')
                ->with('error', 'Error deleting item: ' . $e->getMessage());
        }
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
            DB::rollBack();

            // Return JSON error for AJAX submission
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function updateSKUs($request, $itemCode)
    {
        // dd($request);
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
        // FIRST: Handle deleted images
        if ($request->filled('deleted_images')) {
            try {
                $deletedImageIds = json_decode($request->deleted_images, true);

                if (is_array($deletedImageIds) && count($deletedImageIds) > 0) {
                    $imagesToDelete = \App\Models\MItemImage::whereIn('ID', $deletedImageIds)->get();

                    foreach ($imagesToDelete as $image) {
                        // Delete physical file
                        $filePath = public_path('uploads/items/' . $image->Image_Name);
                        if (file_exists($filePath)) {
                            @unlink($filePath); // Suppress errors with @
                        }
                        // Delete database record
                        $image->delete();
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error deleting images: ' . $e->getMessage());
            }
        }

        // SECOND: Check total photo count (existing + new) doesn't exceed 5
        $existingImageCount = \App\Models\MItemImage::where('Item_Code', $itemCode)->count();
        // Get uploaded files

        if (
            !$request->hasFile('Photos') ||
            empty($request->file('Photos'))
        ) {
            return;
        }

        $uploadedFiles = $request->file('Photos');
        // Validate total won't exceed 5
        $totalAfterUpload = $existingImageCount + count($uploadedFiles);
        if ($totalAfterUpload > 5) {
            throw new \Exception('Maximum 5 photos allowed. You currently have ' . $existingImageCount . ' existing photos and trying to add ' . count($uploadedFiles) . ' more.');
        }

        // THIRD: Find the next available number - this is the key fix
        // Get ALL existing image names (including those that might have been deleted but numbers should continue)
        $existingImages = \App\Models\MItemImage::where('Item_Code', $itemCode)->get();

        $maxNumber = 0;
        $usedNumbers = [];

        foreach ($existingImages as $image) {
            $filename = $image->Image_Name;
            $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

            // Match pattern: itemcode_number
            if (preg_match('/^' . preg_quote($itemCode, '/') . '_(\d+)$/', $filenameWithoutExt, $matches)) {
                $number = (int)$matches[1];
                $usedNumbers[] = $number;
                $maxNumber = max($maxNumber, $number);
            }
        }

        // Also check existing files in the uploads folder to find any gaps
        $uploadPath = public_path('uploads/items');
        if (file_exists($uploadPath)) {
            $files = scandir($uploadPath);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                $filenameWithoutExt = pathinfo($file, PATHINFO_FILENAME);

                // Match pattern: itemcode_number
                if (preg_match('/^' . preg_quote($itemCode, '/') . '_(\d+)$/', $filenameWithoutExt, $matches)) {
                    $number = (int)$matches[1];
                    if (!in_array($number, $usedNumbers)) {
                        $usedNumbers[] = $number;
                        $maxNumber = max($maxNumber, $number);
                    }
                }
            }
        }

        // Sort used numbers to find the next available number
        sort($usedNumbers);
        $counter = 1;

        // Find the first gap in numbers or use next number after max
        for ($i = 1; $i <= $maxNumber + 1; $i++) {
            if (!in_array($i, $usedNumbers)) {
                $counter = $i;
                break;
            }
        }

        // FOURTH: Process each uploaded file
        foreach ($uploadedFiles as $file) {
            // Validate file is valid and is an image
            if (!$file->isValid()) {
                throw new \Exception('Invalid file uploaded');
            }

            // Validate file type
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                throw new \Exception('Invalid file type: ' . $file->getMimeType() . '. Allowed: JPG, PNG, WEBP, GIF');
            }

            // Validate file size (max 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('File too large: ' . $file->getClientOriginalName() . '. Maximum 5MB');
            }

            // Create filename: itemCode_counter.extension
            $ext = strtolower($file->getClientOriginalExtension());
            $finalName = $itemCode . '_' . $counter . '.' . $ext;

            // Ensure unique filename (in case there's a file with this number already)
            while (file_exists(public_path('uploads/items/' . $finalName))) {
                $counter++;
                $finalName = $itemCode . '_' . $counter . '.' . $ext;
            }

            // Create directory if it doesn't exist
            $uploadPath = public_path('uploads/items');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Store the file
            try {
                $file->move($uploadPath, $finalName);
            } catch (\Exception $e) {
                throw new \Exception('Failed to save file: ' . $e->getMessage());
            }

            // Create database record
            \App\Models\MItemImage::create([
                'Item_Code'   => $itemCode,
                'Image_Name'  => $finalName,
                'CreatedDate' => now(),
                'UpdatedDate' => now(),
                'Createdby'   => 'admin',
                'Updatedby'   => 'admin',
            ]);

            // Add this number to used numbers
            $usedNumbers[] = $counter;

            // Find next available number for next file
            sort($usedNumbers);
            $counter = 1;
            for ($i = 1; $i <= $maxNumber + count($uploadedFiles) + 1; $i++) {
                if (!in_array($i, $usedNumbers)) {
                    $counter = $i;
                    break;
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
