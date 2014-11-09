<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

// MySQL server 
Define("db_name",mysql_db);
Define("db_host",mysql_host);
Define("db_user",mysql_user);
Define("db_pass",mysql_password); 

// Dalsi nastaveni
//$file_name= // jmeno souboru pro DUMP
$show_echo=0; // Zobrzit DUMP (0=ne / 1=ano);
$crlf="\n"; // nastaveni konce radku

// FUNKCE
function GetTableDef($table, $crlf)
{
	// MySQL >= 3.23.20
	$schema_create = "";
	$schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
	$result = mysql_query("SHOW CREATE TABLE $table");
	$data=mysql_fetch_array($result);
	$schema_create .= $data[1] . ";$crlf$crlf";
	mysql_free_result($result);
	return $schema_create;
} // end of the 'GetTableDef()' function


function GetTableContent($table,$crlf)
{
	global $use_backquotes;
	global $rows_cnt;
	global $current_row;
	
	$schema_insert="";
	$local_query = "SELECT * FROM $table ".($show_echo/*?'LIMIT 4':''*/);
	$result = mysql_query($local_query);
	if ($result != FALSE) {
		$fields_cnt = mysql_num_fields($result);
		$rows_cnt   = mysql_num_rows($result);
		// Checks whether the field is an integer or not
		for ($j = 0; $j < $fields_cnt; $j++) {
			$field_set[$j] = mysql_field_name($result, $j);
			$type          = mysql_field_type($result, $j);
			if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
			$type == 'bigint'  ||$type == 'timestamp') {
				$field_num[$j] = TRUE;
			} else {
				$field_num[$j] = FALSE;
			}
		} // end for
		// Sets the scheme
		//$schema_insert .= "INSERT INTO $table VALUES (";
		$search       = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
		$replace      = array('\0', '\n', '\r', '\Z');
		$current_row  = 0;
		while ($row = mysql_fetch_row($result)) {
			$schema_insert .= "INSERT INTO $table VALUES (";
			$current_row++;
			for ($j = 0; $j < $fields_cnt; $j++) {
				if (!isset($row[$j])) {
					$values[]     = 'NULL';
				} else if ($row[$j] == '0' || $row[$j] != '') {
					// a number
					if ($field_num[$j]) {
						$values[] = $row[$j];
					}
					// a string
					else {
						$values[] = "'" . str_replace($search, $replace, $row[$j]) . "'";
					}
				} else {
					$values[]     = "''";
				} // end if
			} // end for
			
			
			FPutS($GLOBALS['file_dump'],$schema_insert);$schema_insert="";
			
			$max=SizeOf($values);
			for ($i=0;$i<$max;$i++){
				if ($i!=0) $schema_insert .= ", ";
				$schema_insert .= $values[$i] ;
				FPutS($GLOBALS['file_dump'],$schema_insert);$schema_insert="";
			}
			$schema_insert .= ");$crlf";
			
			unset($values);
		} // end while
	} // end if ($result != FALSE)
	mysql_free_result($result);
	
	
	FPutS($GLOBALS['file_dump'],$schema_insert);$schema_insert="";
	return $schema_insert;
} // end of the 'GetTableContent()' function
// MAIN

$dump_buffer="";
@$dbspojeni=mysql_connect(db_host,db_user,db_pass);
if (!$dbspojeni){
	echo "Error: Can't connect to MySQL server.";
	Die();
}
mysql_query("set names utf8");
mysql_select_db(db_name);

$tables=mysql_list_tables(db_name);
$num_tables=mysql_numrows($tables);
$dump_buffer="#MySQL DUMP$crlf";


$GLOBALS['file_dump']=Fopen($file_name,"w");


for ($i=0;$i<$num_tables;$i++){
	$table=mysql_tablename($tables,$i);
	
	if(strpos($table,mpx)!==false or $_GET['export']==2){
	//if(strpos($table,'memory')===false){
	//if($table!=mpx.'log'){

		$dump_buffer.="$crlf#Table name: $table$crlf$crlf";
		$dump_buffer.=GetTableDef($table,"$crlf");
		$dump_buffer.="$crlf#DATA$crlf";
		//$dump_buffer.=GetTableContent($table,"$crlf");
		$dump_buffer.="$crlf$crlf";
		FPutS($GLOBALS['file_dump'],$dump_buffer);

		GetTableContent($table,"$crlf");
				
		if ($show_echo==1){
 			 echo nl2br($dump_buffer);
		}
		$dump_buffer='';
		//echo nl2br($dump_buffer);
	//}}}
	}

}

mysql_close();


FClose($GLOBALS['file_dump']);
chmod($file_name,0777);

create_zip($file_name,$file_zip);

?>
