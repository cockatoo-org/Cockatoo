{
"@R":"1364879139",
"type":"HorizontalWidget",
"subject":"events",
"description":"",
"css":"#events div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}\r\n#events div.private,\r\n#events div.private a {\r\n  color: #999999;\r\n}\r\n#events div dt.writable a {\r\n  color: #0000cc;\r\n}\r\n#events div.private dt.writable a {\r\n  color: #9999ff;\r\n}",
"js":"",
"id":"events",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>\u30a4\u30d9\u30f3\u30c8<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n  <?cs each: item = A.mongo.events ?>\r\n<div class=\"<?cs if:!item.public ?>private<?cs \/if?>\" >\r\n<?cs if: item.writable ?>\r\n  <dt class=\"writable\"><a href=\"<?cs var:C._base ?>\/events\/<?cs var:item._u ?>\"><?cs var:item.date ?> <?cs var:item.time ?> <?cs var:item.title ?><\/a> (by <?cs var:item._ownername ?> <time><?cs var:item._timestr ?><\/time>)<\/dt>\r\n<?cs else ?>\r\n  <dt><a href=\"<?cs var:item.event_url ?>\"><?cs var:item.date ?> <?cs var:item.time ?> <?cs var:item.title ?><\/a> (by <?cs var:item._ownername ?> <time><?cs var:item._timestr ?><\/time>)<\/dt>\r\n<?cs \/if ?>\r\n<dd><?cs var:item.subtitle ?><\/dd>\r\n<\/div>\r\n  <?cs \/each ?>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs if:S.login.writable?>\r\n  <div class=\"edit\"><a href=\"<?cs var:C._base ?>\/events\/edit\/new\">\u65b0\u898f\u30a4\u30d9\u30f3\u30c8<\/a><\/div>\r\n<?cs \/if ?>\r\n",
"action":[
"action:\/\/mongo-action\/mongo\/EventAction?getA"
],
"header":"",
"bottom":"",
"_u":"events\/events"
}