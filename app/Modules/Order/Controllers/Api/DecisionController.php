<?php

namespace Modules\Order\Controllers\Api;

use GuzzleHttp\Client;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Forms\Decision\LicenseRequiredForm;
use Modules\Order\Models\Decisions\CustomerFilesModel;
use Modules\Order\Models\Decisions\DecisionLicenseModel;
use Modules\Order\Models\Decisions\DecisionModel;
use Modules\Order\Models\Decisions\DecisionsCustomerFiles;
use Modules\Order\Models\Decisions\DecisionTypeModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\UserAccount\UserModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Request\HttpRequest;

class DecisionController extends Controller
{
    private $data;

    private const LIMIT_SELECT_DECISIONS = 5;
    private const SUPPORTED_LICENSE_FORMATS = ['jpg', 'jpeg', 'png', 'pdf'];

    public function __construct($request)
    {
        parent::__construct($request);
        $this->data = json_decode(file_get_contents('php://input'), true);
    }

    private function saveFiles($decision_id): int
    {
        $files = $_FILES['attachments'];
        $number_saved_files = 0;

        if (!$files) {
            return $number_saved_files;
        }

        $number_files = count($files['name']);

        for ($i = 0; $i < $number_files; $i++) {
            $uploaded_file = new UploadedFile(
                $files['tmp_name'][$i],
                $files['name'][$i],
                $files['type'][$i],
                (int)$files['size'][$i],
                (int)$files['error'][$i],
            );

            $file = new CustomerFilesModel([
                'path' => $uploaded_file,
                'user_id' => $_POST['decision_id'],
                'title' => $files['name'],
            ]);
            $file->original_name = $files['name'][$i];

            $file->save();

            $link = new DecisionsCustomerFiles([
                "file_id" => $file->pk,
                "decision_id" => $decision_id,
            ]);

            $link->save();
            $number_saved_files++;
        }

        return $number_saved_files;
    }

    public function solve()
    {
        $user = Xcart::app()->getUser(true);

        if ($user->getIsGuest()) {
            http_response_code(401);
            return;
        }

        if (strpos($_SERVER['HTTP_CONTENT_TYPE'], 'application/json') !== false) {
            $data = $this->data;
        } else {
            $data = $_POST;
        }

        /**
         * @var $decision DecisionModel
         */
        $decision = DecisionModel::objects()->get([
            'decision_id' => $data['decision_id'],
            'solved' => false
        ]);

        if (!$decision) {
            http_response_code(403);
            return;
        }

        //check user is decision owner
        $order = OrderModel::objects()->get([
            'orderid' => $decision->order_id,
        ]);

        if ($order->user_id !== $user->user_id) {
            http_response_code(403);
            return;
        }

        //validate decision data
        switch ($decision->type) {
            case 'po-send-check':
                $options = $decision['options'];
                $i = $data['address'];

                if ($options['addresses'][$i]) {
                    $options['selectedAddress'] = $i;
                    $decision->solve($options);
                }
                break;
            case 'license-required':
                if ($this->saveFiles($decision->decision_id)) {
                    $decision->solve([]);
                }
                break;
            case 'estimated-time-arrival':
                if (!$data['advice'] || !$data['comment']) {
                    break;
                }

                $advices = ['wait', 'ship', 'cancel', 'replace'];

                if (in_array($data['advice'], $advices) === -1) {
                    break;
                }

                $decision->solve([
                    'advice' => $data['advice'],
                    'comment' => $data['comment'],
                ]);
                break;
        }

        if (!$decision->solved) {
            http_response_code(400);
            return;
        }

        /**
         * send new decisions required count
         * @var $user UserModel
         */
        $user = UserModel::objects()->get(["user_id" => $user->user_id])->getAttributes();
        unset($user->password);
        $this->jsonResponse(["user" => $user]);
    }

    public static function getDecisions($user_id, $solved, $limit, $offset, $order)
    {
        $filters = ['order__user_id' => $user_id, 'solved' => $solved];
        $qm = DecisionModel::objects()
            ->filter($filters)
            ->limit($limit)
            ->offset($offset)
            ->order($order)
            ->asArray();

        $decisions = $qm->all();

        return array_map(function ($decision) {
            $decision['options'] = json_decode($decision['options']);
            return $decision;
        }, $decisions);
    }

    public function getDecisionsAction()
    {
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            $this->jsonResponse([]);
            return;
        }

        $decision = DecisionController::getDecisions(
            $user['user_id'],
            $this->data['solved'],
            self::LIMIT_SELECT_DECISIONS,
            $this->data['offset'],
            $this->data['solved'] ? ['-updated'] : ['-created']
        );

