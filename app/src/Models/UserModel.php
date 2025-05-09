<?php

namespace App\Models;

class UserModel extends BaseModel
{

    function createUser(array $new_user): mixed
    {
        $last_id = $this->insert("ws_users", $new_user);
        return $last_id;
    }
    public function userExistsByEmail(string $email): bool
    {
        $sql = "SELECT 1 FROM ws_users WHERE email = :email LIMIT 1";
        $result = $this->fetchSingle($sql, ['email' => $email]);
        return $result !== false;
    }

    public function getUserEmail(string $email): mixed
    {
        $sql = "SELECT * FROM ws_users WHERE email = :email";
        return $this->fetchSingle($sql, ['email' => $email]);
    }
}
