<?php

namespace App\Services;

use App\Core\Result;
use App\Models\UserModel;
use App\Validation\Validator;
use App\Core\PasswordTrait;


class UserService
{
    use PasswordTrait;
    public function __construct(private UserModel $user_model) {}

    function createUser(array $new_user_data): Result
    {
        //TODO: 1) Validate the received resource data about the new resource to be created.
        //* VALIDATE USING VALITRON

        $rules = array(
            'user_id' => [
                ['regex', '/^[A-Z][0-9]{2}$/']
            ],
            'first_name' => [
                'required',
                'ascii',
                array('lengthMin', 2)
            ],
            "last_name" => [
                
                'required',
                array('lengthMin', 2)
            ],
            "email" => [
                'required',
                'email'
            ],
            "password" => [
                'required'
            ],
            "isAdmin" => [
                'required',
                ['in', [0, 1]]

            ]

        );
        //! RETRURN RIGHT AWAY AS SOON AS YOU DETECT ANY INVALID INPUTS

        //TODO: 2) Insert the resource into the DB table
        //* We can use an array and using the first 1 so we can make our lives easier
        //$new_allergen = $new_user_data[0];
        $validator = new Validator($new_user_data, [], 'en');
        $validator->mapFieldsRules($rules);

        //todo: check is an existing user is in there
        if ($this->user_model->userExistsByEmail($new_user_data['email'])) {
            return Result::failure("A user with this email already exists.");
        }


        if (!$validator->validate()) {

            echo $validator->errorsToString();
            // echo '<br>';
            echo $validator->errorsToJson();
            return Result::failure("error!");
        }

        $new_user_data['isAdmin'] = isset($new_user_data['isAdmin']) && $new_user_data['isAdmin'] ? 1 : 0;
        //Todo: hash the password
        $new_user_data['password'] = $this->cryptPassword($new_user_data['password']);
        // echo $new_user_data;
       // echo "success";
        $last_inserted_id =  $this->user_model->createUser($new_user_data); //
        return Result::success("The new user has been created successfully!", $last_inserted_id);
    }

    public function authenticateUser(string $email, string $password): Result
    {

        //? Step 1) Get user email for authentication
        $user = $this->user_model->getUserEmail($email);

        //? Step 2) Check if user exists
        if (!$user) {
            return Result::failure("No user");
        }

        //? Step 3) Verify password
        if (!password_verify($password, $user['password'])) {
            echo "failed";
            return Result::failure("Invalid email or password");
        }

        //? Step 4) Group the user data
        $userData = [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'isAdmin' => $user['isAdmin'], // use this for access control
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name']
        ];

        //? Step 5) Return successful authentication information
        return Result::success("Authentication successful", $userData);
    }
}
