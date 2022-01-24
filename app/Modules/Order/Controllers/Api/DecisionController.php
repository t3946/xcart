<?php

namespace Modules\Order\Controllers\Api;

use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\Decisions\DecisionModel;
use Modules\Order\Models\Decisions\DecisionLicenseModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xcart\App\Controller\Controller;
use Modules\Order\Models\Decisions\DecisionFilesModel;
use Modules\Order\Forms\Decision\LicenseRequiredForm;
use Xcart\App\Main\Xcart;

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

    public function solve()
    {
        $user = Xcart::app()->getUser(true);

        if ($user->getIsGuest()) {
            http_response_code(401);
            return;
        }

        $type = $_POST['type'] ?? $this->data['type'];
        $decision_id = $_POST['decision_id'] ?? $this->data['decision_id'];

        $decision = DecisionModel::objects()->get([
            'type' => $type,
            'decision_id' => $decision_id,
            'solved' => false
        ]);

        if (!$decision) {
            http_response_code(400);
            return;
        }

        $order_id = $decision->order_id;
        $order = OrderModel::objects()->get(['orderid' => $order_id]);

        // user is not decision owner
        if ($order->user_id !== $user->user_id) {
            http_response_code(403);
            return;
        }

        //проверить заголовк, чтобы понять каким способом отправлены данные.
        //если есть файлы -- сохранить всё и закрепить всё за определённым decision

        if (strpos($_SERVER['HTTP_CONTENT_TYPE'], "application/json") !== false) {
            $decision->solve($this->data['options']);
        } elseif (strpos($_SERVER['HTTP_CONTENT_TYPE'], "multipart/form-data") !== false) {
            $files = $_FILES['attachments'];

            //todo: обработать случай нескольких файлов
            $uploaded_file = new UploadedFile(
                $files['tmp_name'],
                $files['name'],
                $files['type'],
                (int)$files['size'],
                (int)$files['error'],
            );

            $file = new DecisionFilesModel([
                'path' => $uploaded_file,
                'decision_id' => $_POST['decision_id'],
                'title' => $files['name'],
            ]);


            $file->save();

            $decision->solve($this->data['options']);
        }
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

        //incorrect decision
//        if ($decision->type !== DecisionModel::DECISION_LICENSE_REQUIRED) {
//            return;
//        }

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

        //incorrect decision
//        if ($decision->type !== DecisionModel::DECISION_PAYMENT_REQUIRED) {
//            return;
//        }

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

    public function getEtaProductsAction($order_id)
    {
        /**
         * @var $order OrderModel
         */
        $order = OrderModel::objects()->get(['orderid' => $order_id]);
        $products = $order->getProducts();
        $details = $order->detail_models;
        $order_products_with_amount = [];

        /**
         * @var $detail OrderDetailModel
         * @var $product ProductModel
         */
        foreach ($details as $i => $detail) {
            foreach ($products as $j => $product) {
                if ($detail->productid !== $product->productid) {
                    continue;
                }

                $order_products_with_amount[] = [
                    'product' => $product->getAttributes(),
                    'orderAmount' => $detail->getAttribute('amount'),
                    'estimateTimeArrival' => $product->getETADate(),
                ];
            }
        }

        $this->jsonResponse($order_products_with_amount);
    }

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
