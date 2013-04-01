{
"@R":"1364799494",
"type":"HorizontalWidget",
"subject":"eventsattender",
"description":"",
"css":"#attender > div {\r\n  border-style: solid;\r\n  border-width: 1px;\r\n  border-radius: 4px;\r\n  margin: 10px 0 0 5px;\r\n}\r\n#attender > div > h6{\r\n  font-size: 1.0em;\r\n\/\/  background-color: #402817;\r\n  background-color: #336699;\r\n  color: #ffffff;\r\n  margin: auto 0 0 0;\r\n  padding: 2px 5px;\r\n}\r\n#attender > div.owner > div { \r\n  font-weight: 700;\r\n}\r\n#attender > div > div {\r\n  padding: 5px;\r\n}\r\n#attender > div> div > dt {\r\n  font-weight: 700;\r\n}\r\n#attender > div > div > dt.my {\r\n  color: #00aa00;\r\n font-weight: 700;\r\n}\r\n\r\n#attender > div > div > dd {\r\n  border-bottom-style: dashed;\r\n  border-bottom-width: 1px;\r\n  margin: 0;\r\n  padding: 0 0 0 15px;\r\n}\r\n",
"js":"",
"id":"attender",
"class":"",
"body":"<div class=\"owner\">\r\n<h6>\u30a4\u30d9\u30f3\u30c8\u30aa\u30fc\u30ca\u30fc<\/h6>\r\n<div>\r\n<?cs var:A.mongo.event._ownername ?>\r\n<\/div>\r\n<\/div>\r\n\r\n<div class=\"attenders\">\r\n<h6>\u53c2\u52a0\u8005<\/h6>\r\n<div>\r\n<?cs each:item = A.mongo.event.attenders ?>\r\n<dt class=\"<?cs var:item.my ?>\"><?cs var:item.name ?><\/dt>\r\n<dd><?cs var:item.msg ?><\/dd>\r\n<?cs \/each ?>\r\n<\/div>\r\n<\/div>\r\n\r\n<div class=\"waiterse\">\r\n<h6>\u30ad\u30e3\u30f3\u30bb\u30eb\u5f85\u3061<\/h6>\r\n<div>\r\n<?cs each:item = A.mongo.event.waiters?>\r\n<dt class=\"<?cs var:item.my ?>\"><?cs var:item.name ?><\/dt>\r\n<dd><?cs var:item.msg ?><\/dd>\r\n<?cs \/each ?>\r\n<\/div>\r\n<\/div>\r\n\r\n<div class=\"cancelers\">\r\n<h6>\u30ad\u30e3\u30f3\u30bb\u30eb<\/h6>\r\n<div>\r\n<?cs each:item = A.mongo.event.cancelers ?>\r\n<dt class=\"<?cs var:item.my ?>\"><?cs var:item.name ?><\/dt>\r\n<dd><?cs var:item.msg ?><\/dd>\r\n<?cs \/each ?>\r\n<\/div>\r\n<\/div>",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"events\/attender"
}