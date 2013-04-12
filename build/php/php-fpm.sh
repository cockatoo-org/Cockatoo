#!/usr/bin/env bash
PIDFILE=/usr/local/php/var/run/php-fpm.pid


function check () {
    MSG=$1
    if [ -f $PIDFILE ]; then
	ps vp `cat $PIDFILE` | grep php-fpm > /dev/null 2>&1
	if [ $? == 0 ]; then
	    cat $PIDFILE | xargs echo "process running...  " $MSG " "
	    return 0;
	fi
	cat $PIDFILE | xargs echo "process not running...  " $MSG " "
    fi
    echo "process not running...  "
    return 1;
}

function start () {
    /usr/local/php/sbin/php-fpm
    return $?
}

function signal () {
    kill -$1  `cat $PIDFILE`
}
function stop () {
    signal SIGQUIT
    WAIT=10
    for i in `eval echo "{1..$WAIT}"`
    do
      check "($i/$WAIT)";
      if [ $? != 0 ] ;then
	  return 0;
      fi
      sleep 1;
    done
    echo "process cannot terminate ! so send kill -9 !! "
    signal SIGKILL
    return 1;
}
function graceful () {
    signal SIGUSR2
}
case $1 in
    start)
	start $*;
	;;
    stop)
	stop $*;
	;;
    restart)
	stop $*;
	start $*;
	;;
    graceful)
	graceful $*;
	;;
    *)
	echo "USAGE : (start|stop|restart)"
	exit 1;
	;;
esac
exit $?;

