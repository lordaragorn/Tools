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
$reading = @fopen("config.php", 'r');
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
?>