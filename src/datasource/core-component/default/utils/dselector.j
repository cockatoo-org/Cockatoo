{
"@R":"1343202469",
"type":"HorizontalWidget",
"subject":"dselector",
"description":"dselector",
"css":"#dselector b.caption {\n margin: 5px 10px;\n}\n#dateselector input[type=\"text\"] {\n  width: 80px;\n}\n#dateselector input[type=\"submit\"] {\n visibility: hidden;\n}\n",
"js":"$(function(){\n $('#dateselector input[name=\"dselector\"]').datepicker({ dateFormat: 'yy-mm-dd' });\n  $('#dateselector input[name=\"dselector\"]').change(function (){\n $('#dateselector input[type=\"submit\"]').click(); \n });\n});",
"id":"dselector",
"class":"",
"body":"<form id=\"dateselector\" method=\"GET\" action=\"\">\n <b class=\"caption\">Head of the date <\/b>\n <?cs each:item=S._g ?>\n  <?cs if:name(item)!=\"dselector\" ?>\n   <?cs if:name(item)!=\"_R\" ?>\n    <input type=\"hidden\" name=\"<?cs name:item  ?>\" value=\"<?cs var:item ?>\"><\/input>\n   <?cs \/if ?>\n  <?cs \/if ?>\n <?cs \/each ?>\n <input id=\"datepick\" type=\"text\" name=\"dselector\" value=\"<?cs var:S._g.dselector ?>\"><\/input>\n <input type=\"submit\" value=\"go\"><\/input>\n<\/form>\n",
"action":[
""
],
"_u":"utils\/dselector"
}