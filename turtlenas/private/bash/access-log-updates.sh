#!/bin/bash
while true; do
    tail -f /var/log/nginx/access.log | tee vIP
done
    
    
