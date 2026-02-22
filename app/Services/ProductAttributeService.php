<?php

namespace App\Services;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductAttributeService
{
    /**
     * Get paginated list of product attributes with value and product counts
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing attributes and total count
     */
    public function getList(
        array $search = [],
        string $sortfield = 'position',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = ProductAttribute::query()
            ->select([
                'llx_product_attribute.rowid',
                'llx_product_attribute.ref',
                'llx_product_attribute.label',
                'llx_product_attribute.position',
                DB::raw('COUNT(DISTINCT llx_product_attribute_value.rowid) as nb_of_values'),
                DB::raw('COUNT(DISTINCT llx_product_attribute_combination.fk_product_child) as nb_products')
            ])
            ->leftJoin(
                'llx_product_attribute_value',
                'llx_product_attribute_value.fk_product_attribute',
                '=',
                'llx_product_attribute.rowid'
            )
            ->leftJoin(
                'llx_product_attribute_combination2val',
                'llx_product_attribute_combination2val.fk_prod_attr_val',
                '=',
                'llx_product_attribute_value.rowid'
            )
            ->leftJoin(
                'llx_product_attribute_combination',
                'llx_product_attribute_combination.rowid',
                '=',
                'llx_product_attribute_combination2val.fk_prod_combination'
            )
            ->where('llx_product_attribute.entity', $entity)
            ->groupBy([
                'llx_product_attribute.rowid',
                'llx_product_attribute.ref',
                'llx_product_attribute.label',
                'llx_product_attribute.position'
            ]);

        // Apply search filters
        if (!empty($search['ref'])) {
            $query->where('llx_product_attribute.ref', 'like', '%' . $search['ref'] . '%');
        }

        if (!empty($search['label'])) {
            $query->where('llx_product_attribute.label', 'like', '%' . $search['label'] . '%');
        }

        // Get total count before pagination
        $total = $query->count(DB::raw('DISTINCT llx_product_attribute.rowid'));

        // Apply sorting and pagination
        $sortfield = str_replace('t.', 'llx_product_attribute.', $sortfield);
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $attributes = $query->get();

        return [
            'attributes' => $attributes,
            'total' => $total,
        ];
    }

    /**
     * Get a single product attribute by ID
     *
     * @param int $id Attribute ID
     * @return ProductAttribute|null
     */
    public function getById(int $id): ?ProductAttribute
    {
        return ProductAttribute::with('values')->find($id);
    }

    /**
     * Create a new product attribute
     *
     * @param array $data Attribute data
     * @return ProductAttribute
     */
    public function create(array $data): ProductAttribute
    {
        return ProductAttribute::create($data);
    }

    /**
     * Update an existing product attribute
     *
     * @param int $id Attribute ID
     * @param array $data Updated data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $attribute = ProductAttribute::findOrFail($id);
        return $attribute->update($data);
    }

    /**
     * Delete a product attribute
     *
     * @param int $id Attribute ID
     * @return bool
     */
    public function delete(int $id): bool
    {
        $attribute = ProductAttribute::findOrFail($id);
        return $attribute->delete();
    }
}
