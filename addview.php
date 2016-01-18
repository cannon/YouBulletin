<?php 
include 'sql.php';
$db=sql_start();

$found = $db->query("SELECT views,viewdata,viewtime FROM youbulletin WHERE id=".$db->quote($_GET["id"]))->fetch(PDO::FETCH_ASSOC);
$ips = json_decode($found["viewdata"],true);
if(!isset($ips[$_SERVER['REMOTE_ADDR']])){
	$ips[$_SERVER['REMOTE_ADDR']]=1;
	$db->query("UPDATE youbulletin SET views=".$db->quote($found["views"]+1).",viewdata=".$db->quote(json_encode($ips)).",viewtime=".$db->quote(microtime(TRUE))." WHERE id=".$db->quote($_GET["id"]));
}
?>
