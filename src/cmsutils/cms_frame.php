<?php
/**
 * cms_frame.php - CMS
 *  
 * @access public
 * @package cockatoo-cms
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-script-type" content="text/javascript">
<meta name="description" content="">
<?php
//$CONTENT_DRAWER->drawCommonCss();
?>
<link rel="stylesheet" href="css/redmond/jquery-ui-1.8.22.custom.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/cockatoo-cms.css" type="text/css" media="all" />

<style type="text/css"><!--
#co-frame {
  font-size: 12px;
  color:#808080;
  font-family:"Lucida Grande",verdana,arial,helvetica,sans-serif;
  min-width: 1000px;
  width: expression(document.body.clientWidth < 1002? "1000px" : "auto");
  font-weight:normal;
}
#co-main {
  margin: 0;
  padding: 0;
  -moz-border-radius:10px 10px 10px 10px;
}
a {
    text-decoration: none;
    /* cursor: pointer; */	
}
h1,h2,h3,h4,h5,h6 {
  margin: 0;
  padding: 0;
}

div.co-Widget {
  border: 1px dashed #A0A0A0;
  -moz-border-radius: 7px 7px 7px 7px;
  cursor: move;
}
div.co-Widget > h3 {
  padding: 0 0 0 0;
  margin: 0 0 0 0;
  color : #808080;
}

div.co-Widget > h3 > span {
  color: #FFFFFF;
/*  float:right; */
  position: absolute;
  top: 0;
  cursor: pointer;
  margin  : 0 3px 0 0;
  padding : 0 0 16px 16px;
  background-image: url(/_cms_/css/ui-darkness/images/ui-icons_ffffff_256x240.png);
}
div.co-Widget > h3 > span.prev {
  background-position: 0 -64px;
  right:38px;
}
div.co-Widget > h3 > span.next {
  background-position: -32px -64px;
  right:19px;
}
div.co-Widget > h3 > span.up {
  background-position: 0 -48px;
  right:76px;
}
div.co-Widget > h3 > span.down {
  background-position: -64px -48px;
  right:57px;
}
div.co-Widget > h3 > span.del {
  background-position: -96px -128px;
  right:0;
}

div.co-Wbody {
  min-height: 80px;
  height: 100%;
  width: 100%;
}
div.co-Pagelayout > h3 {
  background-color : #000000;
  color : #C0C0C0;
}
div.co-Pagelayout > div.co-Wbody {
  background-color : #404040;
  color : #FFFFFF;
}
div.co-Layout > h3 {
  background-color : #444444;
  color : #C0C0C0;
}
div.co-Layout > div.co-Wbody {
  background-color : #888888;
  color : #FFFFFF;
}
div.co-Html5 > h3 {
  background-color : #bbbbbb;
  color : #FFFFFF;
}
div.co-Html5 > div.co-Wbody {
  background-color : #eeeeee;
}
div.co-Horizontal > h3 {
   background-color : #FFD8D8;
}
div.co-Horizontal > div.co-Wbody {
   background-color : #FFE0E0;
}
div.co-Vertical > h3 {
   background-color : #D8D8FF;
}
div.co-Vertical > div.co-Wbody {
   background-color : #E0E0FF;
}
div.co-Vertical > div.co-Wbody > div {
   width: 200px;
}
div.co-Tile > h3 {
   background-color : #D8FFD8;
}
div.co-Tile > div.co-Wbody {
   background-color : #E0FFE0;
}
div.co-Random > h3 {
   background-color : #FFD8FF;
}
div.co-Random > div.co-Wbody {
   background-color : #FFE0FF;
}
div.co-Tab > h3 {
   background-color : #D8FFFF;
}
div.co-Tab > div.co-Wbody {
   background-color : #E0FFFF;
   min-height: 30px;
}
div.co-Plain > h3 {
   background-color : #F0F0F0;
}
div.co-Plain > div.co-Wbody {
   background-color : #FCFCFC;
   min-height: 30px;
}
div.co-Json > h3 {
   background-color : #E0E0FF;
}
div.co-Json > div.co-Wbody {
   background-color : #F0F0FF;
   min-height: 30px;
}
div.co-Bin > h3 {
   background-color : #E0E0Ep;
}
div.co-Bin > div.co-Wbody {
   background-color : #F0F0E0;
   min-height: 30px;
}

