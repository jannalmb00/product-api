<?php

namespace App\Models;

/**
 *
 * Allergen model handles data related to allergns in the system
 */
class AllergensModel extends BaseModel
{
    /**
     * GET: Retrieves the list of allergens from the databse with optional filtering, sorting and pagination
     *
     * @param array $filters The filtrs to apply: 'allergen_name', 'allergen_reaction_type ', 'food_group', 'food_origin', and 'food_type'
     *
     * @return array List of allergens after all the filters
     */
    public function getAllergens(array $filters): array
    {
        //? FOR FILTERING
        $filters_map = [];

        $sql = "SELECT * FROM allergens WHERE 1";

        // //? 1: FILTERING - CHECK THE DATA TYPE
        //Define filters
        $stringToFilter = ['allergen_name', 'allergen_reaction_type ', 'food_group', 'food_origin', 'food_type'];

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

        //? Sorting
        $approved_ordering = ['allergen_name', 'allergen_reaction_type ', 'food_group', 'food_origin', 'food_type'];
        $sql = $this->sortAndOrder($filters, 'allergen_id',  $approved_ordering, $sql);

        //? PAGINATE
        return $this->paginate($sql, $filters_map);
    }
    /**
     * GET: Retrives the details of the specified product
     *
     * @param array $filter The filters to apply the query:
     *
     * @return array List of fetails for the specified allergen
     *
     */
    public function getAllergenById(array $filter): mixed
    {
        //Sends the id, table name, column name
        $result = $this->prepareIdSQL($filter['id'], 'allergens', 'allergen_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }

    /**
     * GET: Retrives the ingredietns of the specified allergen
     *
     * @param array $filters The filters to apply the query:
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

        // $sql = " SELECT DISTINCT pi.* FROM ingredients product_ingredients pi WHERE i

        // Provide the fitlers that we can accept ... I am not sure if we need filters for sub-collection resource but I will add just in case
        //? Erase the filters if we oont need it
        $stringToFilter = ['ingredient_name', 'processing_type'];

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

        // Add filter for GMO status
        if (isset($filters['isGMO']) && ($filters['isGMO'] === '1' || $filters['isGMO'] === '0')) {
            $sql .= " AND i.isGMO = :isGMO";
            $filters_map['isGMO'] = (int)$filters['isGMO'];
        }

        //* Sorting
        $approved_ordering = ['ingredient_name', 'processing_type', 'isGMO'];
        $sql = $this->sortAndOrder($filters, 'ingredient_id', $approved_ordering, $sql);

        //* Pagination
        return $this->paginate($sql, $filters_map);
    }

    function insertAllergen($new_allergen): mixed
    {
        $last_id = $this->insert("allergens", $new_allergen);
        // $last_id = $this->update("allergens", $new_allergen);
        return $last_id;
    }
    function deleteAllergen(string $allergen_id): int
    {
        return $this->delete('allergens', ["allergen_id" => $allergen_id]);
    }

    function updateAllergen(array $data, array $condition)
    {
        return $this->update('allergens', $data, $condition);
    }
}
