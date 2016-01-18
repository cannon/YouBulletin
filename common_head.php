<?php

//Initialize Database
include 'sql.php';
$db=sql_start();

include 'config.php';

//Time conversions
function ideal_8601toseconds($period) {
  $datetime = new DateTime('@0');
  $datetime->add(new DateInterval($period));

  return $datetime->format('U');
}
function timeago($time) {
	$time = time()-floor($time);
	if($time<60) {
		return $time." second".splural($time);
	}
	$time = floor($time/60);
	if($time<60) {
		return $time." minute".splural($time);
	}
	$time = floor($time/60);
	if($time<24) {
		return $time." hour".splural($time);
	}
	$time = floor($time/24);
	return $time." day".splural($time);
}
function splural($number) {
	if($number==1) {
		return "";
	} else {
		return "s";
	}
}

if(isset($_COOKIE["nickname"])){
	$nickname = $_COOKIE["nickname"];
} else {
	//Default name
	$nickname = "Anonymous";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="view.js"></script>
		<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
		<meta name="viewport" content="width=device-width">
