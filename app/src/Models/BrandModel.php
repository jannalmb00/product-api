<?php

namespace App\Models;

/**
 * Brand model handles data related to brands in the system
 *
 * It interacts with the database
 */
class BrandModel extends BaseModel
{
    /**
     *  Retrieves products that belong to a specific brand, with optional filtering and sorting
     * @param array $filters
     * @return array
     */
    public function getProductsByBrand(array $filters): array
    {
        // Extract brand ID from the filters
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
;
        $sql = $this->sortAndOrder($filters, 'product_id',  $stringToFilter, $sql);

        return $this->paginate($sql, $filters_map);
    }
}
