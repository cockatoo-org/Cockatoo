{
"@R":"1362374121",
"type":"HorizontalWidget",
"subject":"header",
"description":"header",
"css":"#header nav.top {\r\n  background-color: #402817;\r\n  color: #AA814D;\r\n  width:100%;\r\n  display:block;\r\n  float:left;\r\n}\r\n#header #logo {\r\n  float:left;\r\n}\r\n#header #title {\r\n  font-size: 2em;\r\n  margin: 30px;\r\n  color: #f0f0f0;\r\n  float:left;\r\n}\r\n#header #identity {\r\n  margin: 2px 8px;\r\n  float:right;\r\n}\r\n#header #identity a {\r\n  color: #AA814D;\r\n}\r\n\r\n#header nav.main {\r\n  line-height: 1.31;\r\n  background-color: #f6f4cd;\r\n  border-top: 1px solid #f6f4cd;\r\n  color: #4c3a2c;\r\n  width:100%;\r\n  float:right;\r\n}\r\n#header nav.main ul {\r\n  list-style: none;\r\n  padding: 0;\r\n  margin: 0 5px;\r\n}\r\n#header nav.main ul > li {\r\n  font-size: 0.7em;\r\n  float: right;\r\n  padding: 2px 4px;\r\n}\r\n#header nav.main ul > li.selected {\r\n  background-color: #F0F0F0;\r\n}\r\n#header nav.main ul > li:hover {\r\n  background-color: #f8f8f8;\r\n}\r\n#header nav.main a {\r\n  color: #4c3a2c;\r\n}\r\n\r\n",
"js":"$( function (){\r\n  mainNavs = $('#header nav.main > ul > li');\r\n  mainNavs.each( function () {\r\n    $(this).removeClass('selected');\r\n    link = $(this).find('> a').attr('href');\r\n    if ( link == window.location.pathname) {\r\n      $(this).addClass('selected');\r\n    }\r\n  });\r\n  \r\n})\r\n",
"id":"header",
"class":"",
"body":"<hearder>\r\n<nav class=\"top\" role=\"navigation\">\r\n  <div id=\"logo\"><img alt=\"MongoDB JP User Group \" src=\"\/_s_\/mongo\/default\/img\/mongojp-logo.png\"><\/img><\/div>\r\n  <div id=\"title\">\u65e5\u672cMongoDB\u30e6\u30fc\u30b6\u30fc\u4f1a<\/div>\r\n<?cs if:! S.login.user ?>\r\n  <div id=\"identity\"><a href=\"\/mongo\/login\">login<\/a><\/div>\r\n<?cs else ?>\r\n  <div id=\"identity\"><a href=\"\/mongo\/login\"><?cs var:S.login.user ?><\/a><\/div>\r\n<?cs \/if ?>\r\n<\/nav>\r\n<nav class=\"main\" role=\"navigation\">\r\n  <ul>\r\n    <li><a href=\"\/mongo\/links\">\u30ea\u30f3\u30af<\/a><\/li>\r\n    <li><a href=\"\/mongo\/news\">\u30cb\u30e5\u30fc\u30b9<\/a><\/li>\r\n    <li><a href=\"\/mongo\/events\">\u30a4\u30d9\u30f3\u30c8<\/a><\/li>\r\n    <li><a href=\"\/mongo\/exams\">\u554f\u984c\u96c6<\/a><\/li>\r\n    <li><a href=\"\/mongo\/tips\">TIPS<\/a><\/li>\r\n    <li><a href=\"\/mongo\/docs\">\u30c9\u30ad\u30e5\u30e1\u30f3\u30c8<\/a><\/li>\r\n    <li><a href=\"\/mongo\/forums\">\u30d5\u30a9\u30fc\u30e9\u30e0<\/a><\/li>\r\n    <li><a href=\"\/mongo\/main\">\u30e1\u30a4\u30f3<\/a><\/li>\r\n  <\/ul>\r\n<\/nav>\r\n<\/header>\r\n",
"action":[
""
],
"_u":"header",
"header":"",
"bottom":""
}