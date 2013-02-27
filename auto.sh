#!/bin/bash

api=( http://www.mtianya.com/api/channel http://www.mtianya.com/api/item )
for i in ${api[*]}
do
        STU=`curl --head $i | awk 'NR==1' | awk '{print $2}'`
        if [ "$STU" != "200" ]; then
                fpmrestart
                sleep 10s
                echo "[Error]" "$i : $STU" $(date +"%y-%m-%d %H:%M:%S") "fpmrestart" > /root/m.log
        fi
done
