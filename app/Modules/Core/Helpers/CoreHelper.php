<?php

namespace Modules\Core\Helpers;


class CoreHelper
{
    public static function stripTags($content)
    {
        $content =  preg_replace('/<[^>]*>/', ' ', $content);
        $content = preg_replace('/\s+/', ' ', $content);
        return trim($content);
    }

    public static function cipherText(string $text): string
    {
        $key = '9a9f67b471242fd0539569c4984ea0d387682a0981b7562f';
        $cipher = "aes-128-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        return openssl_encrypt($text, $cipher, $key, $options=0, $iv, $tag) ?: '';
    }
}