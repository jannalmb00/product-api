<?php

namespace App\Models;

/**
 * Categories model handles data related to categories in the system
 *
 * It interacts with the database
 */
class CategoriesModel extends BaseModel
{

    /**
     * POST: Insert a new category in the database
     *
     * @param array $new_category Associative array of category data
     * @return mixed ID_of the inserted record or result from operation
     */
    public function insertNewCategory(array $new_category): mixed
    {
        // fetch last inserted category id
        $sql = "SELECT category_id FROM categories ORDER BY key_id DESC LIMIT 1";
        $lastCatId = $this->fetchSingle($sql);

        if ($lastCatId != null) {

            if (preg_match('/C-(\d+)/', $lastCatId['category_id'], $matches)) { {

                    // convert last digits to int
                    $lastCatNumber = (int) $matches[1];
                    $nextCatNumber = $lastCatNumber + 1;
                    $nextCatId = 'C-' . sprintf("%04d", $nextCatNumber);
                }
            }
        }

        // add category id to array
        $new_category['category_id'] = $nextCatId;
        // From base model , pass table name, array conatining key value pairs
        $last_id = $this->insert('categories', $new_category);

        return $last_id;
    }

    /**
     * PUT: Method use to update an existing category in the database based on category id
     * @param array $update_category_data Associative array containing category fields and category id to update
     * @return int the result of the update operation
     */
    public function updateCategory(array $update_category_data): mixed
    {
        //extract the category_id
        $category_id_data = $update_category_data["category_id"];
        //unset the category id to avoide updating it
        unset($update_category_data["category_id"]);

        //for update
        $last_id = $this->update('categories', $update_category_data, ["category_id" => $category_id_data]);

        return $last_id;
    }

    /**
     * DELETE: Delete a category from the database using the given category_id
     * @param string $category_id identifier to to perform delete
     * @return int the number of rows affected by the delete operation
     */
    function deleteCategory(string $category_id): int
    {
        //perform the delete operaton using the base model's delete method
        return $this->delete('categories', ["category_id" => $category_id]);
    }

    /**
     * GET: Retrieves the list of categories from the database with optional filtering, sorting and pagination
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
                FROM categories c
                LEFT JOIN categories pc ON c.parent_category_id = pc.category_id
                WHERE 1";

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
        $result = $this->prepareIdSQL($filter['id'], 'categories', 'category_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }

    /**
     * GET: Retrieves the brand of specified category in the system
     *
     * @param array $filters The filters to apply to the query:  'brand_name', 'brand_country'
     * @return array
     */
    public function getBrandsByCategory(array $filters): mixed
    {
        $category_id = $filters['category_id'];

        // Use both of the category id from the parent and direct cat id
        $filters_map = [
            "category_id" => $category_id,
        ];

        //* SQL query FROM brands, products and category
        $sql = "SELECT DISTINCT c.category_id, c.category_name, b.*, c.*
            FROM brands b
            JOIN products p ON b.brand_id = p.brand_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.category_id = :category_id";

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
