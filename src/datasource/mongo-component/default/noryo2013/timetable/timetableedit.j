{
"@R":"1368786620",
"type":"HorizontalWidget",
"subject":"timetableedit",
"description":"",
"css":"\r
\r
",
"js":"",
"id":"noryo2013_timetable",
"class":"",
"body":"<style>\r
#noryo2013_timetable {\r
  padding-top: 80px;\r
}\r
#noryo2013_timetable table.timetable{\r
  margin: 0 auto;\r
  opacity: 0.9;\r
  background-color: #f6f4cd;\r
  color : #885500;\r
  text-align: left;\r
  border: 2px solid #402817;\r
  border-radius: 8px;\r
  line-height: 1.2em;\r
  border-spacing: 0;\r
}\r
#noryo2013_timetable table.timetable th {\r
  padding : 5px;\r
  border-bottom: 2px solid #402817;\r
  background-color: #402817;\r
  color: #FFFFFF;\r
  text-align: center;\r
}\r
#noryo2013_timetable table.timetable th.place,\r
#noryo2013_timetable table.timetable input[name=\"place\"]{\r
  width: 80px;\r
}\r
#noryo2013_timetable table.timetable th.start,\r
#noryo2013_timetable table.timetable th.end,\r
#noryo2013_timetable table.timetable input[name=\"start\"],\r
#noryo2013_timetable table.timetable input[name=\"end\"] {\r
  width: 40px;\r
}\r
#noryo2013_timetable table.timetable th.title,\r
#noryo2013_timetable table.timetable input[name=\"title\"] {\r
  width: 250px;\r
}\r
#noryo2013_timetable table.timetable th.incharge,\r
#noryo2013_timetable table.timetable input[name=\"incharge\"] {\r
  width: 60px;\r
}\r
#noryo2013_timetable table.timetable tr.view td {\r
  border-bottom: 1px dashed  #402817;\r
  padding: 2px 0 4px 0;\r
  cursor : pointer;\r
}\r
#noryo2013_timetable table.timetable tr.view td.title{\r
  font-weight:600;\r
}\r
#noryo2013_timetable table.timetable tr.form {\r
  display:none;\r
}\r
#noryo2013_timetable table.timetable tr.form td.key{\r
  font-weight:600;\r
}\r
\r
#noryo2013_timetable table.timetable textarea[name=\"overview\"] {\r
  width: 600px;\r
  height: 50px;\r
}\r
#noryo2013_timetable table.timetable textarea[name=\"origin\"] {\r
  width: 600px;\r
  height:400px;\r
}\r
#noryo2013_timetable table.timetable input[name=\"types\"] {\r
  width: 600px;\r
}\r
#noryo2013_timetable table.timetable input[name=\"targets\"] {\r
  width: 600px;\r
}\r
</style>\r
\r
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
 <tr class=\"view\">\r
 <td class=\"place\"   ></td>\r
 <td class=\"start\"   ></td>\r
 <td class=\"end\"     ></td>\r
 <td class=\"title\"   >*new*</td>\r
 <td class=\"incharge\"></td>\r
 <td class=\"overview\"></td>\r
 </tr>\r
 <tr class=\"form\">\r
  <td colspan=\"6\">\r
<div>\r
  <form method=\"POST\" action=\"<?cs var:C._base ?>/noryo2013/timetable/edit\">\r
   <input type=\"submit\" name=\"op\" value=\"save\"></input>\r
   <input type=\"submit\" name=\"op\" value=\"remove\"></input>\r
   <input type=\"hidden\" name=\"_u\" value=\"new\"></input>\r
