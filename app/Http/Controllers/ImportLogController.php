<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemImportLog;
use App\Models\ItemImportDataLog;
use App\Models\ItemImportErrorLog;
use Illuminate\Support\Facades\Auth;
use App\Models\MItem;
use Illuminate\Support\Facades\DB;

class ImportLogController extends Controller
{
    public function index()
    {
        $importLogs = ItemImportLog::orderBy('Imported_Date', 'desc')
            ->paginate(10); // Show 10 records per page

        return view('home', compact('importLogs'));
    }


    public function detail($id, Request $request)
    {
        // Get the log detail
        $logDetail = ItemImportLog::findOrFail($id);

        // Get per_page from request or default to 10
        $perPage = $request->input('per_page', 10);

        $type = $request->input('type');

        if ($type === 'data') {
            return $this->records($id, 'data', $request);
        } elseif ($type === 'errors') {
            return $this->records($id, 'errors', $request);
        }

        if ($logDetail->Import_Type == 1) {
            // Item Master - Raw SQL UNION with proper SQL Server syntax
            $query = "
        SELECT 
            Item_Code, 
            Item_Name, 
            JanCD, 
            MakerName, 
            Memo, 
            ListPrice, 
            SalePrice,
            'success' as status,
            NULL as Error_Msg,
            ID as original_id,
            'data' as source_table
        FROM [Item_Import_DataLog] 
        WHERE ImportLog_ID = :logId
        UNION ALL
        SELECT 
            Item_Code, 
            Item_Name, 
            JanCD, 
            MakerName, 
            Memo, 
            ListPrice, 
            SalePrice,
            'error' as status,
            Error_Msg,
            ID as original_id,
            'error' as source_table
        FROM [Item_Import_ErrorLog] 
        WHERE ImportLog_ID = :logId2
        ORDER BY status DESC, original_id ASC
        OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY
    ";

            // Get total count for pagination
            $totalQuery = "
        SELECT COUNT(*) as total FROM (
            SELECT ID FROM [Item_Import_DataLog] WHERE ImportLog_ID = :logId
            UNION ALL
            SELECT ID FROM [Item_Import_ErrorLog] WHERE ImportLog_ID = :logId2
        ) as combined
    ";

            $total = DB::selectOne($totalQuery, ['logId' => $id, 'logId2' => $id])->total;

            // Get paginated results
            $currentPage = $request->input('page', 1);
            $offset = ($currentPage - 1) * $perPage;

            $records = DB::select($query, [
                'logId' => $id,
                'logId2' => $id,
                'limit' => $perPage,
                'offset' => $offset
            ]);

            // Convert to collection and format prices
            $combinedLogs = collect($records)->map(function ($item) {
                return $this->formatItemPrices($item);
            });

            // Create LengthAwarePaginator
            $combinedLogs = new \Illuminate\Pagination\LengthAwarePaginator(
                $combinedLogs,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $dataCount = ItemImportDataLog::where('ImportLog_ID', $id)->count();
            $errorCount = ItemImportErrorLog::where('ImportLog_ID', $id)->count();
        } else {
            // SKU - Similar UNION query with SQL Server syntax
            $query = "
        SELECT 
            Item_Code, 
            Size_Code, 
            Color_Code, 
            Size_Name, 
            Color_Name, 
            JanCD, 
            Quantity,
            'success' as status,
            NULL as Error_Msg,
            ID as original_id,
            'data' as source_table
        FROM [Item_Import_DataLog] 
        WHERE ImportLog_ID = :logId
        UNION ALL
        SELECT 
            Item_Code, 
            Size_Code, 
            Color_Code, 
            Size_Name, 
            Color_Name, 
            JanCD, 
            Quantity,
            'error' as status,
            Error_Msg,
            ID as original_id,
            'error' as source_table
        FROM [Item_Import_ErrorLog] 
        WHERE ImportLog_ID = :logId2
        ORDER BY status DESC, original_id ASC
        OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY
    ";

            // Get total count for pagination
            $totalQuery = "
        SELECT COUNT(*) as total FROM (
            SELECT ID FROM [Item_Import_DataLog] WHERE ImportLog_ID = :logId
            UNION ALL
            SELECT ID FROM [Item_Import_ErrorLog] WHERE ImportLog_ID = :logId2
        ) as combined
    ";

            $total = DB::selectOne($totalQuery, ['logId' => $id, 'logId2' => $id])->total;

            // Get paginated results
            $currentPage = $request->input('page', 1);
            $offset = ($currentPage - 1) * $perPage;

            $records = DB::select($query, [
                'logId' => $id,
                'logId2' => $id,
                'limit' => $perPage,
                'offset' => $offset
            ]);

            // Convert to collection
            $combinedLogs = collect($records);

            // Create LengthAwarePaginator
            $combinedLogs = new \Illuminate\Pagination\LengthAwarePaginator(
                $combinedLogs,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $dataCount = ItemImportDataLog::where('ImportLog_ID', $id)->count();
            $errorCount = ItemImportErrorLog::where('ImportLog_ID', $id)->count();
        }

        $dataLogs = collect([]);
        $errorLogs = collect([]);

        return view('home', compact('logDetail', 'dataLogs', 'errorLogs', 'dataCount', 'errorCount', 'combinedLogs'));
    }

    public function showByCode($itemCode)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Find the item by Item_Code (not ID)
        $item = MItem::where('Item_Code', $itemCode)->first();

        if (!$item) {
            return redirect()->route('mitems.index')
                ->with('error', 'Item not found with code: ' . $itemCode);
        }

        // Get SKUs and images for this item
        $skus = \App\Models\MSKU::where('Item_Code', $item->Item_Code)->get();
        $existingImages = \App\Models\MItemImage::where('Item_Code', $item->Item_Code)->get();

        // Redirect to edit page with the item's ID
        return redirect()->route('mitems.edit', ['id' => $item->id]);
    }

    public function records($id, $type, Request $request)
    {
        // Get the log detail
        $logDetail = ItemImportLog::findOrFail($id);

        // Get per_page from request or default to 10
        $perPage = $request->input('per_page', 10);

        if ($type === 'data') {
            if ($logDetail->Import_Type == 1) {
                // Item Master Success Records with pagination
                $dataLogs = ItemImportDataLog::where('ImportLog_ID', $id)
                    ->select('Item_Code', 'Item_Name', 'JanCD', 'MakerName', 'Memo', 'ListPrice', 'SalePrice')
                    ->orderBy('ID', 'asc')
                    ->paginate($perPage)
                    ->through(function ($item) {
                        return $this->formatItemPrices($item);
                    });
            } else {
                // SKU Success Records with pagination
                $dataLogs = ItemImportDataLog::where('ImportLog_ID', $id)
                    ->select('Item_Code', 'Size_Code', 'Color_Code', 'Size_Name', 'Color_Name', 'JanCD', 'Quantity')
                    ->orderBy('ID', 'asc')
                    ->paginate($perPage);
            }

            $dataCount = $dataLogs->total(); // Use total() for pagination
            $errorLogs = collect([]);
            $errorCount = 0;
            $showType = 'data';
        } else {
            if ($logDetail->Import_Type == 1) {
                // Item Master Error Records with pagination
                $errorLogs = ItemImportErrorLog::where('ImportLog_ID', $id)
                    ->select('Item_Code', 'Item_Name', 'JanCD', 'MakerName', 'Memo', 'ListPrice', 'SalePrice', 'Error_Msg')
                    ->orderBy('ID', 'asc')
                    ->paginate($perPage)
                    ->through(function ($item) {
                        return $this->formatItemPrices($item);
                    });
            } else {
                // SKU Error Records with pagination
                $errorLogs = ItemImportErrorLog::where('ImportLog_ID', $id)
                    ->select('Item_Code', 'Size_Code', 'Color_Code', 'Size_Name', 'Color_Name', 'JanCD', 'Quantity', 'Error_Msg')
                    ->orderBy('ID', 'asc')
                    ->paginate($perPage);
            }

            $errorCount = $errorLogs->total(); // Use total() for pagination
            $dataLogs = collect([]);
            $dataCount = 0;
            $showType = 'errors';
        }

        return view('home', compact('logDetail', 'dataLogs', 'errorLogs', 'dataCount', 'errorCount', 'showType'));
    }

    public function getData($logId)
    {
        $data = ItemImportDataLog::where('ImportLog_ID', $logId)
            ->select('Item_Code', 'Item_Name', 'JanCD', 'Size_Name', 'Color_Name')
            ->get();

        return response()->json($data);
    }

    public function getErrors($logId)
    {
        $errors = ItemImportErrorLog::where('ImportLog_ID', $logId)
            ->select('Item_Code', 'Error_Msg')
            ->get();

        return response()->json($errors);
    }

    /**
     * Format prices for an item (ListPrice and SalePrice)
     * 
     * @param object $item
     * @return object
     */
    private function formatItemPrices($item)
    {
        // Format ListPrice
        if (isset($item->ListPrice)) {
            $item->ListPrice = $this->formatPrice($item->ListPrice);
        }

        // Format SalePrice
        if (isset($item->SalePrice)) {
            $item->SalePrice = $this->formatPrice($item->SalePrice);
        }

        return $item;
    }

    /**
     * Format price from nvarchar to proper currency format
     * Handles cases where price might contain .000 or other decimal parts
     * 
     * @param string|null $price
     * @return string
     */
    private function formatPrice($price)
    {
        if ($price === null || $price === '' || $price === '-') {
            return '-';
        }

        // Price is now always a string (nvarchar)
        $priceString = (string) $price;

        // Remove any commas if present
        $priceString = str_replace(',', '', $priceString);

        // Remove any non-numeric characters except decimal point and minus sign
        $numericPart = preg_replace('/[^0-9.\-]/', '', $priceString);

        if ($numericPart === '' || !is_numeric($numericPart)) {
            return $priceString; // Return original string if no numeric part
        }

        // Convert to float
        $floatPrice = (float) $numericPart;

        // Check if the price ends with .000 and remove it
        if (fmod($floatPrice, 1) == 0) {
            // It's a whole number - format without decimals
            return number_format($floatPrice, 0, '.', ',');
        } else {
            // It has decimal values - format with 2 decimal places
            return number_format($floatPrice, 2, '.', ',');
        }
    }
}
