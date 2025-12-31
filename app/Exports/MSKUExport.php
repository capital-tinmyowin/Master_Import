<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class MSKUExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomCsvSettings
{
    protected Collection $skus;

    public function __construct(Collection $skus)
    {
        $this->skus = $skus;
    }

    public function collection()
    {
        return $this->skus;
    }

    public function headings(): array
    {
        return [
            'Item Admin Code',
            '商品番号',
            'サイズ名 (項目選択肢別在庫用横軸選択肢)',
            'カラー名 (項目選択肢別在庫用縦軸選択肢)',
            'サイズコード',
            'カラーコード',
            'JANコード',
            '在庫数',
        ];
    }

    public function map($sku): array
    {
        return [
            $sku->Item_AdminCode,
            $sku->Item_Code,
            $sku->Size_Name,
            $sku->Color_Name,
            $sku->Size_Code,
            $sku->Color_Code,
            $sku->JanCD,
            $sku->Quantity,
        ];
    }

    // ⭐ THIS FIXES THE JAPANESE GARBLED TEXT ⭐
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => true,        // ✅ MUST HAVE for Excel
            'output_encoding' => 'UTF-8',
        ];
    }
}
