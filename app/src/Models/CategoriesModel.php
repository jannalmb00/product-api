<?php

namespace App\Models;

/**
 * Categories model handles data related to categories in the system
 *
 * It interacts with the database
 */
class CategoriesModel extends BaseModel
{

    public function insertNewCategory(array $new_category): mixed
    {
        // From base model , pass table name, array conatining key value pairs
        $last_id = $this->insert('category', $new_category);

        return $last_id;
    }


    public function updateCategory(array $update_category_data): mixed
    {
        // From base model , pass table name, array conatining key value pairs
        //    $last_id = $this->update('category', ["data" => $update_category_data[1]], ["category_id" => $update_category_data[0]]);

        //dd(is_array($update_category_data));

        $category_id_data = $update_category_data["category_id"];

        unset($update_category_data["category_id"]);

        //for update
        $last_id = $this->update('category', $update_category_data, ["category_id" => $category_id_data]);
        // dd($last_id);

        return $last_id;
    }

    function deleteCategory(string $category_id): int
    {
        return $this->delete('category', ["category_id" => $category_id]);
    }

    /**
     * GET: Retriveds the list of categories from the database with optional filtering, sorting and pagination
     *
     * @param array $filters The filters to apply to the query: 'category_name', 'category_type', 'parent_category'
     *
     * @return array List of categories after all the filter
     */
    public function getCategories(array $filters): array
    {
        // //? FOR FILTERING
        $filters_map = [];

        $sql = "SELECT c.*, pc.category_name AS parent_category_name
                FROM category c
                LEFT JOIN category pc ON c.parent_category_id = pc.category_id
                WHERE 1";

        // JOIN category pc ON c.category_id = pc.parent_category_id, , pc.category_name as parent_category_name

        // //? 1: FILTERING - CHECK THE DATA TYPE
        //Define filters
        $stringToFilter = ['category_name', 'category_type', 'parent_category'];

        // Loop: making string filters shorter
        foreach ($stringToFilter as $filterField) {

            // Checks if there is filter param and ands the sql statement for the filter, returns it
            // Call the prepareStringSQL function for each field
            //map of valid filters, current filter
            $filterResult = $this->prepareStringSQL($filters, $filterField, $filterField);

            // Check if sqlPart is not empty, meaning there is a filter for that
            if (!empty($filterResult['sqlPart'])) {

                // Adds filter to the map
                $filters_map[$filterField] = $filterResult['value'];

                // Adds filtered sql to base sql statement
                $sql .= $filterResult['sqlPart'];
            }
        }

        //? Sorting
        $approved_ordering = ['category_name', 'category_type', 'parent_category'];
        $sql = $this->sortAndOrder($filters, 'category_id',  $approved_ordering, $sql);
        //dd($sql);
        //? PAGINATE
        return $this->paginate($sql, $filters_map);
    }


    /**
     * GET: Retrieves the details of the specified category
     *
     * @param array $filter The filters to apply to the query:
     *
     * @return array List of details for the specified category
     */
    public function getCategoryById(array $filter): mixed
    {
        //Sends the id, table name, column name
        $result = $this->prepareIdSQL($filter['id'], 'category', 'category_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }

    /**
     * GET: Retrievs the brand of specified category in the system
     *
     * @param array $filters The filters to apply to the query:  'brand_name', 'brand_country'
     * @return array
     */
    public function getBrandsByCategory(array $filters): mixed
    {
        $category_id = $filters['category_id'];

        // Use both of the category id from the parent and direct cat id
        $filters_map = [
            "direct_category_id" => $category_id,
            "parent_category_id" => $category_id
        ];

        //* SQL query FROM brands, products and category
        $sql = "SELECT DISTINCT b.*
            FROM brands b
            JOIN product p ON b.brand_id = p.brand_id
            LEFT JOIN category c ON p.category_id = c.category_id
            WHERE p.category_id = :direct_category_id
               OR c.parent_category_id = :parent_category_id";

        //Provide the fitlers that we can accept ... I am not sure if we need filters for sub-collection resource but I will add just in case
        //? Erase the filters if we oont need it
        $stringToFilter = ['brand_name', 'brand_country'];

        // Loop through string filters and apply them w/ prepareStringSQL
        foreach ($stringToFilter as $filterField) {
            // Get filter SQL for this field
            $filterResult = $this->prepareStringSQL($filters, $filterField, $filterField);

            //  Add to query if there's a filter provided
            if (!empty($filterResult['sqlPart'])) {
                $filters_map[$filterField] = $filterResult['value'];
                $sql .= $filterResult['sqlPart'];
            }
        }

        //* Sorting
        $approved_ordering = ['brand_name', 'brand_country'];
        $sql = $this->sortAndOrder($filters, 'brand_id', $approved_ordering, $sql);

        //* Pagination
        return $this->paginate($sql, $filters_map);
    }
}
