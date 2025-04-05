<?php

namespace App\Services;

use App\Core\Result;
use App\Models\ProductsModel;

use App\Validation\Validator;


class ProductsService
{
    public function __construct(private ProductsModel $model, private Validator $validator) {}

    function createProducts(array $new_player_info): Result
    { // returns Result class
        // TODO: 1- Validate the recieved data about the new resource to be created.

        //--- here is where you do the checklist
        //* Using Valitron -- use VALIDATOR class (this uses Valitron already)
        //! Return as soon as you detect any invalid inputs -- use early return technique. continue if valid.  ---> RETURN Result::failure (set the code in the controller not here)
        // Return Result::failure("Error!", ["username"=>"wrong username"] );

        // ? 2- Insert new resource into the DB table
        //if list, drop in for loop and each item inthat list call insert
        //* Just process the first collection / first element in the array, if there are any errors just do that
        $new_product = $new_player_info[0];
        $this->model->insertNewProduct($new_product);


        // Pass the last inserted id --> this is what you retrun

        $last_insert_id = '100';
        // return successful result
        return Result::success("Product has been created.", $last_insert_id);
    }
}
