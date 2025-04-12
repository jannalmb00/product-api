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
                ['regex', '/^P\d{5,6}$/'],
                'required'
            ],

            'product_name' => [
                ['lengthMin', 4],
                'required'
            ],

            'product_barcode' => [
                'numeric',
                'required'
            ],

            'product_origin' => [
                ['lengthMin', 4],
                'required',
            ],

            'product_serving_size' => [
                'numeric',
                'required',
            ],

            'brand_id' => [
                ['regex', '/^B\d{4}$/'],
                'required',
            ],

            'category_id' => [
                ['regex', '/^C-\d{4}$/'],
                'required',
            ],

            'nutrition_id' => [
                ['regex', '/^N\d{5}$/'],
                'optional'
            ],

            'diet_id' => [
                ['regex', '/^DA\d{4}$/'],
                'optional'
            ],

            'environmental_id' => [
                ['regex', '/^E\d{5}$/'],
                'optional'
            ],

        );

        $new_product = $product_data[0];

        $validator = new Validator($new_product, [], 'en');

        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            $errorJSON =  $validator->errorsToJson();
            print($errorJSON);
            return Result::failure("Product data validation failed", $validator->errors());
        }

        $last_inserted_id = $this->products_model->insertProduct($product_data);

        return Result::success("Product has been created successfully!", $last_inserted_id);
    }

    /**
     * Update an existing product
     *
     * @param array $data refers to the updated product data
     * @return Result refers to the result of the operation whether it is success or failure
     */
    public function updateProduct(array $product_data): Result
    {
        //  Rules less strict for updates
        $rules = array(


            'product_name' => [
                ['lengthMin', 4],
                'optional'
            ],

            'product_origin' => [
                ['lengthMin', 4],
                'optional'
            ],

            'product_barcode' => [
                'numeric',
                'optional'
            ],

            'product_serving_size' => [
                'numeric',
                'optional'
            ],

            'brand_id' => [
                ['regex', '/^B\d{4}$/'],
                'optional'
            ],
            'category_id' => [
                ['regex', '/^C-\d{4}$/'],
                'optional'
            ],

            'nutrition_id' => [
                ['regex', '/^N\d{5}$/'],
                'optional'
            ],

            'diet_id' => [
                ['regex', '/^DA\d{4}$/'],
                'optional'
            ],

            'environmental_id' => [
                ['regex', '/^E\d{5}$/'],
                'optional'
            ],

        );

        $validator = new Validator($product_data, [], 'en');
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            return Result::failure("Data is not valid");
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
     * @param array $condition The condition to identify the product to delete
     * @return Result refers to the result of the operation whether it is success or failure
     */
    public function deleteProduct(array $product_ids): Result
    {
        $validation_errors = [];

        foreach ($product_ids as $key => $product_id) {
            $validator = new Validator(['product_id' => $product_id]);

            $rules = array(
                'product_id' => [
                    ['regex', '/^P\d{5,6}$/']
                ]
            );

            $validator->mapFieldsRules($rules);

            if (!$validator->validate()) {
                $validation_errors[] = [
                    "product_id" => $product_id,
                    "error" => $validator->errorsToString()
                ];
            }

            $rowsDeleted = $this->products_model->deleteProduct($product_id);
        }

        if (count($validation_errors) > 0) {
            return Result::failure("Some of the product IDs are not valid", $validation_errors);
        }

        return Result::success("The product(s) have been deleted successfully!");
    }
}
