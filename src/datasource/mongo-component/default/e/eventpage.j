{
"@R":"1361861109",
"type":"HorizontalWidget",
"subject":"eventpage",
"description":"",
"css":"#eventpage div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}\r\n#eventpage table.details {\r\n  padding: 5px 0 0 10px;\r\n}\r\n#eventpage table.details th {\r\n  min-width: 100px;\r\n}\r\n",
"js":"",
"id":"eventpage",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"h1\"><h1><?cs var:A.mongo.event.title?><\/h1><\/div>\r\n<div class=\"hd1\">\r\n<div class=\"hd2\">\r\n<div class=\"hd3\"><?cs var:A.mongo.event.subtitle?><\/div>\r\n<\/div>\r\n<table class=\"details\"><tbody>\r\n<tr>\r\n<th>\u65e5\u6642<\/th>\r\n<td><?cs var:A.mongo.event.date?> <?cs var:A.mongo.event.time?><\/td>\r\n<\/tr><tr>\r\n<th>\u4f1a\u5834<\/th>\r\n<td><?cs var:A.mongo.event.address?><\/td>\r\n<\/tr><tr>\r\n<th>URL<\/th>\r\n<td><a href=\"<?cs var:A.mongo.event.url ?>\"><?cs var:A.mongo.event.url ?><\/a><\/td>\r\n<\/tr><tr>\r\n<th>\u5b9a\u54e1<\/th>\r\n<td><?cs var:A.mongo.event.capacity?>\u4eba<\/td>\r\n<\/tr>\r\n<\/tbody><\/table>\r\n<\/div>\r\n<?cs each:item = A.mongo.event.contents ?>\r\n  <?cs call:drawTags(item)?>\r\n<?cs \/each ?>\r\n<\/div>\r\n<?cs if:S.login.user ?>\r\n  <div class=\"edit\"><a href=\"\/mongo\/events\/edit\/<?cs var:A.mongo.event.eventid ?>\">\u7de8\u96c6<\/a><\/div>\r\n<?cs \/if ?>\r\n<\/div>\r\n",
"action":[
"action:\/\/mongo-action\/mongo\/EventAction?get"
],
"header":"",
"bottom":"",
"_u":"e\/eventpage"
}