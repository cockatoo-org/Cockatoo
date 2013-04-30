{
"@R":"1367317568",
"type":"HorizontalWidget",
"subject":"noryo1_timetable",
"description":"",
"css":"#noryo1_timetable div.mongo{\r
  margin: 0 auto;\r
  width: 960px;\r
  height: 1000px;\r
  background-image: url(\"/_s_/mongo/sp/noryo1/top.png\");\r
  background-origin: padding-box;\r
  background-position: center center;\r
  background-repeat: no-repeat;\r
  background-size: contain;\r
  padding: 100px 0 0 0;\r
}\r
#noryo1_timetable div.mongo table{\r
  margin: 0 auto;\r
  position: static;\r
  opacity: 0.9;\r
  background-color: #FFf0dd;\r
  color : #885500;\r
  text-align: left;\r
  border: 2px solid #bb6611;\r
  border-radius: 8px;\r
}\r
#noryo1_timetable div.mongo table a {\r
  color : #885500;  \r
  text-decoration: none;\r
}\r
\r
#noryo1_timetable div.mongo table th {\r
  padding : 5px;\r
  border-bottom: 2px solid #bb6611;\r
}\r
#noryo1_timetable div.mongo table th.timebox{\r
  width: 200px;\r
}\r
#noryo1_timetable div.mongo table th.event{\r
  width: 400px;\r
}\r
#noryo1_timetable div.mongo table th.good{\r
  width: 100px;\r
}\r
#noryo1_timetable div.mongo table td {\r
  padding : 5px;\r
}\r
#noryo1_timetable div.mongo table td:hover {\r
  background-color: #bb6611;\r
  color: #FFFFFF;\r
}\r
#noryo1_timetable div.mongo table td:hover a {\r
  color: #FFFFFF;\r
  text-decoration: underline;\r
}\r
\r
#noryo1_timetable div.mongo table td.event {\r
  \r
}\r
#noryo1_timetable div.mongo table td.event > h3 {\r
  padding: 0;\r
  margin: 0;\r
}\r
#noryo1_timetable div.mongo table td.event > div {\r
  margin-top: 5px;\r
  margin-left: 15px;\r
}\r
\r
#noryo1_timetable div.mongo table td.good {\r
  text-align: center;\r
}\r
#noryo1_timetable div.mongo table td.good input[type=\"button\"] {\r
  border-radius : 8px;\r
  background-color: #442200;\r
  color : #ffffff;\r
  font-weight:600;\r
}\r
",
"js":"",
"id":"noryo1_timetable",
"class":"",
"body":"<div class=\"mongo\">\r
<div class=\"window\" style=\"width:100%;clear:both;\">\r
<div class=\"hd1\">\r
<div class=\"h2\">\r
  <h2></h2>\r
</div>\r
<div class=\"hd2\">\r
\r
<table>\r
<tbody>\r
<tr>\r
<th class=\"timebox\">\u30bf\u30a4\u30e0\u30dc\u30c3\u30af\u30b9</th>\r
<th class=\"event\">\u30b3\u30f3\u30c6\u30f3\u30c4</th>\r
<th class=\"good\">\u307f\u3093\u306a\u306e\u8a55\u4fa1</th>\r
</tr>\r
<?cs each: item = A.mongo.timeboxs ?>\r
  <tr class=\"<?cs if:!item.public ?>private<?cs /if?>\">\r
    <td class=\"timebox\"><time><?cs var:item.start ?></time> \uff5e <time><?cs var:item.end ?></time></td>\r
    <td class=\"event\"><h3><a href=\"<?cs var:C._base ?>/noryo1/timetable/<?cs var:item.docid ?>\"><?cs var:item.title ?></a></h3><div><?cs var:item.summary ?></div></td>\r
    <td class=\"good\"><div class=\"point\"><?cs var:item.point ?></div><input type=\"button\" value=\"\u826f\u304b\u3063\u305f\uff01\"></input></td>\r
  </tr>\r
<?cs /each ?>\r
</tbody>\r
</table>\r
</div>\r
</div>\r
</div>\r
</div>\r
",
"action":[
"action://mongo-action/mongo/TimetableAction?getA"
],
"header":"",
"bottom":"",
"_u":"noryo1/timetable/timetable"
}