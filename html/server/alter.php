<?php
   mysql_connect('squibturf.com', 'admin', 'squibber') or die(mysql_error());   
   $sql = "SHOW TABLES FROM `Squibs`";
	$result = mysql_query($sql);
	
	
	   mysql_select_db('Squibs');
	while ($row = mysql_fetch_row($result)) {
       mysql_query("ALTER TABLE `{$row[0]}` ADD TYPE VARCHAR(60)");
   }




?>