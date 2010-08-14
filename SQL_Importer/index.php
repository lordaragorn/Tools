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
include "style.css";
$reading = @fopen("config.php", 'r');
if ($reading)
{
    require "config.php";
    if ($host == "" || $port == "" || $name == "" || $pass == "" || $worlddb == "" || $realmddb == "" || $scriptdev2db == "" || $charactersdb == "" || $errors != 0)
        ShowForm();
    else if (isset($_POST['database']))
    {
        if ($_POST['database'] == "New")
            $New = 1;
        else if ($_POST['database'] == "Update")
            $New = 0;
        $host = $host . ":" . $port;
        $link = mysql_connect($host, $name, $pass);
        if ($link)
        {
            global $can_import;
            CheckConnect();
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
                if (!count_files_recursive($dir1) == 0 && $New == 1)
                {
                    $file_count = $file_count + count_files_recursive($dir1);
                    $error_dirs .= $dir1 . "<br>";
                }
                if (!count_files_recursive($dir2) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir2);
                    $error_dirs .= $dir2 . "<br>";
                }
                if (!count_files_recursive($dir3) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir3);
                    $error_dirs .= $dir3 . "<br>";
                }
                if (!count_files_recursive($dir4) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir4);
                    $file_count = $file_count + count_files_recursive("D:/Users/Dark/Desktop/Batch/Database/Updates/335/SQLs_for_Next_Update_Pack/MaNGOS/ruby_sanctum/");
                    $file_count = $file_count + count_files_recursive("D:/Users/Dark/Desktop/Batch/Database/Updates/335/SQLs_for_Next_Update_Pack/MaNGOS/icc sql/");
                    $error_dirs .= $dir4 . "<br>";
                }
                if (!count_files_recursive($dir5) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir5);
                    $error_dirs .= $dir5 . "<br>";
                }
                if (!count_files_recursive($dir6) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir6);
                    $error_dirs .= $dir6 . "<br>";
                }
                if (!count_files_recursive($dir7) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir7);
                    $error_dirs .= $dir7 . "<br>";
                }
                if (!count_files_recursive($dir8) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir8);
                    $error_dirs .= $dir8 . "<br>";
                }
                if (!count_files_recursive($dir9) == 0)
                {
                    $file_count = $file_count + count_files_recursive($dir9);
                    $error_dirs .= $dir9 . "<br>";
                }
                if ($file_count == 0)
                    die("No SQL files found in directory/directories: <br>" . $error_dirs . "<br>please check if the directory is correctly pointing to the SQL files you wish to import.");
                else
                {
                    if ($New == 1)
                        ImportDB($dir1);
                    ImportDB($dir2);
                    ImportDB($dir3);
                    ImportDB($dir4);
                    ImportDB($dir5);
                    ImportDB($dir6);
                    ImportDB($dir7);
                    ImportDB($dir8);
                    ImportDB($dir9);
                }
            }
            else
                header("Location: index.php");
        }
        else
            die("Database connection could not be established, please check your config file.");
    }
    else
    {
        ?>
        <html>
        <body>
        <div id="wrapper">
            <div id="header">
                <p>This is a test interface for the SQL-Importer.</p>
                <p>This will allow you to update your database using a more sophisticated way than the batch part.</p>
            </div>
            <div id="main">
                <form name="database" action="<?php echo $PHP_SELF;?>" method="POST">
                <p>Update existing database now! <input type="submit" name="database" value="Update"></p>
                <p>Create a clean database now! <input type="submit" name="database" value="New"></p>
                </form>
            </div>
        </div>   
        </body>
        </html>
        <?php
    }
}
else
    ShowForm();
?>