<table>\r
<tbody>\r
<tr class=\"place\"   ><td class=\"key\">\u4f1a\u5834</td>   <td><input type=\"text\" name=\"place\" value=\"\"></input></td></tr>\r
<tr class=\"start\"   ><td class=\"key\">\u958b\u59cb\u6642\u523b</td><td><input type=\"text\" name=\"start\" value=\"\"></input></td></tr>\r
<tr class=\"end\"     ><td class=\"key\">\u7d42\u4e86\u6642\u523b</td><td><input type=\"text\" name=\"end\"   value=\"\"></input></td></tr>\r
<tr class=\"title\"   ><td class=\"key\">\u30bf\u30a4\u30c8\u30eb</td><td><input type=\"text\" name=\"title\" value=\"*new*\"></input></td></tr>\r
<tr class=\"incharge\"><td class=\"key\">\u8b1b\u6f14\u8005</td>  <td><input type=\"text\" name=\"incharge\" value=\"\"></input></td></tr>\r
<tr class=\"overview\"><td class=\"key\">\u6982\u8981</td>   <td><textarea name=\"overview\" ></textarea></td></tr>\r
<tr class=\"targets\" ><td class=\"key\">\u5bfe\u8c61</td>   <td><input type=\"text\" name=\"targets\" value=\"\"></input></td></tr>\r
<tr class=\"types\" >  <td class=\"key\">\u5f62\u5f0f</td>   <td><input type=\"text\" name=\"types\" value=\"\"></input></td></tr>\r
<tr class=\"origin\"  ><td class=\"key\">\u5185\u5bb9</td>   <td><textarea name=\"origin\" ></textarea></td></tr>\r
</tbody>\r
</table>\r
  </form>\r
</div>\r
  </td>\r
 </tr>\r
<?cs each: item = A.mongo.timeboxs.raw ?>\r
 <tr class=\"view\">\r
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
  <form method=\"POST\" action=\"<?cs var:C._base ?>/noryo2013/timetable/edit\">\r
   <input type=\"submit\" name=\"op\" value=\"save\"></input>\r
   <input type=\"submit\" name=\"op\" value=\"remove\"></input>\r
   <input type=\"hidden\" name=\"_u\" value=\"<?cs var:item._u ?>\"></input>\r
<table>\r
<tbody>\r
<tr class=\"place\"   ><td class=\"key\">\u4f1a\u5834</td>   <td><input type=\"text\" name=\"place\" value=\"<?cs var:item.place ?>\"></input></td></tr>\r
<tr class=\"start\"   ><td class=\"key\">\u958b\u59cb\u6642\u523b</td><td><input type=\"text\" name=\"start\" value=\"<?cs var:item.start ?>\"></input></td></tr>\r
<tr class=\"end\"     ><td class=\"key\">\u7d42\u4e86\u6642\u523b</td><td><input type=\"text\" name=\"end\"   value=\"<?cs var:item.end ?>\"  ></input></td></tr>\r
<tr class=\"title\"   ><td class=\"key\">\u30bf\u30a4\u30c8\u30eb</td><td><input type=\"text\" name=\"title\" value=\"<?cs var:item.title ?>\"></input></td></tr>\r
<tr class=\"incharge\"><td class=\"key\">\u8b1b\u6f14\u8005</td>  <td><input type=\"text\" name=\"incharge\" value=\"<?cs var:item.incharge ?>\"></input></td></tr>\r
<tr class=\"overview\"><td class=\"key\">\u6982\u8981</td>   <td><textarea name=\"overview\" ><?cs var:item.overview ?></textarea></td></tr>\r
<tr class=\"targets\" ><td class=\"key\">\u5bfe\u8c61</td>   <td><input type=\"text\" name=\"targets\" value=\"<?cs each: target = item.targets ?><?cs var:target ?><?cs if:!last(target) ?>, <?cs /if ?><?cs /each ?>\"></input></td></tr>\r
<tr class=\"types\" >  <td class=\"key\">\u5f62\u5f0f</td>   <td><input type=\"text\" name=\"types\" value=\"<?cs each: type = item.types ?><?cs var:type ?><?cs if:!last(type) ?>,<?cs /if ?><?cs /each ?>\"></input></td></tr>\r
<tr class=\"origin\"  ><td class=\"key\">\u5185\u5bb9</td>   <td><textarea name=\"origin\" ><?cs var:item.origin ?></textarea></td></tr>\r
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
<script src=\"/_s_/core/default/js/jquery-1.7.2.min.js\"></script>\r
<script>\r
$(function(){\r
  $('#noryo2013_timetable table.timetable tr.view').click(function(){\r
   $(this).next().slideToggle();\r
  });\r
});\r
</script>\r
\r
\r
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