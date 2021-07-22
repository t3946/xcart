<?php

namespace Modules\User\Helpers;

use Xcart\App\Helpers\ClassNames;

class PasswordHelper
{
    use ClassNames;

    public static function hash($password, $algo = PASSWORD_DEFAULT, $options = [])
    {
        return password_hash($password, $algo, $options);
    }

    /**
     * проверяет соответствие хеша и пароля
     * @param string $password пароль
     * @param string $hash хеш пароля
     * @return boolean true если хеш соотносится с паролем иначе false
    */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
