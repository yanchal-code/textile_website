<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductImage;
use App\Models\ProductVideo;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\LeafCategory;
use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow
{

    protected $leafCategoryId;

    public function __construct($leafCategoryId)
    {
        $this->leafCategoryId = $leafCategoryId;
    }

    public function collection(Collection $rows)
    {
        $leafCategory = LeafCategory::find($this->leafCategoryId);
        if (!$leafCategory) {
            throw new \Exception('Leaf category not found.');
        }

        $specFields = json_decode($leafCategory->spec_fields, true) ?? [];


        $firstVarFieldRaw = $leafCategory->v1st ?? '';
        $secondVarFieldRaw = $leafCategory->v2nd ?? '';

        $firstVarField = $firstVarFieldRaw !== '' ? strtolower(str_replace(' ', '_', $firstVarFieldRaw)) : false;
        $secondVarField = $secondVarFieldRaw !== '' ? strtolower(str_replace(' ', '_', $secondVarFieldRaw)) : false;

        $currentProduct = null;

        foreach ($rows as $row) {
            $variation = null;

            if ($row['name'] !== null && $row['sku'] !== null) {

                $category = Category::firstOrCreate(
                    ['name' => $row['category']],
                    ['slug' => Str::slug($row['category'])]
                );

                $subCategoryId = null;
                if (!empty($row['sub_category'])) {
                    $subCategory = SubCategory::firstOrCreate(
                        ['name' => $row['sub_category'], 'category_id' => $category->id],
                        ['slug' => Str::slug($row['sub_category'])]
                    );
                    $subCategoryId = $subCategory->id;
                }

                $brandid = null;
                if (!empty($row['brand'])) {
                    $brand = Brand::firstOrCreate(
                        ['name' => $row['brand']],
                        ['slug' => Str::slug($row['brand'])]
                    );
                    $brandid = $brand->id;
                }


                foreach ($specFields as $specField) {
                    $fieldName = $specField['name'];
                    $normalizedFieldName = strtolower(str_replace(' ', '_', $fieldName));

                    $normalizedRow = [];
                    foreach ($row as $key => $value) {
                        $normalizedKey = strtolower(str_replace(' ', '_', $key));
                        $normalizedRow[$normalizedKey] = $value;
                    }

                    $specs[$fieldName] = $normalizedRow[$normalizedFieldName] ?? null;
                }


                if ($firstVarFieldRaw || $secondVarFieldRaw) {

                    $data =  [
                        'name' => $row['name'],
                        'meta_title' => $row['meta_title'],
                        'meta_description' => $row['meta_description'],
                        'meta_keywords' => $row['meta_keywords'],
                        'alt_image_text' => $row['alt_image_text'],

                        'slug' => Str::slug($row['name']),
                        'sku' => $row['sku'],
                        'price' => $row['price'],

                        'compare_price' => $row['compare_price'],
                        'category_id' => $category->id,
                        'sub_category_id' => $subCategoryId,
                        'leaf_category_id' => $this->leafCategoryId,
                        'brand_id' => $brandid,
                        'design_number' => $row['design_number'],
                        'quantity' => $row['quantity'],
                        'sort_description' => $row['sort_description'],
                        'description' => $row['description'],

                        'has_variations' => $row['has_variations'],
                        'is_featured' => $row['is_featured'] ?? 0,
                        'shipping' => $row['shipping'],
                        'shippingAddons' => $row['shipping_addons'],

                        'specs' => json_encode($specs)
                    ];

                    if ($firstVarField) {
                        $data['color'] = $row['default_' . $firstVarField];
                    }

                    if ($secondVarField) {
                        $data['size'] = $row['default_' . $secondVarField];
                    }

                } else {
                    $data = [

                        'name' => $row['name'],

                        'meta_title' => $row['meta_title'],
                        'meta_description' => $row['meta_description'],
                        'meta_keywords' => $row['meta_keywords'],
                        'alt_image_text' => $row['alt_image_text'],

                        'slug' => Str::slug($row['name']),

                        'sku' => $row['sku'],
                        'price' => $row['price'],
                        'compare_price' => $row['compare_price'],

                        'category_id' => $category->id,
                        'sub_category_id' => $subCategoryId,
                        'leaf_category_id' => $this->leafCategoryId,
                        'brand_id' => $brandid,

                        'design_number' => $row['design_number'],
                        'quantity' => $row['quantity'],

                        'sort_description' => $row['sort_description'],
                        'description' => $row['description'],

                        'is_featured' => $row['is_featured'] ?? 0,
                        'shipping' => $row['shipping'],
                        'shippingAddons' => $row['shipping_addons'],
                        'specs' => json_encode($specs)
                    ];
                }

                $currentProduct = Product::create($data);
            }


            if ($firstVarFieldRaw || $secondVarFieldRaw) {

                if (!empty($row['variation_sku'])) {
                    $variation_data = [
                        'product_id' => $currentProduct->id,
                        'sku' => $row['variation_sku'],
                        'price' => $row['variation_price'],
                        'c_price' => $row['variation_c_price'],
                        'quantity' => $row['variation_quantity']
                    ];
                    if ($firstVarField) {
                        $variation_data['color'] = $row['variation_' . $firstVarField];
                    }

                    if ($secondVarField) {
                        $variation_data['size'] = $row['variation_' . $secondVarField];
                    }

                    $variation = ProductVariation::create($variation_data);
                }
            }

            // Handle Product Images
            for ($i = 1; $i <= 5; $i++) {

                if (!empty($row['image' . $i])) {
                    ProductImage::create([
                        'product_id' => $currentProduct->id,
                        'variation_id' => $variation ? $variation->id : null,
                        // 'image' => 'uploads/products/' . $row['image' . $i],
                        'image' => $row['image' . $i],
                        'is_default' => ($row['image' . $i] == $row['default_image']) ? 1 : 0
                    ]);
                }
            }

        }
    }
}
