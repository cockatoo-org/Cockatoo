{
"@R":"1362991527",
"type":"HorizontalWidget",
"subject":"dselector",
"description":"dselector",
"css":"#dselector b.caption {\r\n margin: 5px 10px;\r\n}\r\n#dateselector input[type=\"text\"] {\r\n  width: 80px;\r\n}\r\n#dateselector input[type=\"submit\"] {\r\n visibility: hidden;\r\n}\r\n",
"js":"$(function(){\r\n $('#dateselector input[name=\"dselector\"]').datepicker({ dateFormat: 'yy-mm-dd' });\r\n  $('#dateselector input[name=\"dselector\"]').change(function (){\r\n $('#dateselector input[type=\"submit\"]').click(); \r\n });\r\n});",
"id":"dselector",
"class":"core",
"body":"<form id=\"dateselector\" method=\"GET\" action=\"\">\r\n <b class=\"caption\">Head of the date <\/b>\r\n <?cs each:item=S._g ?>\r\n  <?cs if:name(item)!=\"dselector\" ?>\r\n   <?cs if:name(item)!=\"_R\" ?>\r\n    <input type=\"hidden\" name=\"<?cs name:item  ?>\" value=\"<?cs var:item ?>\"><\/input>\r\n   <?cs \/if ?>\r\n  <?cs \/if ?>\r\n <?cs \/each ?>\r\n <input id=\"datepick\" type=\"text\" name=\"dselector\" value=\"<?cs var:S._g.dselector ?>\"><\/input>\r\n <input type=\"submit\" value=\"go\"><\/input>\r\n<\/form>\r\n",
"action":[
""
],
"_u":"utils\/dselector",
"header":"",
"bottom":""
}