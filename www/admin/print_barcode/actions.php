<?php
@session_start();
include("config.php");
?>
<?php
if(!isset($_SESSION['userid'])) { header("Location: home.php"); exit; }
?>
<?php
if(mysql_result(mysql_query("SELECT `moder` FROM `users` WHERE `userid`='".$_SESSION['userid']."';"), 0, 0)!="true") { header("Location: home.php"); exit; }
?>
<?php
if(isset($_POST['fid_moder_description']) && trim($_POST['fid_moder_description'])!="" && count($_POST['del_items'])>0){

	foreach($_POST['del_items'] as $fid){
	
	$_POST['fid_moder']=$fid;
	$getanswerinfo=mysql_query("SELECT `threads`.`tid` AS `tid`, `threads`.`name` AS `name`, `answers`.`from` AS `from` FROM `answers`, `threads` WHERE `answers`.`fid`='".$_POST['fid_moder']."' AND `threads`.`tid`=`answers`.`tid`;");
	$answerinfo=mysql_fetch_object($getanswerinfo);
	$_GET['tid']=$answerinfo->tid;
	$_POST['userid_moder']=$answerinfo->from;
	$_POST['tid_name']=$answerinfo->name;

	$new_answers_count=mysql_result(mysql_query("SELECT COUNT(`fid`) FROM `answers` WHERE `tid`='".$_GET['tid']."' AND `deleted`='0';"), 0, 0)-1;
	mysql_query("UPDATE `threads` SET `answers`='".$new_answers_count."' WHERE `tid`='".$_GET['tid']."';");
	mysql_query("UPDATE `answers` SET `deleted`='1', `deleted_by_owner`='0' WHERE `fid`='".@$_POST['fid_moder']."' LIMIT 1;");
	if($_POST['userid_moder']!="anonymous") {
	mysql_query("UPDATE `users` SET `points`=".mysql_result(mysql_query("SELECT `points` FROM `users` WHERE `userid`='".$_POST['userid_moder']."';"), 0, 0)."-4 WHERE `userid`='".$_POST['userid_moder']."';");
	mysql_query("INSERT INTO tempnotifications(`userid`, `points`) VALUES('".$_POST['userid_moder']."', '-4');");
	mysql_query("UPDATE `users` SET `reputation`=".mysql_result(mysql_query("SELECT `reputation` FROM `users` WHERE `userid`='".$_POST['userid_moder']."';"), 0, 0)."-2 WHERE `userid`='".$_POST['userid_moder']."';");
	mysql_query("INSERT INTO tempnotifications(`userid`, `reputation`) VALUES('".$_POST['userid_moder']."', '-2');");
	mysql_query("INSERT INTO events(`userid`, `content`, `source`, `status`, `date`, `time`) VALUES('".$_POST['userid_moder']."', 'Your answer to the question \"".mysql_real_escape_string($_POST['tid_name'])."\" is deleted: ".mysql_real_escape_string($_POST['fid_moder_description']).".', 'thread.php?tid=".@$_GET['tid']."', 'notreaded', '".@date("d.m.Y")."', '".@date("H:i:s")."');");
	mysql_query("UPDATE `users` SET `acount`=".mysql_result(mysql_query("SELECT `acount` FROM `users` WHERE `userid`='".$_POST['userid_moder']."';"), 0, 0)."-1 WHERE `userid`='".$_POST['userid_moder']."';");
	mysql_query("INSERT INTO history(`userid`, `type`, `date`, `sum`, `description`, `content`, `src`) VALUES('".$_POST['userid_moder']."', 'reputation', '".@date("d.m.Y H:i:s")."', '-2', 'Your answer is deleted', '".mysql_real_escape_string($_POST['tid_name'])."', 'thread.php?tid=".@$_GET['tid']."');");
	}
	mysql_query("INSERT INTO moderation(`userid`, `description`, `content`, `src`, `time`) VALUES('".$_SESSION['userid']."', 'Answer is deleted: ".mysql_real_escape_string($_POST['fid_moder_description'])."', '".mysql_real_escape_string($_POST['tid_name'])."', 'thread.php?tid=".@$_GET['tid']."', '".@date("d.m.Y H:i:s")."');");
	
	}
	
	header("Location: ".$_SERVER["HTTP_REFERER"]);  exit;
}
?>
<?php
if(isset($_POST['tid_moder_description']) && trim($_POST['tid_moder_description'])!="" && count($_POST['del_items'])>0){

	foreach($_POST['del_items'] as $tid){
	
	$_GET['tid']=$tid;
	$_POST['tid_moder']=$tid;
	$getathreadinfo=mysql_query("SELECT `threads`.`tid` AS `tid`, `threads`.`name` AS `name`, `threads`.`from` AS `from` FROM `threads` WHERE `threads`.`tid`='".$_POST['tid_moder']."';");
	$threadinfo=mysql_fetch_object($getathreadinfo);
	$_POST['userid_moder']=$threadinfo->from;
	$_POST['tid_name']=$threadinfo->name;

	mysql_query("UPDATE `threads` SET `deleted`='1', `deleted_by_owner`='0' WHERE `tid`='".@$_POST['tid_moder']."';");
	mysql_query("UPDATE `answers` SET `deleted`='1', `deleted_by_deletion`='1' WHERE `deleted`='0' AND `tid`='".@$_POST['tid_moder']."';");
	if($_POST['userid_moder']!="anonymous") {
	mysql_query("UPDATE `users` SET `reputation`=".mysql_result(mysql_query("SELECT `reputation` FROM `users` WHERE `userid`='".$_POST['userid_moder']."';"), 0, 0)."-2 WHERE `userid`='".$_POST['userid_moder']."';");
	mysql_query("INSERT INTO tempnotifications(`userid`, `reputation`) VALUES('".$_POST['userid_moder']."', '-2');");
	mysql_query("INSERT INTO events(`userid`, `content`, `source`, `status`, `date`, `time`) VALUES('".$_POST['userid_moder']."', 'Your question \"".mysql_real_escape_string($_POST['tid_name'])."\" is deleted: ".mysql_real_escape_string($_POST['tid_moder_description']).".', 'thread.php?tid=".@$_GET['tid']."', 'notreaded', '".@date("d.m.Y")."', '".@date("H:i:s")."');");
	mysql_query("UPDATE `users` SET `qcount`=".mysql_result(mysql_query("SELECT `qcount` FROM `users` WHERE `userid`='".$_POST['userid_moder']."';"), 0, 0)."-1 WHERE `userid`='".$_POST['userid_moder']."';");
	mysql_query("INSERT INTO history(`userid`, `type`, `date`, `sum`, `description`, `content`, `src`) VALUES('".$_POST['userid_moder']."', 'reputation', '".@date("d.m.Y H:i:s")."', '-2', 'Your question is deleted', '".mysql_real_escape_string($_POST['tid_name'])."', 'thread.php?tid=".@$_GET['tid']."');");
	}
	mysql_query("INSERT INTO moderation(`userid`, `description`, `content`, `src`, `time`) VALUES('".$_SESSION['userid']."', 'A question is deleted: ".mysql_real_escape_string($_POST['tid_moder_description'])."', '".mysql_real_escape_string($_POST['tid_name'])."', 'thread.php?tid=".@$_GET['tid']."', '".@date("d.m.Y H:i:s")."');");
	
	}
	
	header("Location: ".$_SERVER["HTTP_REFERER"]);  exit;
}
?>