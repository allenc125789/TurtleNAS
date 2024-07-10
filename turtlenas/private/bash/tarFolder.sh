#!/bin/bash

# $1=ENCRYPT/PLAINTEXT, $2=CWD, $3=PASSWORD

base=$(basename "$2")
hash=$(echo -n find "$2" -mindepth 1 -type f -print0 | sort -z | sha1sum | sed 's/ .$//' | xargs)
if [ "$1" == "ENCRYPT" ]; then
    rm "/tmp/${hash}.tar.gz.gpg"
    (tar -c -z -f "/tmp/${hash}.tar.gz" -C "$2" ".")
    (gpg --batch --no-options --passphrase "$3" --output "/tmp/${hash}.tar.gz.gpg" --symmetric "/tmp/${hash}.tar.gz")
    echo "${hash}.tar.gz.gpg"
elif [ "$1" == "PLAINTEXT" ]; then
    rm "/tmp/${hash}.tar.gz"
    (tar -c -z -f "/tmp/${hash}.tar.gz" -C "$2" ".")
    echo "${hash}.tar.gz"
fi

exit
