{
"@R":"1369111670",
"type":"HorizontalWidget",
"subject":"noryo2013_timetable_header",
"description":"",
"css":"#noryo2013_timetable_header div.nav {\r
  margin: 80px 5px 0 5px;\r
  font-size: 1.2em;\r
  color: #4c3a2c;\r
}\r
#noryo2013_timetable_header div.nav ul {\r
  list-style: none;\r
  padding: 0;\r
  margin: 0;\r
}\r
#noryo2013_timetable_header div.nav li {\r
  float: left;\r
  padding:7px 15px 4px 15px;\r
  border-radius: 8px 8px 0 0;\r
  background-color: #f6f4cd;\r
  border-width: 2px;\r
  border-style: solid;\r
  border-color: #4c3a2c;\r
  opacity: 0.9;\r
}\r
#noryo2013_timetable_header div.nav li.selected {\r
//  border-width: 2px 2px 0 2px;\r
   border-bottom: 2px solid #f6f4cd;\r
}\r
#noryo2013_timetable_header div.nav li:hover {\r
  background-color: #f05500;\r
}\r
#noryo2013_timetable_header div.nav li:hover > a {\r
  color: #ffffff;\r
}\r
\r
div.noryo2013_timetable div.tab {\r
  margin: 0 5px;\r
  padding: 5px;\r
  opacity: 0.9;\r
  background-color: #f6f4cd;\r
  color : #885500;\r
  text-align: left;\r
  border-width: 0 2px 2px 2px;\r
  border-style: solid;\r
  border-color: #402817;\r
  border-radius: 0 0 8px 8px;\r
  line-height: 1.2em;\r
  overflow:hidden;\r
}\r
div.noryo2013_timetable div.tab div.session{\r
  width:296px;\r
  border-radius: 8px;\r
  background-color: #fFf8e0;\r
  border: 1px solid  #402817;\r
  opacity: 0.9;\r
  float: left; \r
  overflow:hidden;\r
}\r
div.noryo2013_timetable div.tab div.session a{\r
}\r
div.noryo2013_timetable div.tab div.session img.logo{\r
 float:left;\r
 height:60px;\r
}\r
\r
div.noryo2013_timetable div.tab div.session:hover {\r
  cursor: pointer;\r
  background-color: #cc5500;\r
  color: #FFFFFF;\r
}\r
div.noryo2013_timetable div.tab div.session:hover a {\r
  color: #FFFFFF;\r
}\r
\r
div.noryo2013_timetable div.tab div.session div.title{\r
  font-weight: 600;\r
  text-decoration: underline;\r
}\r
div.noryo2013_timetable div.tab div.session div.incharge{\r
  float:right;\r
  text-decoration: underline;\r
}\r
div.noryo2013_timetable div.tab div.session div.overview{\r
  float:left;\r
  margin-left:10px;\r
}\r
\r
div.noryo2013_timetable div.details {\r
  display:none;\r
}\r
\r
div.noryo2013_timetable div.detail {\r
  padding: 5px;\r
  font-size: 1.5em;\r
  line-height: 1.5em;\r
  background-color: #f6f4cd;\r
  border-radius: 16px;\r
  border: 2px solid  #402817;\r
  display:none;\r
}\r
div.noryo2013_timetable div.detail td.key {\r
  font-weight:600;\r
  color: #cc5500;\r
  width: 120px;\r
  vertical-align:top;\r
  padding-left: 10px;\r
}\r
div.noryo2013_timetable div.detail td.sep {\r
  font-weight:600;\r
  vertical-align:top;\r
}\r
div.noryo2013_timetable div.detail td.comma {\r
  font-weight:600;\r
  vertical-align: bottom;\r
}\r
div.noryo2013_timetable div.detail td.title {\r
  font-weight:600;\r
  font-size: 1.5em;\r
}\r
div.noryo2013_timetable div.detail hr.sep {\r
  border-color: #4c3a2c;\r
}\r
div.noryo2013_timetable div.detail img.logo {\r
  height: 60px;\r
}\r
\r
div.noryo2013_timetable div.tab table.timetable{\r
  border: 2px solid #402817;\r
  border-radius: 8px;\r
  border-spacing: 0;\r
}\r
div.noryo2013_timetable div.tab table.timetable th {\r
  padding : 2px 5px 1px 5px;\r
  border-bottom: 2px solid #402817;\r
  background-color: #402817;\r
  color: #FFFFFF;\r
  font-size: 1.2em;\r
}\r
div.noryo2013_timetable div.tab table.timetable th.timebox{\r
  width: 80px;\r
}\r
div.noryo2013_timetable div.tab table.timetable th.place {\r
  width: 299px;\r
  border-left: 2px solid  #402817;\r
  border-bottom: 1px dashed  #402817;\r
  text-align: center;\r
}\r
\r
div.noryo2013_timetable div.tab table.timetable td {\r
}\r
div.noryo2013_timetable div.tab table.timetable td.timebox {\r
  height: 98px;\r
  vertical-align:10px;\r
  text-decoration: underline;\r
  font-weight:600;\r
  border-bottom: 1px dashed  #402817;\r
}\r
div.noryo2013_timetable div.tab table.timetable td div.t.t00 {\r
  border-top: 1px solid  #402817;\r
}\r
div.noryo2013_timetable div.tab table.timetable td div.t.t30 {\r
  border-top: 1px dashed  #402817;\r
}\r
div.noryo2013_timetable div.tab table.timetable td div.t {\r
  height:30px;\r
}\r
\r
div.noryo2013_timetable div.tab table.timetable td.place {\r
  border-left: 2px solid  #402817;\r
  border-bottom: 1px dashed  #402817;\r
}\r
\r
div.noryo2013_timetable div.tab table.timetable div.session{\r
  width:296px;\r
  border-radius: 8px;\r
  background-color: #fFf8e0;\r
  border: 1px solid  #402817;\r
  opacity: 0.9;\r
/*  float: left; */\r
  overflow:hidden;\r
/*  z-index: 9999; */\r
}\r
div.noryo2013_timetable div.tab table.timetable div.session a{\r
}\r
div.noryo2013_timetable div.tab table.timetable div.session img.logo{\r
 float:left;\r
 height:60px;\r
}\r
\r
div.noryo2013_timetable div.tab table.timetable div.session:hover {\r
  cursor: pointer;\r
  background-color: #cc5500;\r
  color: #FFFFFF;\r
}\r
div.noryo2013_timetable div.tab table.timetable div.session:hover a {\r
  color: #FFFFFF;\r
}\r
\r
div.noryo2013_timetable div.tab table.timetable div.session div.title{\r
  font-weight: 600;\r
  text-decoration: underline;\r
}\r
div.noryo2013_timetable div.tab table.timetable div.session div.incharge{\r
  float:right;\r
  text-decoration: underline;\r
}\r
div.noryo2013_timetable div.tab table.timetable div.session div.overview{\r
  float:left;\r
  margin-left:10px;\r
}\r
",
"js":"$( function (){\r
  timetableNavs = $('#noryo2013_timetable_header div.nav > ul > li');\r
  timetableNavs .each( function () {\r
    $(this).removeClass('selected');\r
    link = $(this).find('> a').attr('href');\r
    if ( link == window.location.pathname) {\r
      $(this).addClass('selected');\r
    }\r
  });\r
})\r
\r
  function time2date(str){\r
    var t = str.split(':');\r
    var d = new Date(0,0,0,0,0,0);\r
    d.setHours(t[0]);\r
    d.setMinutes(t[1]);\r
    return d;\r
  }\r
