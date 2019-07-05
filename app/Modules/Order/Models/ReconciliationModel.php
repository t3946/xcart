<?php


namespace Modules\Order\Models;


use DateInterval;
use DateTime;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;
use Xcart\Reconciliation;

class ReconciliationModel extends Model
{
    public const RECONCILIATION_STATUS_RECONCILED = 'R';
    public const RECONCILIATION_STATUS_PRE_RECONCILED = 'P';
    public const RECONCILIATION_STATUS_DROPPED = 'D';
    public const RECONCILIATION_STATUS_NULL = '';

    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_reconciliations';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'date_csv' => UnixTimestampField::class,
            'file_upload_date' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
            ],
            'account' => [
                'field' => 'account_id',
                'class' => ForeignField::class,
                'modelClass' => ReconciliationAccountModel::class,
                'link' => ['account_id' => 'id']
            ],
            'invoices' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupInvoiceModel::class,
                'link' => ['id' => 'reconciliation_id']
            ],
            'memos' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupMemoModel::class,
                'link' => ['id' => 'reconciliation_id']
            ],
            'distributors' => [
                'class' => ManyToManyField::class,
                'modelClass' => DistributorModel::class,
                'through' => ReconciliationManufacturerModel::class,
            ],
        ];
    }

    public function getDescriptionBold()
    {
        if ($dx = $this->distributors->limit(1)->get()){
            $result = strtoupper(trim($this->description_csv));
            $v_arr = explode('<OR>', strtoupper($dx->d_search_keyphrase_for_reconciliation));
            foreach ($v_arr as $k) {
                if (trim($k)) {
                    $result = \str_replace(trim($k), '<b>' . trim($k) . '</b>', $result);
                }
            }
        }
        return $result ?? $this->description_csv;
    }

    public function getDistributors()
    {
        if (!$_reference = strtoupper(trim($this->description_csv))) {return [];}

        foreach (DistributorModel::objects() as $dx)
        {
            $v_arr = explode('<OR>', strtoupper($dx->d_search_keyphrase_for_reconciliation));
            if (\strpos($_reference, $v_arr) !== false) {
                $result[] = $dx;
            }
        }

        return $result ?? [];
    }

    public function isExpense()
    {
        if (!$_reference = strtoupper(trim($this->description_csv))) {return true;}

        foreach (ReconciliationSearchKeyphraseModel::objects()->order(['code']) as $key_phrase) {
            if ($search_keyphrase = trim($key_phrase->search_keyphrase)) {
                $v_arr = explode('<OR>', $search_keyphrase);
                if (\strpos($_reference, $v_arr) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getLookupLink()
    {
        $sRegex = '/PURCHASE AUTHORIZED ON (\d{2})\/(\d{2})/';
        preg_match($sRegex, $this->description_csv, $aMatches);
        if ($aMatches) {
            $sMonth = $aMatches[1];
            $sDay = $aMatches[2];
            $dCurDate = new DateTime();
            $sCurYear = $dCurDate->format('Y');

            $dTransactionDate = new DateTime();
            $dTransactionDate->setDate($sCurYear, (int)$sMonth, (int)$sDay);

            $useDate = $dTransactionDate;

            if ($dCurDate < $dTransactionDate) {
                $dTransactionDateLastYear = new DateTime();
                $dTransactionDateLastYear->setDate((int)$sCurYear - 1, (int)$sMonth, (int)$sDay);
                $useDate = $dTransactionDateLastYear;
            }

            $subDate = clone $useDate;
            $addDate = clone $useDate;
            $subDate->sub(new DateInterval('P6D'));
            $addDate->add(new DateInterval('P1D'));

            $sSearchString = "after: %s before: %s ";
            return urlencode(sprintf($sSearchString, $subDate->format('Y/m/d'), $addDate->format('Y/m/d')));
        }

        return '';
    }

}