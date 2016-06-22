<?php
session_start();


require "./top.inc.php";
require "./init.php";

func_new_mail_notification(['OrderLink'=>'TS-62760']);
