#!/bin/bash
if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root"
    exit 1
fi




####: Pre-Installation.
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
aDEPENDS=("gpg" "rsync" "sshfs")
    #: Dependancy Check
echo -e 'You will need the dependancies: '"${aDEPENDS[*]}"
	while IFS= read -r -p $'If they are not installed, they will be now. Continue? (y/n)\n\n' sPLATFORM; do
    case $sPLATFORM in
        y|Y|yes|Yes|YES)
        break
        ;;
        n|N|no|No|NO)
        echo "Exiting..."
        exit
        ;;
esac
done
apt-get install ${aDEPENDS[*]}


#: Creating Users.
echo -e "Creating an admin user: netacbackup"
if useradd -m netacbackup; then
    passwd netacbackup
else
    echo ""
fi


#: Creating Directories.
    #: Configuration Directory.
mkdir -p /home/netacbackup/.local/share/netacbackup
sCONFIGDIR="/home/netacbackup/.local/share/netacbackup"
    #: User Files Directory.
mkdir -p $sCONFIGDIR"/Users"
    #: Encrypted-files Directory
mkdir -p $sCONFIGDIR"/Encrypted-Files"
    #: Settings Directory
mkdir -p $sCONFIGDIR"/Settings"
mkdir -p $sCONFIGDIR"/Settings/web-files"


#: Grouping and Security.
    #: Sudo.
adduser netacbackup sudo
echo "netacbackup ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
