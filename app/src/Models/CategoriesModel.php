<?php

namespace App\Models;

class CategoriesModel extends BaseModel
{

    public function getCategories(array $filters): array
    {
        // //? FOR FILTERING
        $filters_map = [];

        $sql = "SELECT * FROM category WHERE 1";

        // //? 1: FILTERING - CHECK THE DATA TYPE
        //Define filters
        $stringToFilter = ['category_name', 'category_type', 'parent_category'];

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
        $approved_ordering = ['category_name', 'category_type', 'parent_category'];
        $sql = $this->sortAndOrder($filters, 'category_id',  $approved_ordering, $sql);

        //? PAGINATE
        return $this->paginate($sql, $filters_map);
    }

    public function getCategoryById(array $filter): mixed
    {
        //Sends the id, table name, column name
        $result = $this->prepareIdSQL($filter['id'], 'category', 'category_id');

        //? PAGINATE
        return $this->paginate($result['sqlPart'], $result[0]);
    }
}
