#!/usr/bin/env bash
export PATH=/usr/local/bin:/usr/sbin:$PATH
export PATH=/usr/local/php/bin:$PATH

export COCKATOO_ROOT=/usr/local/cockatoo/
export COCKATOO_CONF=${COCKATOO_ROOT}config.php

PIDFILE=${COCKATOO_ROOT}daemon/var/gateway_controller.pid
EXECUTE=${COCKATOO_ROOT}gateway/gateway_controller.php
ARGS=""
WAIT=60

. `dirname $0`/proc_ctrl
