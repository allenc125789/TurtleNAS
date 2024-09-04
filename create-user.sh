#!/bin/bash

# $1=New Username

sWARNING=" ((\033[1;33mWARNING\033[0m)) "
sERROR=" ((\033[0;31mERROR\033[0m)) "
vDOMAIN=$(grep "domain" /etc/resolv.conf | awk '{print $NF}')
vPWD=$(dirname $0)
vFILESYSTEM=$(df -P . | sed -n '$s/[[:blank:]].*//p')
vUUID=$(/usr/sbin/blkid -s UUID -o value "$vFILESYSTEM")

if [ -z "${1}" ]; then
    echo "Argument needed. Example: (#/: bash ./create-user.sh JohnDoe)."
    exit
else
    :
fi

#: Creating Users.
if sudo useradd -M $1; then
    passwd $1
else
    :
fi

mkdir -v -p "/media/LOCAL/$vUUID/$1" && chown -R sysadmin:www-data "/media/LOCAL/$vUUID/$1"


#: SQL.
    #: Create DB tables.
mariadb -e "USE turtlenas; CREATE TABLE files_$1 (fullpath NVARCHAR(255) PRIMARY KEY, parent NVARCHAR(255), name NVARCHAR(255),date VARCHAR(224), size VARCHAR(255), mtime CHAR(244) );"
mariadb -e "USE turtlenas; INSERT INTO drives (user, type, disk, uuid) VALUES('$1', 'LOCAL', '$vFILESYSTEM', '$vUUID');"
    #: Create DB Users.
mariadb -e "GRANT ALL PRIVILEGES ON turtlenas.files_$1 TO 'www-data'@'localhost' WITH GRANT OPTION"
