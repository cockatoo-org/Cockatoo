{
"@R":"1367896405",
"type":"HorizontalWidget",
"subject":"noryo2013_timetable",
"description":"",
"css":"",
"js":"",
"id":"noryo2013_timetable",
"class":"mongo",
"body":"<table>\r
<tbody>\r
<tr>\r
<th class=\"timebox\">Time box</th>\r
<th class=\"event\" colspan=\"2\">\u30b3\u30f3\u30c6\u30f3\u30c4</th>\r
<th class=\"good\">\u307f\u3093\u306a\u306e\u8a55\u4fa1</th>\r
</tr>\r
<?cs each: item = A.mongo.timeboxs ?>\r
  <tr class=\"session <?cs if:!item.public ?>private<?cs /if?>\">\r
    <td class=\"timebox\"><time><?cs var:item.start ?></time> \u301c <time><?cs var:item.end ?></time></td>\r
    <td class=\"field\"><div><a href=\"<?cs var:C._base ?>/noryo2013/timetable/<?cs var:item._u ?>\"><pre>{\r
  <span class=\"field title\">title</span>\r
  <span class=\"field\">overview</span>\r
  <span class=\"field\">types</span>\r
  <span class=\"field\">targets</span>\r
  <span class=\"field\">in-charge</span>\r
}</pre></a><div></td>\r
    <td class=\"value\"><div><a href=\"<?cs var:C._base ?>/noryo2013/timetable/<?cs var:item._u ?>\"><pre>\r
: <span class=\"value title\">\"<?cs var:item.title ?>\"</span>,\r
: <span class=\"value\">\"<?cs var:item.overview ?>\"</span>,\r
: [ <span class=\"value\"><?cs each: type = item.types ?>\"<?cs var:type ?>\"<?cs if:!last(type) ?>,<?cs /if ?><?cs /each ?></span> ],\r
: [ <span class=\"value\"><?cs each: target = item.targets ?>\"<?cs var:target ?>\"<?cs if:!last(target) ?>, <?cs /if ?><?cs /each ?></span> ],\r
: <span class=\"value\">\"<?cs var:item.incharge ?>\"</span>\r
</pre></a></div></td>\r
    <td class=\"good\"><div class=\"point\"><?cs var:item.point ?></div><input type=\"button\" value=\"\u826f\u304b\u3063\u305f\uff01\"></input></td>\r
  </tr>\r
<?cs /each ?>\r
</tbody>\r
</table>\r
",
"action":[
"action://mongo-action/mongo/TimetableAction?getA"
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/mongo/default/css/noryo2013_timetable.css\"></link>",
"bottom":"",
"_u":"noryo2013/timetable/timetable"
}