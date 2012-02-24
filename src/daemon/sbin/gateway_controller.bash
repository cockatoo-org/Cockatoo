#!/usr/bin/env bash
export PATH=/usr/local/bin:/usr/sbin:$PATH
export PATH=/usr/local/php/bin:$PATH

export COCKATOO_CONF=/usr/local/cockatoo/config.php

PIDFILE=/usr/local/cockatoo/daemon/var/gateway_controller.pid
EXECUTE=/usr/local/cockatoo/gateway/gateway_controller.php
ARGS=""
WAIT=60

. `dirname $0`/proc_ctrl
