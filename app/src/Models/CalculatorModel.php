<?php

namespace App\Models;

use App\Validation\Validator;
use App\Core\Result;


class CalculatorModel extends BaseModel
{
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

        if (!$validator->validate()) {
            return [
                'success' => false,
                'message' => 'Invalid inputs',
                'errors' => $validator->errors()
            ];
        }


        //TODO extract the inputsss pookie

        //variables: gender (female,male),weights(kg),height(cm),age(yrs)

        $gender = strtolower($data['gender']);
        $weight = $data['weight'];
        $height = $data['height'];
        $age = $data['age'];

        if ($gender == 'female') {
            // echo "female";
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
           // echo $bmr;
        } else {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
        }
        // dd($data);

        //activit
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


        $tdee = $bmr * $multiplier;

        $result = [
            'bmr' => round($bmr, 2),
            'tdee' => round($tdee, 2),
            'unit' => 'kcal/day'

        ];

        //retur


        //TODO: return result
        return $result;
    }
}
