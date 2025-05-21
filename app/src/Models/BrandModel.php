<?php

namespace App\Models;

/**
 * Categories model handles data related to categories in the system
 *
 * It interacts with the database
 */
class BrandModel extends BaseModel
{
    public function getProductsByBrand(array $filters): array
    {

        $brand_id = $filters['brand_id'];
        $filters_map = ["brand_id" => $brand_id];

        $sql = "SELECT DISTINCT p.*,
                c.category_name, b.brand_name
                FROM products p
                JOIN categories c ON p.category_id = c.category_id
                JOIN brands b ON p.brand_id = b.brand_id
                WHERE p.brand_id = :brand_id";

        $stringToFilter = ['product_name', 'product_origin', 'category_name'];

        foreach ($stringToFilter as $filterField) {

            // Checks if there is filter param and ands the sql statement for the filter, returns it
            // Call the prepareStringSQL function for each field
            $filterResult = $this->prepareStringSQL($filters, $filterField, $filterField);

            // Check if sqlPart is not empty, meaning there is a filter for that
            if (!empty($filterResult['sqlPart'])) {

                // Adds filter to the map
                $filters_map[$filterField] = $filterResult['value'];

                // Adds filtered sql to base sql statement
                $sql .= $filterResult['sqlPart'];
            }
        }

        //$approved_ordering = ['product_name', 'product_origin'];
        $sql = $this->sortAndOrder($filters, 'product_id',  $stringToFilter, $sql);

        //dd($sql);

        return $this->paginate($sql, $filters_map);
    }
}
