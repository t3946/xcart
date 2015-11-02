<?php

set_time_limit(86400);

if ($_POST['email']) {
	$date = date("Y-m-d H:i:s");
	$res = @mail($_POST['email'], "New test $date", "Test message $date", 'webmaster@example.com');
	if ($res){
		echo "message sent to $_POST[email] <br />";
	} else {
		echo "messge was not sent <br />";
	}
	$str =  "Start: $date Finish: ".date("Y-m-d H:i:s");
	echo $str;
	$fo = fopen("./var/log/testmail.txt","a");
	if ($fo) {
		fwrite($fo,"\n--------\n");
		fwrite($fo, "To: $_POST[email] $str");
		fclose($fo);
	}
}

echo "<br /> REMOTE ADDRESS: $_SERVER[REMOTE_ADDR]";
echo "<br /> SERVER ADDRESS: $_SERVER[SERVER_ADDR] <hr />";

?>

<form method="POST" name="emailform" action="testmail.php">

<input type='text' size='30' name='email'>
<br>
<input type='submit'>

</form>
