#!/usr/bin/env sh
grep -v -e 'net.ipv4.tcp_tw_reuse' -e 'net.ipv4.tcp_tw_recycle' -e '# TIME_WAIT turning' /etc/sysctl.conf  > /tmp/sysctrl.conf.tmp
echo ' '                            >> /tmp/sysctrl.conf.tmp
echo '# TIME_WAIT turning'          >> /tmp/sysctrl.conf.tmp
echo 'net.ipv4.tcp_tw_recycle = 1'  >> /tmp/sysctrl.conf.tmp
echo 'net.ipv4.tcp_tw_reuse   = 1'  >> /tmp/sysctrl.conf.tmp
mv /tmp/sysctrl.conf.tmp /etc/sysctl.conf
/sbin/sysctl -p
