<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class AuthModel extends Database
{
    /**
     * Return user with api key
     */
    public function getUser($api_token_key)
    {
        return $this->select("SELECT user_id FROM UserTable WHERE api_token_key = \"$api_token_key\"");
    }
}