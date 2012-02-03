#!/usr/bin/env bash
export PATH=/usr/local/bin:/usr/sbin:$PATH
export PATH=/usr/local/php/bin:$PATH

export COCKATOO_ROOT=/usr/local/cockatoo/

PIDFILE=${COCKATOO_ROOT}daemon/var/action_controller.pid
EXECUTE=${COCKATOO_ROOT}action/action_controller.php
ARGS=" -f ${COCKATOO_ROOT}daemon/etc/action.conf"
WAIT=60

. `dirname $0`/proc_ctrl
