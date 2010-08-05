<?php
ob_start();
function WriteVars()
{
    $Pattern = '/\s*/m';
    $Replace = '';
    $settingsfile = "config.php";
    $reading = @fopen($settingsfile, 'r');
    if ($reading)
        require "config.php";
    if ($_POST["host"] != "" && $host == "")
    {
        $set_host = trim($_POST["host"]);
        $set_host = preg_replace($Pattern, $Replace, $set_host);
    }
    if ($_POST["port"] != "" && $port == "")
    {
        $set_port = trim($_POST["port"]);
        $set_port = preg_replace($Pattern, $Replace, $set_port);
    }
    if ($_POST["name"] != "" && $name == "")
    {
        $set_name = trim($_POST["name"]);
        $set_name = preg_replace($Pattern, $Replace, $set_name);
    }
    if ($_POST["pass"] != "" && $pass == "")
    {
        $set_pass = trim($_POST["pass"]);
        $set_pass = preg_replace($Pattern, $Replace, $set_pass);
    }
    if ($_POST["worlddb"] != "" && $worlddb == "")
    {
        $set_worlddb = trim($_POST["worlddb"]);
        $set_worlddb = preg_replace($Pattern, $Replace, $set_worlddb);
    }
    if ($_POST["scriptdev2db"] != "" && $scriptdev2db == "")
    {
        $set_scriptdev2db = trim($_POST["scriptdev2db"]);
        $set_scriptdev2db = preg_replace($Pattern, $Replace, $set_scriptdev2db);
    }
    if ($_POST["charactersdb"] != "" && $charactersdb == "")
    {
        $set_charactersdb = trim($_POST["charactersdb"]);
        $set_charactersdb = preg_replace($Pattern, $Replace, $set_charactersdb);
    }
    if ($_POST["realmddb"] != "" && $realmddb == "")
    {
        $set_realmddb = trim($_POST["realmddb"]);
        $set_realmddb = preg_replace($Pattern, $Replace, $set_realmddb);
    }
    $newfile = "config.php";
    $creation = fopen($newfile, 'w');
    $stringData = "<?php\n";
    fwrite($creation, $stringData);
    //Host
    if ($set_host == "" && $host == "")
        $stringData = '$host' . " = " . '""' . ";\n";
    else if ($host != "")
        $stringData = '$host' . " = " . $host . ";\n";
    else
        $stringData = '$host' . " = " . $set_host . ";\n";
    fwrite($creation, $stringData);
    //Port
    if ($set_port == "" && $port == "")
        $stringData = '$port' . " = " . '""' . ";\n";
    else if ($port != "")
        $stringData = '$port' . " = " . $port . ";\n";
    else
        $stringData = '$port' . " = " . $set_port . ";\n";
    fwrite($creation, $stringData);
    //Name
    if ($set_name == "" && $name == "")
        $stringData = '$name' . " = " . '""' . ";\n";
    else if ($name != "")
        $stringData = '$name' . " = " . $name . ";\n";
    else
        $stringData = '$name' . " = " . $set_name . ";\n";
    fwrite($creation, $stringData);
    //Password
    if ($set_pass == "" && $pass == "")
        $stringData = '$pass' . " = " . '""' . ";\n";
    else if ($pass != "")
        $stringData = '$pass' . " = " . $pass . ";\n";
    else
        $stringData = '$pass' . " = " . $set_pass . ";\n";
    fwrite($creation, $stringData);
    //World
    if ($set_worlddb == "" && $worlddb == "")
        $stringData = '$worlddb' . " = " . '""' . ";\n";
    else if ($worlddb != "")
        $stringData = '$worlddb' . " = " . $worlddb . ";\n";
    else
        $stringData = '$worlddb' . " = " . $set_worlddb . ";\n";
    fwrite($creation, $stringData);
    //SD2
    if ($set_scriptdev2db == "" && $scriptdev2db == "")
        $stringData = '$scriptdev2db' . " = " . '""' . ";\n";
    else if ($scriptdev2db != "")
        $stringData = '$scriptdev2db' . " = " . $scriptdev2db . ";\n";
    else
        $stringData = '$scriptdev2db' . " = " . $set_scriptdev2db . ";\n";
    //Characteres
    fwrite($creation, $stringData);
    if ($set_charactersdb == "" && $charactersdb == "")
        $stringData = '$charactersdb' . " = " . '""' . ";\n";
    else if ($charactersdb != "")
        $stringData = '$charactersdb' . " = " . $charactersdb . ";\n";
    else
        $stringData = '$charactersdb' . " = " . $set_charactersdb . ";\n";
    fwrite($creation, $stringData);
    //Realm
    if ($set_realmddb == "" && $realmddb == "")
        $stringData = '$realmddb' . " = " . '""' . ";\n";
    else if ($realmddb != "")
        $stringData = '$realmddb' . " = " . $realmddb . ";\n";
    else
        $stringData = '$realmddb' . " = " . $set_realmddb . ";\n";
    fwrite($creation, $stringData);
    $errors = 0;
    if ($set_host == "")
    {
        if ($host == "")
            $errors = $errors + 1;
    }
    if ($set_port == "")
    {
        if ($port == "")
            $errors = $errors + 1;
    }
    if ($set_name == "")
    {
        if ($name == "")
            $errors = $errors + 1;
    }
    if ($set_pass == "")
    {
        if ($pass == "")
            $errors = $errors + 1;
    }
    if ($set_worlddb == "")
    {
        if ($worlddb == "")
            $errors = $errors + 1;
    }
    if ($set_charactersdb == "")
    {
        if ($charactersdb == "")
            $errors = $errors + 1;
    }
    if ($set_scriptdev2db == "")
    {
        if ($scriptdev2db == "")
            $errors = $errors + 1;
    }
    if ($set_realmddb == "")
    {
        if ($realmddb == "")
            $errors = $errors + 1;
    }
    $stringData = '$errors' . " = " . $errors . ";\n";
    fwrite($creation, $stringData);
    $stringData = "?>";
    fwrite($creation, $stringData);
    fclose($creation);
    $reading = @fopen($settingsfile, 'r');
    if (!$reading)
        die ("Unexpected error during writing of the config file.");
    header("Location: Auto-Compiler.php");
    ob_end_clean;
    exit;
}
$settingsfile = "http://" . $_SERVER['HTTP_HOST'] . "/test/config.php";
$reading = @fopen($settingsfile, 'r');
if ($reading)
{
    require "config.php";
    if ($host == "" || $port == "" || $name == "" || $pass == "" || $worlddb == "" || $realmddb == "" || $scriptdev2db == "" || $charactersdb == "" || $errors != 0)
    {
        if ($errors != 0)
        {
            if ($errors == 8)
                echo "<center>You didn't fill in anything the last time.</center>";
            else if ($errors >= 2)
                echo "<center>You forgot to fill in a couple of fields.</center>";
            else
                echo "<center>You forgot to fill in one of the fields.</center>";
        }
        ?>
        <html>
        <body>
        <center><br>
        <form name="input" action="<?php echo $PHP_SELF; ?>" method="post">
        <?php
        if ($set_host == "")
        {?>
            Host address: <input type="text" name="host" value="localhost"><br>
        <?php
        }
        if ($port == "")
        {?>
            Mysql Port: <input type="text" name="port" value="3306"><br>
        <?php
        }
        if ($name == "")
        {?>
            Mysql Username: <input type="text" name="name" value="root"><br>
        <?php
        }
        if ($pass == "")
        {?>
            Mysql Password: <input type="text" name="pass" value="mangos"><br>
        <?php
        }
        if ($worlddb == "")
        {?>
            World Database name: <input type="text" name="worlddb" value="mangos"><br>
        <?php
        }
        if ($charactersdb == "")
        {?>
             Characters Database name: <input type="text" name="charactersdb" value="characters"><br>
        <?php
        }
        if ($scriptdev2db == "")
        {?>
            ScriptDev2 Database name: <input type="text" name="scriptdev2db" value="scriptdev2"><br>
        <?php
        }
        if ($realmddb == "")
        {?>
             Realm Database name: <input type="text" name="realmddb" value="realmd"><br>
        <?php
        }?>
        <input type="hidden" name="submitted" value="1"><br>
        <input type="submit" value="Save" />
        </form>
        </center>
        </body>
        </html>
        <?php
        if ($_REQUEST['submitted'] == 1)
            WriteVars();
    }
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
        </body>
        </html>
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
                        ?>
                        <form name="sure" action="<?php echo $PHP_SELF; ?>" method="POST">
                        Are you sure?<br>
                        <input type="submit" name="sure" value="Yes"><br>
                        <input type="submit" name="sure" value="No"><br>
                        <?php
                        if ($_POST['sure'] == "No")
                            return;
                        else
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
    echo "<center>This will save your settings to a file for later usage.</center>";
    ?>
    <html>
    <body><br>
    <center>
    <form name="input" action="<?php echo $PHP_SELF; ?>" method="post">
    <?php
    if ($set_host == "")
    {?>
        Host address: <input type="text" name="host" value="localhost"><br>
    <?php
    }
    if ($port == "")
    {?>
        Mysql Port: <input type="text" name="port" value="3306"><br>
    <?php
    }
    if ($name == "")
    {?>
        Mysql Username: <input type="text" name="name" value="root"><br>
    <?php
    }
    if ($pass == "")
    {?>
        Mysql Password: <input type="text" name="pass" value="mangos"><br>
    <?php
    }
    if ($worlddb == "")
    {?>
        World Database name: <input type="text" name="worlddb" value="mangos"><br>
    <?php
    }
    if ($charactersdb == "")
    {?>
        Characters Database name: <input type="text" name="charactersdb" value="characters"><br>
    <?php
    }
    if ($scriptdev2db == "")
    {?>
        ScriptDev2 Database name: <input type="text" name="scriptdev2db" value="scriptdev2"><br>
    <?php
    }
    if ($realmddb == "")
    {?>
         Realm Database name: <input type="text" name="realmddb" value="realmd"><br>
    <?php
    }?>
    <input type="hidden" name="submitted2" value="1"><br>
    <input type="submit" value="Save" />
    </form>
    </center>
    </body>
    </html>
    <?php
    if ($_REQUEST['submitted2'] == 1)
        WriteVars();
}
ob_end_clean;
?>