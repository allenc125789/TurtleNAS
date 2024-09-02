#!/bin/bash

# $=Username, $2=Group requested

# Used to check admin group privlige.
string=$(getent group $2)

# Parsing.
rules=$(echo "$string" | sed "s/$2:.*://g")
spaces=$(echo "$rules" | sed "s/,/ /g")
groupContent=( $spaces )

# Compare to Admin Group.
if [[ ${groupContent[@]} =~ $1 ]]
then
    echo "1"
else
    echo "0"
fi
