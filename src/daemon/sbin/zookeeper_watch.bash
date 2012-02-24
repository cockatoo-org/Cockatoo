#!/usr/bin/env bash
export PATH=/usr/local/bin:/usr/sbin:$PATH
export PATH=/usr/local/php/bin:$PATH

export COCKATOO_CONF=/usr/local/cockatoo/config.php

PIDFILE=/usr/local/cockatoo/daemon/var/zookeeper_watch.pid
EXECUTE=/usr/local/cockatoo/zookeeper/zookeeper_watch.php
ARGS=" "

. `dirname $0`/proc_ctrl
