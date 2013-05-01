{
"public":"on",
"title":"Memory check script",
"origin":"** \u4f7f\u3044\u65b9\r
*** \u57fa\u672c\r
 $ check_mongo.sh \r
*** Admin DB \u8a8d\u8a3c\r
 $ check_mongo.sh -u crumb -p jp\r
*** \u30ea\u30e2\u30fc\u30c8\r
 $ check_mongo.sh localhost:27017\r
*** 1\u5206\u9593\u9694\u306710\u56de\r
 $ check_mongo.sh -c 10 -i 60 -q\r
\r
\r
** script\r
 #!/usr/bin/env bash\r
 \r
 usage (){\r
     cat<<EOF\r
 Usage    :\r
      check_mongo.sh [options] [host[:port]]\r
 \r
 Optoins  :\r
      -h [ --help     ]      : This message.\r
      -u [ --username ]      : MongoDB user\r
      -p [ --passowrd ]      : MongoDB user's password\r
      -c [ --count ]         : Number of checks.\r
      -i [ --interval ]      : Check interval.\r
      -t [ --timestamp ]     : Add timestamp to last column\r
      -q [ --quiet ]         : No format header\r
 Output   :\r
      <resident(MB)> <pages in memory(MB)> <over seconds> <memory ratio(%)> [<timestamp>]\r
 \r
 EOF\r
     exit $1\r
 }\r
 INTERVAL=1\r
 COUNT=1\r
 \r
 OPTIONS=`getopt -o hu:p:i:c:tq --long help,username:,password:,interval:,count:,timestamp,quiet -- \"$@\"`\r
 if [ $? != 0 ] ; then\r
  usage 1\r
 fi\r
 eval set -- \"$OPTIONS\"\r
 while true; do\r
     OPT=$1\r
     OPTARG=$2\r
     case $1 in\r
        -h|--help) usage 0 ;;\r
        -u|--username)  MONGO_USER=$OPTARG;shift;;\r
        -p|--password)  MONGO_PASS=$OPTARG;shift;;\r
        -i|--interval)  INTERVAL=$OPTARG;shift;;\r
        -c|--count)     COUNT=$OPTARG;shift;;\r
        -t|--timestamp) TIMESTAMP=1;;\r
        -q|--quiet)     QUIET=1;;\r
        --) shift;break;;\r
        *) echo \"Internal error! \" >&2; usage 1 ;;\r
     esac\r
     shift\r
 done\r
 \r
 if [ \"${MONGO}\" = \"\" ]; then\r
     MONGO=/usr/local/mongo/bin/mongo\r
 fi\r
 \r
 if [ \"${1}\" = \"\" ]; then\r
     MONGO_CONN=$1\r
 fi\r
 \r
 MONGO_AUTH=\r
 if [ \"${MONGO_USER}\" != \"\" -a \"${MONGO_PASS}\" != \"\" ];then\r
     MONGO_AUTH=' -u '${MONGO_USER}' -p '${MONGO_PASS}' --authenticationDatabase admin'\r
 fi\r
 PAGE_SIZE=`getconf PAGE_SIZE`\r
 SYSTEM_MEMORY=`cat /proc/meminfo | grep MemTotal | awk '{print $2*1024}'`\r
 \r
 # header\r
 if [ \"${QUIET}\" == \"\" ];then\r
     printf \"%10s %10s %10s  %6s  %10s %8s\\n\" 'RES(mb)' 'PAGE(mb)' 'SEC' 'MEM(%)' 'DATE' 'TIME'\r
 fi\r
 # check loop\r
 for i in `eval echo \"{1..$COUNT}\"`\r
 do\r
 if [ \"${i}\" != \"1\" ]; then\r
     sleep ${INTERVAL}\r
 fi\r
 MONGO_STATUS=`${MONGO} --quiet ${MONG_CONN} ${MONGO_AUTH} <<<\"\r
 db.serverStatus().mem.resident\r
 ws=db.serverStatus({workingSet:1}).workingSet\r
 Math.floor(ws.pagesInMemory * $PAGE_SIZE / (1024 * 1024))\r
 ws.overSeconds\r
 Math.floor((ws.pagesInMemory * $PAGE_SIZE / $SYSTEM_MEMORY)*10000)/100\r
 \" | grep -v [a-z{}]`;\r
 if [ \"${TIMESTAMP}\" != \"\" ];then\r
     DATE=`date +'%Y-%m-%d %H:%M:%S'`\r
     MONGO_STATUS=${MONGO_STATUS}' '${DATE}\r
 fi\r
 printf \"%10s %10s %10s  %6s  %10s %8s\\n\" ${MONGO_STATUS};\r
 done\r
** \u89e3\u8aac\r
:RESIDENT:\u30d7\u30ed\u30bb\u30b9\u4f7f\u7528\u30e1\u30e2\u30ea\r
\r
:PAGE_SIZE:\u30e1\u30e2\u30ea\u30fc\u30da\u30fc\u30b8\u30b5\u30a4\u30ba\u3002\r
\u901a\u5e38\u306eLinux\u3067\u306f4096 (4kb)\r
\r
:pagesInMemory:MongoDB \u306eworkingSetAnalyzer\u304c\u8fd4\u5374\u3059\u308b\u5024\u3002\r
mongod\u304cmmap\u3057\u3066\u3044\u308b\u30c7\u30fc\u30bf\u30d5\u30a1\u30a4\u30eb\u306e\u4e2d\u3067\u30e1\u30e2\u30ea\u30fc\u306b\u4e57\u3063\u3066\u3044\u308b\u30da\u30fc\u30b8\u6570\u3002\r
PAGE_SIZE\u3092\u639b\u3051\u308b\u3068\u30d0\u30a4\u30c8\u6570\u306b\u306a\u308b\u3002\r
\r
:overSeconds:pagesInMemory\u306b\u542b\u307e\u308c\u308b\u30da\u30fc\u30b8\u4e2d\u3001\u6700\u65b0\u3068\u6700\u53e4\u306e\u30da\u30fc\u30b8\u306e\u6642\u9593\u5dee\u3002\r
\u53e4\u3044\u30da\u30fc\u30b8\u304c\u30e1\u30e2\u30ea\u30fc\u4e0a\u306b\u542b\u307e\u308c\u3066\u3044\u308b\u7a0b\u3001\u30e1\u30e2\u30ea\u30fc\u306b\u4f59\u88d5\u304c\u3042\u308b\u3068\u898b\u505a\u305b\u308b\u70ba\u3001\u30e1\u30e2\u30ea\u30fc\u5897\u8a2d\u306e\u76ee\u5b89\u306b\u306a\u308b\u3002\r
",
"op":"save",
"contents":[
{
"tag":"div",
"attr":{
"class":"hd1"
},
"children":[
{
"tag":"div",
"attr":{
"class":"hd2"
},
"children":[
{
"tag":"div",
"attr":{
"class":"h3"
},
"children":[
{
"tag":"h3",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u4f7f\u3044\u65b9"
},
{
"tag":"a",
"attr":{
"href":"#\u4f7f\u3044\u65b9",
"name":"\u4f7f\u3044\u65b9"
},
"children":[
{
"tag":"text",
"text":"+"
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"hd3"
},
"children":[
{
"tag":"div",
"attr":{
"class":"h4"
},
"children":[
{
"tag":"h4",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u57fa\u672c"
},
{
"tag":"a",
"attr":{
"href":"#\u57fa\u672c",
"name":"\u57fa\u672c"
},
"children":[
{
"tag":"text",
"text":"+"
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"hd4"
},
"children":[
{
"tag":"pre",
"attr":[

],
"children":[
{
"tag":"text",
"text":"$ check_mongo.sh 
"
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"h4"
},
"children":[
{
"tag":"h4",
"attr":[

],
"children":[
{
"tag":"text",
"text":"Admin DB \u8a8d\u8a3c"
},
{
"tag":"a",
"attr":{
"href":"#Admin DB \u8a8d\u8a3c",
"name":"Admin DB \u8a8d\u8a3c"
},
"children":[
{
"tag":"text",
"text":"+"
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"hd4"
},
"children":[
{
"tag":"pre",
"attr":[

],
"children":[
{
"tag":"text",
"text":"$ check_mongo.sh -u crumb -p jp
"
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"h4"
},
"children":[
{
"tag":"h4",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u30ea\u30e2\u30fc\u30c8"
},
{
"tag":"a",
"attr":{
"href":"#\u30ea\u30e2\u30fc\u30c8",
"name":"\u30ea\u30e2\u30fc\u30c8"
},
"children":[
{
"tag":"text",
"text":"+"
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"hd4"
},
"children":[
{
"tag":"pre",
"attr":[

],
"children":[
{
"tag":"text",
"text":"$ check_mongo.sh localhost:27017
"
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"h4"
},
"children":[
{
"tag":"h4",
"attr":[

],
"children":[
{
"tag":"text",
"text":"1\u5206\u9593\u9694\u306710\u56de"
},
{
"tag":"a",
"attr":{
"href":"#1\u5206\u9593\u9694\u306710\u56de",
"name":"1\u5206\u9593\u9694\u306710\u56de"
},
"children":[
{
"tag":"text",
"text":"+"
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"hd4"
},
"children":[
{
"tag":"pre",
"attr":[

],
"children":[
{
"tag":"text",
"text":"$ check_mongo.sh -c 10 -i 60 -q
"
}
]
},
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":""
}
]
},
{
"tag":"br",
"text":""
}
]
},
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":""
}
]
},
{
"tag":"br",
"text":""
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"h3"
},
"children":[
{
"tag":"h3",
"attr":[

],
"children":[
{
"tag":"text",
"text":"script"
},
{
"tag":"a",
"attr":{
"href":"#script",
"name":"script"
},
"children":[
{
"tag":"text",
"text":"+"
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"hd3"
},
"children":[
{
"tag":"pre",
"attr":[

],
"children":[
{
"tag":"text",
"text":"#!/usr/bin/env bash

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

OPTIONS=`getopt -o hu:p:i:c:tq --long help,username:,password:,interval:,count:,timestamp,quiet -- \"$@\"`
if [ $? != 0 ] ; then
 usage 1
fi
eval set -- \"$OPTIONS\"
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
       *) echo \"Internal error! \" >&2; usage 1 ;;
    esac
    shift
done

if [ \"${MONGO}\" = \"\" ]; then
    MONGO=/usr/local/mongo/bin/mongo
fi

if [ \"${1}\" = \"\" ]; then
    MONGO_CONN=$1
fi

MONGO_AUTH=
if [ \"${MONGO_USER}\" != \"\" -a \"${MONGO_PASS}\" != \"\" ];then
    MONGO_AUTH=' -u '${MONGO_USER}' -p '${MONGO_PASS}' --authenticationDatabase admin'
fi
PAGE_SIZE=`getconf PAGE_SIZE`
SYSTEM_MEMORY=`cat /proc/meminfo | grep MemTotal | awk '{print $2*1024}'`

# header
if [ \"${QUIET}\" == \"\" ];then
    printf \"%10s %10s %10s  %6s  %10s %8s\\n\" 'RES(mb)' 'PAGE(mb)' 'SEC' 'MEM(%)' 'DATE' 'TIME'
fi
# check loop
for i in `eval echo \"{1..$COUNT}\"`
do
if [ \"${i}\" != \"1\" ]; then
    sleep ${INTERVAL}
fi
MONGO_STATUS=`${MONGO} --quiet ${MONG_CONN} ${MONGO_AUTH} <<<\"
db.serverStatus().mem.resident
ws=db.serverStatus({workingSet:1}).workingSet
Math.floor(ws.pagesInMemory * $PAGE_SIZE / (1024 * 1024))
ws.overSeconds
Math.floor((ws.pagesInMemory * $PAGE_SIZE / $SYSTEM_MEMORY)*10000)/100
\" | grep -v [a-z{}]`;
if [ \"${TIMESTAMP}\" != \"\" ];then
    DATE=`date +'%Y-%m-%d %H:%M:%S'`
    MONGO_STATUS=${MONGO_STATUS}' '${DATE}
fi
printf \"%10s %10s %10s  %6s  %10s %8s\\n\" ${MONGO_STATUS};
done
"
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"h3"
},
"children":[
{
"tag":"h3",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u89e3\u8aac"
},
{
"tag":"a",
"attr":{
"href":"#\u89e3\u8aac",
"name":"\u89e3\u8aac"
},
"children":[
{
"tag":"text",
"text":"+"
}
]
}
]
}
]
},
{
"tag":"div",
"attr":{
"class":"hd3"
},
"children":[
{
"tag":"dl",
"attr":[

],
"children":[
{
"tag":"dt",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"RESIDENT"
}
]
}
]
},
{
"tag":"dd",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u30d7\u30ed\u30bb\u30b9\u4f7f\u7528\u30e1\u30e2\u30ea"
}
]
}
]
}
]
},
{
"tag":"dl",
"attr":[

],
"children":[
{
"tag":"dt",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"PAGE_SIZE"
}
]
}
]
},
{
"tag":"dd",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u30e1\u30e2\u30ea\u30fc\u30da\u30fc\u30b8\u30b5\u30a4\u30ba\u3002"
}
]
},
{
"tag":"br",
"attr":[

],
"children":[

]
},
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u901a\u5e38\u306eLinux\u3067\u306f4096 (4kb)"
}
]
}
]
}
]
},
{
"tag":"dl",
"attr":[

],
"children":[
{
"tag":"dt",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"pagesInMemory"
}
]
}
]
},
{
"tag":"dd",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"MongoDB \u306eworkingSetAnalyzer\u304c\u8fd4\u5374\u3059\u308b\u5024\u3002"
}
]
},
{
"tag":"br",
"attr":[

],
"children":[

]
},
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"mongod\u304cmmap\u3057\u3066\u3044\u308b\u30c7\u30fc\u30bf\u30d5\u30a1\u30a4\u30eb\u306e\u4e2d\u3067\u30e1\u30e2\u30ea\u30fc\u306b\u4e57\u3063\u3066\u3044\u308b\u30da\u30fc\u30b8\u6570\u3002"
}
]
},
{
"tag":"br",
"attr":[

],
"children":[

]
},
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"PAGE_SIZE\u3092\u639b\u3051\u308b\u3068\u30d0\u30a4\u30c8\u6570\u306b\u306a\u308b\u3002"
}
]
}
]
}
]
},
{
"tag":"dl",
"attr":[

],
"children":[
{
"tag":"dt",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"overSeconds"
}
]
}
]
},
{
"tag":"dd",
"attr":[

],
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"pagesInMemory\u306b\u542b\u307e\u308c\u308b\u30da\u30fc\u30b8\u4e2d\u3001\u6700\u65b0\u3068\u6700\u53e4\u306e\u30da\u30fc\u30b8\u306e\u6642\u9593\u5dee\u3002"
}
]
},
{
"tag":"br",
"attr":[

],
"children":[

]
},
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\u53e4\u3044\u30da\u30fc\u30b8\u304c\u30e1\u30e2\u30ea\u30fc\u4e0a\u306b\u542b\u307e\u308c\u3066\u3044\u308b\u7a0b\u3001\u30e1\u30e2\u30ea\u30fc\u306b\u4f59\u88d5\u304c\u3042\u308b\u3068\u898b\u505a\u305b\u308b\u70ba\u3001\u30e1\u30e2\u30ea\u30fc\u5897\u8a2d\u306e\u76ee\u5b89\u306b\u306a\u308b\u3002"
}
]
}
]
}
]
}
]
}
]
}
]
}
],
"_owner":"crumbjp",
"_ownername":"@crumbjp",
"_time":1365990594,
"_timestr":"2013-04-15",
"_u":"516793e2a3e8a"
}