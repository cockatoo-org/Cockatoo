#!/usr/bin/env bash

usage (){
    cat<<EOF
Usage    :
     check_mongo.sh [options] [host[:port]]

Optoins  :
     -h [ --help     ]      : This message.
     -u [ --username ]      : MongoDB user
     -p [ --passowrd ]      : MongoDB user's password
     -c [ --count ]         : Number of checks.
     -i [ --interval ]      : Check interval.
     -t [ --timestamp ]     : Add timestamp to last column
     -q [ --quiet ]         : No format header
Output   :
     <resident(MB)> <pages in memory(MB)> <over seconds> <memory ratio(%)> [<timestamp>]

EOF
    exit $1
}
INTERVAL=1
COUNT=1

OPTIONS=`getopt -o hu:p:i:c:tq --long help,username:,password:,interval:,count:,timestamp,quiet -- "$@"`
if [ $? != 0 ] ; then
 usage 1
fi
eval set -- "$OPTIONS"
while true; do
    OPT=$1
    OPTARG=$2
    case $1 in
	-h|--help) usage 0 ;;
	-u|--username)  MONGO_USER=$OPTARG;shift;;
	-p|--password)  MONGO_PASS=$OPTARG;shift;;
	-i|--interval)  INTERVAL=$OPTARG;shift;;
	-c|--count)     COUNT=$OPTARG;shift;;
	-t|--timestamp) TIMESTAMP=1;;
	-q|--quiet)     QUIET=1;;
	--) shift;break;;
	*) echo "Internal error! " >&2; usage 1 ;;
    esac
    shift
done

if [ "${MONGO}" = "" ]; then
    MONGO=/usr/local/mongo/bin/mongo
fi

if [ "${1}" = "" ]; then
    MONGO_CONN=$1
fi

MONGO_AUTH=
if [ "${MONGO_USER}" != "" -a "${MONGO_PASS}" != "" ];then
    MONGO_AUTH=' -u '${MONGO_USER}' -p '${MONGO_PASS}' --authenticationDatabase admin'
fi
PAGE_SIZE=`getconf PAGE_SIZE`
SYSTEM_MEMORY=`cat /proc/meminfo | grep MemTotal | awk '{print $2*1024}'`

# header
if [ "${QUIET}" == "" ];then
    printf "%10s %10s %10s  %6s  %10s %8s\n" 'RES(mb)' 'PAGE(mb)' 'SEC' 'MEM(%)' 'DATE' 'TIME'
fi
# check loop
for i in `eval echo "{1..$COUNT}"`
do
if [ "${i}" != "1" ]; then
    sleep ${INTERVAL}
fi
MONGO_STATUS=`${MONGO} --quiet ${MONG_CONN} ${MONGO_AUTH} <<<"
db.serverStatus().mem.resident
ws=db.serverStatus({workingSet:1}).workingSet
Math.floor(ws.pagesInMemory * $PAGE_SIZE / (1024 * 1024))
ws.overSeconds
Math.floor((ws.pagesInMemory * $PAGE_SIZE / $SYSTEM_MEMORY)*10000)/100
" | grep -v [a-z{}]`;
if [ "${TIMESTAMP}" != "" ];then
    DATE=`date +'%Y-%m-%d %H:%M:%S'`
    MONGO_STATUS=${MONGO_STATUS}' '${DATE}
fi
printf "%10s %10s %10s  %6s  %10s %8s\n" ${MONGO_STATUS};
done
