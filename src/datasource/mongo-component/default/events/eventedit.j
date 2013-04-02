{
"@R":"1364879760",
"type":"HorizontalWidget",
"subject":"eventedit",
"description":"",
"css":"#eventedit th {\r\n  font-size: 0.8em;\r\n  line-height: 0.8;\r\n}\r\n#eventedit th.public,\r\n#eventedit th.event_id,\r\n#eventedit th.event_url {\r\n  color: #cc0000;\r\n  font-size: 1.0em;\r\n  font-weight: 700;\r\n  line-height: 1.0;\r\n}\r\n#eventedit input,\r\n#eventedit textarea {\r\n  font-size: 0.8em;\r\n}\r\n#eventedit input[type=\"text\"] {\r\n  width : 500px;\r\n}\r\n#eventedit textarea {\r\n  width  : 500px;\r\n  height : 100px;\r\n}\r\n\r\n\r\n",
"js":"$(function() {\r\n  function setfield(event){\r\n      $('form input[name=\"event_id\"]').val(event.event_id);\r\n      $('form input[name=\"event_url\"]').val(event.event_url);\r\n      $('form input[name=\"title\"]').val(event.title);\r\n      $('form input[name=\"subtitle\"]').val(event.catch);\r\n      $('form input[name=\"address\"]').val(event.address);\r\n      $('form input[name=\"place\"]').val(event.place);\r\n    var date = '';\r\n    if ( event.started_at ) {\r\n      var dd = new Date(event.started_at);\r\n      var m = (dd.getMonth()+1);\r\n      m = (m<10)?('0'+m):m;\r\n      var d = dd.getDate();\r\n      d = (d<10)?('0'+d):d;\r\n      date = (dd.getYear()+1900)+'\/'+m+'\/'+dd.getDate();\r\n    }\r\n      $('form input[name=\"date\"]').val(date);\r\n      $('form input[name=\"limit\"]').val(event.limit);\r\n    if ( event.event_id ) {\r\n      $('form input[type=\"submit\"]').removeAttr('disabled');\r\n    }else{\r\n      $('form input[type=\"submit\"]').attr('disabled','disabled');\r\n    }\r\n  }\r\n  function getattendbeta(event_id){\r\n    setfield({});\r\n    var apiurl = 'http:\/\/api.atnd.org\/events\/';\r\n      $.ajax({\r\n\turl: apiurl,\r\n\ttype: 'GET',\r\n\tdataType: 'jsonp',\r\n\tdata: {\r\n\t  event_id:event_id,\r\n\t  format:'jsonp' \r\n\t},\r\n\tsuccess: function(data){\r\n\t  var event = data.events[0];\r\n\t  setfield(event);\r\n\t}\r\n      });\r\n  }\r\n  function getattend(event_id){\r\n    setfield({});\r\n    var apiurl = 'http:\/\/api.atnd.org\/eventatnd\/event\/';\r\n      $.ajax({\r\n\turl: apiurl,\r\n\ttype: 'GET',\r\n\tdataType: 'jsonp',\r\n\tdata: {\r\n\t  event_id:event_id,\r\n\t  format:'jsonp' \r\n\t},\r\n\tsuccess: function(data){\r\n\t  var event = data.events[0].event[0];\r\n\t  setfield(event);\r\n\t}\r\n      });\r\n  }\r\n  function getevent(event_id){\r\n    if ( \/^\\d+$\/.exec(event_id) ) {\r\n      getattendbeta(event_id);\r\n    }else{\r\n      getattend(event_id);\r\n    }\r\n  }\r\n\r\n    $('#eventedit input[name=\"event_id\"]').change(function(){\r\n      var event_id = $(this).val();\r\n      getevent(event_id);\r\n    });\r\n    $('#eventedit input[name=\"event_url\"]').change(function(){\r\n      var url = $(this).val();\r\n      if ( url ) {\r\n\tvar attend=\/^http:\\\/\\\/atnd\\.org\\\/event\\\/(.+)$\/.exec(url);\r\n\tif ( attend ) {\r\n\t  var attendm = \/E0*(\\d+)(\\\/\\d+)?\/.exec(attend[1]);\r\n\t  if ( ! attendm[2] ) {\r\n\t    attendm[2] = '\/0';\r\n\t  }\r\n\t  var event_id = attendm[1] + attendm[2];\r\n\t  getevent(event_id);\r\n\t  return;\r\n\t}\r\n\tvar attendbeta=\/^http:\\\/\\\/atnd\\.org\\\/events\\\/(.+)$\/.exec(url);\r\n\tif ( attendbeta ) {\r\n\t  getevent(attendbeta[1]);\r\n\t  return;\r\n\t}\r\n      }\r\n    });\r\n});\r\n$(function() {\r\n  $('#eventedit input[name=\"date\"]').datepicker({'dateFormat':'yy-mm-dd'} );\r\n  $('#eventedit input[name=\"capacity\"]').change(function(){\r\n    var v = $(this).val();\r\n    $(this).prev('div').remove();\r\n    if ( v < N_ATTENDERS ) {\r\n      $(this).parents('form').find('input[type=\"submit\"]').attr('disabled','disabled');\r\n      $(this).before('<div style=\"color:red\">\u73fe\u53c2\u52a0\u8005\u6570\u3088\u308a\u5c11\u306a\u3044\u6307\u5b9a\u306f\u3067\u304d\u307e\u305b\u3093<\/div>');\r\n    }else {\r\n      $(this).parents('form').find('input[type=\"submit\"]').removeAttr('disabled');\r\n    }\r\n  });\r\n});",
"id":"eventedit",
"class":"page",
"body":"<?cs if: A.mongo.event.writable ?>\r\n<div class=\"mongo\">\r\n<div class=\"window\" style=\"width:100%;clear:both;\">\r\n<div class=\"hd1\">\r\n<div class=\"h2\">\r\n  <h2>ATND (ATND beta) information<\/h2>\r\n<\/div>\r\n<div class=\"hd2\">\r\n<form method=\"POST\" action=\"<?cs var:C._base ?>\/events\/edit\/<?cs var:A.mongo.event.docid ?>\">\r\n  <table><tbody>\r\n    <tr>\r\n    <th class=\"public\">\u516c\u958b<\/th>\r\n    <td><input type=\"checkbox\" name=\"public\" <?cs if:A.mongo.event.public ?>checked<?cs \/if ?>><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th class=\"event_url\">Event URL<\/th>\r\n    <td><input type=\"text\" name=\"event_url\" value=\"<?cs var:A.mongo.event.event_url ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th class=\"event_id\">Event ID<\/th>\r\n    <td><input type=\"text\" name=\"event_id\" value=\"<?cs var:A.mongo.event.event_id ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u30bf\u30a4\u30c8\u30eb<\/th>\r\n    <td class=\"title\"><input type=\"text\" name=\"title\" readonly=\"readonly\" value=\"<?cs var:A.mongo.event.title ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u30b5\u30d6\u30bf\u30a4\u30c8\u30eb<\/th>\r\n    <td class=\"catch\"><input type=\"text\" name=\"subtitle\" readonly=\"readonly\" value=\"<?cs var:A.mongo.event.subtitle ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u958b\u50ac\u65e5<\/th>\r\n    <td class=\"started_at\"><input type=\"text\" name=\"date\" readonly=\"readonly\" value=\"<?cs var:A.mongo.event.date ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <\/tr><tr>\r\n    <th>\u4f1a\u5834\uff08\u4f4f\u6240\uff09<\/th>\r\n    <td class=\"address\"><input type=\"text\" name=\"address\" readonly=\"readonly\" value=\"<?cs var:A.mongo.event.address ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u4f1a\u5834\u540d<\/th>\r\n    <td class=\"place\"><input type=\"text\" name=\"place\" readonly=\"readonly\" value=\"<?cs var:A.mongo.event.place ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u5b9a\u54e1<\/th>\r\n    <td class=\"limit\"><input type=\"text\" name=\"limit\" readonly=\"readonly\" value=\"<?cs var:A.mongo.event.limit ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n<!--\r\n    <th>\u958b\u50ac\u65e5<\/th>\r\n    <td><input type=\"text\" name=\"date\" value=\"<?cs var:A.mongo.event.date ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u6642\u9593<\/th>\r\n    <td><input type=\"text\" name=\"time\" value=\"<?cs var:A.mongo.event.time ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u4f1a\u5834\uff08\u4f4f\u6240\u7b49 google map\u9023\u643a\uff09<\/th>\r\n    <td><input type=\"text\" name=\"address\"  value=\"<?cs var:A.mongo.event.address ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u5b9a\u54e1<\/th>\r\n    <td><input type=\"text\" name=\"capacity\"  value=\"<?cs var:A.mongo.event.capacity ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u30bf\u30a4\u30c8\u30eb<\/th>\r\n    <td><input type=\"text\" name=\"title\"  value=\"<?cs var:A.mongo.event.title ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u30b5\u30d6\u30bf\u30a4\u30c8\u30eb<\/th>\r\n    <td><input type=\"text\" name=\"subtitle\"  value=\"<?cs var:A.mongo.event.subtitle ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>URL<\/th>\r\n    <td><input type=\"text\" name=\"url\"  value=\"<?cs var:A.mongo.event.url ?>\"><\/input><\/td>\r\n    <\/tr><tr>\r\n    <th>\u6982\u8981<\/th>\r\n    <td><textarea name=\"origin\"><?cs var:A.mongo.event.origin ?><\/textarea><\/td>\r\n    <\/tr><tr>\r\n-->\r\n    <th><\/th>\r\n    <td>\r\n    <input type=\"hidden\" name=\"docid\" value=\"<?cs var:A.mongo.event.docid ?>\"><\/input>\r\n    <input type=\"submit\" name=\"op\" value=\"preview\"><\/input>\r\n    <input type=\"submit\" name=\"op\" value=\"save\"><\/input>\r\n    <a target=\"_blank\" href=\"<?cs var:C._base ?>\/notation\">notation<\/a>\r\n    <\/td>\r\n    <\/tr>\r\n  <\/tbody><\/table>\r\n<\/form>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<\/div>\r\n<script>\r\n<?cs set: a = #0 ?><?cs each:item = A.mongo.event.attenders ?><?cs set: a = a+#1 ?><?cs \/each ?>\r\nvar N_ATTENDERS=<?cs var:a ?>;\r\n<\/script>\r\n<?cs \/if ?>\r\n",
"action":[
""
],
"header":"<link rel=\"stylesheet\" type=\"text\/css\" media=\"all\" href=\"\/_s_\/core\/default\/css\/smoothness\/jquery-ui-1.8.21.custom.css\"><\/link>",
"bottom":"<script src=\"http:\/\/hirkubota:30080\/_s_\/core\/default\/js\/jquery-ui-1.8.21.custom.min.js\"><\/script>",
"_u":"events\/eventedit"
}