#!/bin/bash

# Used to check admin group privlige.

string=$(getent group admin)
rules=$(echo "$string" | sed "s/admin:.*://g")
spaces=$(echo "$rules" | sed "s/,/ /g")
admins=( $spaces )

if [[ ${admins[@]} =~ $1 ]]
then
    true
else
    false
fi
