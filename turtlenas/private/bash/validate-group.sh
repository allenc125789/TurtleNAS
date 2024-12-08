#!/bin/bash

# $=Username, $2=Group requested

# Used to check admin group privlige.
string=$(getent group $2)

# Parsing.
rules=$(echo "$string" | sed "s/$2:.*://g")
spaces=$(echo "$rules" | sed "s/,/ /g")
groupContent=( $spaces )

# Check if user is in group.
if [[ " ${groupContent[*]} " =~ [[:space:]]${1}[[:space:]] ]]
then
    echo "1"
else
    echo "0"
fi
