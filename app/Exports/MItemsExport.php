<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class MItemsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting
{
    protected $itemCode;
    protected $itemName;
    protected $sortBy;
    protected $sortOrder;
    protected $format;
    protected $useLikeSearch;

    public function __construct(
        $itemCode = null,
        $itemName = null,
        $sortBy = null,
        $sortOrder = 'asc',
        $format = 'excel',
        $useLikeSearch = 0
    ) {
        $this->itemCode = $itemCode;
        $this->itemName = $itemName;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
        $this->format = $format;
        $this->useLikeSearch = $useLikeSearch;
    }

    public function collection()
    {
        $itemsArray = DB::select(
            'EXEC sp_GetMItems @Item_Code = ?, @ItemName = ?, @UseLikeSearch = ?',
            [
                $this->itemCode,
                $this->itemName,
                $this->useLikeSearch
            ]
        );

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
        if ($this->format === 'csv') {
            return [
                $item->Item_Code,
                $item->ItemName,
                $item->JanCD,
                $item->MakerName,
                $item->Memo,
                number_format($item->ListPrice),
                number_format($item->SalePrice),
            ];
        }

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
        return $this->format === 'excel'
            ? [
                'F' => '#,##0',
                'G' => '#,##0',
            ]
            : [];
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->format !== 'excel') {
            return [];
        }

        return [
            1 => ['font' => ['bold' => true]],
            'A:G' => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                ],
            ],
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
}
