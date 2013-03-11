{
"@R":"1362992609",
"type":"HorizontalWidget",
"subject":"profile",
"description":"profile",
"css":"#profile {\r\n  padding: 0 50px;\r\n}\r\n\r\n#profile h5 {\r\n  margin: 0 0 0 0;\r\n  padding: 0 2px 0 2px;\r\n  font-size: 1.2em;\r\n}\r\n\r\n#profile div.input {\r\n  text-align:left;\r\n}\r\n\r\n#profile div.input > h6 {\r\n   margin: 0 0 0 0;\r\n   padding: 0 5px 0 0;\r\n   width: 80px;\r\n   text-align:left;\r\n}\r\n#profile div.input > input[type=\"text\"],\r\n#profile div.input > input[type=\"password\"] {\r\n    margin: 0 0 0 0;\r\n    padding: 0 0 0 0;\r\n    position: relative;\r\n    text-align: left;\r\n    width: 180px;\r\n }\r\n",
"js":"",
"id":"profile",
"class":"core",
"body":"<h5>Update profile<\/h5>\r\n<div class=\"window\">\r\n<form class=\"setuser\" method=\"POST\" action=\"<?cs var:C._base ?>\/profile\">\r\n<div class=\"input\"> <h6>User<\/h6> <input name=\"passwd\" type=\"text\" value=\"<?cs var:S.login.user ?>\" readonly=\"readonly\" \/><\/div>\r\n<div class=\"input\"> <h6>Password<\/h6> <input name=\"passwd\" type=\"password\" value=\"\" \/><\/div>\r\n<div class=\"input\"> <h6>Confirm<\/h6> <input name=\"confirm\" type=\"password\" value=\"\" \/><\/div>\r\n<div class=\"input\"> <h6>Email<\/h6> <input name=\"email\" type=\"text\" value=\"<?cs var:S.login.email ?>\" \/><\/div>\r\n   <?cs if:?S._g.r ?>\r\n   <input name=\"r\" type=\"hidden\" value=\"<?cs var:S._g.r ?>\" \/>\r\n   <?cs \/if ?> \r\n<div class=\"input\">\r\n<input name=\"submit\" type=\"submit\" value=\"update profile\" \/>\r\n<\/div>\r\n",
"action":[
""
],
"_u":"utils\/profile",
"header":"",
"bottom":""
}