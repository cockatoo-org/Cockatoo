{
"@R":"1367314402",
"type":"HorizontalWidget",
"subject":"news",
"description":"",
"css":"#news div.edit {\r
  float: right;\r
  font-size: 0.7em;\r
}\r
#news li.private,\r
#news li.private a {\r
  color: #999999;\r
}",
"js":"",
"id":"news",
"class":"page",
"body":"<div class=\"mongo\">\r
<div class=\"window\" style=\"width:100%;clear:both;\">\r
<div class=\"hd1\">\r
<div class=\"h2\">\r
  <h2>NEWS</h2>\r
</div>\r
<div class=\"hd2\">\r
  <ul>\r
  <?cs each: item = A.mongo.newss ?>\r
    <li class=\"<?cs if:!item.public ?>private<?cs /if?>\" ><a href=\"<?cs var:C._base ?>/news/<?cs var:item._u ?>\"><?cs var:item.title ?></a> (by <?cs var:item._ownername ?> <time><?cs var:item._timestr ?></time>)</li>\r
  <?cs /each ?>\r
  </ul>\r
</div>\r
</div>\r
</div>\r
</div>\r
<?cs if:S.login.writable?>\r
  <div class=\"edit\"><a href=\"<?cs var:C._base ?>/news/edit/news\">\u65b0\u898fNEWS</a></div>\r
<?cs /if ?>\r
",
"action":[
"action://mongo-action/mongo/NewsAction?getA"
],
"header":"",
"bottom":"",
"_u":"news/news"
}