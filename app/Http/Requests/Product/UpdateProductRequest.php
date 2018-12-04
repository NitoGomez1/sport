<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'brand_id'         => ['integer', 'exists:brands,id'],
            'category_id'      => ['integer', 'exists:categories,id'],
            'product_level_id' => ['nullable', 'integer', 'exists:product_levels,id'],
            'dkt_id'           => ['nullable', 'integer', 'unique:products,dkt_id'],
            'name'             => ['string', 'required', 'unique:products,name'],
            'description'      => ['string'],
            'source'           => ['nullable', 'string', 'url', 'unique:products,source'],
            'image'            => ['string', 'url'],
            'price'            => ['numeric', 'max:9999'],
            'gtin'             => ['nullable', 'string', 'digits:13', 'unique:products,gtin'],
            'material'         => ['string', 'max:20'],
            'color'            => ['nullable', 'string', 'max:20'],
            'size'             => ['nullable', 'string', 'max:20'],
            'supermodel'       => ['nullable', 'string', 'max:20'],
            'id_article'       => ['nullable', 'numeric'],
            'review_count'     => ['numeric'],
            'product_md5'      => ['string'],
            'is_prototype'     => ['boolean'],
        ];
    }
}
