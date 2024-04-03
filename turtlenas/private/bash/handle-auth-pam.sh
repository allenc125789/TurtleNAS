#!/bin/bash

# edit /etc/pam.d/su to allow no password for www-data when running an su command for user

#auth  [success=ignore default=1] pam_succeed_if.so user = sys-admin
#auth  sufficient                 pam_succeed_if.so use_uid user = www-data

# then edit sudoers to allow this script when run by www-data

# EDIT, wont need sudo now due to su!! this is the fix to no wildcards in sudo!

# https://unix.stackexchange.com/questions/113754/allow-user1-to-su-user2-without-password/115090#115090
# https://askubuntu.com/a/294748

su sysadmin -c ' python3 /var/www/turtlenas/private/python3/pam-auth.py $username $password'
