<?php
function connect(){
	$DB_USER =  "db15902";
	$DB_PASSWORD = "Mtxvu6SJ";
	$DB_HOST = "external-db.s15902.gridserver.com";
	$dbc = mysql_connect ($DB_HOST, $DB_USER, $DB_PASSWORD) or $error = mysql_error();
	mysql_select_db("db15902_swap") or $error = mysql_error();
	mysql_query("SET NAMES `utf8`") or $error = mysql_error();

	if($error){echo "<!-- $error -->";}}

function disconnect_data(){
	@mysql_close($dbc);
	@mysql_close();
}
?>