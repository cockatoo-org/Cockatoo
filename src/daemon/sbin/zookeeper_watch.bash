#!/usr/bin/env bash
export PATH=/usr/local/bin:/usr/sbin:$PATH
export PATH=/usr/local/php/bin:$PATH

export COCKATOO_ROOT=/usr/local/cockatoo/
export COCKATOO_CONF=${COCKATOO_ROOT}config.php

PIDFILE=${COCKATOO_ROOT}daemon/var/zookeeper_watch.pid
EXECUTE=${COCKATOO_ROOT}zookeeper/zookeeper_watch.php
ARGS=" "

. `dirname $0`/proc_ctrl