function draw_timetable(h,cb_appendTo){\r
    for ( var i in timetable ) {\r
      var height = h;\r
      var session = timetable[i];\r
      if ( ! session.start || ! session.end ) {\r
\tcontinue;\r
      }\r
      if ( ! height ) {\r
\tvar start = time2date(session.start);\r
\tvar end   = time2date(session.end);\r
\tsession.during = (end.getTime() - start.getTime()) / 60000;\r
\theight = session.during * 2;\r
      }\r
\t$('<div class=\"session\" u=\"'+session._u+'\">'+\r
\t  ((session.images.logo)?('<img class=\"logo\" src=\"/_s_/mongo/timetable/'+session.images.logo+'\"></img>'):'') +\r
\t  '<div class=\"title\">'+session.title+'</div>'+\r
\t  '<div class=\"incharge\">'+session.incharge+\"</div>\"+\r
\t  '<div class=\"overview\">'+session.overview+'</div>'+\r
\t  '</div>')\r
//      .css('background','url(\"/_s_/mongo/timetable/'+session.images.logo+'\") no-repeat')\r
//      .css('background-size','30px')\r
\t.css('height',height + 'px')\r
\t.appendTo(cb_appendTo(session));\r
    }\r
  $('div.noryo2013_timetable div.tab div.session').click(function(){\r
    var u = $(this).attr('u');\r
      $('<div id=\"mordal\"></div>')\r
      .css('position','absolute')\r
      .css('top',0)\r
      .css('left',0)\r
      .css('height','10000')\r
      .css('width','10000')\r
      .css('z-index',1000)\r
      .css('background-color','#000000')\r
      .css('opacity',0.3)\r
      .appendTo('body');\r
\r
     var origin_height = $('div.noryo2013_timetable').height();\r
\r
     $('div.detail[u=\"'+u+'\"]').clone()\r
      .attr('id','detail')\r
      .css('position','absolute')\r
      .css('z-index',9999)\r
      .css('top','0px')\r
      .css('left',0)\r
      .appendTo('div.noryo2013_timetable')\r
      .slideDown('normal', function(){ \r
         $('div.noryo2013_timetable').height($(this).height()+150 );\r
         });\r
\r
      $('#mordal').click(function(){\r
\t$(this).remove();\r
\t$('#detail').slideUp('normal',function(){$(this).remove();});\r
        $('div.noryo2013_timetable').height(origin_height);\r
      });\r
      $('#detail > div.close').click(function(){\r
\t  $(this).parent().slideUp('normal',function(){$(this).remove();});\r
\t$('#mordal').remove();\r
        $('div.noryo2013_timetable').height(origin_height);\r
      });\r
  });\r
}",
"id":"noryo2013_timetable_header",
"class":"",
"body":"<nav><div class=\"nav\" role=\"navigation\">\r
  <ul>\r
    <li><a href=\"<?cs var:C._base ?>/noryo2013/top\">TOP</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/noryo2013/exhibition\">\u5c55\u793a</a></li>\r
    <li><a href=\"<?cs var:C._base ?>/noryo2013/timetable\">\u30bf\u30a4\u30e0\u30c6\u30fc\u30d6\u30eb</a></li>\r
