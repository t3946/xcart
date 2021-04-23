<?php


namespace Modules\Help\Controllers\Api;

use Xcart\App\Controller\Controller;

class ApiHelpController extends Controller
{
    private const JSON = '{
                       "menuItems": [
                         {
                           "id": 1,
                           "title": "Product questions",
                           "icon": "/static/frontend/images/icons/help_center/card.svg",
                           "activeIcon": "/static/frontend/images/icons/help_center/card_active.svg",
                           "items": {
                             "route": "/help/",
                             "itemContent": [
                               {
                                 "question": "Will you be getting more stock?",
                                 "answer": "You can use any of the payment methods listed below:  Visa, MasterCard, American Express, PayPal, Money order, Check (educational institutions and governmental bodies only) ",
                                 "formType": "question"
                               },
                               {
                                 "question": "When do you process a payment ?",
                                 "answer": "1"
                               },
                               {
                                 "question": "When do you process a payment ?",
                                 "answer": "1"
                               },
                               {
                                 "question": "When do you process a payment ?",
                                 "answer": "1"
                               }
                             ]
                           }
                         },
                         {
                           "id": 2,
                           "title": "Product questions",
                           "icon": "/static/frontend/images/icons/help_center/message.svg",
                           "activeIcon": "/static/frontend/images/icons/help_center/message_active.svg",
                           "items": {
                             "route": "/help/4",
                             "itemContent": [
                               {
                                 "question": "2",
                                 "answer": "2"
                               },
                               {
                                 "question": "2",
                                 "answer": "2"
                               },
                               {
                                 "question": "Will you be getting more stock?",
                                 "answer": "You can use any of the payment methods listed below:  Visa, MasterCard, American Express, PayPal, Money order, Check (educational institutions and governmental bodies only) ",
                                 "formType": "question"
                               },
                               {
                                 "question": "2",
                                 "answer": "2"
                               }
                             ]
                           }
                         },
                         {
                           "id": 3,
                           "title": "3",
                           "icon": "/static/frontend/images/icons/help_center/order.svg",
                           "activeIcon": "/static/frontend/images/icons/help_center/order_active.svg",
                           "items": {
                             "route": "/help/3",
                             "itemContent": [
                               {
                                 "question": "3",
                                 "answer": "3"
                               },
                               {
                                 "question": "3",
                                 "answer": "3"
                               },
                               {
                                 "question": "3",
                                 "answer": "3"
                               },
                               {
                                 "question": "3",
                                 "answer": "3"
                               }
                             ]
                           }
                         },
                         {
                           "id": 4,
                           "title": "4",
                           "icon": "/static/frontend/images/icons/help_center/placing.svg",
                           "activeIcon": "/static/frontend/images/icons/help_center/placing_active.svg",
                           "items": {
                             "route": "/help/2",
                             "itemContent": [
                               {
                                 "question": "4",
                                 "answer": "4"
                               },
                               {
                                 "question": "4",
                                 "answer": "4"
                               },
                               {
                                 "question": "4",
                                 "answer": "4"
                               }
                             ]
                           }
                         },
                         {
                           "id": 5,
                           "title": "1",
                           "icon": "/static/frontend/images/icons/help_center/question.svg",
                           "activeIcon": "/static/frontend/images/icons/help_center/question_active.svg",
                           "items": {
                             "route": "/help/1",
                             "itemContent": [
                               {
                                 "question": "5",
                                 "answer": "5"
                               },
                               {
                                 "question": "5",
                                 "answer": "5"
                               },
                               {
                                 "question": "5",
                                 "answer": "5"
                               },
                               {
                                 "question": "5",
                                 "answer": "5"
                               },
                               {
                                 "question": "5",
                                 "answer": "5"
                               }
                             ]
                           }
                         }
                       ]
                     }';

    public function actionGetHelpItems()
    {
     $helpItems = json_decode(self::JSON, true);

     $this->jsonResponse($helpItems);
    }
}