<?php

namespace App\Models;

/**
 * Product models handles data related to products in the system
 *
 * It interacts with the database
 */
class ProductsModel extends BaseModel
{

    public function insertNewProduct(array $new_product): mixed
    {
        // From base model , pass table name, array conatining key value pairs
        $last_id = $this->insert('product', $new_product);

        //for update
        //   $last_id = $this->update('products', $new_product, ["product_id" => ]);


        return $last_id;
    }

    public function getProducts(array $filters): array
    {
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
     *GET: Retrieves the detals of the specified product

     * @param array $filter The filters to apply the query:
     *
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
     *
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
}
