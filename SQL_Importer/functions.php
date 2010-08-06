<?php
function ImportDB()
{
    $reading = @fopen("config.php", 'r');
    if ($reading)
        require "config.php";
    else
        die("Config file not present.");
    $link = mysql_connect($host, $name, $pass);
    $dir = ".";
    $file_count = num_files($dir);
    $files = preg_find('/\.sql$/D', $dir, PREG_FIND_RECURSIVE);
    $teller = 0;
    foreach($files as $file)
    {
        $teller = $teller + 1;
        $percentage = round($teller / $file_count * 100);
        if ($percentage == 100)
            $color = "#43CF24";
        else
            $color = "#EF0E0E";
        echo "<hr><div align=center><b><font color=" . $color . ">";
        echo $percentage . "%";
        echo "</b></font></div>";
        if (preg_match("*_mangos_*", $file))
            $targetdb = $worlddb;
        else if (preg_match("*_characters_*", $file))
            $targetdb = $charactersdb;
        else if (preg_match("*_realmd_*", $file))
            $targetdb = $realmddb;
        else
            die("<hr><br>Unknown file " . $file);
        echo "<hr><br>File " . $file . ' containing query(s):<br><br> was imported at ' . $targetdb . "<br><br>";
        $file_content = file($file);
        foreach($file_content as $sql_line)
        {
            if(trim($sql_line) != "" && strpos($sql_line, "--") === false)
            {
                echo $sql_line . '<br>';
                echo "<br>";
                $connect = mysql_select_db($targetdb);
                $output = mysql_query($sql_line);
                if ($output)
                {
                    $result = mysql_result($output, 0);
                    if ($result)
                        echo "Result = " . $result . "<br><br>";
                }
                else
                    echo "Failed to import because of: " . mysql_error($link) . ".\n<br><br><br>";
            }
            else
                die("SQL file is empty or in a wrong format");
        }
    }
}
Function preg_find($pattern, $start_dir='.', $args=NULL)
{
    static $depth = -1;
    ++$depth;
    $files_matched = array();
    $fh = opendir($start_dir);
    while (($file = readdir($fh)) !== false)
    {
        if (strcmp($file, '.')==0 || strcmp($file, '..')==0)
            continue;
        $filepath = $start_dir . '/' . $file;
        if (preg_match($pattern,($args & PREG_FIND_FULLPATH) ? $filepath : $file))
        {
            $doadd =    is_file($filepath)
                   || (is_dir($filepath) && ($args & PREG_FIND_DIRMATCH))
                   || (is_dir($filepath) && ($args & PREG_FIND_DIRONLY));
            if ($args & PREG_FIND_DIRONLY && $doadd && !is_dir($filepath))
                $doadd = false;
            if ($args & PREG_FIND_NEGATE)
                $doadd = !$doadd;
            if ($doadd)
            {
                if ($args & PREG_FIND_RETURNASSOC)
                {
                    $fileres = array();
                    if (function_exists('stat'))
                    {
                        $fileres['stat'] = stat($filepath);
                        $fileres['du'] = $fileres['stat']['blocks'] * 512;
                    }
                    if (function_exists('fileowner')) $fileres['uid'] = fileowner($filepath);
                    if (function_exists('filegroup')) $fileres['gid'] = filegroup($filepath);
                    if (function_exists('filetype')) $fileres['filetype'] = filetype($filepath);
                    if (function_exists('mime_content_type')) $fileres['mimetype'] = mime_content_type($filepath);
                    if (function_exists('dirname')) $fileres['dirname'] = dirname($filepath);
                    if (function_exists('basename')) $fileres['basename'] = basename($filepath);
                    if (($i=strrpos($fileres['basename'], '.'))!==false) $fileres['ext'] = substr($fileres['basename'], $i+1); else $fileres['ext'] = '';
                    if (isset($fileres['uid']) && function_exists('posix_getpwuid')) $fileres['owner'] = posix_getpwuid ($fileres['uid']);
                    $files_matched[$filepath] = $fileres;
                }
                else
                    array_push($files_matched, $filepath);
            }
        }
        if( is_dir($filepath) && ($args & PREG_FIND_RECURSIVE) )
        {
            if (!is_link($filepath) || ($args & PREG_FIND_FOLLOWSYMLINKS))
                $files_matched = array_merge($files_matched, preg_find($pattern, $filepath, $args));
        }
    }
    closedir($fh); 
    if (($depth==0) && ($args & (PREG_FIND_SORTKEYS|PREG_FIND_SORTBASENAME|PREG_FIND_SORTMODIFIED|PREG_FIND_SORTFILESIZE|PREG_FIND_SORTDISKUSAGE)) )
    {
        $order = ($args & PREG_FIND_SORTDESC) ? 1 : -1;
        $sortby = '';
        if ($args & PREG_FIND_RETURNASSOC)
        {
            if ($args & PREG_FIND_SORTMODIFIED)  $sortby = "['stat']['mtime']";
            if ($args & PREG_FIND_SORTBASENAME)  $sortby = "['basename']";
            if ($args & PREG_FIND_SORTFILESIZE)  $sortby = "['stat']['size']";
            if ($args & PREG_FIND_SORTDISKUSAGE) $sortby = "['du']";
            if ($args & PREG_FIND_SORTEXTENSION) $sortby = "['ext']";
        }
        $filesort = create_function('$a,$b', "\$a1=\$a$sortby;\$b1=\$b$sortby; if (\$a1==\$b1) return 0; else return (\$a1<\$b1) ? $order : 0- $order;");
        uasort($files_matched, $filesort);
    }
    --$depth;
    return $files_matched;
}
function num_files($directory='.')
{
    return count(glob($directory."/*.sql"));
}
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
function ShowForm()
{
    $settingsfile = "http://" . $_SERVER['HTTP_HOST'] . "/test/config.php";
    $reading = @fopen($settingsfile, 'r');
    if ($reading)
        require "config.php";
    echo "<center>This will save your settings to a file for later usage.</center>";
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
?>