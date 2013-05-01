{
"@R":"1364531847",
"type":"HorizontalWidget",
"subject":"exams",
"description":"",
"css":"#exams div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}\r\n#exams div.private,\r\n#exams div.private a {\r\n  color: #999999;\r\n}\r\n#exams dt {\r\n}\r\n#exams div.prevscore {\r\n  color: #CC0000;\r\n}",
"js":"",
"id":"exams",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>\u554f\u984c<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n  <?cs each: item = A.mongo.exams?>\r\n<div class=\"<?cs if:!item.public ?>private<?cs \/if?>\">\r\n<dt><a href=\"<?cs var:C._base ?>\/exams\/<?cs var:item._u ?>\"><?cs var:item.qname?> \uff1a\uff08\u5168<?cs var:item.qnum ?>\u554f\uff09<\/a> (by <?cs var:item._ownername ?> <time><?cs var:item._timestr ?><\/time>) <\/dt><dd><?cs if:S.login.exam[item._u].score ?><div class=\"prevscore\">\u524d\u56de\u30b9\u30b3\u30a2\uff1a <?cs var:S.login.exam[item._u].score ?>\u70b9<\/div><?cs \/if ?>\r\n<?cs var:item.qsummary ?><\/dd>\r\n<\/div>\r\n  <?cs \/each ?>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs if:S.login.writable?>\r\n  <div class=\"edit\"><a href=\"<?cs var:C._base ?>\/exams\/edit\/new\">\u65b0\u898f\u554f\u984c<\/a><\/div>\r\n<?cs \/if ?>",
"action":[
"action:\/\/mongo-action\/mongo\/ExamAction?getA"
],
"header":"",
"bottom":"",
"_u":"exams\/exams"
}