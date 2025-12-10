<?php

namespace App\Imports;

use App\Models\MItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class MItemsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new MItem([
            'Item_Code'   => $row['item_code'],
            'ItemName'    => $row['itemname'],
            'JanCD'       => $row['jancd'],
            'MakerName'   => $row['makername'],
            'Memo'        => $row['memo'],
            'ListPrice'   => $row['listprice'],
            'SalePrice'   => $row['saleprice'],
            'CreatedDate' => Carbon::now(),
            'UpdatedDate' => Carbon::now(),
            'Createdby'   => 'admin',
            'Updatedby'   => 'admin'
        ]);
    }
}
