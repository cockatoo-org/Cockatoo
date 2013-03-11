{
"@R":"1362992578",
"type":"HorizontalWidget",
"subject":"login",
"description":"login",
"css":"#login form {\r\n  display:inline;\r\n}\r\n#login {\r\n  text-align:center;\r\n}\r\n#login div.input {\r\n    text-align: left;\r\n}\r\n#login div.input > h6 {\r\n    margin:0;\r\n    padding:0;\r\n    text-align: left;\r\n    width: 80px;\r\n}\r\n#login  div.user {\r\n\/*   float: right;  *\/\r\n}\r\n#login  div.user > span {\r\n\/*  color: blue; *\/\r\n}\r\n\r\n#login h5 {\r\n  margin: 0 0 0 0;\r\n  padding: 0 2px 0 2px;\r\n  font-size: 1.2em;\r\n}\r\n#login div.input > input[type=\"text\"],\r\n#login div.input > input[type=\"password\"] {\r\n    margin: 0 0 0 0;\r\n    padding: 0 0 0 0;\r\n    position: relative;\r\n    text-align: left;\r\n    width: 180px;\r\n }\r\n\r\n#login div.oauth img {\r\n  height:24px;\r\n  width:24px;\r\n}\r\n",
"js":"",
"id":"login",
"class":"core",
"body":"<?cs if: S.login.user ?>\r\n<div class=\"window\">\r\n <div class=\"user\">\r\n   Welcome <span><?cs var:S.login.user ?><\/span>\r\n <div>\r\n <form method=\"POST\" action=\"<?cs var:C._base ?>\/profile\">\r\n   <?cs if:?S._g.r ?>\r\n   <input name=\"r\" type=\"hidden\" value=\"<?cs var:S._g.r ?>\" \/>\r\n   <?cs \/if ?> \r\n   <input name=\"submit\" type=\"submit\" value=\"logout\" \/>\r\n <\/form>\r\n <form method=\"GET\" action=\"<?cs var:C._base ?>\/profile\">\r\n   <?cs if:?S._g.r ?>\r\n   <input name=\"r\" type=\"hidden\" value=\"<?cs var:S._g.r ?>\" \/>\r\n   <?cs \/if ?> \r\n   <input name=\"submit\" type=\"submit\" value=\"profile\" \/>\r\n <\/form>\r\n <\/div>\r\n<?cs if: S.login.root ?>\r\n<a id=\"reset\" href=\"<?cs var:C._base ?>\/admin\">admin tool<\/a>\r\n<?cs \/if ?>\r\n <\/div>\r\n<\/div>\r\n<?cs else ?>\r\n<div class=\"window\">\r\n  <form method=\"POST\" action=\"<?cs var:C._base ?>\/login\">\r\n   <div class=\"input\"><h6>User<\/h6> <input name=\"user\" type=\"text\" value=\"\" \/><\/div>\r\n   <div class=\"input\"><h6>Password<\/h6> <input name=\"passwd\" type=\"password\" value=\"\" \/><\/div>\r\n   <?cs if:?S._g.r ?>\r\n   <div class=\"input\"><input name=\"r\" type=\"hidden\" value=\"<?cs var:S._g.r ?>\" \/><\/div>\r\n   <?cs \/if ?> \r\n   <div class=\"input\"> <input name=\"submit\" type=\"submit\" value=\"login\" \/><input name=\"submit\" type=\"submit\" value=\"password reset\" \/><\/div>\r\n  <\/form>\r\n  <div class=\"oauth\">\r\n  <img src=\"\/_s_\/core\/default\/oauth.png\"><\/img>\r\n  <a href=\"<?cs var:C._base ?>\/logintwitter?r=<?cs if:S._g.r ?><?cs var:S._g.r ?><?cs else ?><?cs var:S._r._eurl ?><?cs \/if ?>\"><img src=\"\/_s_\/core\/default\/twitter_oauth.png\" alt=\"twitter oauth\"><\/img><\/a>\r\n  <a href=\"<?cs var:C._base ?>\/logingoogle?r=<?cs if:S._g.r ?><?cs var:S._g.r ?><?cs else ?><?cs var:S._r._eurl ?><?cs \/if ?>\"><img src=\"\/_s_\/core\/default\/google_oauth.png\" alt=\"google oauth\"><\/img><\/a>\r\n  <\/div>\r\n<\/div>\r\n<?cs \/if ?>\r\n\r\n",
"action":[
""
],
"_u":"utils\/login",
"header":"",
"bottom":""
}