div.co-Tab > div.co-Wbody > div.co-Widget.co-TabChild {
  clear: none;
  float:left;
  height: 20px;
  list-style-type:none;
  white-space:nowrap;
  -moz-border-radius:5px 5px 0px 0px;
  background: #E0E0E0 url("") no-repeat 2px center scroll;
  color:#A0A0A0;
  border-color:#FFFFFF #CFCFCF #CFCFCF #FFFFFF;
  border-style:solid;
  border-width:2px 1px 1px 2px;
  padding:2px 5px 2px 5px;
  margin-bottom:0;
  margin-right:0;
}

div.co-Tab > div.co-Wbody > div.co-Widget.co-TabChild:hover {
  background: #FF2020 url("") no-repeat 2px center scroll;
  color:#FFFFFF;
}
div.co-Tab > div.co-Wbody > div.co-Widget.co-TabChild:last-child {
  position:relative;
  float:left;
  overflow: hidden;
}
div.co-Tab > div.co-Wbody > div.co-Widget.co-TabChild.selected {
  color:#FFFFFF;
  background: #FF4040 url("") no-repeat 2px center scroll;
}


div.co-Tab > div.co-Wbody > div.co-Widget.co-TabChild a {
  text-decoration: none;
  color:#A0A0A0;
}
div.co-Tab > div.co-Wbody > div.co-Widget.co-TabChild.selected a{
  text-decoration: none;
  color:#FFFFFF;
}
div.co-Tab > div.co-Wbody > div.co-Widget.co-TabChild a:hover {
  text-decoration: none;
  color:#FFFFFF;
}

#co-toolbar {
/*   float:left; */
  position: absolute;
  overflow: hidden;
  width : 150px;
  border: 1px solid #A0A0A0;
}
#co-toolbar > h3 {
  background-color : #E0E0E0;
  padding: 0 0 0 0;
  margin: 10px 0 0 0;
}
#co-toolbar > div.co-Trash {
  float: none;
  width: 140px;
  height: 100px;
}
#co-toolbar > div.co-Trash > div.co-Wbody {
  height: 50px;
  overflow: hidden;
}
#co-toolbar > div.co-Trash > div.co-Wbody div {
  font-size: 0.6em;
  height: 12px;
  min-height : 12px;
  width : 100px;
  min-width : 100px;
  padding: 0 0 0 0;
  margin:  0 0 0 0;
}
#co-toolbar > div.co-Trash > div.co-Wbody div.co-Wbody {
  height: 0px;
  min-height: 0px;
}

#co-toolbar div.co-WidgetTree div.co-Widget {
  font-size: 0.6em;
  margin: 5px 1px 0 0;
  height: 20px;
  min-height: 20px;
  width: 70px;
}
#co-toolbar div.co-WidgetTree div.co-Widget > h3 {
  min-height: 20px;
  height: 20px;
}
#co-toolbar div.co-WidgetTree div.co-Widget > div.co-Wbody {
  height: 0px;
  min-height: 0px;
  width: 100%;
  min-width: 20px;
}

#co-toolbar div.co-WidgetTree div.dir {
  margin-left:10px;
}
#co-toolbar div.co-WidgetTree h4.name {
  margin-left:-10px;
  background-color : #F0F0E0;
  cursor: pointer;
}

#co-toolbar > div > div.co-Widget {
  font-size: 0.6em;
  float: left;
  margin: 5px 1px 0 0;
  height: 20px;
  min-height: 20px;
  width: 70px;
}

#co-toolbar > div >  div > div.co-Wbody {
  height: 0px;
  min-height: 0px;
  width: 100%;
  min-width: 20px;
}
#co-toolbar > div >  div.co-Widget > h3 {
  min-height: 20px;
  height: 20px;
}
#co-toolbar > div >  div.co-Widget.co-TabChild > h3 {
  background: #E0E0E0 url("") no-repeat 2px center scroll;
  height: 100%;
}
#co-toolbar div.co-Widget.co-TabChild > div.co-Wbody {
  height: 0px;
  min-height: 0px;
}

