<?php

namespace App\Exports;

use App\Models\LeafCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExampleExport implements FromCollection, WithHeadings
{
    protected $leafCategoryId;

    public function __construct($leafCategoryId)
    {
        $this->leafCategoryId = $leafCategoryId;
    }


    public function headings(): array
    {
        $leafCategory = LeafCategory::find($this->leafCategoryId);
        if (!$leafCategory) return [];

        $specFields = json_decode($leafCategory->spec_fields, true) ?? [];
        $specFieldNames = array_map(fn($field) => $field['name'], $specFields);

        $baseFields = [
            'name',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'alt_image_text',
            'sku',
            'category',
            'sub_category',
            'leaf_category',
            'brand',
            'design_number',
            'quantity',
            'price',
            'compare_price',
            'shipping',
            'shipping_addons',
            'sort_description',
            'description',
            'is_featured',
            'is_active',
            'has_variations',
        ];

        // Preparing the variation fields
        $firstVarFieldRaw = $leafCategory->v1st ?? '';
        $secondVarFieldRaw = $leafCategory->v2nd ?? '';

        $firstVarField = $firstVarFieldRaw !== '' ? strtolower(str_replace(' ', '_', $firstVarFieldRaw)) : false;
        $secondVarField = $secondVarFieldRaw !== '' ? strtolower(str_replace(' ', '_', $secondVarFieldRaw)) : false;

        $variationFields = [];

        if ($firstVarField || $secondVarField) {

            if ($secondVarField) array_unshift($variationFields, 'variation_' . $secondVarField);
            if ($firstVarField) array_unshift($variationFields, 'variation_' . $firstVarField);

            if ($secondVarField) array_unshift($variationFields, 'default_' . $secondVarField);
            if ($firstVarField) array_unshift($variationFields,  'default_' . $firstVarField);

            $variationFields = array_merge($variationFields, [
                'variation_sku',
                'variation_price',
                'variation_c_price',
                'variation_quantity',
            ]);
        }

        $imageFields = ['image1', 'image2', 'image3', 'image4', 'image5', 'default_image'];

        return array_merge($baseFields, $variationFields, $imageFields, $specFieldNames);
    }

    public function collection()
    {
        $leafCategory = LeafCategory::find($this->leafCategoryId);
        if (!$leafCategory) return collect([]);

        $specFields = json_decode($leafCategory->spec_fields, true) ?? [];
        $specFieldNames = array_map(fn($field) => $field['name'], $specFields);

        $firstVarFieldRaw = $leafCategory->v1st ?? '';
        $secondVarFieldRaw = $leafCategory->v2nd ?? '';

        $firstVarField = $firstVarFieldRaw !== '' ? strtolower(str_replace(' ', '_', $firstVarFieldRaw)) : false;
        $secondVarField = $secondVarFieldRaw !== '' ? strtolower(str_replace(' ', '_', $secondVarFieldRaw)) : false;

        $variationFields = [];

        if ($firstVarField || $secondVarField) {
            if ($secondVarField) array_unshift($variationFields, 'variation_' . $secondVarField);
            if ($firstVarField) array_unshift($variationFields, 'variation_' . $firstVarField);
            if ($secondVarField) array_unshift($variationFields, 'default_' . $secondVarField);
            if ($firstVarField) array_unshift($variationFields,  'default_' . $firstVarField);

            $variationFields = array_merge($variationFields, [
                'variation_sku',
                'variation_price',
                'variation_c_price',
                'variation_quantity',
            ]);
        }

        $imageFields = [
            'image1' => 'img1.jpg',
            'image2' => 'img2.jpg',
            'image3' => 'img3.jpg',
            'image4' => 'img4.jpg',
            'image5' => 'img5.jpg',
            'default_image' => 'img1.jpg',
        ];

        $specData = array_fill_keys($specFieldNames, '');

        return collect([
            array_merge(
                [
                    'name' => '',
                    'meta_title' => '',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'alt_image_text' => '',
                    'sku' => '',
                    'category' => $leafCategory->subCategory->category->name ?? '',
                    'sub_category' => $leafCategory->subCategory->name ?? '',
                    'leaf_category' => $leafCategory->name ?? '',
                    'brand' => '',
                    'design_number' => '',
                    'quantity' => '',
                    'price' => '',
                    'compare_price' => '',
                    'shipping' => '',
                    'shipping_adons' => '',
                    'sort_description' => '',
                    'description' => '',
                    'is_featured' => '',
                    'is_active' => 1,
                    'has_variations' => '',
                ],
                array_fill_keys($variationFields, ''),
                $imageFields,
                $specData
            )
        ]);
    }
}
