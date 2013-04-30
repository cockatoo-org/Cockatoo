{
"@R":"1367317575",
"type":"HorizontalWidget",
"subject":"timetablepage",
"description":"",
"css":"#timetablepage div.edit {\r
  float: right;\r
  font-size: 0.7em;\r
}\r
\r
#timetablepage div.mongo{\r
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
#timetablepage div.mongo div.window {\r
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
"class":"page",
"body":"<div class=\"mongo\">\r
 <div class=\"window\" style=\"width:100%;clear:both;\">\r
  <div class=\"h1\"><h1><?cs var:A.mongo.timebox.title?></h1></div>\r
   <div class=\"hd1\">\r
    <div class=\"hd2\">\r
     <div class=\"hd3\">\r
     <?cs var:A.mongo.timebox.summary?>\r
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
  <div class=\"edit\"><a href=\"<?cs var:C._base ?>/noryo1/timetable/edit/<?cs var:A.mongo.timebox.docid ?>\">\u7de8\u96c6</a></div>\r
<?cs /if ?>\r
</div>\r
",
"action":[
""
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/mongo/default/css/page.css\" />",
"bottom":"",
"_u":"noryo1/timetable/timetablepage"
}