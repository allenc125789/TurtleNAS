#!/bin/bash

# $1=ENCRYPT/PLAINTEXT, $2=CWD, $3=PASSWORD

hash=$(echo -n find "$2" -mindepth 1 -type f -print0 | sort -z | sha1sum | sed 's/ .$//' | xargs)
if [ $1 == "ENCRYPT" ]; then
    (cd "$2" && zip -q -o -e -r "/tmp/${hash}.zip" ./ -P "$3")
    echo "${hash}.zip"
elif [ $1 == "PLAINTEXT" ]; then
    rm "/tmp/${hash}.zip"
    (cd "$2" && zip -q -r "/tmp/${hash}.zip" ./)
    echo "${hash}.zip"
fi

exit
