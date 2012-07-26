{
"@R":"1343273137",
"type":"HorizontalWidget",
"subject":"ulist",
"description":"ulist",
"css":"#ulist div.domain {\n background-color: #F8F8F8;\n border: 1px solid #000000;\n margin: 10px;\n padding: 5px;\n}\n\n#ulist div.domain > h3 {\n margin:5px 0;\n padding: 0;\n cursor: pointer;\n text-decoration: underline;\n color: #0000FF;\n}\n\n#ulist table {\n  border-spacing: 0;\n  font-size: 10pt;\n  width: 100%;\n  text-align: left;\n  border: 2px solid #676767;\n  border-width: 2px 2px 1px 1px;\n}\n#ulist th {\n  border-bottom: 1px solid #676767;\n  border-left: 1px solid #676767;\n  font-weight: bold;\n  vertical-align: middle;\n  background-color: #DFDFDF;\n  background-color: #FFF8F0;\n}\n#ulist td {\n  border-bottom: 1px solid #676767;\n  border-left: 1px solid #676767;\n  vertical-align: middle;\n  padding: 3px;\n  background-color: #FFFFFF;\n}\n#ulist th.url,td.url {\n width: auto;\n max-width:500px;\n word-wrap: break-word;\n}\n#ulist th.interval,td.interval {\n width: 60px;\n}\n#ulist th.last,td.last {\n width: 140px;\n}\n#ulist th.ptime,td.ptime {\n width: 80px;\n}\n#ulist th.total,td.total {\n width: 50px;\n}\n#ulist th.size,td.size {\n width: 80px;\n}\n",
"js":"",
"id":"ulist",
"class":"",
"body":"<table border=\"0\"  cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n<th class=\"url\">URL<\/th>\n<th class=\"interval\">INTERVAL<\/th>\n<th class=\"last\">LAST<\/th>\n<th class=\"ptime\">RESP TIME<\/th>\n<th class=\"total\">TOTAL<\/th>\n<th class=\"size\">TOTAL SIZE<\/th>\n<\/tr>\n<?cs each:item = A.pwatch.urls ?>\n<tr class=\"selectable \">\n<td class=\"url\"><a href=\"show?u=<?cs var:item._u ?>\"><?cs var:item.url ?><\/a><\/td>\n<td class=\"interval\"><?cs var:item.interval ?><\/td>\n<td class=\"last\"><?cs var:item.data.t ?><\/td>\n<td class=\"ptime\"><?cs var:item.data.SUMMARY.ptime ?><\/td>\n<td class=\"total\"><?cs var:item.data.SUMMARY.total ?><\/td>\n<td class=\"size\"><?cs var:item.data.SUMMARY.total_size ?><\/td>\n<\/tr>\n<?cs \/each ?>\n<\/tbody>\n<\/table>\n",
"action":[
"action:\/\/pwatch-action\/pwatch\/PwatchAction?keys"
],
"_u":"ulist"
}