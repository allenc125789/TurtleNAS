#!/bin/bash
if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root."
    exit 1
fi


sWARNING=" ((\033[1;33mWARNING\033[0m)) "
sERROR=" ((\033[0;31mERROR\033[0m)) "
vDOMAIN=$(grep "domain" /etc/resolv.conf | awk '{print $NF}')
vPWD=$(dirname $0)

#: Dependancies.
aDEPENDS=("gpg" "sudo" "rsync" "sshfs" "nginx" "libnginx-mod-http-js" "python3-pam" "ufw" "git" "php8.2" "php8.2-fpm")
    #: Dependancy Check
echo -e 'You will need the dependancies: '"${aDEPENDS[*]}"
while IFS= read -r -p $'If they are not installed, they will be now. Continue? (y/n)\n\n' sCONF; do
    case $sCONF in
        y|Y|yes|Yes|YES)
        break
        ;;
        n|N|no|No|NO)
        echo "Exiting..."
        exit 0
        ;;
    esac
done
apt-get -y install ${aDEPENDS[*]}
if [[ $? > 0 ]]; then
    echo $sERROR"Failed to get dependancies through apt. Exiting."
    exit
else
    :
fi


#: Creating Directories.
    #: SSL Dir.
mkdir -v -p "/etc/nginx/ssl" && chmod 700 "/etc/nginx/ssl"


#: Creating Users.
    #: System Admin.
sudo useradd sysadmin
    #: Admin.
if sudo useradd -m admin; then
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
sudo adduser sysadmin www-data
chown -R sysadmin:www-data "$vPWD/turtlenas"
chmod -R 755 "$vPWD/turtlenas"
    #: Su
echo "auth  [success=ignore default=1] pam_succeed_if.so user = sysadmin" >> /etc/pam.d/su
echo "auth  sufficient                 pam_succeed_if.so use_uid user = www-data" >> /etc/pam.d/su
    #: Sudo.
sudo adduser www-data sudo
echo "www-data ALL=(ALL) !ALL" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/python3 ../python3/pam-auth.py*" >> /etc/sudoers
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

#: Web Server Configuration.
echo -e "Configuring web server..."
    #: Configuration files.
sed -i "s/@/$vDOMAIN/g" $vPWD"/turtlenas-config"
mv "$vPWD/turtlenas-config" "/etc/nginx/sites-available/turtlenas-config"
mv -f "$vPWD/nginx.conf" "/etc/nginx/nginx.conf"
rm -f /etc/nginx/sites-enabled/default
ln -v -s /etc/nginx/sites-available/turtlenas-config /etc/nginx/sites-enabled/
    #: Web page files.
mv "$vPWD/turtlenas" "/var/www"
