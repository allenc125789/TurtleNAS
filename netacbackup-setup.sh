#!/bin/bash
if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root."
    exit 1
fi


sWARNING=" ((\033[1;33mWARNING\033[0m)) "
sERROR=" ((\033[0;31mERROR\033[0m)) "


####: Pre-Installation.
clear
echo -e "netacbackup-setup.sh...\n"
#: Platform detection.
while IFS= read -r -p $'Is this a (1)Client or a (2)Server?\n\n' sPLATFORM; do
    case $sPLATFORM in
        1|c|C|client|Client|CLIENT)
        echo -e "Installing in Client mode...\n\n"
        break
        ;;
        2|s|S|server|Server|SERVER)
        echo -e "Installing in Server mode...\n\n"
        break
        ;;
    esac
done




####CLIENT.




####SERVER.
#: Dependancies.
aDEPENDS=("gpg" "sudo" "rsync" "sshfs" "nginx" "certbot" "ufw" "git" "python3" "python3-certbot-nginx")
    #: Dependancy Check
echo -e 'You will need the dependancies: '"${aDEPENDS[*]}"
while IFS= read -r -p $'If they are not installed, they will be now. Continue? (y/n)\n\n' sPLATFORM; do
    case $sPLATFORM in
        y|Y|yes|Yes|YES)
        break
        ;;
        n|N|no|No|NO)
        echo "Exiting..."
        exit 0
        ;;
    esac
done
apt-get install ${aDEPENDS[*]}
if [[ $? > 0 ]]
then
    echo $sERROR"Failed to get dependancies through apt. Exiting."
    exit
else
    echo ""
fi



#: Creating System User.
echo -e "\n\nCreating a System user: netacbackup"
if useradd -m netacbackup; then
    echo -e "/n/nThis will be your System account. Be sure to create your own seperate Admin and User accounts later using a Web-Browser or the CLI..."
    passwd netacbackup
else
    echo ""
fi


#: Creating Directories.
    #: Configuration Directory.
echo -e "\n\nCreating Directories..."
mkdir -v -p "/home/netacbackup/Local"
mkdir -v -p "/home/netacbackup/Remote"
mkdir -v -p "/home/netacbackup/.local/share/netacbackup"
sCONFIGDIR="/home/netacbackup/.local/share/netacbackup"
    #: User Files Directory.
mkdir -v -p $sCONFIGDIR"/Users"
    #: Encrypted-files Directory
mkdir -v -p $sCONFIGDIR"/Encrypted-Files"
    #: Settings Directory
mkdir -v -p $sCONFIGDIR"/Settings"
    #: index.html Directory
mkdir -v -p "/var/www/netacbackup"


#: Grouping and Security.
echo -e "\n\nUpdating Security..."
    #: Sudo.
adduser netacbackup sudo
echo "netacbackup ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
    #: Firewall.
sudo ufw allow 'Nginx HTTP'
sudo ufw allow 'Nginx HTTPS'
sudo ufw allow 'OpenSSH'
yes | sudo ufw enable
    #: Check if root SSH is enabled.
sSSHCONFIG="/etc/ssh/sshd_config"
if grep "PermitRootLogin yes" $sSSHCONFIG | grep -v "#" || grep "PermitRootLogin prohibit-password" $sSSHCONFIG | grep -v "#"; then
    echo -e $sWARNING": This server's root account might be accessible from SSH. Please consider changing it's permissions in "$sSSHCONFIG. & sleep 2
else
    echo ""
fi

#: Import Git Files
#GIT netacbackup-profile

rm /etc/nginx/sites-enabled/default
