@ECHO off

:MANGOSD
echo.
echo Darkrulerz' Restarter!
pv.exe -d10000
pv.exe > result.txt
FIND "mangosd.exe" result.txt
IF ERRORLEVEL 1 START mangosd.exe
del /Q /F result.txt
cls
GOTO REALMD

:REALMD
echo.
echo Darkrulerz' Restarter!
pv.exe -d10000
pv.exe > result.txt
FIND "realmd.exe" result.txt
IF ERRORLEVEL 1 START realmd.exe
del /Q /F result.txt
cls
GOTO MANGOSD