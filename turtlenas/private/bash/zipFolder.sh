#!/bin/bash

# $1=ENCRYPT/PLAINTEXT, $2=CWD, $3=PASSWORD

base_name=$(basename $2)
hash=$(echo -n $base_name | sha224sum | sed 's/ .$//')
if [ $1 == "ENCRYPT" ]; then
    zip -q -e -r "/tmp/${hash}.zip" $2 -P $3
    echo "${hash}.zip"
elif [ $1 == "PLAINTEXT" ]; then
    rm "/tmp/${hash}.zip"
    zip -q -r -Z "deflate" -o "/tmp/${hash}.zip" $2
    echo "${hash}.zip"
fi
