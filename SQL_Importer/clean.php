<?php
define('PREG_FIND_RECURSIVE', 1);
define('PREG_FIND_DIRMATCH', 2);
define('PREG_FIND_FULLPATH', 4);
define('PREG_FIND_NEGATE', 8);
define('PREG_FIND_DIRONLY', 16);
define('PREG_FIND_RETURNASSOC', 32);
define('PREG_FIND_SORTDESC', 64);
define('PREG_FIND_SORTKEYS', 128); 
define('PREG_FIND_SORTBASENAME', 256);
define('PREG_FIND_SORTMODIFIED', 512);
define('PREG_FIND_SORTFILESIZE', 1024);
define('PREG_FIND_SORTDISKUSAGE', 2048);
define('PREG_FIND_SORTEXTENSION', 4096);
define('PREG_FIND_FOLLOWSYMLINKS', 8192);
include "functions.php";
$reading = fopen("config.php", 'r');
if ($reading)
    require "config.php";
else
    die("Config file not present.");
$link = mysql_connect($host, $name, $pass);
if ($link)
{
    $import = 0;
    $connect_realmddb = mysql_select_db($realmddb);
    $connect_worlddb = mysql_select_db($worlddb);
    $connect_charactersdb = mysql_select_db($charactersdb);
    $connect_scriptdev2db = mysql_select_db($scriptdev2db);
    if ($connect_realmddb)
        $can_import = $can_import + 1;
    else
    {
        if ($realmddb == "")
            echo "The config for the realm database is empty.";
        else
            die ("Error encountered: Tried opening realm database: <b>`$realmddb`</b> in ".__FILE__." on line: ".__LINE__."<br>".mysql_error());
        exit();
    }
    if ($connect_worlddb)
        $can_import = $can_import + 1;
    else
    {
        if ($worlddb == "")
            echo "The config for the world database is empty.";
        else
            die ("Error encountered: Tried opening world database: <b>`$worlddb`</b> in ".__FILE__." on line: ".__LINE__."<br>".mysql_error());
        exit();
    }
    if ($connect_scriptdev2db)
        $can_import = $can_import + 1;
    else
    {
        if ($scriptdev2db == "")
            echo "The config for the scriptdev2 database is empty.";
        else
            die ("Error encountered: Tried opening scriptdev2 database: <b>`$scriptdev2db`</b> in ".__FILE__." on line: ".__LINE__."<br>".mysql_error());
        exit();
    }
    if ($connect_charactersdb)
        $can_import = $can_import + 1;
    else
    {
        if ($charactersdb == "")
            echo "The config for the characters database is empty.";
        else
            die ("Error encountered: Tried opening characters database: <b>`$charactersdb`</b> in ".__FILE__." on line: ".__LINE__."<br>".mysql_error());
        exit();
    }
    if ($can_import == 4)
        ImportDB();
    else
        die("Importing will not continue untill the above issue is solved.");
}
else
{
    die("Database connection could not be established, please check your configs");
    exit();
}
?>