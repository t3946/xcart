<?
$flag = true;
$counter = 0;

ob_start();
while ($flag) {
	ob_start();
	$counter = $counter + 1; 
    	if (preg_match("/Apache(.*)Win/", getenv("SERVER_SOFTWARE")))
        	echo str_repeat(" ", 2500);
    	elseif (preg_match("/(.*)MSIE(.*)\)$/", getenv("HTTP_USER_AGENT")))
        	echo str_repeat(" ", 256);
	echo $counter."<br>";
 	ob_end_flush();	
	if (ob_get_contents() !== false) {
		echo("buffer-".$counter.":<BR>".ob_get_contents()."<BR>");
		ob_end_flush();	
	}	
	echo $counter."-test<br>";
	ob_flush();
	if ($counter == 1000) $flag = false;
}
?> 
