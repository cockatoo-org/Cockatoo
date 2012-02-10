#!/usr/bin/env bash
TOOL_DIR=`dirname $0`
export PATH=/usr/local/php/bin:$PATH
if [ "$COCKATOO_CONF" = "" ]; then
    export COCKATOO_CONF=${TOOL_DIR}/../../config.php
fi
php ${TOOL_DIR}/beak_mv.php $*
