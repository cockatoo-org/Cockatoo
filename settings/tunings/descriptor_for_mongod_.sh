#!/usr/bin/env sh
grep -v -e 'mongodb' -e '# MONGODB nofile turning' /etc/security/limits.conf  > /tmp/limits.conf.tmp
echo ' '                            >> /tmp/limits.conf.tmp
echo '# MONGODB nofile turning'     >> /tmp/limits.conf.tmp
echo 'mongodb soft nofile 65535'    >> /tmp/limits.conf.tmp
echo 'mongodb hard nofile 65535'    >> /tmp/limits.conf.tmp
mv /tmp/limits.conf.tmp  /etc/security/limits.conf
