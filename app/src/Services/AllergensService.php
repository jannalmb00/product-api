<?php

namespace App\Services;

use App\Core\Result;
use App\Models\AllergensModel;
use App\Validation\Validator;


class AllergensService
{
    public function __construct(private AllergensModel $allergens_model) {}

    function createAllergens(array $new_allergens_info): Result
    {
        //TODO: 1) Validate the received resource data about the new resource to be created.
        //* VALIDATE USING VALITRON

        $rules = array(
            'allergen_id' => [
                ['regex', '/^[A-Z][0-9]{2}$/']
            ],
            'allergen_name' => [
                'required',
                'ascii',
                array('lengthMin', 4)
            ],
            "allergen_reaction_type" => [
                'required',
                array('lengthMin', 4)
            ],
            "food_group" => [
                'required',
                array('in', ["Fruits", "Vegetables", "Pulses", "Grains", "Proteins", "Dairy", "Fats and Oils", "Sweets and Snacks", "Beverages"])
            ],
            "food_type" => [
                'required',
                array('lengthMin', 4)
            ],
            "food_origin" => [
                'ascii',
                array('lengthMin', 4)

            ],
            "food_item" => [
                'required',
                'ascii',
                array('lengthMin', 4)
            ]

        );
        //! RETRURN RIGHT AWAY AS SOON AS YOU DETECT ANY INVALID INPUTS

        //TODO: 2) Insert the resource into the DB table
        //* We can use an array and using the first 1 so we can make our lives easier
        $new_allergen = $new_allergens_info[0];
        $validator = new Validator($new_allergen, [], 'en');
        $validator->mapFieldsRules($rules);


        if (!$validator->validate()) {

            echo $validator->errorsToString();
            // echo '<br>';
            echo $validator->errorsToJson();
            return Result::failure("error!");
        }
        $last_inserted_id =  $this->allergens_model->insertAllergen($new_allergen); //
        return Result::success("The allergen has been created successfully!", $last_inserted_id);
    }

    function deleteAllergens(array $condition): Result
    {
        if (!isset($condition['allergen_id'])) {
            return Result::failure("Allergen ID is required");
        }
        //TODO: validate the id
        echo "Service";
        //dd($condition);
        // $validator = new Validator(['regex', '/^[A-Z][0-9]{2}$/']);
        // $validator->rule('allergen_id', 'regex', '/^[A-Z][0-9]{2}$/');
        $validator = new Validator([
            'allergen_id' => ['regex' => '/^[A-Z][0-9]{2}$/']
        ]);

        echo (!$validator->validate());

        if (!$validator->validate()) {
            //     echo $validator->errorsToString();
            //    // echo '<br>';
            echo $validator->errorsToJson();
            return Result::failure("Allergen ID is not valid");
        }

        $rowsDeleted = $this->allergens_model->deleteAllergen($condition);

        if ($rowsDeleted <= 0) {
            return Result::failure("No data has been deleted");
        }
        return Result::success("The allergen has been deleted");
    }

    function updateAllergen(array $data, array $condition): Result
    {
        $rules = array(
            'allergen_id' => [
                ['regex', '/^[A-Z][0-9]{2}$/']
            ],
            'allergen_name' => [
                'required',
                'ascii',
                array('lengthMin', 4)
            ],
            "allergen_reaction_type" => [
                'required',
                array('lengthMin', 4)
            ],
            "food_group" => [
                'required',
                array('in', ["Fruits", "Vegetables", "Pulses", "Grains", "Proteins", "Dairy", "Fats and Oils", "Sweets and Snacks", "Beverages"])
            ],
            "food_type" => [
                'required',
                array('lengthMin', 4)
            ],
            "food_origin" => [
                'ascii',
                array('lengthMin', 4)

            ],
            "food_item" => [
                'required',
                'ascii',
                array('lengthMin', 4)
            ]
        );

        $validator = new Validator($data, [], 'en');
        $validator->mapFieldRules($data, $rules);

        if (!$validator->validate()) {
            //     echo $validator->errorsToString();
            //    // echo '<br>';
            //     echo $validator->errorsToJson();
            return Result::failure("Data is not valid");
        }

        $rowsUpdate = $this->allergens_model->updateAllergen($data, $condition);
        if ($rowsUpdate <= 0) {
            return Result::failure("No row has been updated");
        }
        return Result::success("Updated successfully");
    }
}
