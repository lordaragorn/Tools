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
            die("The config for the realm database is empty.");
        else
        {
            $create_db = mysql_query("CREATE DATABASE IF NOT EXISTS $realmddb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
            if (!$create_db)
                echo "Failed to create database " . $realmddb . " because of:<br>" . mysql_error($link) . ".\n<br><br><br>";
        }
    }
    if ($connect_worlddb)
        $can_import = $can_import + 1;
    else
    {
        if ($worlddb == "")
            die("The config for the world database is empty.");
        else
        {
            $create_db = mysql_query("CREATE DATABASE IF NOT EXISTS $worlddb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
            if (!$create_db)
                echo "Failed to create database " . $realmddb . " because of:<br>" . mysql_error($link) . ".\n<br><br><br>";
        }
    }
    if ($connect_scriptdev2db)
        $can_import = $can_import + 1;
    else
    {
        if ($scriptdev2db == "")
            die("The config for the scriptdev2 database is empty.");
        else
        {
            $create_db = mysql_query("CREATE DATABASE IF NOT EXISTS $scriptdev2db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
            if (!$create_db)
                echo "Failed to create database " . $realmddb . " because of:<br>" . mysql_error($link) . ".\n<br><br><br>";
        }
    }
    if ($connect_charactersdb)
        $can_import = $can_import + 1;
    else
    {
        if ($charactersdb == "")
            die("The config for the characters database is empty.");
        else
        {
            $create_db = mysql_query("CREATE DATABASE IF NOT EXISTS $charactersdb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
            if (!$create_db)
                echo "Failed to create database " . $realmddb . " because of:<br>" . mysql_error($link) . ".\n<br><br><br>";
        }
    }
    if ($can_import == 4)
    {
        $dir1 = "D:/Users/Dark/Desktop/Batch/Database/Full Database";
        $dir2 = "D:/Users/Dark/Desktop/Batch/Core/sql/updates";
        $dir3 = "D:/Users/Dark/Desktop/Batch/Database/Updates/335/Update_Packs/ScriptDev2";
        $dir4 = "D:/Users/Dark/Desktop/Batch/Database/Updates/335/Update_Packs/MaNGOS";
        $dir5 = "D:/Users/Dark/Desktop/Batch/Database/Updates/335/Update_Packs/Characters";
        $dir6 = "D:/Users/Dark/Desktop/Batch/Database/Updates/335/SQLs_for_Next_Update_Pack/Characters";
        $dir7 = "D:/Users/Dark/Desktop/Batch/Database/Updates/335/SQLs_for_Next_Update_Pack/ScriptDev2";
        $dir8 = "D:/Users/Dark/Desktop/Batch/Database/Updates/335/SQLs_for_Next_Update_Pack/MaNGOS";
        $dir9 = "D:/Users/Dark/Desktop/Batch/Database/Updates/335/SQLs_for_Next_Update_Pack/Realmd";
        if (!num_files($dir1) == 0)
            $file_count = num_files($dir1);
        if (!num_files($dir2) == 0)
            $file_count = $file_count + num_files($dir2);
        if (!num_files($dir3) == 0)
            $file_count = $file_count + num_files($dir3);
        if (!num_files($dir4) == 0)
            $file_count = $file_count + num_files($dir4);
        if (!num_files($dir5) == 0)
            $file_count = $file_count + num_files($dir5);
        if (!num_files($dir6) == 0)
            $file_count = $file_count + num_files($dir6);
        if (!num_files($dir7) == 0)
            $file_count = $file_count + num_files($dir7);
        if (!num_files($dir8) == 0)
            $file_count = $file_count + num_files($dir8);
        if (!num_files($dir9) == 0)
            $file_count = $file_count + num_files($dir9);
        if ($file_count == 0)
            die("No SQL files found in directory: \"" . $dir . "\"<br>please check if the directory is correctly pointing to the SQL files you wish to import.");
        else
        {
            ImportDB($dir1);
            /*ImportDB($dir2);
            ImportDB($dir3);
            ImportDB($dir4);
            ImportDB($dir5);
            ImportDB($dir6);
            ImportDB($dir7);
            ImportDB($dir8);
            ImportDB($dir9);*/
        }
    }
    else
    {
        header("Location: clean.php");
    }
}
else
{
    die("Database connection could not be established, please check your configs");
    exit();
}
?>