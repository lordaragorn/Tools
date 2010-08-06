<?php
ob_start();
include "functions.php"
$settingsfile = "http://" . $_SERVER['HTTP_HOST'] . "/config.php";
$reading = @fopen($settingsfile, 'r');
if ($reading)
{
    require "config.php";
    if ($host == "" || $port == "" || $name == "" || $pass == "" || $worlddb == "" || $realmddb == "" || $scriptdev2db == "" || $charactersdb == "" || $errors != 0)
        ShowForm();
    else
    {
        ?>
        <html>
        <body>
        <br><br>
        <center>
        This is a test interface for the Auto-Compiler.<br>
        This will allow you to update your database using a more sophisticated way than the batch part.<br><br>
        <form name="database" action="<?php echo $PHP_SELF; ?>" method="POST">
        Update existing database now! <input type="submit" name="database" value="Update"><br>
        Create a clean database now! <input type="submit" name="database" value="New"><br>
        </form>
        </center>
        <?php
        $host = $host . ":" . $port;
        $link = mysql_connect($host, $name, $pass);
        if ($link)
        {
            $database_connect = mysql_select_db($worlddb, $link);
            if ($database_connect)
            {
                echo "<br><br><center>Connection to the database ('$worlddb') is succesfully established, above buttons will now function.</center>";
                if (isset($_POST['database']))
                {
                    if ($_POST['database'] == "New")
                    {
                        header("Location: clean.php");
                    }
                    else
                    {
                        header("Location: update.php");
                    }
                }
            }
            else
            {
                die ("Error encountered: Tried opening world database: <b>`$worlddb`</b> in ".__FILE__." on line: ".__LINE__."<br>".mysql_error());
                exit();
            }
        }
        else
        {
            echo "Database connection could not be established, please check your configs";
            exit();
        }
    }
}
else
{
    ShowForm();
}
ob_end_clean;
?>