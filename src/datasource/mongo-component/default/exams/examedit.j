{
"@R":"1365745326",
"type":"HorizontalWidget",
"subject":"examedit",
"description":"",
"css":"#examedit h3.q {\r
  cursor: pointer;\r
}\r
#examedit div.q {\r
  display: none;\r
}\r
#examedit h5 {\r
  margin:0;\r
  padding:0;\r
}\r
#examedit h6 {\r
  margin:0;\r
  padding:0;\r
}\r
\r
#examedit form {\r
  width : 500px;\r
}\r
#examedit input,\r
#examedit textarea {\r
  font-size: 0.8em;\r
}\r
#examedit input[type=\"text\"] {\r
  width : 500px;\r
}\r
#examedit textarea {\r
  width  : 500px;\r
  height : 100px;\r
}\r
#examedit input[name=\"op\"][value=\"remove\"] {\r
  float: right;\r
  font-weight: 600;\r
}",
"js":"$(function(){\r
  $('#examedit h3.q').click(function(ev){\r
    $(this).parent().next().slideToggle();\r
  });\r
  $('#exampage div.hd3').hide();\r
  $('#exampage h3.q').click(function(ev){\r
    $(this).parent().next().slideToggle();\r
  });\r
});",
"id":"examedit",
"class":"page",
"body":"<?cs if: A.mongo.exam.writable ?>\r
<div class=\"mongo\">\r
<div class=\"window\" style=\"width:100%;clear:both;\">\r
<div class=\"hd1\">\r
<div class=\"h2\">\r
  <h2>\u7de8\u96c6</h2>\r
</div>\r
<div class=\"hd2\">\r
<form id=\"questions\" method=\"POST\" action=\"<?cs var:C._base ?>/exams/edit/<?cs var:A.mongo.exam._u ?>\">\r
<h5>\u516c\u958b<input type=\"checkbox\" name=\"public\" <?cs if:A.mongo.exam.public ?>checked<?cs /if ?>></input></h5>\r
\r
<h5>\u554f\u984c\u30bf\u30a4\u30c8\u30eb</h5>\r
<input type=\"text\" name=\"qname\" value=\"<?cs var:A.mongo.exam.qname ?>\"></input>\r
<h5>\u6982\u8981</h5>\r
<textarea name=\"qsummary\" ><?cs var:A.mongo.exam.qsummary ?></textarea>\r
<a target=\"_blank\" href=\"<?cs var:C._base ?>/notation\">notation</a>\r
<h5>\u51fa\u984c\u6570\uff08\uff15\u554f\u4ee5\u4e0a\uff09</h5>\r
<input type=\"text\" name=\"qnum\" value=\"<?cs var:A.mongo.exam.qnum ?>\"></input>\r
\r
<?cs set: i = 0 ?>\r
<?cs each: question = A.mongo.exam.qs ?>\r
<?cs set: i = i+1 ?>\r
<div class=\"h3\"><h3 class=\"q <?cs var:question.valid ?>\">Q<?cs var:i?>.</h3></div>\r
<div id=\"q<?cs name:question ?>\" class=\"hd3 q\">\r
  <h6> \u554f\u984c\u6587</h6>\r
  <textarea class=\"q\" name=\"q<?cs name:question ?>\" ><?cs var:question.q ?></textarea>\r
  <a target=\"_blank\" href=\"<?cs var:C._base ?>/notation\">notation</a>\r
  <h6> \u7b54\u3048</h6>\r
  <input type=\"text\" name=\"q<?cs name:question ?>a\" value=\"<?cs var:question.a ?>\"></input>\r
  <h6> \u7b54\u3048\u5019\u88dc</h6>\r
  <ol>\r
    <?cs each: candidate = question.c ?>\r
    <li><input type=\"text\" name=\"q<?cs name:question ?>c<?cs name:candidate ?>\" value=\"<?cs var:candidate ?>\"></input></li>\r
    <?cs /each ?>\r
  </ol>\r
  <h6>\u89e3\u8aac\u6587</h6>\r
  <textarea name=\"q<?cs name:question ?>e\" ><?cs var:question.e ?></textarea>\r
  <a target=\"_blank\" href=\"<?cs var:C._base ?>/notation\">notation</a>\r
</div>\r
<?cs /each ?>\r
\r
<input type=\"hidden\" name=\"_u\" value=\"<?cs var:A.mongo.exam._u ?>\"></input>\r
<input type=\"submit\" name=\"op\" value=\"save\"></input>\r
<input type=\"submit\" name=\"op\" value=\"preview\"></input>\r
<input type=\"submit\" name=\"op\" value=\"remove\"></input>\r
</form>\r
</div>\r
</div>\r
</div>\r
</div>\r
<?cs /if ?>\r
",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"exams/examedit"
}