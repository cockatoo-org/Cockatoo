{
"@R":"1363596223",
"type":"HorizontalWidget",
"subject":"examedit",
"description":"",
"css":"#examedit h3.q {\r\n  cursor: pointer;\r\n}\r\n#examedit div.q {\r\n  display: none;\r\n}\r\n#examedit h5 {\r\n  margin:0;\r\n  padding:0;\r\n}\r\n#examedit h6 {\r\n  margin:0;\r\n  padding:0;\r\n}\r\n\r\n#examedit input,\r\n#examedit textarea {\r\n  font-size: 0.8em;\r\n}\r\n#examedit input[type=\"text\"] {\r\n  width : 500px;\r\n}\r\n#examedit textarea {\r\n  width  : 500px;\r\n  height : 100px;\r\n}\r\n",
"js":"$(function(){\r\n  $('#examedit h3.q').click(function(ev){\r\n    $(this).parent().next().slideToggle();\r\n  });\r\n  $('#exampage div.hd3').hide();\r\n  $('#exampage h3.q').click(function(ev){\r\n    $(this).parent().next().slideToggle();\r\n  });\r\n});",
"id":"examedit",
"class":"page",
"body":"<?cs if: S.login.user ?>\r\n<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>\u7de8\u96c6<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n<form id=\"questions\" method=\"POST\" action=\"\/mongo\/exams\/edit\/<?cs var:A.mongo.exam.docid ?>\">\r\n<h5>\u516c\u958b<input type=\"checkbox\" name=\"public\" <?cs if:A.mongo.exam.public ?>checked<?cs \/if ?>><\/input><\/h5>\r\n\r\n<h5>\u554f\u984c\u30bf\u30a4\u30c8\u30eb<\/h5>\r\n<input type=\"text\" name=\"qname\" value=\"<?cs var:A.mongo.exam.qname ?>\"><\/input>\r\n<h5>\u6982\u8981<\/h5>\r\n<textarea name=\"qsummary\" ><?cs var:A.mongo.exam.qsummary ?><\/textarea>\r\n<a target=\"_blank\" href=\"\/mongo\/notation\">notation<\/a>\r\n<h5>\u51fa\u984c\u6570\uff08\uff15\u554f\u4ee5\u4e0a\uff09<\/h5>\r\n<input type=\"text\" name=\"qnum\" value=\"<?cs var:A.mongo.exam.qnum ?>\"><\/input>\r\n\r\n<?cs set: i = 0 ?>\r\n<?cs each: question = A.mongo.exam.qs ?>\r\n<?cs set: i = i+1 ?>\r\n<div class=\"h3\"><h3 class=\"q <?cs var:question.valid ?>\">Q<?cs var:i?>.<\/h3><\/div>\r\n<div id=\"q<?cs name:question ?>\" class=\"hd3 q\">\r\n  <h6> \u554f\u984c\u6587<\/h6>\r\n  <textarea class=\"q\" name=\"q<?cs name:question ?>\" ><?cs var:question.q ?><\/textarea>\r\n  <a target=\"_blank\" href=\"\/mongo\/notation\">notation<\/a>\r\n  <h6> \u7b54\u3048<\/h6>\r\n  <input type=\"text\" name=\"q<?cs name:question ?>a\" value=\"<?cs var:question.a ?>\"><\/input>\r\n  <h6> \u7b54\u3048\u5019\u88dc<\/h6>\r\n  <ol>\r\n    <?cs each: candidate = question.c ?>\r\n    <li><input type=\"text\" name=\"q<?cs name:question ?>c<?cs name:candidate ?>\" value=\"<?cs var:candidate ?>\"><\/input><\/li>\r\n    <?cs \/each ?>\r\n  <\/ol>\r\n  <h6>\u89e3\u8aac\u6587<\/h6>\r\n  <textarea name=\"q<?cs name:question ?>e\" ><?cs var:question.e ?><\/textarea>\r\n  <a target=\"_blank\" href=\"\/mongo\/notation\">notation<\/a>\r\n<\/div>\r\n<?cs \/each ?>\r\n\r\n<input type=\"hidden\" name=\"docid\" value=\"<?cs var:A.mongo.exam.docid ?>\"><\/input>\r\n<input type=\"submit\" name=\"op\" value=\"save\"><\/input>\r\n<input type=\"submit\" name=\"op\" value=\"preview\"><\/input>\r\n<\/form>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs \/if ?>\r\n",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"exams\/examedit"
}