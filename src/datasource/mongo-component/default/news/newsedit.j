{
"@R":"1365745159",
"type":"HorizontalWidget",
"subject":"newsedit",
"description":"",
"css":"#newsedit h5 {\r
  margin:0;\r
  padding:0;\r
}\r
#newsedit h6 {\r
  margin:0;\r
  padding:0;\r
}\r
#newsedit form {\r
  width : 800px;\r
}\r
#newsedit input,\r
#newsedit textarea {\r
  font-size: 0.8em;\r
}\r
#newsedit input[type=\"text\"] {\r
  width : 800px;\r
}\r
#newsedit textarea {\r
  width  : 800px;\r
  height : 600px;\r
}\r
#newsedit input[name=\"op\"][value=\"remove\"] {\r
  float: right;\r
  font-weight: 600;\r
}\r
\r
\r
",
"js":"",
"id":"newsedit",
"class":"page",
"body":"<?cs if: A.mongo.news.writable ?>\r
<div class=\"mongo\">\r
<div class=\"window\" style=\"width:100%;clear:both;\">\r
<div class=\"hd1\">\r
<div class=\"h2\">\r
  <h2>NEWS\u5185\u5bb9</h2>\r
</div>\r
<div class=\"hd2\">\r
<form method=\"POST\" action=\"<?cs var:C._base ?>/news/edit/<?cs var:A.mongo.news._u ?>\">\r
  <h5>\u516c\u958b<input type=\"checkbox\" name=\"public\" <?cs if:A.mongo.news.public ?>checked<?cs /if ?>></input></h5>\r
  <h5>NEWS\u30bf\u30a4\u30c8\u30eb</h5>\r
  <input type=\"text\" name=\"title\" value=\"<?cs var:A.mongo.news.title ?>\"></input>\r
  <h5>\u5185\u5bb9</h5>\r
  <textarea name=\"origin\" ><?cs var:A.mongo.news.origin ?></textarea>\r
  <br>\r
  <input type=\"hidden\" name=\"_u\" value=\"<?cs var:A.mongo.news._u ?>\"></input>\r
  <input type=\"submit\" name=\"op\" value=\"save\"></input>\r
  <input type=\"submit\" name=\"op\" value=\"preview\"></input>\r
  <a target=\"_blank\" href=\"<?cs var:C._base ?>/notation\">notation</a>\r
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
"_u":"news/newsedit"
}