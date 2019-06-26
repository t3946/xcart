<?php


namespace Modules\Order\Models;


use DateInterval;
use DateTime;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ReconciliationModel extends Model
{
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
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
            ],
        ];
    }

    public function getDescriptionBold()
    {
        if ($dx = $this->distributor){
            $result = strtoupper(trim($this->description_csv));
            $v_arr = explode('<OR>', strtoupper($dx->d_search_keyphrase_for_reconciliation));
            foreach ($v_arr as $k) {
                if (trim($k)) {
                    $result = \str_replace(trim($k), "<B>" . trim($k) . "</B>", $result);
                }
            }
        }
        return $result ?? $this->description_csv;
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