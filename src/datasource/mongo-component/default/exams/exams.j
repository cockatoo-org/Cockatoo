{
"@R":"1362474210",
"type":"HorizontalWidget",
"subject":"exams",
"description":"",
"css":"#exams div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}",
"js":"",
"id":"exams",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>\u554f\u984c<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n  <?cs each: item = A.mongo.exams?>\r\n<dt><a href=\"\/mongo\/exams\/<?cs var:item.examid ?>\"><?cs var:item.qname?> \uff1a\uff08\u5168<?cs var:item.qnum ?>\u554f\uff09<\/a><\/dt><dd><?cs var:item.qsummary ?><\/dd>\r\n  <?cs \/each ?>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs if:S.login.writable?>\r\n  <div class=\"edit\"><a href=\"\/mongo\/exams\/edit\/new\">\u65b0\u898f\u554f\u984c<\/a><\/div>\r\n<?cs \/if ?>",
"action":[
"action:\/\/mongo-action\/mongo\/ExamAction?getA"
],
"header":"",
"bottom":"",
"_u":"exams\/exams"
}