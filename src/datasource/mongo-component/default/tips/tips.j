{
"@R":"1362559829",
"type":"HorizontalWidget",
"subject":"tips",
"description":"",
"css":"#tips div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}",
"js":"",
"id":"tips",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>TIPS<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n  <ul>\r\n  <?cs each: item = A.mongo.tips ?>\r\n    <li><a href=\"\/mongo\/tips\/<?cs var:item.tipid ?>\"><?cs var:item.title ?><\/a><\/li>\r\n  <?cs \/each ?>\r\n  <\/ul>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs if:S.login.writable?>\r\n  <div class=\"edit\"><a href=\"\/mongo\/tips\/edit\/new\">\u65b0\u898fTIPS<\/a><\/div>\r\n<?cs \/if ?>\r\n",
"action":[
"action:\/\/mongo-action\/mongo\/TipAction?getA"
],
"header":"",
"bottom":"",
"_u":"tips\/tips"
}