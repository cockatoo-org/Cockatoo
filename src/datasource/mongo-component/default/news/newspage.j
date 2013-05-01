{
"@R":"1367313890",
"type":"HorizontalWidget",
"subject":"newspage",
"description":"",
"css":"#newspage div.edit {\r
  float: right;\r
  font-size: 0.7em;\r
}\r
",
"js":"",
"id":"newspage",
"class":"page",
"body":"<div class=\"mongo\">\r
  <div class=\"window\" style=\"width:100%;clear:both;\">\r
  <div class=\"credit\">by <?cs var:A.mongo.news._ownername ?> <time><?cs var:A.mongo.news._timestr ?></time></div>\r
    <div class=\"h1\"><h1><?cs var:A.mongo.news.title?></h1></div>\r
    <div class=\"hd1\">\r
      <div class=\"hd2\">\r
\t<?cs each:item = A.mongo.news.contents ?>\r
\t  <?cs call:drawTags(item)?>\r
\t<?cs /each ?>\r
\t<br clear=\"both\">\r
      </div>\r
    </div>\r
  </div>\r
<?cs if:A.mongo.news.writable ?>\r
  <div class=\"edit\"><a href=\"<?cs var:C._base ?>/news/edit/<?cs var:A.mongo.news._u ?>\">\u7de8\u96c6</a></div>\r
<?cs /if ?>\r
</div>\r
",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"news/newspage"
}