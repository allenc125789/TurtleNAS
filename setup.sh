#!/bin/bash
if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root."
    exit 1
fi


sWARNING=" ((\033[1;33mWARNING\033[0m)) "
sERROR=" ((\033[0;31mERROR\033[0m)) "
vDOMAIN=$(grep "domain" /etc/resolv.conf | awk '{print $NF}')
vPWD=$(dirname $0)
vLOGS="/home/netacbackup/Logs"


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
    echo ""
fi


#: Creating Directories.
    #: Configuration Directory.
echo -e "\n\nCreating Directories..."
mkdir -v -p "/home/netacbackup/Local"
mkdir -v -p "/home/netacbackup/Remote"
mkdir -v -p $vLOGS
mkdir -v -p "/home/netacbackup/.local/share/netacbackup"
sCONFIGDIR="/home/netacbackup/.local/share/netacbackup"
    #: User Files Dir.
mkdir -v -p $sCONFIGDIR"/Users"
    #: Encrypted-files Dir.
mkdir -v -p $sCONFIGDIR"/Encrypted-Files"
    #: Settings Dir.
mkdir -v -p $sCONFIGDIR"/Settings"
    #: index.html (root) Dir.
mkdir -v -p "/var/www/netacbackup"
    #: SSL Dir.
mkdir -v -p "/etc/nginx/ssl" && chmod 700 "/etc/nginx/ssl"


#: Creating System User.
echo -e "\n\nCreating a System user: sysadmin"
if useradd -m sysadmin; then
    echo -e "This will be your System account. Be sure to create your own seperate Admin and User accounts later using a Web-Browser or the CLI..."
    passwd sysadmin
else
    :
fi


#: Grouping and Security.
echo -e "\n\nUpdating Security..."
    #: File Permissions
chmod o=rx "$vPWD/python3/*"
    #: Firewall.
echo -e "Enabling Firewall..."
sudo ufw allow 'Nginx HTTPS'
sudo ufw allow 'OpenSSH'
yes | sudo ufw enable
    #: SSL Creation.
echo -e "\n\nCreating self-signed SSL..."
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/selfsigned.key -out /etc/nginx/ssl/selfsigned.crt
    #: Setting System Admin Privliges.
adduser sysadmin sudo
echo "sysadmin ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
    #: Set sudo "timestamp_timeout=" to 0 in /etc/sudoers, so verification is requested everytime needed.
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
sed -i "s/@/$vDOMAIN/g" $vPWD"/netacbackup-profile"
mv "$vPWD/netacbackup-profile" "/etc/nginx/sites-available"
rm -f /etc/nginx/sites-enabled/default
ln -v -s /etc/nginx/sites-available/netacbackup-profile /etc/nginx/sites-enabled/
    #: Web page files.
aWEBFILES=("/index.html" "/html" "/js" "/css" "/php" "/bash" "/python3")
for sFILE in "${aWEBFILES[@]}"; do
    mv "$vPWD/$sFILE" "/var/www/netacbackup"
done
