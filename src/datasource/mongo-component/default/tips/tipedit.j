{
"@R":"1363596075",
"type":"HorizontalWidget",
"subject":"tipedit",
"description":"",
"css":"#tipedit h5 {\r\n  margin:0;\r\n  padding:0;\r\n}\r\n#tipedit h6 {\r\n  margin:0;\r\n  padding:0;\r\n}\r\n#tipedit input,\r\n#tipedit textarea {\r\n  font-size: 0.8em;\r\n}\r\n#tipedit input[type=\"text\"] {\r\n  width : 800px;\r\n}\r\n#tipedit textarea {\r\n  width  : 800px;\r\n  height : 600px;\r\n}\r\n\r\n\r\n",
"js":"",
"id":"tipedit",
"class":"page",
"body":"<?cs if: A.mongo.tip.writable ?>\r\n<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>TIP\u5185\u5bb9<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n<form method=\"POST\" action=\"<?cs var:C._base ?>\/tips\/edit\/<?cs var:A.mongo.tip.docid ?>\">\r\n  <h5>\u516c\u958b<input type=\"checkbox\" name=\"public\" <?cs if:A.mongo.tip.public ?>checked<?cs \/if ?>><\/input><\/h5>\r\n  <h5>TIP\u30bf\u30a4\u30c8\u30eb<\/h5>\r\n  <input type=\"text\" name=\"title\" value=\"<?cs var:A.mongo.tip.title ?>\"><\/input>\r\n  <h5>\u5185\u5bb9<\/h5>\r\n  <textarea name=\"origin\" ><?cs var:A.mongo.tip.origin ?><\/textarea>\r\n  <br>\r\n  <input type=\"hidden\" name=\"docid\" value=\"<?cs var:A.mongo.tip.docid ?>\"><\/input>\r\n  <input type=\"submit\" name=\"op\" value=\"save\"><\/input>\r\n  <input type=\"submit\" name=\"op\" value=\"preview\"><\/input>\r\n  <a target=\"_blank\" href=\"<?cs var:C._base ?>\/notation\">notation<\/a>\r\n<\/form>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<?cs \/if ?>\r\n",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"tips\/tipedit"
}