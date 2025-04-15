<?php

namespace App\Models;

use App\Core\PasswordTrait;

/**
 * Product models handles data related to products in the system
 *
 * It interacts with the database
 */
class ProductsModel extends BaseModel
{

    use PasswordTrait;
    /**
     * Retrieves the products
     *
     * @param array $filters refers to the filter query parameters
     * @return array refers to the result of the product information
     */
    public function getProducts(array $filters): array
    {

        // $this->cryptPassword();

        // //? FOR FILTERING

        $filters_map = [];

        $sql = "SELECT p.* , c.category_name, b.brand_name
                FROM product p
                JOIN category c ON p.category_id = c.category_id
                JOIN brands b ON p.brand_id = b.brand_id
                WHERE 1";

        // //? 1: FILTERING - CHECK THE DATA TYPE
        //Define string things to filter
        //! ADD brand_name & category_name to the array
        $stringToFilter = ['product_name', 'product_origin', 'category_name', 'brand_name'];

        // Loop: making string filters shorter
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

        // //? Sorting
        $approved_ordering = ['product_name', 'product_origin'];
        $sql = $this->sortAndOrder($filters, 'product_id',  $approved_ordering, $sql);

        //dd($sql);
        //? PAGINATE
        return $this->paginate($sql, $filters_map);
    }

    /**
     * GET: Retrieves the detals of the specified product

     * @param array $filter The filters to apply the query
     * @return array List of details for the specified product
     */
    public function getProductById(array $filter): mixed
    {
        //Sends the id, table name, column name
        $result = $this->prepareIdSQL($filter['id'], 'product', 'product_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }

    /**
     * GET: Retrieves the nutrition of the specified product
     *
     * @param string $id ID of the desired product
     * @param array $filters The filters to apply to the query:
     * @return array List of nutrition of the spcified product
     */
    public function getProductNutrition(string $id, array $filters): mixed
    {

        $filters_map = [];
        //add player_id to map
        $filters_map["id"] = $id;

        $sql = "SELECT p.*, g.*
        FROM goals g
        INNER JOIN players p
        ON p.player_id = g.player_id
        WHERE p.player_id = :id";

        //? FILTERING
        if (isset($filters["tournament_id"])) {
            $sql .= " AND tournament_id = :tournament_id";
            $filters_map["tournament_id"] = $filters['tournament_id'];
        }
        if (isset($filters["match_id"])) {
            $sql .= " AND match_id = :match_id";
            $filters_map["match_id"] = $filters['match_id'];
        }

        return $this->paginate($sql, $filters_map);
    }

    /**
     * Insert a new product
     *
     * @param array $new_product refers to the new product data
     * @return mixed refers to the ID of inserted product
     */
    function insertProduct(array $new_product): mixed
    {
        $last_id = $this->insert("product", $new_product);
        return $last_id;
    }

    /**
     *  Update an existing product
     *
     * @param array $update_product_data refers to the updated product data
     * @return int refers to the mumber of rows affected
     */
    function updateProduct(array $update_product_date)
    {
        $product_id = $update_product_date["product_id"];
        unset($product_id["product_id"]);
        return $this->update('product', $update_product_date, ["product_id" => $product_id]);
    }

    /**
     * Delete a product
     *
     * @param string $product_id refers tpo the id to be deleted
     * @return int refers to the number of rows affected
     */
    function deleteProduct(string $product_id): int
    {
        return $this->delete('product', ["product_id" => $product_id]);
    }
}
