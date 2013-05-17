{
"@R":"1368785871",
"type":"HorizontalWidget",
"subject":"noryo2013_timetable",
"description":"",
"css":"#noryo2013_timetable div.edit {\r
  float: right;\r
}\r
#noryo2013_timetable div.edit a{\r
  color:#ffffff;\r
}\r
\r
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
}\r
#noryo2013_timetable table.timetable th.timebox{\r
  width: 80px;\r
}\r
#noryo2013_timetable table.timetable th.place {\r
  width: 299px;\r
  padding : 0 0 0 1px;\r
  border-left: 2px solid  #402817;\r
  border-bottom: 1px dashed  #402817;\r
  text-align: center;\r
}\r
\r
#noryo2013_timetable table.timetable td {\r
}\r
#noryo2013_timetable table.timetable td.timebox {\r
  height: 98px;\r
  vertical-align:10px;\r
  text-decoration: underline;\r
  font-weight:600;\r
  border-bottom: 1px dashed  #402817;\r
}\r
#noryo2013_timetable table.timetable td div.t.t00 {\r
  border-top: 1px solid  #402817;\r
}\r
#noryo2013_timetable table.timetable td div.t.t30 {\r
  border-top: 1px dashed  #402817;\r
}\r
#noryo2013_timetable table.timetable td div.t {\r
  height:30px;\r
}\r
\r
#noryo2013_timetable table.timetable td.place {\r
  border-left: 2px solid  #402817;\r
  border-bottom: 1px dashed  #402817;\r
}\r
\r
#noryo2013_timetable table.timetable div.session{\r
  width:296px;\r
  border-radius: 8px;\r
  background-color: #fFf8e0;\r
  border: 1px solid  #402817;\r
  opacity: 0.9;\r
  float: left;\r
  overflow:hidden;\r
/*  z-index: 9999; */\r
}\r
#noryo2013_timetable table.timetable div.session a{\r
}\r
\r
#noryo2013_timetable table.timetable div.session:hover {\r
  cursor: pointer;\r
  background-color: #cc5500;\r
  color: #FFFFFF;\r
}\r
#noryo2013_timetable table.timetable div.session:hover a {\r
  color: #FFFFFF;\r
}\r
\r
\r
#noryo2013_timetable table.timetable div.session div.title{\r
  font-weight: 600;\r
  text-decoration: underline;\r
}\r
#noryo2013_timetable table.timetable div.session div.incharge{\r
  float:right;\r
  text-decoration: underline;\r
}\r
#noryo2013_timetable table.timetable div.session div.overview{\r
  float:left;\r
  margin-left:10px;\r
}\r
\r
#noryo2013_timetable div.details {\r
  display:none;\r
}\r
#noryo2013_timetable div.detail {\r
  padding: 5px;\r
  font-size: 1.5em;\r
  line-height: 1.5em;\r
  background-color: #f6f4cd;\r
  border-radius: 16px;\r
  border: 2px solid  #402817;\r
  display:none;\r
}\r
#noryo2013_timetable div.detail td.key {\r
  font-weight:600;\r
  color: #cc5500;\r
  width: 120px;\r
  vertical-align:top;\r
}\r
#noryo2013_timetable div.detail td.sep {\r
  font-weight:600;\r
  vertical-align:top;\r
}\r
#noryo2013_timetable div.detail td.comma {\r
  font-weight:600;\r
  vertical-align: bottom;\r
}\r
#noryo2013_timetable div.detail td.title {\r
  font-weight:600;\r
  font-size: 1.5em;\r
}\r
",
"js":"$(function(){\r
  function time2date(str){\r
    var t = str.split(':');\r
    var d = new Date(0,0,0,0,0,0);\r
    d.setHours(t[0]);\r
    d.setMinutes(t[1]);\r
    return d;\r
  }\r
  for ( var i in timetable ) {\r
    var session = timetable[i];\r
    if ( ! session.start || ! session.end ) {\r
      continue;\r
    }\r
    var start = time2date(session.start);\r
    var end   = time2date(session.end);\r
    session.during = (end.getTime() - start.getTime()) / 60000;\r
    //session.place = 'place1';\r
      $('<div class=\"session\" u=\"'+session._u+'\">'+\r
\t'<div class=\"title\">'+session.title+'</div>'+\r
\t'<div class=\"incharge\">'+session.incharge+\"</div>\"+\r
\t'<div class=\"overview\">'+session.overview+'</div>'+\r
\t'</div>')\r
      .css('height',(session.during*2) + 'px')\r
      .appendTo('#noryo2013_timetable td.' + session.place + '> div.t'+session.start.replace(/:/,''));\r
  }\r
  $('#noryo2013_timetable div.session').click(function(){\r
    var u = $(this).attr('u');\r
      $('<div id=\"mordal\"></div>')\r
      .css('position','absolute')\r
      .css('top',0)\r
      .css('left',0)\r
      .css('height','10000')\r
      .css('width','10000')\r
      .css('z-index',1000)\r
      .css('background-color','#000000')\r
      .css('opacity',0.3)\r
      .appendTo('body');\r
\r
      $('div.detail[u=\"'+u+'\"]').clone()\r
      .attr('id','detail')\r
      .css('position','absolute')\r
      .css('z-index',9999)\r
      .css('top','150px')\r
      .css('left',0)\r
      .appendTo('#noryo2013_timetable').slideDown();\r
\r
      $('#mordal').click(function(){\r
\t$(this).remove();\r
\t$('#detail').slideUp('normal',function(){$(this).remove();});\r
      });\r
      $('#detail > div.close').click(function(){\r
\t  $(this).parent().slideUp('normal',function(){$(this).remove();});\r
\t$('#mordal').remove();\r
      });\r
  });\r
});",
"id":"noryo2013_timetable",
"class":"mongo",
"body":"<table class=\"timetable\">\r
<tbody>\r
<tr>\r
<th class=\"timebox\">Time box</th>\r
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
<div class=\"details\">\r
<?cs each: item = A.mongo.timeboxs.raw ?>\r
 <div class=\"detail\" u=\"<?cs var:item._u ?>\">\r
 <div class=\"close\" style=\"border:5px solid #000000;float:right;width:20px;height:20px;\"></div>\r
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
</div>\r
<?cs /each ?>\r
</div>\r
<script>\r
  var timetable = <?cs var:A.mongo.timeboxs.@json ?>;\r
</script>\r
\r
<?cs if:S.login.writable ?>\r
  <div class=\"edit\"><a href=\"<?cs var:C._base ?>/noryo2013/timetable/edit\">\u7de8\u96c6</a></div>\r
<?cs /if ?>\r
",
"action":[
"action://mongo-action/mongo/TimetableAction?getA"
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/mongo/default/css/noryo2013_timetable.css\"></link>",
"bottom":"",
"_u":"noryo2013/timetable/timetable"
}