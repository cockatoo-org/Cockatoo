{
"@R":"1369188653",
"type":"HorizontalWidget",
"subject":"timetableedit",
"description":"",
"css":"div.noryo2013_timetable div.tab table.timetable th.place,\r
div.noryo2013_timetable div.tab table.timetable select[name=\"place\"] {\r
  width: 80px;\r
}  \r
div.noryo2013_timetable div.tab table.timetable input[name=\"booth\"]{\r
  width: 40px;\r
}\r
div.noryo2013_timetable div.tab table.timetable th.start,\r
div.noryo2013_timetable div.tab table.timetable th.end,\r
div.noryo2013_timetable div.tab table.timetable input[name=\"start\"],\r
div.noryo2013_timetable div.tab table.timetable input[name=\"end\"] {\r
  width: 40px;\r
}\r
div.noryo2013_timetable div.tab table.timetable th.title,\r
div.noryo2013_timetable div.tab table.timetable input[name=\"title\"] {\r
  width: 250px;\r
}\r
div.noryo2013_timetable div.tab table.timetable th.incharge,\r
div.noryo2013_timetable div.tab table.timetable input[name=\"incharge\"] {\r
  width: 60px;\r
}\r
div.noryo2013_timetable div.tab table.timetable tr.view td {\r
  border-bottom: 1px dashed  #402817;\r
  padding: 2px 0 4px 0;\r
  cursor : pointer;\r
}\r
div.noryo2013_timetable div.tab table.timetable tr.view.private td {\r
  background-color: #cccccc;\r
}\r
div.noryo2013_timetable div.tab table.timetable tr.view td.title{\r
  font-weight:600;\r
}\r
div.noryo2013_timetable div.tab table.timetable tr.form {\r
  display:none;\r
}\r
div.noryo2013_timetable div.tab table.timetable tr.form td.key{\r
  font-weight:600;\r
}\r
\r
div.noryo2013_timetable div.tab table.timetable textarea[name=\"overview\"] {\r
  width: 600px;\r
  height: 50px;\r
}\r
div.noryo2013_timetable div.tab table.timetable textarea[name=\"origin\"] {\r
  width: 600px;\r
  height:400px;\r
}\r
div.noryo2013_timetable div.tab table.timetable input[name=\"types\"] {\r
  width: 600px;\r
}\r
div.noryo2013_timetable div.tab table.timetable input[name=\"targets\"] {\r
  width: 600px;\r
}",
"js":"$(function(){\r
  $('div.noryo2013_timetable div.tab table.timetable tr.view').click(function(){\r
   $(this).next().slideToggle();\r
  });\r
  function booth_check(select){\r
    select.next().hide();\r
    if ( select.val() == \"exhibition\" ) {\r
      select.next().show();\r
    }\r
  }\r
  $('select[name=\"place\"]').each(function(){\r
    booth_check($(this));\r
  });\r
  $('select[name=\"place\"]').change(function(){\r
    booth_check($(this));\r
  });\r
});\r
",
"id":"",
"class":"noryo2013_timetable",
"body":"<div class=\"tab\">\r
<?cs if: S.login.writable ?>\r
<table class=\"timetable\">\r
<tbody>\r
 <tr>\r
 <th class=\"place\"   >\u4f1a\u5834</th>\r
 <th class=\"start\"   >\u958b\u59cb\u6642\u523b</th>\r
 <th class=\"end\"     >\u7d42\u4e86\u6642\u523b</th>\r
 <th class=\"title\"   >\u30bf\u30a4\u30c8\u30eb</th>\r
 <th class=\"incharge\">\u767a\u8868\u8005</th>\u3000\r
 <th class=\"overview\">\u6982\u8981</th>\u3000\u3000\r
 </tr>\r
<?cs each: item = A.mongo.timeboxs.raw ?>\r
 <tr class=\"view <?cs if:! item.public ?>private<?cs /if ?>\">\r
 <td class=\"place\"   ><?cs var:item.place ?></td>   \r
 <td class=\"start\"   ><?cs var:item.start ?></td>   \r
 <td class=\"end\"     ><?cs var:item.end ?></td>\t    \r
 <td class=\"title\"   ><?cs var:item.title ?></td>\r
 <td class=\"incharge\"><?cs var:item.incharge ?></td>\r
 <td class=\"overview\"><?cs var:item.overview ?></td>\r
 </tr>\r
 <tr class=\"form\">\r
  <td colspan=\"6\">\r
<div>\r
  <form method=\"POST\" enctype=\"multipart/form-data\" action=\"<?cs var:C._base ?>/noryo2013/edit\">\r
   <input type=\"submit\" name=\"op\" value=\"save\"></input>\r
   <input type=\"submit\" name=\"op\" value=\"remove\"></input>\r
   <input type=\"hidden\" name=\"_u\" value=\"<?cs var:item._u ?>\"></input>\r
