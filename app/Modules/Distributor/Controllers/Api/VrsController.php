<?php


namespace Modules\Distributor\Controllers\Api;


use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\VrsHelperMessagesModel;
use Modules\Distributor\Models\VrsHelperSitesModel;
use Modules\User\Models\UserModel;
use Modules\User\UserModule;
use Throwable;
use Xcart\App\Controller\Controller;
use \Firebase\JWT\JWT;
use Xcart\App\Exceptions\Exception;

class VrsController extends Controller
{
    public string $key = "your_secret_key";
    public string $iss = "http://any-site.org";
    public string $aud = "http://any-site.com";
    public int $iat = 1356999524;
    public int $nbf = 1357000000;

  public function getSiteStatus(string $url)
  {
   $thisSite = VrsHelperSitesModel::objects()->filter(['domain' => $url]);

   $status = 'first-time';

   if(!($thisSite->count() > 0))
   {
       $dx = [];
       $status = 'visited';
       foreach (  DistributorModel::objects()->asArray(true)->all() as $distributor)
       {
           if(str_contains($distributor['url'],$url ))
           {
               $dx = $distributor;
           }
       }
       if($dx)
       {
           $status = 'inactive';
           if($dx['avail'] === 'Y'){
               $status = 'active';
           }
       }

       VrsHelperSitesModel::objects()->getOrCreate(['domain'=>$url, 'status'=>$status]);
       $this->jsonResponse(['status' => $status]);
       return;
   }



        $this->jsonResponse(['status'=>$thisSite[0]['status']]);
  }

  public function getMessageFromDomain(string $domain){
      $site_model = VrsHelperSitesModel::objects()->filter(['domain' => $domain])->asArray(true)->all()[0];
      if($site_model['status'] === 'active' || $site_model['status'] === 'inactive' )
      {
          $dx = DistributorModel::objects()->limit(1)->get(['url__contains' => $domain]);
          $status = $site_model['status'];
          $dx_created_user = ['b_firstname' => $dx->provider_model->getAttributes()['b_firstname'], 'b_lastname' => $dx->provider_model->getAttributes()['b_lastname']];
          $first_message = ['message_text' => "This is our $status Dx",'status' => 'status','ourDx'=> true, 'date' => $dx->created_at, 'user' => $dx_created_user];
      }


      if(!(VrsHelperMessagesModel::objects()->filter(['site_id' => $site_model['site_id']])->count() > 0))
      {
          if($first_message){
              return  [$first_message];
          }
          return [];
      }


      $messages = VrsHelperMessagesModel::objects()->filter(['site_id' => $site_model['site_id']])->order(['date'])->asArray(true)->all();
      $a = [];
      foreach ($messages as $message)
      {
          $message['user'] = UserModel::objects()->filter(['id'=>$message['user_id']])->asArray(true)->all()[0];

          $a[] = $message;
      }


      if($first_message)
      {
          array_unshift($a, $first_message);
      }

      return $a;
  }

  public function getMessages(string $domain)
  {
     $this->jsonResponse(['messages' => $this->getMessageFromDomain($domain)]);
  }

  public function sendMessage()
  {
      $data = json_decode(file_get_contents('php://input'), true);
      $data = $data['message'];
      $message = [];

      $site_model = VrsHelperSitesModel::objects()->filter(['domain' => $data['domain']])->get();

      $message['message_text'] = $data['message_text'];

      $message['status'] = $data['status'];

      $message['user_id'] = $data['user_id'];

      $message['site_id'] = $site_model->site_id;

      if($data['dxStatus']){
          $site_model->status = $data['dxStatus'];
          $site_model->save();
      }

      VrsHelperMessagesModel::objects()->create($message);

      $this->jsonResponse(['messages' => $this->getMessageFromDomain($data['domain'])]);
  }

  public  function userAuthorization()
  {

      $authorizationData = json_decode(file_get_contents('php://input'), true);

      $authorizationData = $authorizationData['loginData'];

      $hasher = UserModule::getPasswordHasher();

      $user = UserModel::objects()->filter(["login"=> $authorizationData['login']])->asArray(true)->all()[0];

      if ($user) {
          if ($hasher::verify($authorizationData['password'], $user['password'])) {
              $token = array(
                  "iss" => $this->iss,
                  "aud" => $this->aud,
                  "iat" => $this->iat,
                  "nbf" => $this->nbf,
                  "data" => array(
                      "id" => $user['id'],
                      "firstname" => $user['firstname'],
                      "lastname" => $user['lastname'],
                      "email" => $user['email']
                  )
              );
              $jwt = JWT::encode($token, $this->key);

              $this->jsonResponse(['jwt'=>$jwt, 'user'=>$user]);
              return;
          }
          $this->jsonResponse(['error'=>['error'=>true,'errorMessage'=>'Incorrect password']]);
          return;
      }
      $this->jsonResponse(['error'=>['error'=>true,'errorMessage'=>'User not Found']]);
  }

  public function userJWTAuthorization()
  {
      $data = json_decode(file_get_contents("php://input"), true);

      $jwt = $data['jwt'];

      if($jwt) {
          try {
              $decoded = JWT::decode($jwt, $this->key, array('HS256'));
              $user = UserModel::objects()->filter(["id"=> $decoded->data->id])->asArray(true)->all()[0];
                $this->jsonResponse(['user' => $user]);
                return;
          }
          catch (Throwable $e){
              $this->jsonResponse('jwt dead');
          }
      }
  }
}