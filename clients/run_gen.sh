#!/bin/bash

file=$1
mv $file /tmp
cd /tmp
./gen_file.sh $file
exit 0
