Extract the archive
place pv.exe (Process Viewer) and Restarter.bat (The Script)
in your core folder (where mangosd.exe and realmd.exe are)
run the batch file and wait for the core to boot

Each 10 seconds the batch will check if mangosd.exe and realmd.exe are still running,
if not it will start the one that isn't running up

NOTE: Not out-of-the-box usable on multi-realm environments




ADDITION: If you run Vista,Win7 or WinServer 2008 you might want to get rid of
"Windows Error Reporting Service" so run the registry key to shut it off