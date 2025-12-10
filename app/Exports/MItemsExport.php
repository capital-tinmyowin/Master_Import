<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;

class MItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting
{
    protected $itemCode;
    protected $itemName;
    protected $sortBy;
    protected $sortOrder;
    protected $format;

    public function __construct($itemCode = null, $itemName = null, $sortBy = null, $sortOrder = 'asc', $format = 'excel')
    {
        $this->itemCode = $itemCode;
        $this->itemName = $itemName;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
        $this->format = $format;
    }

    public function collection()
    {
        $itemsArray = DB::select('EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?', [
            $this->itemCode,
            $this->itemName
        ]);

        $itemsCollection = collect($itemsArray);

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
            '原価'
        ];
    }

    public function map($item): array
    {
        // For CSV, format numbers with commas
        if ($this->format === 'csv') {
            return [
                $item->Item_Code,
                $item->ItemName,
                $item->JanCD,
                $item->MakerName,
                $item->Memo,
                number_format($item->ListPrice), // Add commas for CSV
                number_format($item->SalePrice), // Add commas for CSV
            ];
        }

        // For Excel, keep as numbers for proper formatting
        return [
            $item->Item_Code,
            $item->ItemName,
            $item->JanCD,
            $item->MakerName,
            $item->Memo,
            $item->ListPrice,
            $item->SalePrice,
        ];
    }

    public function columnFormats(): array
    {
        // Only apply Excel formatting for Excel exports
        if ($this->format === 'excel') {
            return [
                'F' => '#,##0', // 定価 - comma format, no decimals
                'G' => '#,##0', // 原価 - comma format, no decimals
            ];
        }

        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Only apply styles for Excel exports
        if ($this->format === 'excel') {
            return [
                // Header row bold
                1 => ['font' => ['bold' => true]],
                
                // Auto-size columns
                'A:G' => [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    ],
                ],
                
                // Right align money columns
                'F' => [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ],
                'G' => [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ],
            ];
        }

        return [];
    }
}