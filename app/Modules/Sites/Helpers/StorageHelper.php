<?php


namespace Modules\Sites\Helpers;


use Exception;


/**
 * Помогает передавать данные с сервера на клиент при рендеринге
*/
class StorageHelper
{
    private static array $store = [];

    /**
     * сохранить данные в хранилище
     * @param object|array|string|int|float|bool|null $data сохраняемые данные
     * @param string|null $key ключ по которому будут сохранены данные
     * @param string|null $context может быть записан как имена разделённые слэшем name1/name2
     * для создания иерархии контекстов
     * @throws Exception
     */
    public static function push($data, ?string $key, ?string $context = ''): void
    {
        if ($context) {
            $namespaces = explode('/', $context);
            $target = &self::$store;

            foreach ($namespaces as $namespace) {
                if (!isset($target[$namespace])) {
                    $target[$namespace] = [];
                }

                $target = &$target[$namespace];
            }

            if ($key) {
                $target[$key] = $data;
            } else {
                $target = $data;
            }
        } else {
            if (!$key) {
                /**
                 * Невозможно определить к чему отнести отправленные данные, если у них нет ни ключа ни контекста
                */
                throw new Exception("\$key and \$context can't be null in one time");
            }

            self::$store[$key] = $data;
        }
    }

    public static function getStorage(): array {
        return self::$store;
    }

    public static function print(): string
    {
        $json = json_encode(self::$store);

        return "<script type=\"text/javascript\">window.appData = $json;</script>";
    }
}
