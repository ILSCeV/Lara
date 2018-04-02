#!/bin/bash
set -e
URL="$1"
 
mkdir -p /tmp/calendar
cd /tmp/calendar
#fetch filelist:
cadaver $URL << EOC | grep .ics | sed 's/.ics.*/.ics/' | sed 's/\ *//' > ./filelist
ls
exit
EOC
 
#download files:
cadaver $URL << EOA
`while read p; do
echo "get $p"
done < ./filelist`
exit
EOA
