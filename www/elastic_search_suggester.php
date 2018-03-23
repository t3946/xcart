<?php

        if (!empty($current_storefront)){
                $tmp_domain = func_query_first_cell("SELECT domain FROM $sql_tbl[storefronts] WHERE storefrontid='$current_storefront'");
        } else {
                $tmp_domain = "www.artistsupplysource.com";
        }


        $url = $config["ElasticSearch_options"]["es_url"].$tmp_domain."/product/_search?size=5&from=0";

        //$e_search_data_substring = preg_replace("/[^0-9a-zA-Z\.\'\-]/S", " ", $twotabsearchtextbox);
        $e_search_data_substring = $twotabsearchtextbox;
       	$e_search_data_substring = trim($e_search_data_substring);

	$query = '
{
   "suggest" : {
     "text" : "'.$e_search_data_substring.'",
     "simple_phrase" : {
       "phrase" : {
         "highlight": {
          "pre_tag": "<em>",
          "post_tag": "</em>"
        },
         "field" :  "productname",
         "size" :   5,
         "direct_generator" : [ {
           "field" :            "description",
           "suggest_mode" :     "missing",
           "min_word_length" :  2
         } ],
         "collate": {
           "query":{

   "dis_max" : {
        "queries" : [
            {
                "query_string": {
                             "query": "{{suggestion}}",
                             "fields": ["productname.productname_original^1.5","sku","upc","brand.brand_original^0.5","description.description_original"]
                                 }
            }
,
            {
         "query_string": {
            "query": "{{suggestion}}",
            "analyzer": "snowball",
           "fields": ["productname.productname^1.5","sku","upc","brand.brand^0.5","description.description"]
                                 }
            }
,
            {
        "match_phrase_prefix": {
            "sku_original": "{{suggestion}}"
                       }
             }

            ]
    }
}
         }
       }
     }
   }
 }
';

	$data_json = $query;

/*        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ("Accept: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result_json = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result_json, true);*/
$result = [];

//func_print_r($result);

//func_print_r($result["suggest"]["simple_phrase"]);

	$suggests = array();

	if (!empty($result["suggest"]["simple_phrase"]) && is_array($result["suggest"]["simple_phrase"])){
		foreach ($result["suggest"]["simple_phrase"] as $k => $v){
			if (!empty($v["options"]) && is_array($v["options"])){
				foreach ($v["options"] as $kk => $vv){
					if (!empty($vv["highlighted"])){
						$suggests[] = $vv["highlighted"];
					}
				}
			}
		}
	}

//func_print_r($suggests);
?>
