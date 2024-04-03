#!/bin/bash

# edit /etc/pam.d/su to allow no password when running command as user

#auth  [success=ignore default=1] pam_succeed_if.so user = sys-admin
#auth  sufficient                 pam_succeed_if.so use_uid user = www-data

# then edit sudoers to allow this script when run by www-data

# https://unix.stackexchange.com/questions/113754/allow-user1-to-su-user2-without-password/115090#115090
# https://askubuntu.com/a/294748

su - sysadmin -c ' printf "%s" "$password" | sudo -n -S -u "$username" echo "1"'