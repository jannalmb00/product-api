<?php

namespace App\Models;

class UserModel extends BaseModel
{

    function createUser(array $new_user): mixed
    {        // echo "user created";

        $last_id = $this->insert("ws_users", $new_user);
        return $last_id;
    }
    public function userExistsByEmail(string $email): bool
    {
        // echo "  checking if user exists   ";
        // echo ' ' . $email . ' ';
        $sql = "SELECT 1 FROM ws_users WHERE email = :email LIMIT 1";
        $result = $this->fetchSingle($sql, ['email' => $email]);
        // dd($result);
        //echo $result;
        return $result !== false;
    }

    public function getUserEmail(string $email): mixed
    {
        $sql = "SELECT * FROM ws_users WHERE email = :email";
        return $this->fetchSingle($sql, ['email' => $email]);
    }
}
