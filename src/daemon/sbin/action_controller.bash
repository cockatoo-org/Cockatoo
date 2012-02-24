#!/usr/bin/env bash
export PATH=/usr/local/bin:/usr/sbin:$PATH
export PATH=/usr/local/php/bin:$PATH

export COCKATOO_CONF=/usr/local/cockatoo/config.php

PIDFILE=/usr/local/cockatoo/daemon/var/action_controller.pid
EXECUTE=/usr/local/cockatoo/action/action_controller.php
ARGS=" -f /usr/local/cockatoo/daemon/etc/action.conf"
WAIT=60

. `dirname $0`/proc_ctrl
