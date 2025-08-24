<?php
namespace App\Security;

Security::check();

class Security
{
    public static function check()
    {
        if (!defined('APP_INIT')) {
            header('Location: /');
            exit;
        }
    }
}
