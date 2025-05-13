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

    // public function insertNewProduct(array $new_product): mixed
    // {
    //     // From base model , pass table name, array conatining key value pairs
    //     $last_id = $this->insert('products', $new_product);

    //     //for update
    //     //$last_id = $this->update('products', $new_product, ["product_id" => ]);


    //     return $last_id;
    // }

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
        $result = $this->prepareIdSQL($filter['id'], 'products', 'product_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }

    /**
     * GET: Retrieves the nutrition of the specified product
     *
     * @param string $id ID of the desired product
     * @param array $filters The filters to apply to the query:
     * @return array List of nutrition of the specified product
     */
    public function getProductByNutrition(array $filters): mixed
    {


        //* Get the product id
        $product_id = $filters['id'];

        // Product id will be initialized with the filters map
        $filters_map = ["product_id" => $product_id];

        //* SQL query to join nutrition with products
        $sql = " SELECT n.*, p.product_id, p.product_name, p.product_barcode, p.product_origin, p.product_serving_size, p.product_image, b.brand_name, c.category_name
        FROM nutritions n
        JOIN products p
        ON n.nutritional_id = p.nutrition_id
        JOIN products pc
        ON p.product_id = pc.product_id
        LEFT JOIN brands b
        ON b.brand_id = p.brand_id
        LEFT JOIN categories c
        ON c.category_id = p.category_id
        WHERE pc.product_id = :product_id
        ";

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
        //     dd($new_product);
        // table name, data array
        $last_id = $this->insert("products", $new_product);
        return $last_id;
    }

    /**
     *  Update an existing product
     *
     * @param array $update_product_data refers to the updated product data
     * @return int refers to the mumber of rows affected
     */
    function updateProduct(array $update_product_data): mixed
    {
        $product_id_data = $update_product_data["product_id"];
        //      dd($product_id_data);
        //  dd($update_product_data);

        unset($update_product_data["product_id"]);


        // return $this->update('product', $update_product_date, ["product_id" => $product_id]);

        //for update
        $last_id = $this->update('products', $update_product_data, ["product_id" => $product_id_data]);
        // dd($last_id);

        return $last_id;
    }

    /**
     * Delete a product
     *
     * @param string $product_id refers tpo the id to be deleted
     * @return int refers to the number of rows affected
     */
    function deleteProduct(string $product_id): int
    {
        return $this->delete('products', ["product_id" => $product_id]);
    }
}
