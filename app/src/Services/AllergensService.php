<?php

namespace App\Services;

use App\Core\Result;
use App\Models\AllergensModel;


class AllergensService
{
    public function __construct(private AllergensModel $allergens_model) {}

    function createAllergens(array $new_allergens_info): Result
    {
        //TODO: 1) Validate the received resource data about the new resource to be created.
        //* VALIDATE USING VALITRON
        //! RETRURN RIGHT AWAY AS SOON AS YOU DETECT ANY INVALID INPUTS
        if(1 == 0){
            return Result::failure("teRROR!", [
                "allergen_name"  => "The following allergen name is invalid",
                "allergen_type" => "The allergen type is invalid",
            ]);
        }
        //TODO: 2) Insert the resource into the DB table
        //* We can use an array and using the first 1 so we can make our lives easier
        $new_allergen = $new_allergens_info[0];
        //  dd($new_allergen);
        $last_inserted_id =  $this->allergens_model->insertAllergen($new_allergen); //
        // Result pattern is implemented
        // $last_insert_id = 29;
        // Return a successful result
        return Result::success("The player has been created successfully!", $last_inserted_id);
    }
}
