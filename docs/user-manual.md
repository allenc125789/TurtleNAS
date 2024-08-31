__Errors__

> (/var/www/turtlenas/public/browser.php)

`(406) Not Acceptable!` This error relates to the filename entered for creating a file/folder with a problematic name. Filters have processed that the file name has "illegal" characters. Filters can be added/removed in the function "filterString()".

`(504) Gateway Timout!` Usually happens due too a function taking too long, such as when uploading many files, or refreshing many files. Check your settings, (using the later __Upload/Download Limits__ section for reference), and double-check all settings meet the expected server-load.

`(404) Not Found!` Usually happens if a file can not be found. Check what's trying to be accessed, and check if the file exists, or a link is found. If this happens when downloading a file, refresh the Database(DB) through the browser.

__Upload/Download Limits__

*- Hardcaps*

> (/etc/nginx.conf)

+ Contains a hardcap filesize limit for what can be uploaded to the server. "client_max_body_size 1000G;"

+ Contains a bandwidth limit nginx. "limit_rate 5m;"

> (/etc/php/8.2/fpm/php.ini) 

+ Contains a hardcap filesize limit for what can be uploaded to the server. "max_file_uploads;", "upload_max_filesize;", "post_max_size;", "max_file_uploads;"

+ After this number of seconds, stored php sessions will be seen as 'garbage' and cleaned up by the garbage collection process. Will stop a download mid upload if set too short. "session.gc_maxlifetime = 43200;"

> (/etc/sites-available/turtlenas-config)

+ Contains a read timeout for cgi. Determines time spent running a PHP script before timing out (causes a 504). "fastcgi_read_timeout 43200;"

__Known Bugs__

These are bugs already known. Many I have plans to fix already, although some are things I may not to personally resolve, and will be noted. Help especially on these bugs are appreciated!

> (/var/www/turtlenas/private/PHP/handle-downloads.php)

+ Downloading "hidden/dotfiles" through a browser will append the filename if starting with a (.) dot. (Example: ".bashrc" --href--> "bashrc"). Uploading and backing up these files is fine otherwise, however if you download a hidden file, it is recommended doing so by using the "Zip Download". Problem could be due to nginx, or modern browser design appending it automatically. Unsure how to fix at the moment.

+ Logging onto a web-user with no files already in their root directory will cause a reaccuring prompt to refresh the database, in an attempt to find files. This can be stopped by pressing cancel and uploading new files. I have ideas to fix, however this specific bug is a low priority for the moment.

+ Creating a new user or logging onto a profile with no files in their profile, will cause an infinite loading loop when entering the File-Browser in an attempt to find files. It can be stopped by pressing "Cancel" during the refresh prompt and uploading a file.

[Return to homepage.](https://github.com/allenc125789/TurtleNAS/blob/main/README.md#post-install--usage)