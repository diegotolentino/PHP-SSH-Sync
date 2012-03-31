PHP SSH Sync
============

Script for remote php/ssh backup and copy.

Configuration
-------------

All configurate option is defined in *.ini file executed, many profiles cam be made.

See sample-config.ini for more information


Startup
-------------

Manual Execution:

	$ php -f /PATH_TO/phpssh.php /PATH_TO/sample-config.ini >> file_to_log.log
	
	OR
	
	$ sh copiador.bat /path-to-config/sample-config.ini
	
Or shendule in crontab:

	sh /path-to/copiador.bat /path-to-config/sample-config.ini