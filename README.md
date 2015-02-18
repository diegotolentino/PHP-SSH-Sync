PHP SSH Sync
============

Script for remote php/ssh backup and copy.

Requirements
-------------

SSH2 lib see http://www.php.net/manual/en/ssh2.installation.php


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