        $this->jsonResponse($decision);
    }

    public function makeLicenseDecisionsAction()
    {
        $decision = DecisionModel::objects()->get(['decision_id' => $_POST['decision_id']]);

        //already solved
        if ($decision->solved === true) {
            return;
        }

        $form = new LicenseRequiredForm();
        $form->populate($_POST, $_FILES);

        if (!$form->isValid()) {
            return;
        }

        $files = $_FILES['LicenseRequiredForm'];
        $extension = pathinfo($files['name']['license'])['extension'];

        //unsupported file
        if (!in_array($extension, self::SUPPORTED_LICENSE_FORMATS)) {
            return;
        }

        $uploaded_file = new UploadedFile(
            $files['tmp_name']['license'],
            $files['name']['license'],
            $files['type']['license'],
            (int)$files['size']['license'],
            (int)$files['error']['license'],
        );

        $decision_license = new DecisionLicenseModel([
            'path' => $uploaded_file,
            'decision_id' => $_POST['decision_id'],
        ]);

        $decision_license->save();
        $decision->setAttribute('solved', '1');
        $decision->update();

        $user = Xcart::app()->auth->getUser(true);
        $this->jsonResponse([
            'notSolved' => DecisionController::getDecisions($user['user_id'], 0, self::LIMIT_SELECT_DECISIONS, 0, ['-created']),
            'solved' => DecisionController::getDecisions($user['user_id'], 1, self::LIMIT_SELECT_DECISIONS, 0, ['-updated']),
        ]);
    }

    //save decision files
    public function solveSUP() {
        /**
         * @var $decision DecisionModel
        */
        $decision = DecisionModel::objects()->get(['decision_id' => $_POST['decision_id']]);

        if ($decision->solved === true) {
            http_response_code(403);
            return;
        }

        $uploaded_file = new UploadedFile(
            $_FILES['files']['tmp_name'][0],
            $_FILES['files']['name'][0],
            $_FILES['files']['type'][0],
            (int)$_FILES['files']['size'][0],
            (int)$_FILES['files']['error'][0],
        );

        $file = new CustomerFilesModel([
            'path' => $uploaded_file,
            'original_name' => $_FILES['files']['name'][0],
        ]);
        $file->save();

        $link = new DecisionsCustomerFiles([
            "user_file_id" => $file->pk,
            "decision_id" => $_POST['decision_id'],
        ]);
        $link->save();

        $options = ['decision_id' => (int)$_POST['decision_id'],];

        switch ($decision_type->slug) {
            case "send-us-po":
                $options['action'] = $_POST['method'];
                break;
            case "additional-information-required":
                $options['phone'] = $_POST['phone'];
                $options['phoneCode'] = $_POST['phoneCode'];
                $options['phone_ext'] = $_POST['phone_ext'];
                break;
        }

        $client = new Client();

        $client->post('http://node-server:3001/api-client/user/decisions/solve', [
            'json' => $options,
            'headers' => [
                'Cookie' => 'session=' . $_COOKIE['session'],
            ]
        ]);

        $data = [
            'action' => 'decision',
            'decision_id' => $decision->decision_id,
        ];
        $msg = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        Xcart::app()->queue->send('emails', $msg, true);
    }

    //solve payment required
    public function solveDecisionsPaymentRequired()
    {
        $decision = DecisionModel::objects()->get([
            'solved' => false,
            'decision_id' => $_POST['decision_id'],
            'type' => $_POST['type'],
        ]);

        //already solved
        if ($decision->solved === true) {
            return;
        }

        $form = new LicenseRequiredForm();
        $form->populate($_POST, $_FILES);

        if (!$form->isValid()) {
            return;
        }

        $files = $_FILES['LicenseRequiredForm'];
        $extension = pathinfo($files['name']['license'])['extension'];

        //unsupported file
        if (!in_array($extension, self::SUPPORTED_LICENSE_FORMATS)) {
            return;
        }

        $uploaded_file = new UploadedFile(
            $files['tmp_name']['license'],
            $files['name']['license'],
            $files['type']['license'],
            (int)$files['size']['license'],
            (int)$files['error']['license'],
        );

        $decision_license = new DecisionLicenseModel([
            'path' => $uploaded_file,
            'decision_id' => $_POST['decision_id'],
        ]);

        $decision_license->save();
        $decision->setAttribute('solved', '1');
        $decision->update();

        $user = Xcart::app()->auth->getUser(true);
        $this->jsonResponse([
            'notSolved' => DecisionController::getDecisions($user['user_id'], 0, self::LIMIT_SELECT_DECISIONS, 0, ['-created']),
            'solved' => DecisionController::getDecisions($user['user_id'], 1, self::LIMIT_SELECT_DECISIONS, 0, ['-updated']),
        ]);
    }

//    public function makeDecisionsAction()
//    {
//        //todo: this method only for eta decision
//        switch ($this->data['type']) {
//            case DecisionModel::DECISION_TYPE_ESTIMATED_TIME_ARRIVAL:
//                $form = new ETADecisionForm();
//                $form->setAttributes($this->data['options']);
//                break;
//        }
//
//        if (!isset($form)) {
//            return;
//        }
//
//        if (!$form->isValid()) {
//            $this->jsonResponse(["errors" => $form->getErrors()]);
//            return;
//        }
//
//        $decision = DecisionModel::objects()->get(['decision_id' => $this->data['decision_id']]);
//
//        $decision->setAttributes(
//            [
//                'solved' => 1,
//                'options' => $form->getAttributes(),
//            ]
//        );
//
//        $decision->save();
//
//        $user = Xcart::app()->auth->getUser(true);
//
//        $this->jsonResponse([
//            'notSolved' => DecisionController::getDecisions($user['user_id'], 0, self::LIMIT_SELECT_DECISIONS, 0, ['-created']),
//            'solved' => DecisionController::getDecisions($user['user_id'], 1, self::LIMIT_SELECT_DECISIONS, 0, ['-updated']),
//        ]);
//    }

    public function createDecision()
    {
        $user = Xcart::app()->getUser(true);

        if ($user->getIsGuest()) {
            http_response_code(401);
            return;
        }

        $type = $this->data["type"];
        $order_id = $this->data["order_id"];
        $order = OrderModel::objects()->get(["orderid" => $order_id]);
        $decision = new DecisionModel([
            'type' => $type,
            'order_id' => $order_id,
            'order_number' => $order->getOrderNumber(),
        ]);

        $decision->save();

        http_response_code(200);
    }
}