<?cs if:S.login.writable ?>\r
    <li><a href=\"<?cs var:C._base ?>/noryo2013/edit\">EDIT</a></li>\r
<?cs /if ?>\r
  </ul>\r
</div></nav>\r
<br clear=\"both\" />\r
\r
<?cs def:draw_details(raw) ?>\r
<div class=\"details\">\r
<?cs each: item = A.mongo.timeboxs.raw ?>\r
 <div class=\"detail\" u=\"<?cs var:item._u ?>\">\r
 <div class=\"close\" style=\"border:1px solid #000000;background-color:#000000;float:right;width:20px;height:20px;\"></div>\r
 <?cs if:item.images.logo ?>\r
   <img class=\"logo\" src=\"/_s_/mongo/timetable/<?cs var:item.images.logo ?>\"></img><br>\r
  <?cs /if ?>\r
{\r
  <table>\r
  <tbody>\r
   <tr>\r
    <td class=\"key\">\"\u30bf\u30a4\u30c8\u30eb\"</td><td class=\"sep\">:</td><td class=\"value title\">\"<?cs var:item.title ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u767a\u8868\u8005\"</td>\u3000<td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.incharge ?>\"</td>   <td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u958b\u59cb\u6642\u523b\"</td><td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.start ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u7d42\u4e86\u6642\u523b\"</td><td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.end ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u6982\u8981\"</td>\u3000\u3000<td class=\"sep\">:</td><td class=\"value\">\"<?cs var:item.overview ?>\"</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u30bf\u30a4\u30d7\"</td>\u3000<td class=\"sep\">:</td><td class=\"value\">[<?cs each: type = item.types ?>\"<?cs var:type ?>\"<?cs if:!last(type) ?>,<?cs /if ?><?cs /each ?>]</td><td class=\"comma\">,</td>\r
   </tr><tr>\r
    <td class=\"key\">\"\u5bfe\u8c61\"</td>\u3000\u3000<td class=\"sep\">:</td><td class=\"value\">[<?cs each: target = item.targets ?>\"<?cs var:target ?>\"<?cs if:!last(target) ?>, <?cs /if ?><?cs /each ?>]</td><td class=\"comma\">,</td>\r
   </tr>\r
 </tbody>\r
 </table>\r
}\r
<hr class=\"sep\" />\r
<div class=\"page\">\r
<?cs each: content = item.contents ?>\r
 <?cs call:drawTags(content)?>\r
<?cs /each ?>\r
</div>\r
</div>\r
<?cs /each ?>\r
</div>\r
<?cs /def ?>",
"action":[
""
],
"header":"",
"bottom":"",
"_u":"noryo2013/timetable/header"
}