{
"@R":"1369189943",
"type":"HorizontalWidget",
"subject":"noryo2013_timetable",
"description":"",
"css":"\r
div.noryo2013_timetable div.tab table.timetable td div.t.t00 {\r
  border-top: 1px solid  #402817;\r
}\r
div.noryo2013_timetable div.tab table.timetable td div.t.t30 {\r
  border-top: 1px dashed  #402817;\r
}\r
div.noryo2013_timetable div.tab table.timetable td div.t {\r
  height:30px;\r
}",
"js":"$(function(){\r
  draw_timetable(null,function(session){return 'div.noryo2013_timetable div.tab table td.' + session.place + '> div.t'+session.start.replace(/:/,'')});\r
});",
"id":"",
"class":"noryo2013_timetable",
"body":"<div class=\"tab\">\r
<table class=\"timetable\">\r
<tbody>\r
<tr>\r
<th class=\"first\"></th>\r
<th class=\"cols place1\">\u30e1\u30a4\u30f3\u30db\u30fc\u30eb</th>\r
<th class=\"cols place2\">\u30bb\u30df\u30ca\u30fc\u30eb\u30fc\u30e0</th>\r
<th class=\"cols place3\">\u30ed\u30d3\u30fc</th>\r
</tr>\r
<tr class=\"\">\r
<td class=\"first\">\r
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
<td class=\"cols place1\">\r
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
<td class=\"cols place2\">\r
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
<td class=\"cols place3\">\r
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
\r
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