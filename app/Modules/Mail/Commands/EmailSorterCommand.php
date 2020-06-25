<?php


namespace Modules\Mail\Commands;


use Mindy\QueryBuilder\Q\QOr;
use Modules\Forms\Models\EmailEntityModel;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\EmailSorterModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class EmailSorterCommand extends Command
{
    const EMAIL_FIELDS = ['to_address', 'from_address'];

    private static function getEmail($string)
    {
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
        preg_match($pattern, $string, $matches);
        return $matches[0];
    }

    private static function getDomain($email)
    {
        return strtolower(substr(strrchr($email, "@"), 1));
    }


    private static function getSearchItems($email, $field): array
    {
        if (($app = Xcart::app()) && $site = $app->getModule('Sites')->getSite()) {
            $config = $site->getGlobalConfig();
            if (in_array($field, self::EMAIL_FIELDS, true) && $eArray = explode(',', $email->$field)) {
                foreach ($eArray as $a) {
                    $a = self::getEmail($a);
                    if (($domain = self::getDomain($a)) !== 's3stores.com' && $domain) {
                        $freeDomains = explode(',', $config['fraud_domains_free_email_provider']);
                        if (!in_array($domain, $freeDomains, true)) {
                            $res[] = $domain;
                        } else {
                            $res[] = $a;
                        }
                    }
                }
            }
        }

        return array_unique($res ?? []);
    }

    public function handle($arguments = [])
    {
        $f = ['dx_models__manufacturerid__isnull' => true, 'order_models__orderid__isnull' => true];
        foreach (EmailModel::objects()->filter($f) as $email) {
            foreach (EmailSorterModel::objects()->filter(['type' => $email->type]) as $sort) {
                $field = $sort->filter_field;

                switch ($sort->cond) {
                    case 'contains':
                        print("Find contains {$sort->value} in {$email->$field} \n");
                        if (stripos($email->$field, $sort->value) !== false) {
                            print("Found contains {$sort->value} in {$email->$field} \n");
                            $class = $sort->entity;
                            /** @var Model $model */
                            $model = new $class;
                            if ($t = $model::objects()->get([$model::getPrimaryKeyName() => $sort->target])) {
                                EmailEntityModel::objects()->getOrCreate([
                                    'email_id' => $email->id,
                                    'entity_id' => $t->pk,
                                    'model' => $sort->entity
                                ]);
                            }
                        }
                        break;
                    case 'equal':
                        $found = strtolower($email->$field) === strtolower($sort->value);
                        break;
                    case 'regexp':
                    case 'related':
                        $filter = [];
                        /** @var Model $entity */
                        $entity = new $sort->entity;
                        if ($sItems = self::getSearchItems($email, $field)) {
                            foreach ($sItems as $domain) {
                                $filter[] = new QOr(["{$sort->related_value}__contains" => $domain]);
                            }
                        }
                        $pk = $entity::getPrimaryKeyName();
                        if ($filter && $c = $entity::objects()->filter(new QOr($filter))->group([$pk])->all()) {
                            foreach ($c as $e) {
                                EmailEntityModel::objects()->getOrCreate([
                                    'email_id' => $email->id,
                                    'entity_id' => $e->$pk,
                                    'model' => $sort->entity
                                ]);
                            }
                        }
                }
            }
        }
    }
}