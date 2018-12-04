<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'brand_id'         => ['integer', 'required', 'exists:brands,id'],
            'category_id'      => ['integer', 'required', 'exists:categories,id'],
            'product_level_id' => ['nullable', 'integer', 'exists:product_levels,id'],
            'dkt_id'           => ['nullable', 'integer', 'unique:products,dkt_id'],
            'name'             => ['string', 'required', 'unique:products,name'],
            'description'      => ['string', 'required'],
            'source'           => ['nullable', 'string', 'url', 'unique:products,source'],
            'image'            => ['required', 'string', 'url'],
            'price'            => ['required', 'numeric', 'max:9999'],
            'gtin'             => ['nullable', 'string', 'digits:13', 'unique:products,gtin'],
            'material'         => ['required', 'string', 'max:20'],
            'color'            => ['nullable', 'string', 'max:20'],
            'size'             => ['nullable', 'string', 'max:20'],
            'supermodel'       => ['nullable', 'string', 'max:20'],
            'id_article'       => ['nullable', 'numeric'],
            'review_count'     => ['numeric'],
            'product_md5'      => ['required', 'string'],
            'is_prototype'     => ['boolean'],
        ];
    }
}
