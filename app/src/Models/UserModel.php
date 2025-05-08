<?php

namespace App\Models;

/**
 *
 * Allergen model handles data related to allergns in the system
 */
class UserModel extends BaseModel
{

    function createUser(array $new_user): mixed
    {


        $last_id = $this->insert("ws_users", $new_user);
        // $last_id = $this->update("allergens", $new_allergen);
        return $last_id;
    }
    public function userExistsByEmail(string $email): bool
    {
        $sql = "SELECT 1 FROM ws_users WHERE email = :email LIMIT 1";
        $result = $this->fetchSingle($sql, ['email' => $email]);
        return $result !== false;
    }
}
