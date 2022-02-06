<?php

interface UserModelInterface {
    public function getUserOrderHistory($limit, $user_id, $api_token_key);
}