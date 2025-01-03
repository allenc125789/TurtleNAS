#!/bin/bash
if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root."
    exit 1
fi


sWARNING=" ((\033[1;33mWARNING\033[0m)) "
sERROR=" ((\033[0;31mERROR\033[0m)) "
vDOMAIN=$(grep "domain" /etc/resolv.conf | awk '{print $NF}')
vPWD=$(dirname $0)
vFILESYSTEM=$(df -P . | sed -n '$s/[[:blank:]].*//p')
vUUID=$(/usr/sbin/blkid -s UUID -o value "$vFILESYSTEM")


#: Dependancies.
aDEPENDS=("gpg" "zip" "sudo" "rsync" "sshfs" "git" "nginx" "libnginx-mod-http-js" "libjs-jquery"\
    "python3-pam" "ufw" "default-mysql-server" "php8.2" "php8.2-fpm" "php8.2-mysql" "rsyslog")
    #: Dependancy Check
apt-get install ${aDEPENDS[*]}
if [[ $? > 0 ]]; then
    echo $sERROR"Failed to get dependancies through apt. Exiting."
    exit
else
    :
fi


    #: Create SSL Dir.
mkdir -v -p '/etc/nginx/ssl' && chmod 700 '/etc/nginx/ssl'
    #: Create media Dir. 
mkdir -v '/media/REMOTE'

mkdir -v -p "/media/LOCAL/$vUUID/admin" && chown www-data "/media/LOCAL/$vUUID/admin"

mkdir -v "/var/www/.gnupg" && chown www-data "/var/www/.gnupg"


    #: Create tmp Dir.


#: Creating Users.
    #: System Admin.
sudo useradd -M sysadmin
    #: Admin.
if sudo useradd -M admin; then
    echo -e "This will be your Admin account. You can login with this to the web-browser, make new users, and add new connections. Make your password secure and remember it for later."
    passwd admin
else
    :
fi


#: Security Configuration.
echo -e "\n\nUpdating Security..."
    #: Bash History
export HISTCONTROL=ignorespace
    #: Firewall.
echo -e "Enabling Firewall..."
sudo ufw allow 'Nginx HTTPS'
sudo ufw allow 'OpenSSH'
yes | sudo ufw enable
    #: SSL Creation.
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/selfsigned.key -out /etc/nginx/ssl/selfsigned.crt
    #: File Permissions and Grouping.
sudo groupadd admin
sudo adduser admin admin
sudo adduser admin www-data
sudo adduser sysadmin www-data
chown -R sysadmin:www-data "$vPWD/turtlenas"
chmod -R 755 "$vPWD/turtlenas"
    #: Sudo.
sudo adduser www-data sudo
echo "www-data ALL=(ALL) !ALL" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/python3 ../private/python3/pam-auth.py*" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/bash ../private/bash/validate-group.sh*" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /bin/apt-get update" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /bin/apt-get upgrade" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /bin/apt-get -y upgrade" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /bin/ip link set * up" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /bin/ip link set * down" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /sbin/dhclient*" >> /etc/sudoers


sudo adduser sysadmin sudo
echo "sysadmin ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
sudo usermod -d /var/www/turtlenas/private sysadmin
sudo adduser admin sudo
    #: Sets sudo "timestamp_timeout=" to 0 in /etc/sudoers, so verification is requested everytime needed.
sed -i "s/Defaults\tenv_reset/Defaults\tenv_reset,timestamp_timeout=0/" /etc/sudoers
    #: SSH Access
sSSHCONFIG="/etc/ssh/sshd_config"
echo -e "DenyUsers\tsysadmin" >> $sSSHCONFIG
        #: Check if SSH root password access is enabled.
if grep "PermitRootLogin yes" $sSSHCONFIG | grep -v "#" || grep "PermitRootLogin prohibit-password" $sSSHCONFIG | grep -v "#"; then
    echo -e $sWARNING": This server's root account might be accessible from SSH. Please consider changing it's permissions in "$sSSHCONFIG. & sleep 2
else
    :
fi

#: SQL.
    #: Create DB tables.
mariadb -e "CREATE DATABASE turtlenas;"
mariadb -e "USE turtlenas; CREATE TABLE drives (user VARCHAR(36), type VARCHAR(6), disk VARCHAR(255), uuid CHAR(36) );"
mariadb -e "USE turtlenas; CREATE TABLE files_admin (fullpath NVARCHAR(255) PRIMARY KEY, parent NVARCHAR(255), name NVARCHAR(255),date VARCHAR(224), size VARCHAR(255), mtime CHAR(244) );"
mariadb -e "USE turtlenas; INSERT INTO drives (user, type, disk, uuid) VALUES('admin', 'LOCAL', '$vFILESYSTEM', '$vUUID');"

    #: Create DB Users.
mariadb -e "CREATE USER 'www-data'@'localhost' IDENTIFIED BY ''"
mariadb -e "GRANT ALL PRIVILEGES ON turtlenas.drives TO 'www-data'@'localhost' WITH GRANT OPTION"
mariadb -e "GRANT ALL PRIVILEGES ON turtlenas.files_admin TO 'www-data'@'localhost' WITH GRANT OPTION"


#: Web Server Configuration.
echo -e "Configuring web server..."
    #: Configuration files.
if [ -z "${vDOMAIN}" ]; then
    sed -i "s/@/_/g" $vPWD"/extra/turtlenas-config"
else
    sed -i "s/@/$vDOMAIN/g" $vPWD"/extra/turtlenas-config"
fi
    #: Web page files.
cp -r -p -f "$vPWD/extra/turtlenas-config" "/etc/nginx/sites-available"
cp -r -p -f "$vPWD/extra/nginx.conf" "/etc/nginx"
cp -r -p -f "$vPWD/extra/php.ini" "/etc/php/8.2/fpm"
cp -r -p -f "$vPWD/turtlenas" "/var/www"
rm -f /etc/nginx/sites-enabled/default
ln -v -s /etc/nginx/sites-available/turtlenas-config /etc/nginx/sites-enabled/
