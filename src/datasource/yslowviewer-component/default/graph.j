{
"@R":"1343882916",
"type":"HorizontalWidget",
"subject":"graph",
"description":"graph",
"css":"div.graph {\r\n\/*  margin: 50px; *\/\r\n  height: 300px;\r\n  width: 650px;\r\n}\r\n#linktext {\r\n  width: 600px;\r\n}\r\n\r\n\r\n",
"js":"$(function () {\r\n  data['summary'][1].yaxis=2;\r\n  summary = new plot(data['times'],data['summary'],'summary');\r\n  summary.draw();\r\n  scores  = new plot(data['times'],data['scores'],'scores');\r\n  scores.draw();\r\n  summary.setLink(scores);\r\n  scores.setLink(summary);\r\n});\r\n",
"id":"",
"class":"",
"body":"<h1>summary<\/h1>\r\n<div id=\"summary\" class=\"graph\"><\/div>\r\n<h1>scores<\/h1>\r\n<div id=\"scores\" class=\"graph\"><\/div>\r\n<script>\r\n  var data = <?cs var:A.yslowviewer.@json ?>;\r\n<\/script>\r\n\r\n<h1>This page<\/h1>\r\n<input type=\"text\" id=\"linktext\"><\/input><br>\r\n<a href=\"\" id=\"linka\">link<\/a>\r\n",
"action":[
"action:\/\/yslowviewer-action\/yslowviewer\/YslowAction?getA"
],
"_u":"graph",
"header":"<link rel=\"stylesheet\" href=\"\/_s_\/yslowviewer\/default\/jquery.graph\/graph.css\" type=\"text\/css\" media=\"all\" \/>",
"bottom":"<script type=\"text\/javascript\" src=\"\/_s_\/yslowviewer\/default\/js\/flot\/jquery.flot.js\"><\/script>\r\n<script type=\"text\/javascript\" src=\"\/_s_\/yslowviewer\/default\/js\/flot\/jquery.flot.selection.js\"><\/script>\r\n<script type=\"text\/javascript\" src=\"\/_s_\/yslowviewer\/default\/jquery.graph\/graph.js\"><\/script>"
}