#co-toolbar div.co-Widget > h3 > span {
  visibility: hidden;
}

#co-cms {
/*   float: right; */
  position: relative;
  margin-top: 0px;
  margin-left: 150px;
  width : auto;
}
#co-main {
  overflow-x:scroll;
}
div.ui-dialog div.label {
  float: left;
  width : 100px;
}
div.ui-dialog div.value {
  position: relative;
  padding-left: 0px;
  margin-left: 100px;
  width: 200px;
}
div.value > input {
  width: 200px;
}
div.value > textarea {
  height:200px;
  width: 200px;
}

div.co-Wbody[over="true"] {
  border: 2px solid #A00000;
}

#ctrl  {
  clear: both;
}
#shortcut-help  {
  float : left;
  vertical-align:bottom;
  margin: 0 0 0 600px;
}
#shortcut-help > a  {
  text-decoration: underline;
  color: blue;
}
#shortcut-help > div.shortcut  {
  display: none;
}
#shortcut-help:hover > div.shortcut  {
  display: block;
  position: fixed;
  overflow: visible;
  z-index:99999;
  text-align: left;
  border: solid 1px #4444FF;
  border-radius: 4px;
  background-color: #ffffff;
}
div.co-Widget.cursor {
  border: 3px solid #EC0000;
}
--></style>

<title>TITLE</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.22.custom.min.js"></script>
<script type="text/javascript" src="js/jquery-json/jquery.json-2.2.js"></script>
<script type="text/javascript" src="js/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/cockatoo-dialog.js"></script>
<script type="text/javascript" src="js/cockatoo-validator.js"></script>

