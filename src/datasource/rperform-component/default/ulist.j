{
"@R":"1343895921",
"type":"HorizontalWidget",
"subject":"ulist",
"description":"ulist",
"css":"#ulist table {\r\n  border-spacing: 0;\r\n  font-size: 10pt;\r\n  width: 100%;\r\n  text-align: left;\r\n  border: 2px solid #676767;\r\n  border-width: 2px 2px 1px 1px;\r\n  margin: 10px 0;\r\n}\r\n#ulist th {\r\n border-bottom: 1px solid #676767;\r\n border-left: 1px solid #676767;\r\n font-weight: bold;\r\n vertical-align: middle;\r\n background-color: #DFDFDF;\r\n padding: 3px 10px;\r\n}\r\n#ulist td {\r\n border-bottom: 1px solid #676767;\r\n border-left: 1px solid #676767;\r\n vertical-align: middle;\r\n padding: 3px 15px;\r\n}\r\n#ulist th.url,td.url {\r\n width: auto;\r\n max-width:500px;\r\n word-wrap: break-word;\r\n}\r\n\r\n#ulist tr.domain {\r\n font-size:1.2em;\r\n color: #0000E0;\r\n text-decoration: underline;\r\n cursor: pointer;\r\n}\r\n#ulist tr.url {\r\n display: none;\r\n}",
"js":"$(function(){\r\n $('tr.domain a').click(function(ev){\r\n  if ( $(this).attr('open') == 'open' ){\r\n   $(this).removeAttr('open');\r\n   $(this).parents('tbody').children('tr.url').hide();\r\n  }else{\r\n   $(this).attr('open','open');\r\n   $(this).parents('tbody').children('tr.url').show();\r\n  }\r\n });\r\n});",
"id":"ulist",
"class":"",
"body":"<?cs each:domain = A.rperform.domains?>\r\n<table>\r\n<tbody>\r\n<tr class=\"domain\">\r\n<th class=\"domain\"><a><?cs var:domain.domain ?><\/a><\/th>\r\n<\/tr>\r\n<?cs each:item = domain.urls?>\r\n<tr class=\"url\">\r\n<td class=\"url\"><a href=\"url?u=<?cs name:item ?>\" ><?cs var:item.url ?><\/a><\/td>\r\n<\/tr>\r\n<?cs \/each ?>\r\n<\/tbody>\r\n<\/table>\r\n<?cs \/each ?>\r\n",
"action":[
"action:\/\/rperform-action\/rperform\/RperformAction?cols"
],
"_u":"ulist",
"header":"",
"bottom":""
}