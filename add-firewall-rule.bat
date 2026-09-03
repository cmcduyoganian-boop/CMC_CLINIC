@echo off
echo Adding firewall rule for Laravel development server...
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=TCP localport=8000
echo.
echo Firewall rule added successfully!
echo.
echo You can now access the site from other devices using:
echo http://192.168.1.59:8000
echo.
pause
