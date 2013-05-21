{
"@R":"1369113135",
"type":"HorizontalWidget",
"subject":"noryo2013_exhibition",
"description":"",
"css":"div.noryo2013_timetable div.tab div.session {\r
  margin: 5px 10px;",
"js":"$( function (){\r
  draw_timetable(180,function(s){ return 'div.noryo2013_timetable div.tab' } );\r
})\r
",
"id":"",
"class":"noryo2013_timetable",
"body":"<div class=\"tab\">\r
</div>\r
\r
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
"action://mongo-action/mongo/TimetableAction?getA&exhibition=1"
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/mongo/default/css/noryo2013_timetable.css\"></link>",
"bottom":"",
"_u":"noryo2013/timetable/exhibition"
}