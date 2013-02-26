{
"@R":"1361854004",
"type":"HorizontalWidget",
"subject":"utils_page",
"description":"",
"css":"",
"js":"",
"id":"",
"class":"",
"body":"<?cs def:drawTags(item) ?><?cs if:item.tag=='text'?><?cs var:item.text?><?cs each:child=item.children?><?cs call:drawTags(child)?><?cs \/each ?><?cs elif:item.tag=='br' ?><br><?cs else ?><<?cs var:item.tag?><?cs each:attr = item.attr ?> <?cs name:attr?>=\"<?cs var:attr?>\"<?cs \/each ?>><?cs each:child = item.children ?><?cs call:drawTags(child)?><?cs \/each ?><\/<?cs var:item.tag?>><?cs \/if ?><?cs \/def ?>\r\n",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"utils\/page"
}