<script language="javascript">
<!--
$(function () {
    $('#Hidden > div').each(function(){
      var widget  = $(this);
      var name = widget.attr('component');
      regex = new RegExp('component://([^\-]+)-[^/]+/([^?]*)');
      name.replace(regex,function(){
	root = arguments[1];
	path = arguments[2]+'?';
	var selector = '#co-toolbar div.co-WidgetTree';
	p=path.split('/');
	p.unshift(root);
	for(i in p){
	  d = p[i];
	  var  current = $(selector);
	  if ( /\?$/.test(d)){
	    //current.children('h4.name').after(widget);
	    current.append(widget);
	  }else{
	    selector += ' > div.'+d;
	    if ( $(selector).length == 0) {
	      current.children('h4.name').after('<div class="dir '+d+'"><h4 class="name">'+d+'</h4></div>');
	      // current.append('<div class="dir '+d+'"><h4 class="name">'+d+'</h4></div>');
	    }
	  }
	}
      });
    });
    $('#co-toolbar div.co-WidgetTree div.dir > div').hide();
    $('#co-toolbar div.co-WidgetTree h4.name').click(function(ev){
	$(this).parent().children('div').toggle();
    });
    $("#fix").click(function (ev) {
        if ( $("#co-main").children("div.co-Widget").size() !== 1 ) {
          return;
        }
        $("#co-main").children("div.co-Widget").each(function (){
            var ret = widget_json($(this));
            fix(OP,ret);
          });
      });
//    $("#preview").click(function (ev) {
// 	preview();
//    });
    $("#co-toolbar > h3").click(function (ev) {
      $(this).next().slideToggle();
    });
  
    $("span.del").click(function (ev) {
      var my = $(this).parents('div.co-Widget:first');
      my.appendTo($('#co-toolbar > div.co-Trash > div.co-Wbody'));
    });
    $("span.up").click(function (ev) {
      var my = $(this).parents('div.co-Widget:first');
      my.insertBefore(my.prev());
    });
    $("span.down").click(function (ev) {
      var my = $(this).parents('div.co-Widget:first');
      my.insertAfter(my.next());
    });
    $("span.prev").click(function (ev) {
      var my = $(this).parents('div.co-Widget:first');
      my.insertBefore(my.parents('div.co-Widget:first'));
    });
    $("span.next").click(function (ev) {
      var my = $(this).parents('div.co-Widget:first');
      my.appendTo(my.next().find('div.co-Wbody:first'));
    });

  function widget_json(widget){
    var ret = {};
    ret.type  =  widget.attr('type');
    ret['class']  = widget.find('> form > div.value > input[name=class]').val();
    ret.height = widget.find('> form > div.value > input[name=height]').val();
    ret.width  = widget.find('> form > div.value > input[name=width]').val();
    ret.min_height = widget.find('> form > div.value > input[name=min_height]').val();
    ret.min_width  = widget.find('> form > div.value > input[name=min_width]').val();
    ret.vpos  = widget.find('> form > div.value > select[name=vpos]').val();
    ret.swidth  = widget.find('> form > div.value > input[name=swidth]').val();
    ret.extra  = widget.attr('extra');
    ret.component = widget.attr('component');
    ret.children = [];
    widget.find('> div.co-Wbody > div.co-Widget').each(function(){
      ret.children.push(widget_json($(this)));
    });
    return ret;
  }
  // @@@ It sounds like there are some bugs around greedy ...
  //      Sometimes it fire in double.
  curEventTimeStamp = 0;

  function set_dd (widget) {
    widget.filter('.co-Template').not('.co-Trash').find('div.co-Wbody').text('');
    widget.filter('.co-Template').not('.co-Trash').draggable({
      delay: 150,
      distance: 10,
      scroll: true,
      scrollSensitivity: 50,
      scrollSpeed: 50,
      zIndex: 9999,
      // revert: true,
      helper: function(e,ui){
	return $(this).clone();
      }
    });
    widget.not('.co-Template').draggable({
      delay: 150,
      distance: 10,
      scroll: true,
      scrollSensitivity: 50,
      scrollSpeed: 50,
      zIndex: 9999,
      revert: true
      // snap: true
    });
    widget.filter('.co-Trash').find('div.co-Wbody').droppable({
      greedy:true,
      tolerance: "pointer",
      accept: function(t){
	return ! (t.hasClass('co-Template') || t.hasClass('co-Pagelayout'))
      },
      drop: function(e,ui){
        ui.draggable.css('left',0);
        ui.draggable.css('top',0);
        ui.draggable.appendTo($(this));
        preview();
      }
    });
    widget.not('.co-Template').not('.co-Pagelayout').children('h3').droppable({
      greedy:true,
      tolerance: "pointer",
      accept: function(t){
	if ( $(this).parents('div.co-Widget').hasClass('co-TabChild') ) {
	  return t.hasClass('co-TabChild');
	}else{
	  return ! t.hasClass('co-TabChild');
	}
	return true;
      },
      drop: function(e,ui){
	if ( curEventTimeStamp == e.timeStamp ) {
	  return; // @@@ maybe greedy bug ...
	}
	curEventTimeStamp = e.timeStamp;

	// Vertical regulation
	if ( $(this).parent().parent().parent().hasClass('co-Vertical') && $(this).parent().parent().children().size() >= 3) {
	  full = true;
	  $(this).parent().parent().children().each(function (i,child){
	    if ( child == ui.draggable.context ) {
	      full = false;
	    }
	  });
	  if ( full ) {
	    return; // Cannot add any more !!
	  }
	}

        ui.draggable.css('left',0);
        ui.draggable.css('top',0);
	if ( ui.draggable.hasClass('co-Template') ) {
	  var t = ui.draggable.clone();
	  t.removeClass('co-Template');
	  t.children('div.co-Wbody').text('');
	  set_dd(t);
          if ( t.hasClass('co-Fixed') || $(this).parent().hasClass('co-Fixed') ){
            $('#co-main').children().appendTo($('div.co-Trash > div.co-Wbody'));
            t.appendTo($('#co-main'));
          }else {
            $(this).parent().before(t);
          }
	} else {
            $(this).parent().before(ui.draggable);
	}
        // verticalProc( $(this).parent().parent().parent() );
        preview();
      },
      over: function(ev, ui) {
      },
      out: function(ev, ui) {
      }
    });
    widget.not('.co-Template').not('.co-Pagelayout').not('.co-Fixed').children('div.co-Wbody').droppable({
      greedy:true,
      tolerance: "pointer",
      accept: function(t){
	if ( $(this).parents('div.co-Widget').hasClass('co-Tab') ) {
	  return t.hasClass('co-TabChild');
	}else{
	  return ! t.hasClass('co-TabChild');
	}
	return true;
      },
      drop: function(e,ui){
	if ( curEventTimeStamp == e.timeStamp ) {
	  return; // @@@ maybe greedy bug ...
	}
	curEventTimeStamp = e.timeStamp;

	// Vertical nregulation
	if ( $(this).parent().hasClass('co-Vertical') && $(this).children().size() >= 3) {
	  full = true;
	  $(this).children().each(function (i,child){
	    if ( child == ui.draggable.context ) {
	      full = false;
	    }
	  });
	  if ( full ) {
	    return; // Cannot add any more !!
	  }
	}

        ui.draggable.css('left',0);
        ui.draggable.css('top',0);
	if ( ui.draggable.hasClass('co-Template') ) {
	  var t = ui.draggable.clone();
	  t.removeClass('co-Template');
	  t.children('div.co-Wbody').text('');
	  set_dd(t);
          if ( t.hasClass('co-Fixed') ){
            $('#co-main').children().appendTo($('div.co-Trash > div.co-Wbody'));
            t.appendTo($('#co-main'));
          }else {
            t.appendTo($(this));
          }
	} else {
          ui.draggable.appendTo($(this));
	}
	$(this).removeAttr('over');
//        verticalProc( $(this).parent() );
        preview();
      },
      over: function(e, ui) {
	$(this).attr('over',true);
      },
      out: function(e,ui) {
	$(this).removeAttr('over');
      }
    });
//    function verticalProc( t , f ) {
//      if ( ! f ) {
//	f = t.children('form');
//      }
//      if ( f.find('select[name=vpos]') ) {
//        t.find('> div.co-Wbody > div.co-Widget').css('margin','0 0 0 0');
//        t.find('> div.co-Wbody > div.co-Widget').removeClass('co-VMain');
//        t.find('> div.co-Wbody > div.co-Widget').addClass('co-VSub');
//        t.find('> div.co-Wbody > div.co-Widget:last-child').removeClass('co-VSub');
//        t.find('> div.co-Wbody > div.co-Widget:last-child').addClass('co-VMain');
//        t.find('> div.co-Wbody > div.co-VMain').css('float','none');
//        t.find('> div.co-Wbody > div.co-VMain').css('width','auto');
//        t.find('> div.co-Wbody > div.co-VMain').css('margin-'+f.find('select[name=vpos]').val(),f.find('input[name=swidth]').val());
//        t.find('> div.co-Wbody > div.co-VSub').not('.co-VMain').css('float',f.find('select[name=vpos]').val());
//        t.find('> div.co-Wbody > div.co-VSub').css('width',f.find('input[name=swidth]').val());
//      }
//    }
    function auto_size(v){
      if ( /\S/.test(v) ) {
	return v;
      }
      return 'auto';
    }
    widget.not('.co-Template').dblclick(function (e) {
      if ( $('body > div.ui-dialog:visible').size() == 0) {
	form = $(this).children('form').clone();
	var t = $(this);
	form.bind('notice',function (ev) {
//	  t.css('height'    ,auto_size($(this).find('input[name=height]').val()));
//	  t.css('width'     ,auto_size($(this).find('input[name=width]').val()));
//	  t.css('min-height',auto_size($(this).find('input[name=min_height]').val()));
//	  t.css('min-width' ,auto_size($(this).find('input[name=min_width]').val()));
//            verticalProc(t,$(this));
            preview();
	});
	form.children('div.label').click( function (e){
	  if ( $(this).next().is(':hidden') ) {
	      $(this).next().show();
	      $(this).css('float','left');
	  }else {
	      $(this).next().hide();
	      $(this).css('float','none');
	  }
	});
        form.show().dialog_form(
           {
	     height:300,
	     width: 400,
             title: $(this).children('h3').text(),
             target: $(this)
	   },
           {
	    rules: {
	      subject: {
		required: true
	      },
	      description: {
		required: true
	      },
	      body: {
	      },
	      height: {
		css_size: true
	      },
	      width: {
		css_size: true
	      },
	      min_height: {
		css_size: true
	      },
	      min_width: {
		css_size: true
	      },
	      swidth: {
		css_size: true
	      },
	      'class': {
	      },
	      js: {
	      },
	      css: {
	      },
	      body: {
	      },
	      action: {
		brls: true
	      }
	    }
	  });
      }
    });
  }
<?php
    print 'SERVICE_ID="'.$SERVICE.'";TEMPLATE_ID="'.$TEMPLATE.'";PAGE_ID="'.$PATH.'";LAYOUT_ID="'.$LAYOUT.'";OP="'.$OP.'";';
?>
  set_dd($('div.co-Widget'));
  function preview(){
    if ( $("#co-main").children("div.co-Widget").size() !== 1 ) {
      return;
    }
    $("#co-main").children("div.co-Widget").each(function (){
 	var ret = widget_json($(this));
        $('#pform > input[name="layout"]').val($.toJSON(ret));
        
      });
    $("#co-toolbar > div.co-Trash").children("div.co-Wbody").each(function (){
 	var ret = $(this).html();
        $('#pform > input[name="trash"]').val(ret);
      });
    $('#pform').submit();
  }
  function fix(op,data){
    $.ajax({
      url: 'cms_ajax.php',
      type:'POST',
      dataType: 'json',
      data: {
        op     : op,
        service_id    : SERVICE_ID,
        template_id    : TEMPLATE_ID,
        page_id    : PAGE_ID,
        layout_id    : LAYOUT_ID,
	layout : $.toJSON(data)
      },
      success: function( data ){
        if ( 'emsg' in data ) {
          var m = $('b.message');
          m.text(data.emsg).slideDown(1000);
          setTimeout(function(){ m.slideUp(1000);},3000);
          return;
        }
	//location.reload();
	location = location;
      }
    });
  }

  function cur_select(cur,nxt){
    if ( nxt.length == 1 ) {
      cur.removeClass('cursor');
      nxt.addClass('cursor');
      return true;
    }
    return false;
  }
  function cur_down() {
    var cur  = $('div.co-Widget.cursor');
    var nxt = cur.nextAll('div.co-Widget:first');
    return cur_select(cur,nxt);
  }
  function cur_up() {
    var cur  = $('div.co-Widget.cursor');
    var nxt = cur.prevAll('div.co-Widget:first');
    return cur_select(cur,nxt);
  }
  function cur_prev() {
    var cur  = $('div.co-Widget.cursor');
    var nxt = cur.parents('div.co-Widget:first');
    return cur_select(cur,nxt);
  }
  function cur_next() {
    var cur  = $('div.co-Widget.cursor');
    var nxt = cur.find('div.co-Widget:first');
    return cur_select(cur,nxt);
  }
  function cur_space() {
    var cur  = $('div.co-Widget.cursor').dblclick();
  }
  function cur_move_up() {
    var cur  = $('div.co-Widget.cursor').find("span.up").click();
  }
  function cur_move_down() {
    var cur  = $('div.co-Widget.cursor').find("span.down").click();
  }
  function cur_move_prev() {
    var cur  = $('div.co-Widget.cursor').find("span.prev").click();
  }
  function cur_move_next() {
    var cur  = $('div.co-Widget.cursor').find("span.next").click();
  }
  function cur_del() {
    var cur  = $('div.co-Widget.cursor');
    if ( cur_prev() ) {
      cur.find("span.del").click();
    }
  }
  function cur_update() {
  }
  $(window).keydown(function(e){
      if ( e.ctrlKey == true ) {
        if ( e.shiftKey == true ) {
          if       ( e.which == 40 ) { // down
            cur_move_down();
          }else if ( e.which == 38 ) { // up
            cur_move_up();
          }else if ( e.which == 37 ) { // left
            cur_move_prev();
          }else if ( e.which == 39 ) { // right
            cur_move_next();
          }else if ( e.which == 68 ) { // d
            cur_del();
          }
        }else{
          if       ( e.which == 40 ) { // down
            cur_down();
          }else if ( e.which == 38 ) { // up
            cur_up();
          }else if ( e.which == 37 ) { // left
            cur_prev();
          }else if ( e.which == 39 ) { // right
            cur_next();
          }else if ( e.which == 32 ) { // space
            cur_space();
          }else if ( e.which == 65 ) { // a
            cur_add();
          }
        }
				return false;
      }
      return true;
    });
  function shortcut(){
    node = $('<div class="shortcut"></div>');
    node.append('<table><tbody></tbody></table>');
    node.find('tbody').append('<tr><th>Shortcut</th><th>Effect</th></tr>')
      .append('<tr><th>Ctrl-[SPACE]</th><td>Click current component</td></tr>')
      .append('<tr><th>Ctrl-[UP]</th><td>Move CURSOR forcus to upward</td></tr>')
      .append('<tr><th>Ctrl-[DOWN]</th><td>Move CURSOR forcus to downward</td></tr>')
      .append('<tr><th>Ctrl-[RIGHT]</th><td>Move CURSOR forcus to child</td></tr>')
      .append('<tr><th>Ctrl-[LEFT]</th><td>Move CURSOR forcus to parent</td></tr>')
      .append('<tr><th>Ctrl-Shift-[DOWN]</th><td>Move COMPONENT to downward</td></tr>')
      .append('<tr><th>Ctrl-Shift-[UP]</th><td>Move COMPONENT to upward</td></tr>')
      .append('<tr><th>Ctrl-Shift-[RIGHT]</th><td>Move COMPONENT into next component</td></tr>')
      .append('<tr><th>Ctrl-Shift-[LEFT]</th><td>Move COMPONENT to forward of parent component</td></tr>')
      .append('<tr><th>Ctrl-Shift-d</th><td>Delete COMPONENT</td></tr>')
    node.hover(null,function(){
//	$(this).empty();
      });
    $('#shortcut-help').append(node);
    $('#co-main > div.co-Widget').addClass('cursor');
  }
  shortcut();
  });
