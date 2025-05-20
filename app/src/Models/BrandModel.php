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

        $sql = "SELECT DISTINCT p.*
                FROM products p
                WHERE p.brand_id = :brand_id";

        return $this->paginate($sql, $filters_map);
    }
}
