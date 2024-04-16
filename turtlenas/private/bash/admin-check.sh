#!/bin/bash

# Used to check admin group privlige.
string=$(getent group admin)

# Parsing.
rules=$(echo "$string" | sed "s/admin:.*://g")
spaces=$(echo "$rules" | sed "s/,/ /g")
admins=( $spaces )

# Compare to Admin Group.
if [[ ${admins[@]} =~ $1 ]]
then
    echo "1"
else
    echo "0"
fi
