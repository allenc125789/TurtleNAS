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
    #: Configuration Directory.
echo -e "\n\nCreating Directories..."
mkdir -v -p "/home/turtlenas/Local"
mkdir -v -p "/home/turtlenas/Remote"
mkdir -v -p $vLOGS
mkdir -v -p "/home/turtlenas/.local/share/turtlenas"
sCONFIGDIR="/home/turtlenas/.local/share/turtlenas"
    #: Settings Dir.
mkdir -v -p $sCONFIGDIR"/Settings"
    #: SSL Dir.
mkdir -v -p "/etc/nginx/ssl" && chmod 700 "/etc/nginx/ssl"


#: Creating System Admin User.
sudo useradd sysadmin

#: Creating Admin User.
if sudo useradd -m admin; then
    echo -e "This will be your Admin account. You can login with this to the web-browser, make new users, and add new connections. Make your password secure and remember it for later."
    passwd sysadmin
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
chmod -R o=rx "$vPWD/turtlenas"
    #: Sudo.
sudo adduser www-data sudo
echo "www-data ALL=(ALL) !ALL" >> /etc/sudoers
echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/python3 ../python3/pam-auth.py*" >> /etc/sudoers
sudo adduser sysadmin sudo
echo "sysadmin ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
    #: Sets sudo "timestamp_timeout=" to 0 in /etc/sudoers, so verification is requested everytime needed.
sed -i "s/Defaults\tenv_reset/Defaults\tenv_reset,timestamp_timeout=0/" /etc/sudoers
    #: Check if root SSH is enabled.
sSSHCONFIG="/etc/ssh/sshd_config"
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
