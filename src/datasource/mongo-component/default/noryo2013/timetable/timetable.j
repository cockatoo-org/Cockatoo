{
"@R":"1369111638",
"type":"HorizontalWidget",
"subject":"noryo2013_timetable",
"description":"",
"css":"\r
",
"js":"$(function(){\r
  draw_timetable(null,function(session){return 'div.noryo2013_timetable div.tab table td.' + session.place + '> div.t'+session.start.replace(/:/,'')});\r
});",
"id":"",
"class":"noryo2013_timetable",
"body":"<div class=\"tab\">\r
<table class=\"timetable\">\r
<tbody>\r
<tr>\r
<th class=\"timebox\"></th>\r
<th class=\"place place1\">\u4f1a\u5834\uff11</th>\r
<th class=\"place place2\">\u4f1a\u5834\uff12</th>\r
<th class=\"place place3\">\u4f1a\u5834\uff13</th>\r
</tr>\r
<tr class=\"\">\r
<td class=\"timebox\">\r
<div class=\"t t00 t1200\">12:00</div>\r
<div class=\"t t15 t1215\"></div>\r
<div class=\"t t30 t1230\"></div>\r
<div class=\"t t45 t1245\"></div>\r
<div class=\"t t00 t1300\">13:00</div>\r
<div class=\"t t15 t1315\"></div>\r
<div class=\"t t30 t1330\"></div>\r
<div class=\"t t45 t1345\"></div>\r
<div class=\"t t00 t1400\">14:00</div>\r
<div class=\"t t15 t1415\"></div>\r
<div class=\"t t30 t1430\"></div>\r
<div class=\"t t45 t1445\"></div>\r
<div class=\"t t00 t1500\">15:00</div>\r
<div class=\"t t15 t1515\"></div>\r
<div class=\"t t30 t1530\"></div>\r
<div class=\"t t45 t1545\"></div>\r
<div class=\"t t00 t1600\">16:00</div>\r
<div class=\"t t15 t1615\"></div>\r
<div class=\"t t30 t1630\"></div>\r
<div class=\"t t45 t1645\"></div>\r
<div class=\"t t00 t1700\">17:00</div>\r
<div class=\"t t15 t1715\"></div>\r
<div class=\"t t30 t1730\"></div>\r
<div class=\"t t45 t1745\"></div>\r
</td>\r
<td class=\"place place1\">\r
<div class=\"t t00 t1200\"></div>\r
<div class=\"t t15 t1215\"></div>\r
<div class=\"t t30 t1230\"></div>\r
<div class=\"t t45 t1245\"></div>\r
<div class=\"t t00 t1300\"></div>\r
<div class=\"t t15 t1315\"></div>\r
<div class=\"t t30 t1330\"></div>\r
<div class=\"t t45 t1345\"></div>\r
<div class=\"t t00 t1400\"></div>\r
<div class=\"t t15 t1415\"></div>\r
<div class=\"t t30 t1430\"></div>\r
<div class=\"t t45 t1445\"></div>\r
<div class=\"t t00 t1500\"></div>\r
<div class=\"t t15 t1515\"></div>\r
<div class=\"t t30 t1530\"></div>\r
<div class=\"t t45 t1545\"></div>\r
<div class=\"t t00 t1600\"></div>\r
<div class=\"t t15 t1615\"></div>\r
<div class=\"t t30 t1630\"></div>\r
<div class=\"t t45 t1645\"></div>\r
<div class=\"t t00 t1700\"></div>\r
<div class=\"t t15 t1715\"></div>\r
<div class=\"t t30 t1730\"></div>\r
<div class=\"t t45 t1745\"></div>\r
</td>\r
<td class=\"place place2\">\r
<div class=\"t t00 t1200\"></div>\r
<div class=\"t t15 t1215\"></div>\r
<div class=\"t t30 t1230\"></div>\r
<div class=\"t t45 t1245\"></div>\r
<div class=\"t t00 t1300\"></div>\r
<div class=\"t t15 t1315\"></div>\r
<div class=\"t t30 t1330\"></div>\r
<div class=\"t t45 t1345\"></div>\r
<div class=\"t t00 t1400\"></div>\r
<div class=\"t t15 t1415\"></div>\r
<div class=\"t t30 t1430\"></div>\r
<div class=\"t t45 t1445\"></div>\r
<div class=\"t t00 t1500\"></div>\r
<div class=\"t t15 t1515\"></div>\r
<div class=\"t t30 t1530\"></div>\r
<div class=\"t t45 t1545\"></div>\r
<div class=\"t t00 t1600\"></div>\r
<div class=\"t t15 t1615\"></div>\r
<div class=\"t t30 t1630\"></div>\r
<div class=\"t t45 t1645\"></div>\r
<div class=\"t t00 t1700\"></div>\r
<div class=\"t t15 t1715\"></div>\r
<div class=\"t t30 t1730\"></div>\r
<div class=\"t t45 t1745\"></div>\r
</td>\r
<td class=\"place place3\">\r
<div class=\"t t00 t1200\"></div>\r
<div class=\"t t15 t1215\"></div>\r
<div class=\"t t30 t1230\"></div>\r
<div class=\"t t45 t1245\"></div>\r
<div class=\"t t00 t1300\"></div>\r
<div class=\"t t15 t1315\"></div>\r
<div class=\"t t30 t1330\"></div>\r
<div class=\"t t45 t1345\"></div>\r
<div class=\"t t00 t1400\"></div>\r
<div class=\"t t15 t1415\"></div>\r
<div class=\"t t30 t1430\"></div>\r
<div class=\"t t45 t1445\"></div>\r
<div class=\"t t00 t1500\"></div>\r
<div class=\"t t15 t1515\"></div>\r
<div class=\"t t30 t1530\"></div>\r
<div class=\"t t45 t1545\"></div>\r
<div class=\"t t00 t1600\"></div>\r
<div class=\"t t15 t1615\"></div>\r
<div class=\"t t30 t1630\"></div>\r
<div class=\"t t45 t1645\"></div>\r
<div class=\"t t00 t1700\"></div>\r
<div class=\"t t15 t1715\"></div>\r
<div class=\"t t30 t1730\"></div>\r
<div class=\"t t45 t1745\"></div>\r
</td>\r
</tr>\r
</tbody>\r
</table>\r
</div>\r
<?cs call:draw_details(A.mongo.timeboxs.raw) ?>\r
<div class=\"details\">\r
<?cs each: item = A.mongo.timeboxs.raw ?>\r
 <div class=\"detail\" u=\"<?cs var:item._u ?>\">\r
 <div class=\"close\" style=\"border:1px solid #000000;background-color:#000000;float:right;width:20px;height:20px;\"></div>\r
 <?cs if:item.images.logo ?>\r
   <img class=\"logo\" src=\"/_s_/mongo/timetable/<?cs var:item.images.logo ?>\"></img><br>\r
  <?cs /if ?>\r
{\r
  <table>\r
  <tbody>\r
   <tr>\r
    <td class=\"key\">\"\u30bf\u30a4\u30c8\u30eb\"</td><td class=\"sep\">:</td><td class=\"value title\">\"<?cs var:item.title ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u767a\u8868\u8005\"</td>\u3000<td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.incharge ?>\"</td>   <td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u958b\u59cb\u6642\u523b\"</td><td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.start ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u7d42\u4e86\u6642\u523b\"</td><td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.end ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u6982\u8981\"</td>\u3000\u3000<td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.overview ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u30bf\u30a4\u30d7\"</td>\u3000<td class=\"sep\">:</td><td class=\"value\">[<?cs each: type = item.types ?>\"<?cs var:type ?>\"<?cs if:!last(type) ?>,<?cs /if ?><?cs /each ?>]</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u5bfe\u8c61\"</td>\u3000\u3000<td class=\"sep\">:</td><td class=\"value\">[<?cs each: target = item.targets ?>\"<?cs var:target ?>\"<?cs if:!last(target) ?>, <?cs /if ?><?cs /each ?>]</td><td class=\"comma\">,</td>\r
   </tr>\r
 </tbody>\r
 </table>\r
}\r
<hr class=\"sep\" />\r
<div class=\"page\">\r
<?cs each: content = item.contents ?>\r
 <?cs call:drawTags(content)?>\r
<?cs /each ?>\r
</div>\r
</div>\r
<?cs /each ?>\r
</div>\r
<script>\r
  var timetable = <?cs var:A.mongo.timeboxs.@json ?>;\r
</script>\r
",
"action":[
"action://mongo-action/mongo/TimetableAction?getA"
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/mongo/default/css/noryo2013_timetable.css\"></link>",
"bottom":"",
"_u":"noryo2013/timetable/timetable"
}