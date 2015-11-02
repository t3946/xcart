<?php

set_time_limit(86400);

if ($HTTP_POST_VARS['email']) {
	$date = date("Y-m-d H:i:s");
	$res = @mail($HTTP_POST_VARS['email'], "New test $date", "Test message $date", 'webmaster@example.com');
	if ($res){
		echo "message sent to $HTTP_POST_VARS[email] <br />";
	} else {
		echo "messge was not sent <br />";
	}
	$str =  "Start: $date Finish: ".date("Y-m-d H:i:s");
	echo $str;
	$fo = fopen("./var/log/testmail.txt","a");
	if ($fo) {
		fwrite($fo,"\n--------\n");
		fwrite($fo, "To: $HTTP_POST_VARS[email] $str");
		fclose($fo);
	}
}

echo "<br /> REMOTE ADDRESS: $HTTP_SERVER_VARS[REMOTE_ADDR]";
echo "<br /> SERVER ADDRESS: $HTTP_SERVER_VARS[SERVER_ADDR] <hr />";

?>

<form method="POST" name="emailform" action="testmail.php">

<input type='text' size='30' name='email'>
<br>
<input type='submit'>

</form>
