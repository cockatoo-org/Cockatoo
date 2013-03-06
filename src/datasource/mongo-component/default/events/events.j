{
"@R":"1362474224",
"type":"HorizontalWidget",
"subject":"events",
"description":"",
"css":"#events div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}",
"js":"",
"id":"events",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>\u30a4\u30d9\u30f3\u30c8<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n  <?cs each: item = A.mongo.events ?>\r\n<dt><a href=\"\/mongo\/events\/<?cs var:item.eventid ?>\"><?cs var:item.date ?> <?cs var:item.time ?> <?cs var:item.title ?><\/a><\/dt><dd><?cs var:item.subtitle ?><\/dd>\r\n  <?cs \/each ?>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs if:S.login.writable?>\r\n  <div class=\"edit\"><a href=\"\/mongo\/events\/edit\/new\">\u65b0\u898f\u30a4\u30d9\u30f3\u30c8<\/a><\/div>\r\n<?cs \/if ?>\r\n",
"action":[
"action:\/\/mongo-action\/mongo\/EventAction?getA"
],
"header":"",
"bottom":"",
"_u":"events\/events"
}