-->
</script>
</head>

<body id="co-frame">
  <b class="message"></b><br>
  <?php
  if ( $LAYOUT ) {
    print "$SERVICE/(layout)$LAYOUT<br>";
  }else{
    print "$SERVICE/$PATH<br>";
  }
print "$TEMPLATE<br>";

?>
  <div id="shortcut-help"><a>shourtcut help</a></div>
  <div id="ctrl">
   <input id="fix" type="submit" value="fix" />
   <!-- 
   <input id="preview" type="submit" value="preview" />
   -->
  </div>
<!--
  <div>
   <input id="reset" type="submit" value="reset" />
  </div>
-->
 <div id="co-toolbar">
  <h3>Trash</h3>
  <div class="co-Widget co-Horizontal co-Trash co-Template">
    <h3>ごみ箱</h3>
    <div class="co-Wbody">
<?php
  if ( $trash ) {
    print $trash;
  }
?>
    </div>
  </div>
  <h3>Widgets</h3>
  <div class="co-WidgetTree"><h4 class="name"></h4>
  </div>
  <div id="Hidden" style="display:none">
<?php
foreach( $COMPONENTS_DRAWERS as $COMPONENTS_DRAWER ) {
  $COMPONENTS_DRAWER->drawTemplate();
}
?>
  </div>
 </div>
 <div id="co-cms">
  <div id="co-main">
<?php
$CONTENT_DRAWER->drawCMS();
?>
  </div>
 </div>
 <form id="pform" method="POST" action="">
  <input type="hidden" name="layout" value="" />
  <input type="hidden" name="trash" value="" />
 </form>
</body>
</html>
