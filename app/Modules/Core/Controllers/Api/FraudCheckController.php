<?php
namespace Modules\Core\Controllers\Api;
use Modules\Core\Models\FraudCheckColumnModel;
use Modules\Core\Models\FraudCheckModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class FraudCheckController extends Controller {

	public function getFraudFullName()
	{
		return FraudCheckModel::objects()->filter(['type' => 'full_name']);
	}
	public function getFraudAddress()
	{
		return FraudCheckModel::objects()->filter(['type' => 'address']);
	}
	public function getAll()
	{
		$ar_result = ['status' => false];
		foreach ($this->getFraudFullName() as $item) {
			$ar_result['full_name']['data'][] = [
				'section' => "{$item->f_fraud->fraud_name}:{$item->t_fraud->fraud_name}",
				'value' => $item->weight,
				'f_fraud' => $item->f_fraud->fraud_code,
				't_fraud' => $item->t_fraud->fraud_code,
			];
		}
        $ar_result['full_name']['columns'] = FraudCheckColumnModel::objects()->filter(['type' => 'full_name'])->valuesList(['fraud_name'], true);
		$ar_result['full_name']['data_section'] = array_column($ar_result['full_name']['data'], 'section');
		foreach ($this->getFraudAddress() as $item) {
			$ar_result['address']['data'][] = [
				'section' => "{$item->f_fraud->fraud_name}:{$item->t_fraud->fraud_name}",
				'value' => $item->weight,
				'f_fraud' => $item->f_fraud->fraud_name,
				't_fraud' => $item->t_fraud->fraud_name,
			];
		}
		$ar_result['address']['columns'] = FraudCheckColumnModel::objects()->filter(['type' => 'address'])->valuesList(['fraud_name'], true);
		$ar_result['address']['data_section'] = array_column($ar_result['address']['data'], 'section');
		$ar_result['status'] = true;
		$this->jsonResponse($ar_result);
	}
	public function updateWeight()
    {
        $post = Xcart::app()->request->post;
        $update = json_decode($post['update'], true);
        foreach ($update as $fraud_group => $value) {
            $ar_fraud = explode(':', $fraud_group);
            $f_fraud_column = FraudCheckColumnModel::objects()->get(['fraud_name' => $ar_fraud[0]]);
            $t_fraud_column = FraudCheckColumnModel::objects()->get(['fraud_name' => $ar_fraud[1]]);
            $fraud = FraudCheckModel::objects()->get(['f_fraud_id' => $f_fraud_column, 't_fraud_id' => $t_fraud_column]);
            $fraud->weight = $value;
            $fraud->save();
        }
        $this->jsonResponse(['status' => true]);
    }
}