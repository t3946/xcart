<?php

require_once( "inc/classWheel.php" );


$cWheelCore = new WheelCore("sites.txt");

$cWheelCore->readFileSites();

$gSitesArrayJS = $cWheelCore->getJSonArray();

include_once ("template/wheeltemplate.tpl.php");

