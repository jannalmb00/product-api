<?php

namespace App\Models;

class AllergensModel extends BaseModel
{

    public function getAllergens(array $filters): array
    {
        // //? FOR FILTERING
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
    public function getAllergenById(array $filter): mixed
    {
        //Sends the id, table name, column name
        $result = $this->prepareIdSQL($filter['id'], 'allergens', 'allergen_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }
}
