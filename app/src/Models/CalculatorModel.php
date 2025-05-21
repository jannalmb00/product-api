<?php

namespace App\Models;

use App\Validation\Validator;
use App\Core\Result;

/**
 * Model for calculating and validating inputs for the calculation resource
 */
class CalculatorModel extends BaseModel
{

    /**
     * Calculates BMR and TDEE (total daily energy expenditure)
     * @param array $data Input data including gender, weight, height, age, and activity level.
     * @return array{bmr: float, tdee: float, unit: string|array{errors: array|bool, message: string, success: bool}} Result with BMR, TDEE, or validation error.
     */
    function calculateCalories(array $data)
    {
        //TODO: validate inputs first
        $rules = array(
            'gender' => [
                'required',
                ['in', ['female', 'male']]
            ],
            'weight' => [
                'required',
                'numeric'
            ],
            'height' => [
                'required',
                'numeric'
            ],
            'age' => [
                'required',
                'numeric'
            ],
            'activity_per_week' => [
                'required',
                'numeric'
            ]

        );

        $validator = new Validator($data, [], 'en');
        $validator->mapFieldsRules($rules);

        // Return error if validation fails
        if (!$validator->validate()) {
            return [
                'success' => false,
                'message' => 'Invalid inputs. Please check inputs.',
                'errors' => $validator->errors()
            ];
        }


        //TODO extract the inputss
        //variables: gender (female,male),weights(kg),height(cm),age(yrs)

        $gender = strtolower($data['gender']);
        $weight = $data['weight'];
        $height = $data['height'];
        $age = $data['age'];

        // Calculate BMR (Basal Metabolic Rate)
        if ($gender == 'female') {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
        } else {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
        }

        // Activity multiplier based on activity/week
        $activity_per_week = $data['activity_per_week'];

        if ($activity_per_week === 0) {
            $multiplier = 1.2;
        } elseif ($activity_per_week <= 2) {
            $multiplier = 1.4;
        } elseif ($activity_per_week > 2 && $activity_per_week <= 3) {
            $multiplier = 1.6;
        } elseif ($activity_per_week >= 4 && $activity_per_week <= 5) {
            $multiplier = 1.75;
        } elseif ($activity_per_week >= 6 && $activity_per_week <= 7) {
            $multiplier = 2.0;
        } elseif ($activity_per_week > 7) {
            $multiplier = 2.3;
        }

        // Calculate TDEE
        $tdee = $bmr * $multiplier;

        $result = [
            'bmr' => round($bmr, 2),
            'tdee' => round($tdee, 2),
            'unit' => 'kcal/day'
        ];


        //TODO: return result
        return $result;
    }


    /**
     * Calculates recommended daily fiber intake.
     * @param array $data  Must include 'daily_calories'.
     * @return array{daily_calories: mixed, formula_used: string, recommended_fiber_intake: array{unit: string, value: float}|array{errors: array|bool, message: string, success: bool}}  Recommended fiber intake in grams or validation error.
     */
    function calculateFiberIntake(array $data)
    {
        // Validate inputs
        $rules = array(
            'daily_calories' => [
                'required',
                'numeric',
            ]
        );

        $validator = new Validator($data, [], 'en');
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            return [
                'success' => false,
                'message' => 'Invalid inputs. Please check inputs.',
                'errors' => $validator->errors()
            ];
        }

        $dailyCalories = $data['daily_calories']; // Fetch input

        // Calculate
        $recommendedFiberIntake = ($dailyCalories / 1000) * 14;

        // Prepare result
        $result = [
            'daily_calories' => $dailyCalories,
            'recommended_fiber_intake' => [
                'value' => round($recommendedFiberIntake, 1),
                'unit' => 'g/day'
            ],
            'formula_used' => 'daily_calories / 1000 * 14g'
        ];

        return $result;
    }


    /**
     *  Calculates BMI and returns the category.
     * @param array $data  Must include weight (kg), height (cm) and gender.
     * @return array{bmi: float, category: string|array{errors: array|bool, message: string, success: bool}} BMI value and category, or validation error.
     */
    function calculateBMI(array $data)
    {
        // Validate inputs
        $rules = array(
            'gender' => [
                'required',
                ['in', ['female', 'male']]
            ],
            'weight_kg' => [
                'required',
                'numeric'
            ],
            'height_cm' => [
                'required',
                'numeric'
            ]
        );

        $validator = new Validator($data, [], 'en');
        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            return [
                'success' => false,
                'message' => 'Invalid inputs. Please check inputs.',
                'errors' => $validator->errors()
            ];
        }

        // convert height from cm to m
        $height_m = $data["height_cm"] / 100;
        $weight = $data["weight_kg"];

        // bmi calculation
        $bmi = $weight / ($height_m ** 2);
        $bmi = round($bmi, 2);

        // bmi range
        if ($bmi < 18.5) {
            $category = 'Underweight';
        } elseif ($bmi < 25) {
            $category = 'Normal weight';
        } elseif ($bmi < 30) {
            $category = 'Overweight';
        } elseif ($bmi <= 35) {
            $category = 'Obese';
        } else {
            $category = 'Severely Obese';
        }

        // Prepare result
        $result = [
            'bmi' => $bmi,
            'category' => $category
        ];

        return $result;
    }
}
