{
"@R":"1343275693",
"type":"HorizontalWidget",
"subject":"ne_ulist",
"description":"ne_ulist",
"css":"#ulist div.domain {\n background-color: #F8F8F8;\n border: 1px solid #000000;\n margin: 10px;\n padding: 5px;\n}\n\n#ulist div.domain > h3 {\n margin:5px 0;\n padding: 0;\n cursor: pointer;\n text-decoration: underline;\n color: #0000FF;\n}\n\n#ulist table {\n  border-spacing: 0;\n  font-size: 10pt;\n  width: 100%;\n  text-align: left;\n  border: 2px solid #676767;\n  border-width: 2px 2px 1px 1px;\n}\n#ulist th {\n  border-bottom: 1px solid #676767;\n  border-left: 1px solid #676767;\n  font-weight: bold;\n  vertical-align: middle;\n  background-color: #DFDFDF;\n  background-color: #FFF8F0;\n}\n#ulist td {\n  border-bottom: 1px solid #676767;\n  border-left: 1px solid #676767;\n  vertical-align: middle;\n  padding: 3px;\n  background-color: #FFFFFF;\n}\n#ulist th.url,td.url {\n width: auto;\n max-width:500px;\n word-wrap: break-word;\n}\n#ulist th.last,td.last {\n width: 140px;\n}\n#ulist th.ptime,td.ptime {\n width: 80px;\n}\n",
"js":"$(function(){\n $('#ulist div.domain > h3').click(function(){\n  var elem = $(this).next();\n  if ( elem.attr('hide') ){\n   elem.show();\n   elem.removeAttr('hide');\n  }else{\n   elem.hide();\n   elem.attr('hide','hide');\n  }\n });\n});",
"id":"ulist",
"class":"",
"body":"<?cs each:domain = A.yslowviewer.domains?>\n<div class=\"domain\">\n <h3><?cs var:domain.domain ?><\/h3>\n <table>\n <tbody>\n  <tr>\n   <th class=\"url\">URL<\/th>\n   <th class=\"last\">LAST<\/th>\n   <th class=\"ptime\">RESP TIME<\/th>\n  <\/tr>\n <?cs each:item = domain.urls?>\n  <tr>\n   <td class=\"url\"><a href=\"ne_url?u=<?cs name:item ?>\" ><?cs var:item.url ?><\/a><\/td>\n   <td class=\"last\"><?cs var:item.t ?><\/td>\n   <td class=\"ptime\"><?cs var:item.lt ?>(msec)<\/td>\n  <\/tr>\n <?cs \/each ?>\n <\/tbody>\n <\/table>\n<\/div>\n<?cs \/each ?>\n",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/NetexportAction?cols"
],
"_u":"ne_ulist"
}