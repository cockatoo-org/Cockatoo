{
"@R":"1364794093",
"type":"HorizontalWidget",
"subject":"tippage",
"description":"",
"css":"#tippage div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}\r\n",
"js":"",
"id":"tippage",
"class":"page",
"body":"<div class=\"mongo\">\r\n  <div class=\"window\" style=\"width:100%;clear:both;\">\r\n  <div class=\"credit\">by <?cs var:A.mongo.tip._ownername ?> <time><?cs var:A.mongo.tip._timestr ?><\/time><\/div>\r\n    <div class=\"h1\"><h1><?cs var:A.mongo.tip.title?><\/h1><\/div>\r\n    <div class=\"hd1\">\r\n      <div class=\"hd2\">\r\n\t<?cs each:item = A.mongo.tip.contents ?>\r\n\t  <?cs call:drawTags(item)?>\r\n\t<?cs \/each ?>\r\n\t<br clear=\"both\">\r\n      <\/div>\r\n    <\/div>\r\n  <\/div>\r\n<?cs if:A.mongo.tip.writable ?>\r\n  <div class=\"edit\"><a href=\"<?cs var:C._base ?>\/tips\/edit\/<?cs var:A.mongo.tip._u ?>\">\u7de8\u96c6<\/a><\/div>\r\n<?cs \/if ?>\r\n<\/div>\r\n",
"action":[
""
],
"header":"",
"bottom":"<script src=\"https:\/\/maps.googleapis.com\/maps\/api\/js?v=3.exp&sensor=false&libraries=places\"><\/script>",
"_u":"tips\/tippage"
}