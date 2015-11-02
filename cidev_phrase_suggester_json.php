<?php

if (!isset($from_script)){
	$from_script = "";
}

if ($from_script == "elastic_search.php"){
	$twotabsearchtextbox = $e_search_data["substring"];
}
else {
	require './auth.php';
}

//func_print_r($twotabsearchtextbox);

include $xcart_dir."/elastic_search_suggester.php";


//$suggests = "";

if (empty($suggests)){

                $e_search_data_substring = trim($twotabsearchtextbox);
                $spaces = substr_count($e_search_data_substring, ' ') + 2;

//                $search_phrase_updated = $e_search_data_substring." ";
                $search_phrase_updated = ltrim($twotabsearchtextbox);

                $query = "select LOWER(SUBSTRING_INDEX(SS.search_phrase,' ',$spaces)) As Suggester
       from xcart_search_stats SS 
       where SS.storefrontid = '$current_storefront' and SS.hits>0 and SS.search_phrase like '$search_phrase_updated%'
       group By Suggester
       Order By COUNT(SS.id) desc
        Limit 6";

                $query_result = func_query($query);

                if (!empty($query_result)){
                        foreach ($query_result as $k => $v){
                                $Suggester = $v["Suggester"];

                                $tmp_Suggester = str_replace($e_search_data_substring, "</em>".$e_search_data_substring."<em>", $Suggester);
                                $tmp_Suggester = trim($tmp_Suggester);

                                $suggest = "<em>".$tmp_Suggester."</em>";
                                $suggest = str_replace("<em></em>","",$suggest);

                                $suggests[] = $suggest;
                        }
                }
}


if (!empty($suggests)){
	foreach ($suggests as $k => $suggest){

		$suggest = str_replace("<em>","<em><strong>",$suggest);
		$suggest = str_replace("</em>","</strong></em>",$suggest);

		$suggests_arr[$k]["twotabsearchtextbox"] = $suggest;

		$clean_suggest = strip_tags($suggest);
		$clean_suggest = trim($clean_suggest);
		$suggests_arr[$k]["clean_suggest"] = $clean_suggest;

		if (trim($twotabsearchtextbox) == $clean_suggest){
			unset($suggests_arr[$k]);
		}
	}

	if ($from_script == "elastic_search.php"){

		if (!empty($suggests_arr)){
			$smarty->assign("suggests_arr", $suggests_arr);
		}
	}
	else {
		$suggests = json_encode($suggests_arr);

		if (!empty($suggests)){
			print($suggests);
		}
	}
}
?>
