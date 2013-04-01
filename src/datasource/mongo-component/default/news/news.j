{
"@R":"1364807329",
"type":"HorizontalWidget",
"subject":"news",
"description":"",
"css":"#news div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}\r\n#news li.private,\r\n#news li.private a {\r\n  color: #999999;\r\n}",
"js":"",
"id":"news",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>NEWS<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n  <ul>\r\n  <?cs each: item = A.mongo.newss ?>\r\n    <li class=\"<?cs if:!item.public ?>private<?cs \/if?>\" ><a href=\"<?cs var:C._base ?>\/news\/<?cs var:item.docid ?>\"><?cs var:item.title ?><\/a> (by <?cs var:item._ownername ?> <time><?cs var:item._timestr ?><\/time>)<\/li>\r\n  <?cs \/each ?>\r\n  <\/ul>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs if:S.login.writable?>\r\n  <div class=\"edit\"><a href=\"<?cs var:C._base ?>\/news\/edit\/news\">\u65b0\u898fNEWS<\/a><\/div>\r\n<?cs \/if ?>\r\n",
"action":[
"action:\/\/mongo-action\/mongo\/NewsAction?getA"
],
"header":"",
"bottom":"",
"_u":"news\/news"
}