<?php


namespace Modules\Sites\Helpers;


class StorageHelper
{
    private static array $store = [];

    /**
     * сохранить данные в хранилище
     * @param object|array|string|int|float|bool|null $data сохраняемые данные
     * @param string $key ключ по которому будут сохранены данные
     */
    public static function push($data, string $key, string $context = ''): void
    {
        if ($context) {
            if (!isset(self::$store[$context])) {
                self::$store[$context] = [];
            }

            self::$store[$context][$key] = $data;
        } else {
            self::$store[$key] = $data;
        }
    }

    public static function print(): string
    {
        $json = json_encode(self::$store);

        return "<script type=\"text/javascript\">const appData = $json;</script>";
    }
}
