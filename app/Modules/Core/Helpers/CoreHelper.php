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

    public static function cipherText(string $text): array
    {
        $key = '9a9f67b471242fd0539569c4984ea0d387682a0981b7562f';
        $cipher = "aes-128-gcm";
        $res = openssl_encrypt($text, $cipher, $key, $options=0, $key, $tag) ?: '';
        return [
            'text' => $res,
            'tag' => bin2hex($tag)
        ];
    }

    public static function decryptText(string $text, string $tag): string
    {
        $key = '9a9f67b471242fd0539569c4984ea0d387682a0981b7562f';
        $cipher = "aes-128-gcm";

        return openssl_decrypt($text, $cipher, $key, $options=0, $key, hex2bin( $tag ));
    }
}