#!/bin/bash

# $1=ENCRYPT/PLAINTEXT, $2=CWD, $3=PASSWORD

base_name=$(basename $2)
hash=$(echo -n $base_name | sha224sum | sed 's/  .$//')
if [[ $1 == "ENCRYPT" ]]; then
    zip -e -r "/tmp/${hash}.zip" $2 -P $3
elif [[ $1 == "PLAINTEXT" ]]; then
    zip -r "/tmp/${hash}.zip" $2
fi

echo "/tmp/${hash}.zip"
