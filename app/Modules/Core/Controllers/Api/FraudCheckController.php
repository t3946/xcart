<?php
namespace Modules\Core\Controllers\Api;
use Modules\Core\Models\FraudCheckModel;
use Xcart\App\Controller\Controller;

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
				'section' => "{$item->f_fraud->fraud_code}:{$item->t_fraud->fraud_code}",
				'value' => $item->weight,
				'f_fraud' => $item->f_fraud->fraud_code,
				't_fraud' => $item->t_fraud->fraud_code,
			];
			$ar_result['full_name']['columns'] = []
		}
		foreach ($this->getFraudAddress() as $item) {
			$ar_result['address'][] = [
				'section' => "{$item->f_fraud->fraud_code}:{$item->t_fraud->fraud_code}",
				'value' => $item->weight,
				'f_fraud' => $item->f_fraud->fraud_code,
				't_fraud' => $item->t_fraud->fraud_code,
			];
		}
		$ar_result['status'] = true;
		$this->jsonResponse($ar_result);
	}
}