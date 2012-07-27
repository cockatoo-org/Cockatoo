{
"@R":"1343035631",
"type":"HorizontalWidget",
"subject":"ulist",
"description":"ulist",
"css":"#ulist table {\n  border-spacing: 0;\n  font-size: 10pt;\n  width: 100%;\n  text-align: left;\n  border: 2px solid #676767;\n  border-width: 2px 2px 1px 1px;\n  margin: 10px 0;\n}\n#ulist th {\n border-bottom: 1px solid #676767;\n border-left: 1px solid #676767;\n font-weight: bold;\n vertical-align: middle;\n background-color: #DFDFDF;\n padding: 3px 10px;\n}\n#ulist td {\n border-bottom: 1px solid #676767;\n border-left: 1px solid #676767;\n vertical-align: middle;\n padding: 3px 15px;\n}\n#ulist th.url,td.url {\n width: auto;\n max-width:500px;\n word-wrap: break-word;\n}\n\n#ulist tr.domain {\n font-size:1.2em;\n color: #0000E0;\n text-decoration: underline;\n cursor: pointer;\n}\n#ulist tr.url {\n display: none;\n}",
"js":"$(function(){\n $('tr.domain a').click(function(ev){\n  if ( $(this).attr('open') == 'open' ){\n   $(this).removeAttr('open');\n   $(this).parents('tbody').children('tr.url').hide();\n  }else{\n   $(this).attr('open','open');\n   $(this).parents('tbody').children('tr.url').show();\n  }\n });\n});",
"id":"ulist",
"class":"",
"body":"<h1>URL list<\/h1>\n<?cs each:domain = A.rperform.domains?>\n<table>\n<tbody>\n<tr class=\"domain\">\n<th class=\"domain\"><a><?cs var:domain.domain ?><\/a><\/th>\n<\/tr>\n<?cs each:item = domain.urls?>\n<tr class=\"url\">\n<td class=\"url\"><a href=\"url?u=<?cs name:item ?>\" ><?cs var:item.url ?><\/a><\/td>\n<\/tr>\n<?cs \/each ?>\n<\/tbody>\n<\/table>\n<?cs \/each ?>\n",
"action":[
"action:\/\/rperform-action\/rperform\/RperformAction?cols"
],
"_u":"ulist"
}