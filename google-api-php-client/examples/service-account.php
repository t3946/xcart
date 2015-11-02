<?php
/*
 * Copyright 2013 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
session_start();
include_once "templates/base.php";

/************************************************
  Make an API request authenticated with a service
  account.
 ************************************************/
require_once realpath(dirname(__FILE__) . '/../autoload.php');

require "../../auth.php";

/************************************************
  ATTENTION: Fill in these values! You can get
  them by creating a new Service Account in the
  API console. Be sure to store the key file
  somewhere you can get to it - though in real
  operations you'd want to make sure it wasn't
  accessible from the webserver!
  The name is the email address value provided
  as part of the service account (not your
  address!)
  Make sure the Books API is enabled on this
  account as well, or the call will fail.
 ************************************************/
$client_id = '544879562678-602vuj5s9jo0hppg9tb3p07chk4g3mr3.apps.googleusercontent.com'; //Client ID
$service_account_name = '544879562678-602vuj5s9jo0hppg9tb3p07chk4g3mr3@developer.gserviceaccount.com'; //Email Address
$key_file_location = '/var/www/stores/google-api-php-client/examples/key.p12'; //key.p12

echo pageHeader("Service Account Access");


if ($client_id == '<YOUR_CLIENT_ID>'
    || !strlen($service_account_name)
    || !strlen($key_file_location)) {
  echo missingServiceAccountDetailsWarning();
}


$client = new Google_Client();
$client->setApplicationName("Client_Library_Examples");
//$service = new Google_Service_Books($client);
$service = new Google_Service_ShoppingContent($client);

/************************************************
  If we have an access token, we can carry on.
  Otherwise, we'll get one with the help of an
  assertion credential. In other examples the list
  of scopes was managed by the Client, but here
  we have to list them manually. We also supply
  the service account
 ************************************************/
if (isset($_SESSION['service_token'])) {
  $client->setAccessToken($_SESSION['service_token']);
}
$key = file_get_contents($key_file_location);
$cred = new Google_Auth_AssertionCredentials(
    $service_account_name,
    array('https://www.googleapis.com/auth/content'),
    $key
);
$client->setAssertionCredentials($cred);
if ($client->getAuth()->isAccessTokenExpired()) {
  $client->getAuth()->refreshTokenWithAssertion($cred);
}
$_SESSION['service_token'] = $client->getAccessToken();

//$optParams = array('filter' => 'free-ebooks');



$results = $service->products->get("10967321", "online:en:US:74354");
func_print_r($results);

/*
$new_results = $results;
$new_results["title"] = "TEST_TEST_product";
$new_results["id"] = str_replace("74354", "326715", $new_results["id"]);
$new_results["adwordsRedirect"] = str_replace("74354", "326715", $new_results["adwordsRedirect"]);
$new_results["offerId"] = str_replace("74354", "326715", $new_results["offerId"]);
$new_results["link"] = "http://www.rfidlocksandmore.com/product/326715/test/?utm_source=RF-froogle_Google-Shopping&utm_medium=ZephyrLock&utm_campaign=ZPRTEST01";
func_print_r($new_results );

$results2 = $service->products->insert("10967321", $new_results);
func_print_r($results2);
*/


//$results3 = $service->products->delete("10967321", "online:en:US:326715");
//func_print_r($results3);

/*
echo "<h3>Results Of Call:</h3>";

//if (!empty($results))
foreach ($results as $item) {
  func_print_r($item); 
  print(".<br /> \n");
}

echo pageFooter(__FILE__);
*/
