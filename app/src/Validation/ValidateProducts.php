<?php

namespace App\Validation;

error_reporting(E_ERROR | E_PARSE);
//*  Importing the Validator Classs (Using Valitron)
require_once("Validation/Validator.php");
class ValidateProducts
{

    // TODO: Create input validation for all POST, PUT and DELETE.
    function validateCreateProducts()
    {
        //? Step 1: Add a test data array or figure out the product
        $data = array(
            "product_id" => "P00001",
            "product_name" => "Seafood Allergy",
            "product_barcode" => "2147483647",
            "product_origin" => "Canada",
            "product_serving_size" => "355",
            "product_image" => "https://mateina.ca/cdn/shop/files/Mateina-Organic-...",
            "nutrition_id" => "N00001",
            "diet_id" => "DA0001",
            "brand_id" => "B0001",
            "category_id" => "C-0004",
            "environmental_id" => "E00001",
        );

        //? Step 2) Add the rules based on each query input.
        $rules = array(
            'product_id' => array(
                'required',
                array('length', 6),
                array('regex', '/^[A-Z][0-9]{6}/'),
            ),
            'product_name' => array(
                'required',
                array('min', 4),
            ),

            // [
            //     ['regex', '/^[A-Z][0-9]{5}/'],
            //     ['required'],
            //     ['length', 5],
            // ]
        );

        //? Step 2) Initialize the validator class in order to express the error messages
        $validator = new Validator($data, []);

        //? Step 3) Map the fields and the rules in order to validate the query inputs.
        $validator->mapFieldRules($data, $rules);

        //? Step 4) Validate the data
        $validator->validate();
    }

    function validateUpdateProduct(array $fields)
    {
        //?  Step 1) The array containing the data needs to be validated.
        $data = array(
            
        );

        //? Step 2) Add the rules with one or more validation rules.
        $rules = array(
            'allergen_id' => [
                ['regex', '/^[A-z][0-9]{6}/']
            ],

            'product_name' => [
                ['lengthMin', 4]
            ],

            'numeric' => [
                'product_barcode',
                'product_serving_size'
            ],

            'product_origin' => [
                ['lengthMin', 4]
            ],

            'regex' => [
                ['nutrition_id', '/^[A-Z][0-9]{6}$/'],
                ['diet_id', '/^[A-Z][0-9]{6}$/'],
                ['category_id', '/^[A-Z]-[0-9]{6}$/'],
                ['environmental_id', '/^[A-Z][0-9]{6}$/'],
            ]
        );

        $validator = new Validator($data, $rules);
    }

    function testDeleteProduct() {}
}
