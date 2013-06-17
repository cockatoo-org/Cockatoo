{
"@R":"1371452053",
"type":"HorizontalWidget",
"subject":"wikipage",
"description":"",
"css":"",
"js":"",
"id":"",
"class":"",
"body":"<?cs def:drawTags(item) ?><?cs if:item.tag=='text'?><?cs var:item.text?><?cs each:child=item.children?><?cs call:drawTags(child)?><?cs /each ?><?cs elif:item.tag=='br' ?><br><?cs else ?><<?cs var:item.tag?><?cs each:attr = item.attr ?> <?cs name:attr?>=\"<?cs var:attr?>\"<?cs /each ?>><?cs each:child = item.children ?><?cs call:drawTags(child)?><?cs /each ?></<?cs var:item.tag?>><?cs /if ?><?cs /def ?>\r
<?cs def:drawPage(id,page) ?><div id=\"<?cs var:id?>\" class=\"wikipage\"><div class=\"credit\">by <?cs var:page._ownername ?><time><?cs var:page._timestr ?></time></div><?cs each:item = page.contents ?><?cs call:drawTags(item)?><?cs /each ?></div><?cs /def ?>\r
<?cs def:drawEdit(id,page,editpath,notation,base,danger,image) ?><div class=\"wikieditlink\"><a>\u7de8\u96c6</a></div><div class=\"wikiedit\"><form class=\"wikieditaction\" for=\"<?cs var:id?>\" action=\"<?cs var:editpath?>\" pagename=\"<?cs var: page.title?>\" base=\"<?cs alt:base?><?cs var:C._base?><?cs /alt?>/\"><textarea name=\"origin\"><?cs var:page.origin ?></textarea><div class=\"normal\"><input type=\"button\" name=\"op\" value=\"preview\"></input><input type=\"button\" name=\"op\" value=\"save\"></input><a target=\"_blank\" href=\"<?cs var:notation ?>\">notation</a></div><?cs if: danger?><div class=\"danger\"><a class=\"rename\">rename this page</a><input type=\"text\" name=\"rename\" value=\"<?cs var:page.title?>\"></input><input type=\"button\" name=\"op\" value=\"rename\"><input type=\"button\" name=\"op\" value=\"remove\"></div><?cs /if?></form><?cs if: image?><div class=\"wikiimage\"><form class=\"wikiimageaction\" for=\"<?cs var:id?>\" action=\"<?cs var:editpath?>\" pagename=\"<?cs var: page.title?>\"><input type=\"file\" name=\"filename\" value=\"\" /><input type=\"button\" name=\"upload\" value=\"upload\" /><div class=\"wikieditimages\"><table><thead><tr><td>image</td><td>name</td><td>format</td><td>action</td><tr></thead><tbody /></table></div></form></div><?cs /if?></div><?cs /def ?>\r
",
"action":[
""
],
"header":"<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"/_s_/core/default/css/wikipage.css\" />\r
",
"bottom":"<script type=\"text/javascript\" src=\"/_s_/core/default/js/jquery.upload-1.0.2.js\"></script>\r
<script type=\"text/javascript\" src=\"/_s_/core/default/js/wikipage.js\"></script>\r
\r
",
"_u":"utils/wikipage"
}