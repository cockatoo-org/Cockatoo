#!/usr/bin/env sh
NODEJS_HOME=/usr/local/nodejs
PATH=${NODEJS_HOME}/bin:${PATH}
export PATH
LD_LIBRARY_PATH=${NODEJS_HOME}/lib:$LD_LIBRARY_PATH
export LD_LIBRARY_PATH
NODE_PATH=/usr/local/nodejs/lib/node_modules/
export NODE_PATH

CUR=`dirname $0`
JQUERY=${CUR}/jquery-1.4.4.min.js
node ${CUR}/minimize.js ${JQUERY} $*
