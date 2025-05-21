<?php

namespace App\Models;

class UserModel extends BaseModel
{
    /**
     *  Creates a new user in the database
     * @param array $new_user Associative array containing user data to insert.
     * @return mixed Returns the last inserted ID or false on failure.
     */
    function createUser(array $new_user): mixed
    {

        $last_id = $this->insert("ws_users", $new_user);
        return $last_id;
    }

    /**
     *  Checks if a user exists by their email address.
     * @param string $email The email address to search for
     * @return bool Returns true if the user exists, false otherwise.
     */
    public function userExistsByEmail(string $email): bool
    {

        $sql = "SELECT 1 FROM ws_users WHERE email = :email LIMIT 1";
        $result = $this->fetchSingle($sql, ['email' => $email]);
        return $result !== false;
    }


    /**
     * Retrieves full user data by email address.
     * @param string $email The email address of the user.
     * @return mixed Returns an associative array of the user's record,
     *  or false if not found.
     */
    public function getUserEmail(string $email): mixed
    {
        $sql = "SELECT * FROM ws_users WHERE email = :email";
        return $this->fetchSingle($sql, ['email' => $email]);
    }
}