<table>\r
<tbody>\r
<tr class=\"public\"  ><td class=\"key\">\u516c\u958b</td>    <td><input type=\"checkbox\" name=\"public\" <?cs if:item.public ?>checked<?cs /if ?>></input></td></tr>\r
<tr class=\"place\"   ><td class=\"key\">\u4f1a\u5834</td>    <td>\r
<select name=\"place\">\r
  <option <?cs if:item.place == \"exhibition\"?>selected<?cs /if ?> value=\"exhibition\">\u5c55\u793a</option>\r
  <option <?cs if:item.place == \"place1\"    ?>selected<?cs /if ?> value=\"place1\">\u4f1a\u5834\uff11</option>\r
  <option <?cs if:item.place == \"place2\"    ?>selected<?cs /if ?> value=\"place2\">\u4f1a\u5834\uff12</option>\r
  <option <?cs if:item.place == \"place3\"    ?>selected<?cs /if ?> value=\"place3\">\u4f1a\u5834\uff13</option>\r
</select>\r
<select name=\"booth\">\r
  <?cs loop:x = #1, #10, #1 ?>\r
    <?cs set: v = \"A\"+x ?>\r
    <option <?cs if:item.booth == v ?>selected<?cs /if ?> value=\"<?cs var:v ?>\"><?cs var:v ?></option>\r
  <?cs /loop ?>\r
  <?cs loop:x = #1, #10, #1 ?>\r
    <?cs set: v = \"B\"+x ?>\r
    <option <?cs if:item.booth == v ?>selected<?cs /if ?> value=\"<?cs var:v ?>\"><?cs var:v ?></option>\r
  <?cs /loop ?>\r
  <?cs loop:x = #1, #10, #1 ?>\r
    <?cs set: v = \"C\"+x ?>\r
    <option <?cs if:item.booth == v ?>selected<?cs /if ?> value=\"<?cs var:v ?>\"><?cs var:v ?></option>\r
  <?cs /loop ?>\r
</select>\r
</td></tr>\r
<tr class=\"start\"   ><td class=\"key\">\u958b\u59cb\u6642\u523b</td><td><input type=\"text\" name=\"start\" value=\"<?cs var:item.start ?>\"></input></td></tr>\r
<tr class=\"end\"     ><td class=\"key\">\u7d42\u4e86\u6642\u523b</td><td><input type=\"text\" name=\"end\"   value=\"<?cs var:item.end ?>\"  ></input></td></tr>\r
<tr class=\"logo\"    ><td class=\"key\">\u30a4\u30e1\u30fc\u30b8</td><td><?cs var:item.logo ?><br><img src=\"/_s_/mongo/timetable/<?cs var:item.images.logo ?>\"></img><br><input type=\"file\" name=\"logo\" value=\"\"></input></td></tr>\r
<tr class=\"title\"   ><td class=\"key\">\u30bf\u30a4\u30c8\u30eb</td><td><input type=\"text\" name=\"title\" value=\"<?cs var:item.title ?>\"></input></td></tr>\r
<tr class=\"incharge\"><td class=\"key\">\u8b1b\u6f14\u8005</td>  <td><input type=\"text\" name=\"incharge\" value=\"<?cs var:item.incharge ?>\"></input></td></tr>\r
<tr class=\"overview\"><td class=\"key\">\u6982\u8981</td>    <td><textarea name=\"overview\" ><?cs var:item.overview ?></textarea></td></tr>\r
<tr class=\"targets\" ><td class=\"key\">\u5bfe\u8c61</td>    <td><input type=\"text\" name=\"targets\" value=\"<?cs each: target = item.targets ?><?cs var:target ?><?cs if:!last(target) ?>, <?cs /if ?><?cs /each ?>\"></input></td></tr>\r
<tr class=\"types\"   ><td class=\"key\">\u5f62\u5f0f</td>    <td><input type=\"text\" name=\"types\" value=\"<?cs each: type = item.types ?><?cs var:type ?><?cs if:!last(type) ?>,<?cs /if ?><?cs /each ?>\"></input></td></tr>\r
<tr class=\"origin\"  ><td class=\"key\">\u5185\u5bb9<br>   <a target=\"_blank\" href=\"<?cs var:C._base ?>/notation\">notation</a>\r
</td>    <td><textarea name=\"origin\" ><?cs var:item.origin ?></textarea></td></tr>\r
</tbody>\r
</table>\r
  </form>\r
</div>\r
  </td>\r
 </tr>\r
<?cs /each ?>\r
</tbody>\r
</table>\r
\r
<?cs /if ?>\r
\r
</div>\r
\r
\r
\r
\r
",
"action":[
"action://mongo-action/mongo/TimetableAction?getA"
],
"header":"",
"bottom":"",
"_u":"noryo2013/timetable/timetableedit"
}