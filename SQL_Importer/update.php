<?php
ob_start();
require "config.php";
$link = mysql_connect($host, $name, $pass);
if ($link)
{
    $realmddb = mysql_select_db($realmddb);
    if ($realmddb)
    {
    }
    else
    {
        die ("Error encountered: Tried opening realm database: <b>`$realmddb`</b> in ".__FILE__." on line: ".__LINE__."<br>".mysql_error());
        exit();
    }
}
else
{
    echo "Database connection could not be established, please check your configs";
    exit();
}
ob_end_clean();
?>