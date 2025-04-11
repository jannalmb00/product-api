<?php

namespace App\Services;

use App\Models\CategoriesModel;
use App\Core\Result;
use App\Validation\Validator;

class CategoriesService
{

    public function __construct(private CategoriesModel $model, private Validator $validator) {}

    function createCategories(array $new_category_info): Result
    { // returns Result class
        // TODO: 1- Validate the recieved data about the new resource to be created.

        //--- here is where you do the checklist
        //* Using Valitron -- use VALIDATOR class (this uses Valitron already)
        //! Return as soon as you detect any invalid inputs -- use early return technique. continue if valid.  ---> RETURN Result::failure (set the code in the controller not here)
        // Return Result::failure("Error!", ["username"=>"wrong username"] );

        $rules = array(
            'category_id' => [
                ['regex', '/^[A-Z]-[0-9]{4}$/']
            ],
            'category_name' => [
                'required',
                'ascii',
                array('lengthMin', 3)
            ],
            "category_description" => [
                'ascii',
                array('lengthMin', 3)
            ],
            "parent_category_id" => [
                ['regex', '/^[A-Z]-[0-9]{4}$/']
            ],
            "category_type" => [
                'required',
                array('lengthMin', 4)
            ],
            "category_level" => [
                'required',
                array('in', ["parent", "child"])
            ],
            "category_tags" => [
                'required',
                'ascii',
                array('lengthMin', 4)
            ]
        );

        // ? 2- Insert new resource into the DB table
        //* Just process the first collection / first element in the array, if there are any errors just do that
        $new_category = $new_category_info[0];
        $validator = new Validator($new_category, [], 'en');
        $validator->mapFieldsRules($rules);

        // return failure if there's error in validation in the first index
        if (!$validator->validate()) {
            return Result::failure("Error inserting new category to database", $validator->errorsToString());
        }
        $last_insert_id = $this->model->insertNewCategory($new_category);

        // return successful result
        return Result::success("Category has been created successfully!", $last_insert_id);
    }

    function updateCategory(array $update_category_data): Result
    { // returns Result class
        // TODO: 1- Validate the recieved data about the new resource to be created.
        $rules = array(
            'category_id' => [
                ['regex', '/^[A-Z]-[0-9]{4}$/']
            ],
            'category_name' => [
                'ascii',
                array('lengthMin', 3)
            ],
            "category_description" => [
                'ascii',
                array('lengthMin', 3)
            ],
            "parent_category_id" => [
                ['regex', '/^[A-Z]-[0-9]{4}$/']
            ],
            "category_type" => [
                'ascii',
                array('lengthMin', 4)
            ],
            "category_level" => [
                'ascii',
                array('in', ["parent", "child"])
            ],
            "category_tags" => [
                'ascii',
                array('lengthMin', 4)
            ]
        );

        // ? 2- Insert new resource into the DB table
        //* Just process the first collection / first element in the array, if there are any errors just do that
        //$update_category_data = $update_category[0];
        $validator = new Validator($update_category_data);
        $validator->mapFieldsRules($rules);
        //dd($validator);

        // return failure if there's error in validation in the first index
        if (!$validator->validate()) {
            return Result::failure("Error updating category", $validator->errorsToJson());
        }

        $this->model->updateCategory($update_category_data);

        // return successful result
        return Result::success("Category has been updated successfully!");
    }


    function deleteCategories(array $category_ids): Result
    {
        $validation_errors = []; // if array has element then there's error

        //TODO: loop through the received list of allergen IDs.
        foreach ($category_ids as $key => $category_id) {
            //echo "QUACK!!! ". $allergen_id;
            //dd($allergen_id);
            //TODO: And validate them one by one while you are looping over them.
            $validator = new Validator(['category_id' => $category_id]);
            $rules = array(
                'category_id' => [
                    ['regex', '/^[A-Z]-[0-9]{4}$/']
                ]
            );
            $validator->mapFieldsRules($rules);
            //Check if allergen_id is valid
            if (!$validator->validate()) {
                // Accumulate the error messages to be returned to the client.
                $validation_errors[] = [
                    "category_id" => $category_id,
                    "error" => $validator->errorsToString()
                ];
            } {
                //$rowsDeleted = $this->model->deleteAllergen($allergen_id);
            }
        }
        if (count($validation_errors) > 0) {
            return Result::failure("Some of the category IDs are not valid", $validation_errors);
        }
        return Result::success("The category have been deleted successfully!");
    }
}
