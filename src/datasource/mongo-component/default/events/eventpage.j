{
"@R":"1364449322",
"type":"HorizontalWidget",
"subject":"eventpage",
"description":"",
"css":"#eventpage div.edit {\r\n  float: right;\r\n  font-size: 0.7em;\r\n}\r\n#eventpage table.details {\r\n  padding: 15px 0 0 10px;\r\n  min-width: 600px;\r\n  float:left;\r\n}\r\n#eventpage table.details th {\r\n  min-width: 100px;\r\n}\r\n#map {\r\n  float:left;\r\n  height: 200px;\r\n  width: 200px;\r\n  border: 1px solid #333;\r\n  margin-top: 0.6em;\r\n}",
"js":"$(function(){\r\n  var map;\r\n  var infowindow;\r\n  var pyrmont;\r\n  function initialize() {\r\n    var addr = $('#addr').text();\r\n    var geocoder = new google.maps.Geocoder();\r\n    geocoder.geocode({ address: addr,  region:'jp' },function(results,status){\r\n      if (status == google.maps.GeocoderStatus.OK) {\r\n\tvar latlng = results[0].geometry.location;\r\n\tvar lng = latlng.lng();\r\n\tvar lat = latlng.lat();\r\n\tpyrmont = latlng;\r\n\tmap = new google.maps.Map(document.getElementById('map'), {\r\n\t  mapTypeId: google.maps.MapTypeId.ROADMAP,\r\n\t  center: pyrmont,\r\n\t  zoom: 17\r\n\t});\r\n\tvar marker = new google.maps.Marker({\r\n\t  map: map,\r\n\t  position: pyrmont\r\n\t});\r\n      }\r\n    });\t\r\n  }\r\n  initialize();\r\n});",
"id":"eventpage",
"class":"page",
"body":"<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"credit\">by <?cs var:A.mongo.event._ownername ?> <time><?cs var:A.mongo.event._timestr ?><\/time><\/div>\r\n<div class=\"h1\"><h1><?cs var:A.mongo.event.title?><\/h1><\/div>\r\n<div class=\"hd1\">\r\n<div class=\"hd2\">\r\n<div class=\"hd3\"><?cs var:A.mongo.event.subtitle?><\/div>\r\n<\/div>\r\n<table class=\"details\"><tbody>\r\n<tr>\r\n<th>\u65e5\u6642<\/th>\r\n<td><?cs var:A.mongo.event.date?> <?cs var:A.mongo.event.time?><\/td>\r\n<\/tr><tr>\r\n<th>\u4f1a\u5834<\/th>\r\n<td id=\"addr\"><?cs var:A.mongo.event.address?><\/td>\r\n<\/tr><tr>\r\n<th>URL<\/th>\r\n<td><a href=\"<?cs var:A.mongo.event.url ?>\"><?cs var:A.mongo.event.url ?><\/a><\/td>\r\n<\/tr><tr>\r\n<th>\u5b9a\u54e1<\/th>\r\n<td><?cs var:A.mongo.event.capacity?>\u4eba<\/td>\r\n<\/tr>\r\n<\/tbody><\/table>\r\n<div id=\"map\"><\/div>\r\n<br clear=\"both\">\r\n<\/div>\r\n<?cs each:item = A.mongo.event.contents ?>\r\n  <?cs call:drawTags(item)?>\r\n<?cs \/each ?>\r\n<\/div>\r\n<?cs if:S.login.writable ?>\r\n  <div class=\"edit\"><a href=\"\/mongo\/events\/edit\/<?cs var:A.mongo.event.docid ?>\">\u7de8\u96c6<\/a><\/div>\r\n<?cs \/if ?>\r\n<\/div>\r\n",
"action":[
""
],
"header":"",
"bottom":"<script src=\"https:\/\/maps.googleapis.com\/maps\/api\/js?v=3.exp&sensor=false&libraries=places\"><\/script>",
"_u":"events\/eventpage"
}