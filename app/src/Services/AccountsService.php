<?php

namespace App\Services;

use App\Models\AccountModel;
use App\Core\Result;
use App\Validation\Validator;
use App\Exceptions\HttpInvalidInputException;
use App\Exceptions\HttpUnauthorizedException;

class AccountsService
{
    function __construct(private AccountModel $accountModel) {}
    function createAccount(array $new_account_info): Result
    {

        $last_insert_id = $this->accountModel->insertAccount($new_account_info);


        return Result::success("zz");
    }

    public function authenticateUser(string $email, string $password): Result
    {

        $user = $this->accountModel->getUserEmail($email);

        // if user exists
        if (!$user) {
            return Result::failure("No user");
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return Result::failure("Invalid email or password");
        }

        $userData = (object) [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname']
        ];

        return Result::success("Authentication successful", $userData);
    }
}
