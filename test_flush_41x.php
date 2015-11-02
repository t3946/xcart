<?
$flag = true;
$counter = 0;

while ($flag) {
	$counter = $counter + 1;

	echo $counter."<BR>";

   	if (preg_match("/Apache(.*)Win/", getenv("SERVER_SOFTWARE")))
       	echo str_repeat(" ", 2500);
   	elseif (preg_match("/(.*)MSIE(.*)\)$/", getenv("HTTP_USER_AGENT")))
       	echo str_repeat(" ", 256);

	#if (ob_get_contents() !== false) {
		echo("output buffer[".$counter."]:<BR>START[".$counter."]<BR>".ob_get_contents()."END[".$counter."]<BR>");

		if (function_exists('ob_flush')) {
			# for PHP >= 4.2.0
			ob_flush();
		}
		else {
			# for PHP < 4.2.0
			if (ob_get_length() !== FALSE)
			       ob_end_flush();
		}

#		ob_end_flush();	
	#}
	
	flush();
	if ($counter == 1000) $flag = false;
}
?> 
