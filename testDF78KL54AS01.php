<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

global $xcart_dir, $config;

$oG = Xcart\OrderGroup::model(['orderid'=>60, 'manufacturerid' => 0]);

$oG->updateField('manufacturerid', 12);