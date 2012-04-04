{
"@R":"1333424743",
"type":"HorizontalWidget",
"subject":"ps_ulist",
"description":"ps_ulist",
"css":"table {\n  border-spacing: 0;\n  font-size: 10pt;\n  width: 100%;\n  text-align: left;\n  border: 2px solid #676767;\n  border-width: 2px 2px 1px 1px;\n}\nth {\n  border-bottom: 1px solid #676767;\n  border-left: 1px solid #676767;\n  font-weight: bold;\n  vertical-align: middle;\n  background-color: #DFDFDF;\n}\ntd {\n  border-bottom: 1px solid #676767;\n  border-left: 1px solid #676767;\n  vertical-align: middle;\n  padding: 3px;\n}\n",
"js":"",
"id":"ps_ulist",
"class":"",
"body":"<h1>Show URL list<\/h1>\n<table>\n<tbody>\n<tr>\n<th>URL<\/th>\n<th>LATEST<\/th>\n<th>SCORE<\/th>\n<th>RESP TIME<\/th>\n<\/tr>\n<?cs each:item = A.yslowviewer.urls ?>\n<tr>\n<td><a href=\"ps_url?u=<?cs name:item ?>\" ><?cs var:item.url ?><\/a><\/td>\n<td><?cs var:item.t ?><\/td>\n<td><?cs var:item.o ?><\/td>\n<td><?cs var:item.lt ?>(msec)<\/td>\n<\/tr>\n<?cs \/each ?>\n<\/tbody>\n<\/table>\n",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/PagespeedAction?cols"
],
"_u":"ps_ulist"
}