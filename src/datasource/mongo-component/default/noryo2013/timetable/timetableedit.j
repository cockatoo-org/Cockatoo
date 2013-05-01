{
"@R":"1367388795",
"type":"HorizontalWidget",
"subject":"timetableedit",
"description":"",
"css":"#timetableedit h5 {\r
  margin:0;\r
  padding:0;\r
}\r
#timetableedit h6 {\r
  margin:0;\r
  padding:0;\r
}\r
#timetableedit form {\r
  width : 800px;\r
}\r
#timetableedit input,\r
#timetableedit textarea {\r
  font-size: 0.8em;\r
}\r
#timetableedit input[type=\"text\"] {\r
  width : 800px;\r
}\r
#timetableedit textarea {\r
  width  : 800px;\r
  height : 600px;\r
}\r
#timetableedit input[name=\"op\"][value=\"remove\"] {\r
  float: right;\r
  font-weight: 600;\r
}\r
\r
\r
",
"js":"",
"id":"timetableedit",
"class":"",
"body":"<?cs if: A.mongo.timebox.writable ?>\r
<form method=\"POST\" action=\"<?cs var:C._base ?>/noryo2013/timetable/edit/<?cs var:A.mongo.timebox._u ?>\">\r
  <h5>\u516c\u958b<input type=\"checkbox\" name=\"public\" <?cs if:A.mongo.timebox.public ?>checked<?cs /if ?>></input></h5>\r
  <h5>\u958b\u59cb\u6642\u523b</h5>\r
  <input type=\"text\" name=\"start\" value=\"<?cs var:A.mongo.timebox.start ?>\"></input>\r
  <h5>\u7d42\u4e86\u6642\u523b</h5>\r
  <input type=\"text\" name=\"end\" value=\"<?cs var:A.mongo.timebox.end ?>\"></input>\r
  <h5>\u30bf\u30a4\u30c8\u30eb</h5>\r
  <input type=\"text\" name=\"title\" value=\"<?cs var:A.mongo.timebox.title ?>\"></input>\r
  <h5>\u30a4\u30d9\u30f3\u30c8\u5f62\u5f0f(type)</h5>\r
  <input type=\"text\" name=\"type\" value=\"<?cs var:A.mongo.timebox.type ?>\"></input>\r
  <h5>\u30bf\u30fc\u30b2\u30c3\u30c8</h5>\r
  <input type=\"text\" name=\"targets\" value=\"<?cs var:A.mongo.timebox.targets ?>\"></input>\r
  <h5>\u6982\u8981</h5>\r
  <input type=\"text\" name=\"overview\" value=\"<?cs var:A.mongo.timebox.overview ?>\"></input>\r
  <h5>\u8b1b\u6f14\u8005</h5>\r
  <input type=\"text\" name=\"incharge\" value=\"<?cs var:A.mongo.timebox.incharge?>\"></input>\r
  <h5>\u5185\u5bb9</h5>\r
  <textarea name=\"origin\" ><?cs var:A.mongo.timebox.origin ?></textarea>\r
  <br>\r
  <input type=\"hidden\" name=\"_u\" value=\"<?cs var:A.mongo.timebox._u ?>\"></input>\r
  <input type=\"hidden\" name=\"point\" value=\"<?cs var:A.mongo.timebox.point?>\"></input>\r
  <input type=\"submit\" name=\"op\" value=\"save\"></input>\r
  <input type=\"submit\" name=\"op\" value=\"preview\"></input>\r
  <a target=\"_blank\" href=\"<?cs var:C._base ?>/notation\">notation</a>\r
  <input type=\"submit\" name=\"op\" value=\"remove\"></input>\r
</form>\r
<?cs /if ?>\r
",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"noryo2013/timetable/timetableedit"
}