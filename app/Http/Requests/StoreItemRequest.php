<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'Item_Code'  => 'required|unique:M_Item,Item_Code',
            'JanCD'      => 'required|digits:13',
            'MakerName'  => 'nullable|string|max:255',
            'ItemName'   => 'required|string|max:255',
            'ListPrice'  => 'required|numeric',
            'SalePrice'  => 'required|numeric',
            'Note'       => 'nullable|string',
            'sku.Size_Name.*'  => 'required',
            'sku.Color_Name.*' => 'required',
            'sku.JanCD.*'      => 'required|digits:13',
            'sku.Quantity.*'   => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'JanCD.digits' => 'JANコードは13桁でなければなりません。',
            'sku.JanCD.*.digits' => '各SKUのJANコードは13桁でなければなりません。',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation that doesn't trigger page reload errors
            $this->validateJanCodes($validator);
        });
    }

    protected function validateJanCodes($validator)
    {
        $jans = json_decode($this->input('sku.JanCD'), true) ?? [];
        
        foreach ($jans as $index => $jan) {
            if (strlen($jan) !== 13 || !ctype_digit($jan)) {
                $validator->errors()->add(
                    'sku_validation', 
                    "SKU行 " . ($index + 1) . " のJANコードが無効です。"
                );
                break; // Stop after first error
            }
        }
    }
}