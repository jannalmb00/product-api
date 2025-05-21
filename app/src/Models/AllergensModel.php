<?php

namespace App\Models;

use App\Exceptions\HttpNoContentException;

/**
 *
 * Allergen model handles data related to allergens in the system
 */
class AllergensModel extends BaseModel
{
    /**
     * GET: Retrieves the list of allergens from the database with optional filtering, sorting and pagination
     *
     * @param array $filters The filters to apply: 'allergen_name', 'allergen_reaction_type ', 'food_group', 'food_origin', and 'food_type'
     *
     * @return array List of allergens after all the filters
     */
    public function getAllergens(array $filters): array
    {
        //? FOR FILTERING
        $filters_map = [];

        $sql = "SELECT * FROM allergens a WHERE 1";

        // //? 1: FILTERING - CHECK THE DATA TYPE
        //Define filters
        $stringToFilter = ['allergen_name', 'allergen_reaction_type ', 'food_group', 'food_origin', 'food_type', 'food_item'];

        // Loop: making string filters shorter
        foreach ($stringToFilter as $filterField) {

            // Checks if there is filter param and sql statement for the filter, returns it
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

        //? Sorting
        $approved_ordering = ['allergen_name', 'allergen_reaction_type ', 'food_group', 'food_origin', 'food_type', 'food_item'];
        $sql = $this->sortAndOrder($filters, 'allergen_id',  $approved_ordering, $sql);

        //? PAGINATE
        return $this->paginate($sql, $filters_map);
    }
    /**
     * GET: Retrieves the details of the specified product
     *
     * @param array $param The parameter taken from the URI: allergen_id
     *
     * @return array List of details for the specified allergen
     *
     */
    public function getAllergenById(array $param): mixed
    {
        //Sends the id, table name, column name
        $result = $this->prepareIdSQL($param['id'], 'allergens', 'allergen_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }

    /**
     * GET: Retrieves the ingredients of the specified allergen
     *
     * @param array $filters The filters to apply the query: 'allergen_id', 'ingredient_name', 'processing_type', 'isGMO'
     *
     * @return array List of ingredients of the specified allergen
     */
    public function getIngredientsByAllergen(array $filters): mixed
    {
        //* Get the allergen id
        $allergen_id = $filters['allergen_id'];

        // Allergen id will be initialized with the filters map
        $filters_map = ["allergen_id" => $allergen_id];

        //* SQL query to join ingredients with allergens
        $sql = "SELECT DISTINCT i.* FROM ingredients i WHERE i.allergen_id = :allergen_id";

        // Provide the filters that we can accept
        $stringToFilter = ['ingredient_name', 'processing_type', 'isGMO'];

        //* Loop through string filters and apply them w/ prepareStringSQL
        foreach ($stringToFilter as $filterField) {
            // Get filter SQL for this field
            $filterResult = $this->prepareStringSQL($filters, $filterField, $filterField);

            // If filter was provided, we add it to the query
            if (!empty($filterResult['sqlPart'])) {
                $filters_map[$filterField] = $filterResult['value'];
                $sql .= $filterResult['sqlPart'];
            }
        }

        //* Sorting
        $approved_ordering = ['ingredient_name', 'processing_type', 'isGMO'];
        $sql = $this->sortAndOrder($filters, 'ingredient_id', $approved_ordering, $sql);

        //* Pagination
        return $this->paginate($sql, $filters_map);
    }

    /**
     * Insert a new allergen
     * @param mixed $new_allergen refers to the new allergen info
     * @return mixed returns the id of the inserted allergen
     */
    function insertAllergen($new_allergen): mixed
    {
        $last_id = $this->insert("allergens", $new_allergen);
        return $last_id;
    }

    /**
     * Updates an existing allergen
     * @param array $update_allergen_data refers to the updated allergen data
     * @return int refers to the number of rows affected
     */
    function updateAllergen(array $update_allergen_data)
    {
        // Extract allergen id from the data array
        $allergen_id = $update_allergen_data["allergen_id"];

        // Removes the allergen id from the array, we don't want to update that
        unset($update_allergen_data["allergen_id"]);

        // call update method in base model
        return $this->update('allergens', $update_allergen_data, ["allergen_id" =>  $allergen_id]);
    }


    /**
     * Delete an allergen based on allergen_id
     * @param string $allergen_id refers to the ID of the allergen
     * @return int refers to the number of rows affected
     */
    function deleteAllergen(string $allergen_id): int
    {
        // call delete method in base model
        return $this->delete('allergens', ["allergen_id" => $allergen_id]);
    }
}
