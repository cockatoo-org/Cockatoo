{
"@R":"1343013581",
"type":"HorizontalWidget",
"subject":"show",
"description":"show",
"css":"#show h1 {\n word-wrap: break-word;\n}\n#show table {\n border-spacing: 0;\n font-size: 10pt;\n width: 100%;\n text-align: left;\n border: 2px solid #676767;\n border-width: 2px 2px 1px 1px;\n}\n#show th {\n border-bottom: 1px solid #676767;\n border-left: 1px solid #676767;\n font-weight: bold;\n vertical-align: middle;\n background-color: #DFDFDF;\n width: 50px;\n}\n#show td {\n border-bottom: 1px solid #676767;\n border-left: 1px solid #676767;\n vertical-align: middle;\n padding: 3px;\n width: 50px;\n}\n\n#show th.total_size,td.total_size {\n width: 100px;\n}\n#show th.time,td.time {\n width: 100px;\n}\n#show th.ptime,td.ptime {\n width: 80px;\n}\n",
"js":"",
"id":"show",
"class":"show",
"body":"<hr>\n<h1><?cs var:A.pwatch.url ?><\/h1>\n<table border=\"0\"  cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<th class=\"time\">TIME<\/th>\n<th class=\"ptime\">RESP_TIME (ms)<\/th>\n<th class=\"total\">TOTAL<\/th>\n<th class=\"total_size\">TOTAL SIZE (b)<\/th>\n<th class=\"html\">HTML<\/th>\n<th class=\"js\">JS<\/th>\n<th class=\"css\">CSS<\/th>\n<th class=\"img\">IMG<\/th>\n<th class=\"other\">OTHER<\/th>\n<th class=\"error\">ERROR<\/th>\n<th class=\"timeout\">TIMEOUT<\/th>\n<\/tr>\n<?cs each:item = A.pwatch.datas ?>\n<tr >\n<td class=\"time\"><?cs var:item.t ?><\/a><\/td>\n<td class=\"ptime\"><?cs var:item.ptime ?><\/td>\n<td class=\"total\"><?cs var:item.total ?><\/td>\n<td class=\"total_size\"><?cs var:item.total_size ?><\/td>\n<td class=\"html\"><?cs var:item.html ?><\/td>\n<td class=\"js\"><?cs var:item.js ?><\/td>\n<td class=\"css\"><?cs var:item.css ?><\/td>\n<td class=\"img\"><?cs var:item.img ?><\/td>\n<td class=\"other\"><?cs var:item.other ?><\/td>\n<td class=\"error\"><?cs var:item.error ?><\/td>\n<td class=\"timeout\"><?cs var:item.timeout ?><\/td>\n<\/tr>\n<?cs \/each ?>\n<\/tbody>\n<\/table>\n\n\n",
"action":[
"action:\/\/pwatch-action\/pwatch\/PwatchAction?get"
],
"_u":"show"
}