{
"@R":"1367388074",
"type":"HorizontalWidget",
"subject":"timetablepage",
"description":"",
"css":"#timetablepage div.edit {\r
  float: right;\r
  font-size: 0.7em;\r
}\r
\r
#timetablepage div.window {\r
  margin: 0 auto;\r
  position: static;\r
  opacity: 0.9;\r
  background-color: #FFf0dd;\r
  color : #885500;\r
  text-align: left;\r
  border: 2px solid #bb6611;\r
  border-radius: 8px;\r
  padding: 5px;\r
}\r
\r
",
"js":"",
"id":"timetablepage",
"class":"mongo",
"body":"<div id=\"noryo2013_timetable\">\r
<table>\r
<tbody>\r
<tr>\r
<th class=\"timebox\">Time box</th>\r
<th class=\"event\" colspan=\"2\">\u30b3\u30f3\u30c6\u30f3\u30c4</th>\r
<th class=\"good\">\u307f\u3093\u306a\u306e\u8a55\u4fa1</th>\r
</tr>\r
  <tr class=\"<?cs if:!A.mongo.timebox.public ?>private<?cs /if?>\">\r
    <td class=\"timebox\"><time><?cs var:A.mongo.timebox.start ?></time> \u301c <time><?cs var:A.mongo.timebox.end ?></time></td>\r
    <td class=\"field\"><div><a href=\"<?cs var:C._base ?>/noryo2013/timetable/<?cs var:A.mongo.timebox._u ?>\"><pre>{\r
  <span class=\"field title\">title</span>\r
  <span class=\"field\">overview</span>\r
  <span class=\"field\">types</span>\r
  <span class=\"field\">targets</span>\r
  <span class=\"field\">in-charge</span>\r
}</pre></a><div></td>\r
    <td class=\"value\"><div><a href=\"<?cs var:C._base ?>/noryo2013/timetable/<?cs var:A.mongo.timebox._u ?>\"><pre>\r
: <span class=\"value title\">\"<?cs var:A.mongo.timebox.title ?>\"</span>,\r
: <span class=\"value\">\"<?cs var:A.mongo.timebox.overview ?>\"</span>,\r
: [ <span class=\"value\"><?cs each: type = A.mongo.timebox.types ?>\"<?cs var:type ?>\"<?cs if:!last(type) ?>,<?cs /if ?><?cs /each ?></span> ],\r
: [ <span class=\"value\"><?cs each: target = A.mongo.timebox.targets ?>\"<?cs var:target ?>\"<?cs if:!last(target) ?>, <?cs /if ?><?cs /each ?></span> ],\r
: <span class=\"value\">\"<?cs var:A.mongo.timebox.incharge ?>\"</span>\r
</pre></a></div></td>\r
    <td class=\"good\"><div class=\"point\"><?cs var:A.mongo.timebox.point ?></div><input type=\"button\" value=\"\u826f\u304b\u3063\u305f\uff01\"></input></td>\r
  </tr>\r
</tbody>\r
</table>\r
</div>\r
<br>\r
<div class=\"page\">\r
 <div class=\"window\">\r
  <div class=\"h1\"><h1><?cs var:A.mongo.timebox.title?></h1></div>\r
   <div class=\"hd1\">\r
    <div class=\"hd2\">\r
     <div class=\"hd3\">\r
     </div>\r
    </div>\r
   </div>\r
   <?cs each:item = A.mongo.timebox.contents ?>\r
   <?cs call:drawTags(item)?>\r
   <?cs /each ?>\r
   <br clear=\"both\">\r
  </div>\r
 </div>\r
<?cs if:A.mongo.timebox.writable ?>\r
  <div class=\"edit\"><a href=\"<?cs var:C._base ?>/noryo2013/timetable/edit/<?cs var:A.mongo.timebox._u ?>\">\u7de8\u96c6</a></div>\r
<?cs /if ?>\r
",
"action":[
""
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/mongo/default/css/page.css\" />\r
<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/mongo/default/css/noryo2013_timetable.css\"></link>",
"bottom":"",
"_u":"noryo2013/timetable/timetablepage"
}