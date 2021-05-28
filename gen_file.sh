#!/bin/bash

filename="$1"

str=$(<$filename)

username=$(echo $str | cut -f1 -d-)
password=$(echo $str | cut -f2 -d-)

cd ~/EasyRSA
echo -en "\n" | ./easyrsa gen-req $username nopass

cp pki/private/$username.key ~/client-configs/keys/

`scp pki/reqs/$username.req ajackx@xx.xx.xx.xx:/tmp`

ssh ajackx@xx.xx.xx.xx username="$username" bash -s <<- 'ENDSSH'
cd ~/EasyRSA
./easyrsa import-req /tmp/$username.req $username
yes "yes" | ./easyrsa sign-req client $username
`scp pki/issued/$username.crt ajackx@xx.xx.xx.xx:/tmp`
ENDSSH

cp /tmp/$username.crt ~/client-configs/keys

echo "Assembling user profile..."

KEY_DIR=~/client-configs/keys
OUTPUT_DIR=~/client-configs/files
BASE_CONFIG=~/client-configs/base.conf

cat ${BASE_CONFIG} \
    <(echo -e '<ca>') \
    ${KEY_DIR}/ca.crt \
    <(echo -e '</ca>\n<cert>') \
    ${KEY_DIR}/$username.crt \
    <(echo -e '</cert>\n<key>') \
    ${KEY_DIR}/$username.key \
    <(echo -e '</key>\n<tls-auth>') \
    ${KEY_DIR}/ta.key \
    <(echo -e '</tls-auth>') \
    > ${OUTPUT_DIR}/$username.ovpn

echo "script complete"

cp ~/client-configs/files/$username.ovpn /var/www/affinityvpn.com/html/files

exit 0
