<?php

namespace App\Models;

class AccountModel extends BaseModel
{

    public function __construct() {}

    function insertAccount(array $account_info): mixed
    {
        // TODO: do not use the Result pattern. Just throw an exception.

        // $last_userID = $this->insert('wc_users', $new_account);

        return 1;
        //return $last_userID;
    }
}
