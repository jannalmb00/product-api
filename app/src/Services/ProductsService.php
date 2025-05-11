<?php

namespace App\Services;

use App\Core\Result;
use App\Models\ProductsModel;
use App\Validation\Validator;


class ProductsService
{
    public function __construct(private ProductsModel $products_model) {}


    /**
     * Create a new product
     * @param array $product_data refers to the new product info to be added
     * @return Result refers to the result of the operation whether it is success or failure
     */
    public function createProducts(array $product_data): Result
    {
        $rules = array(

            'product_id' => [
                ['regex', '/^P[0-9]{5,6}$/'],
                'required'
            ],

            'product_name' => [
                'ascii',
                array('lengthMin', 3),
                'required'

            ],

            'product_barcode' => [
                'integer',
                'required'
            ],

            'product_origin' => [
                'ascii',
                ['lengthMin', 2],
                'required',
            ],

            'product_serving_size' => [
                'numeric',
            ],
            'product_image' => [
                'ascii',
            ],

            'brand_id' => [
                ['regex', '/^B\d{4}$/'],
            ],

            'category_id' => [
                ['regex', '/^C-\d{4}$/'],
            ],

            'nutrition_id' => [
                ['regex', '/^N\d{5}$/'],
            ],

            'diet_id' => [
                ['regex', '/^DA\d{4}$/'],
            ],

            'environmental_id' => [
                ['regex', '/^E\d{5}$/'],
            ],

        );

        $new_product = $product_data[0];

        //* Validation
        $validator = new Validator($new_product);

        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            $errorJSON =  $validator->errorsToJson();
            echo $errorJSON . "\n\n";
            return Result::failure("Error inserting new product", $validator->errors());
        }

        $last_inserted_id = $this->products_model->insertProduct($new_product);
        //dd($last_inserted_id);
        return Result::success("Product has been created successfully!", $last_inserted_id);
    }

    /**
     * Update an existing product
     *
     * @param array $data refers to the product data to update
     * @return Result refers to the result of the operation whether it is success or failure
     */
    public function updateProduct(array $product_data): Result
    {
        //  Rules less strict for updates
        $rules = array(

            'product_id' => [
                ['regex', '/^P[0-9]{5,6}$/'],
                'required'
            ],
            'product_name' => [
                'ascii',
                array('lengthMin', 3),
            ],

            'product_origin' => [
                'ascii',
                ['lengthMin', 2],
            ],

            'product_barcode' => [
                'integer',
            ],

            'product_serving_size' => [
                'numeric',
            ],
            'product_image' => [
                'ascii',
            ],

            'brand_id' => [
                ['regex', '/^B[0-9]{4}$/'],
            ],
            'category_id' => [
                ['regex', '/^C-[0-9]{4}$/'],
            ],

            'nutrition_id' => [
                ['regex', '/^N[0-9]{5}$/'],
            ],

            'diet_id' => [
                ['regex', '/^DA[0-9]{4}$/'],
            ],

            'environmental_id' => [
                ['regex', '/^E[0-9]{5}$/'],
            ],

        );

        $validator = new Validator($product_data);
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            $errorJSON =  $validator->errorsToJson();
            echo $errorJSON . "\n\n";
            return Result::failure("Data is not valid. Error updating product");
        }

        $rowsUpdated = $this->products_model->updateProduct($product_data);

        if ($rowsUpdated <= 0) {
 
            return Result::failure("No row has been updated");
        }
        return Result::success("Updated successfully");
    }

    /**
     * Delete a product
     *
     * @param array $product_ids refers to the ID(s) to delete
     * @return Result refers to the result of the operation whether it is success or failure
     */
    public function deleteProduct(array $product_ids): Result
    {
        $validation_errors = [];

        foreach ($product_ids as $key => $product_id) {
            $validator = new Validator(['product_id' => $product_id]);

            $rules = array(
                'product_id' => [
                    ['regex', '/^P\d{5,6}$/'],
                    'required'
                ]
            );

            $validator->mapFieldsRules($rules);

            if (!$validator->validate()) {
                $validation_errors[] = [
                    "product_id" => $product_id,
                    "error" => $validator->errorsToString()
                ];
            }

            $this->products_model->deleteProduct($product_id);
        }

        if (count($validation_errors) > 0) {

            return Result::failure("Some of the product IDs are not valid", $validation_errors);
        }


        return Result::success("The product(s) have been deleted successfully!");
    }
}
