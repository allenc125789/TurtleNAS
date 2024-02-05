#!/bin/bash
if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root"
    exit 1
fi




####: Pre-Installation.
clear
echo -e "netacbackup-setup.sh\n"
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
aDEPENDS=("gpg" "rsync" "sshfs" "nginx" "ufw")
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
apt-get install ${aDEPENDS[*]} || echo -e "\n\nDependancy installation failed." && exit 1


#: Creating System User.
echo -e "\n\nCreating a system user: netacbackup"
if useradd -m netacbackup; then
    passwd netacbackup
else
    echo ""
fi
echo -e "/n/nThis will be your System account. Be sure to create your own seperate Admin account later." && sleep 4


#: Creating Directories.
    #: Configuration Directory.
echo -e "\n\nCreating Directories."
mkdir -p /home/netacbackup/.local/share/netacbackup
sCONFIGDIR="/home/netacbackup/.local/share/netacbackup"
    #: User Files Directory.
mkdir -p $sCONFIGDIR"/Users"
    #: Encrypted-files Directory
mkdir -p $sCONFIGDIR"/Encrypted-Files"
    #: Settings Directory
mkdir -p $sCONFIGDIR"/Settings"


#: Grouping and Security.
echo -e "\n\nUpdating Security."
    #: Sudo.
adduser netacbackup sudo
echo "netacbackup ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
    #: Firewall.
sudo ufw allow 'HTTP'
sudo ufw allow 'HTTPS'
sudo ufw allow 'OpenSSH'
yes | sudo ufw enable
