<?
require "./auth.php";
?>
<html><head><title>mySQL script</title></head>
<body>
<form action="opt.php"  method=post name=sqlform>
<input type=hidden name=mode value="optim">
<input type=submit value="Optimize all tables">
</form>
<form action="opt.php"  method=post name=sqlform>
<input type=hidden name=mode value="rep">
<input type=submit value="Repair all tables">
</form>
<?
if (isset($mode)) { ;

if ($mode=="optim") {
foreach ($sql_tbl as $table) {
func_query("OPTIMIZE TABLE $table");
echo "Table <strong>$table</strong> was optimized.<br>";
func_flush();
}
echo "END";
}

if ($mode=="rep") { 
foreach ($sql_tbl as $table) {
func_query("REPAIR TABLE $table");
echo "Table <strong>$table</strong> was repaired.<br>";
func_flush(); 
} 
echo "END"; 
} 

}
?>
</body></html>