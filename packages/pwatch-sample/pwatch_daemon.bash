#!/usr/bin/env bash
export PATH=/usr/local/bin:/usr/sbin:$PATH
export PATH=/usr/local/php/bin:$PATH

export COCKATOO_CONF=/usr/local/cockatoo/config.php

PIDFILE=/usr/local/cockatoo/daemon/var/pwatch_daemon.pid
EXECUTE=/usr/local/cockatoo/daemon/bin/pwatch_daemon.php
ARGS=" "

. `dirname $0`/proc_ctrl
