# $1=ENCRYPT/PLAINTEXT, $2=CWD, $3=PASSWORD

base_name=$(basename $2)
hash=$(echo -n $base_name | sha224sum | sed 's/  .$//')
if [[ $1 == "ENCRYPT" ]]; then
    tar -c -z -f "/tmp/${hash}.tar.gz" $2
    gpg --batch --passphrase $3 --output "/tmp/${hash}.gpg" -c "/tmp/${hash}.tar.gz"
    rm "/tmp/${hash}.tar.gz"
    echo "/tmp/${hash}.gpg"
elif [[ $1 == "PLAINTEXT" ]]; then
    tar -c -z -f "/tmp/${hash}.tar.gz" $2
    echo "/tmp/${hash}.tar.gz"
fi
