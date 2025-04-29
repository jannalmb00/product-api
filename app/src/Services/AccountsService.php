<?php

namespace App\Services;

use App\Models\AccountModel;
use App\Core\Result;
use App\Validation\Validator;

class AccountsService
{
    function __construct(private AccountModel $accountModel) {}
    function createAccount(array $new_account_info) : Result {

        // $rules = array (
        //     'first_name' => [
        //         'required',
        //         'ascii',
        //         array('lengthMin', 2)
        //     ],

        //     'last_name' => [
        //         'required',
        //         'ascii',
        //         array('lengthMin', 2)
        //     ],

        //     'email' => ['email', 'required'],

        //     'password' =>  ['password', 'required'],

        // );

        $last_insert_id = $this->accountModel->insertAccount($new_account_info);


        return Result::success("zz");
    }
}
