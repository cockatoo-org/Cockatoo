{
"public":"on",
"title":"mongod \u8a2d\u5b9a\u30d5\u30a1\u30a4\u30eb\u30c6\u30f3\u30d7\u30ec\u30fc\u30c8",
"origin":"***\u4ee5\u4e0b\u306e\u30c7\u30a3\u30ec\u30af\u30c8\u30ea\u3092\u4f5c\u3063\u3066\u304a\u304f\r\n-\/usr\/local\/mongo\r\n-\/usr\/local\/mongo\/conf\r\n-\/usr\/local\/mongo\/data\r\n-\/usr\/local\/mongo\/logs\r\n\r\n***\/usr\/local\/mongo\/conf\/mongod.conf\r\n replSet=YourRSName\r\n port=27017\r\n dbpath=\/usr\/local\/mongo\/data\r\n pidfilepath=\/usr\/local\/mongo\/logs\/mongod.pid\r\n logpath=\/usr\/local\/mongo\/logs\/mongod.log\r\n logappend=true\r\n quiet=true\r\n fork=true\r\n directoryperdb=true\r\n maxConns=20000\r\n slowms=1000\r\n nohttpinterface = true\r\n notablescan = true\r\n nssize = 4\r\n noauth = true\r\n # 10GB\r\n oplogSize = 10240\r\n\r\n*** mongod\u8d77\u52d5\r\n $ \/path\/to\/mongod -f \/usr\/local\/mongo\/conf\/mongod.conf\r\n",
"_owner":"admin",
"_ownername":"admin",
"docid":"513709d0278b4",
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
"text":"\u4ee5\u4e0b\u306e\u30c7\u30a3\u30ec\u30af\u30c8\u30ea\u3092\u4f5c\u3063\u3066\u304a\u304f"
},
{
"tag":"a",
"attr":{
"href":"#\u4ee5\u4e0b\u306e\u30c7\u30a3\u30ec\u30af\u30c8\u30ea\u3092\u4f5c\u3063\u3066\u304a\u304f",
"name":"\u4ee5\u4e0b\u306e\u30c7\u30a3\u30ec\u30af\u30c8\u30ea\u3092\u4f5c\u3063\u3066\u304a\u304f"
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
"tag":"ul",
"attr":{
"class":"ul0"
},
"children":[
{
"tag":"li",
"attr":{
"class":"ul1"
},
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\/usr\/local\/mongo"
}
]
}
]
},
{
"tag":"li",
"attr":{
"class":"ul1"
},
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\/usr\/local\/mongo\/conf"
}
]
}
]
},
{
"tag":"li",
"attr":{
"class":"ul1"
},
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\/usr\/local\/mongo\/data"
}
]
}
]
},
{
"tag":"li",
"attr":{
"class":"ul1"
},
"children":[
{
"tag":"text",
"attr":[

],
"children":[
{
"tag":"text",
"text":"\/usr\/local\/mongo\/logs"
}
]
}
]
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
"text":"\/usr\/local\/mongo\/conf\/mongod.conf"
},
{
"tag":"a",
"attr":{
"href":"#\/usr\/local\/mongo\/conf\/mongod.conf",
"name":"\/usr\/local\/mongo\/conf\/mongod.conf"
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
"text":" replSet=YourRSName\n port=27017\n dbpath=\/usr\/local\/mongo\/data\n pidfilepath=\/usr\/local\/mongo\/logs\/mongod.pid\n logpath=\/usr\/local\/mongo\/logs\/mongod.log\n logappend=true\n quiet=true\n fork=true\n directoryperdb=true\n maxConns=20000\n slowms=1000\n nohttpinterface = true\n notablescan = true\n nssize = 4\n noauth = true\n # 10GB\n oplogSize = 10240\n"
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
"text":"mongod\u8d77\u52d5"
},
{
"tag":"a",
"attr":{
"href":"#mongod\u8d77\u52d5",
"name":"mongod\u8d77\u52d5"
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
"text":" $ \/path\/to\/mongod -f \/usr\/local\/mongo\/conf\/mongod.conf\n"
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
}
]
}
]
}
],
"_time":1364445613,
"_timestr":"2013-03-28",
"_u":"513709d0278b4"
}