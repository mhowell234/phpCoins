#!/bin/sh

cd /Users/mhowell/Dropbox/numismatics/bin

python getMetalValues.py

cd ..

curl -o /dev/null http://localhost:8888/batch/updateMetalValues.php
