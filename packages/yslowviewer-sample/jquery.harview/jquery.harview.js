/**
 * jquery.harview.js - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/27
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */

;$(function(){
    $.extend($.fn,{
      harViewer: function(har,options){
	if (!this.length) {
	  return;
	}
	return new $.HarViewer($(this),har,options);
      }
    }); 

    $.HarViewer = function (node,har,options) {
      var t = this;
      t.root = node;
      t.settings = $.extend( {}, $.HarViewer.defaults, options );
      t.HAR = har;
      t.init();
    };

  function basename(path) {
    return path.replace(/\\/g,'/').replace( /.*\//, '' );
  }
  function b2k(bytes) {
    return Math.floor(bytes*10/1024)/10;
  }
  function m2s(ms) {
    return Math.floor(ms*100/1000)/100;
  }
  function mime2type(mime) {
    if       ( /html/i.test(mime) ) {
      return 'html';
    }else if ( /image/i.test(mime) ) {
      return 'image';
    }else if ( /javascript/i.test(mime) ) {
      return 'js';
    }else if ( /json/i.test(mime) ) {
      return 'js';
    }else if ( /css/i.test(mime) ) {
      return 'css';
    }else if ( /text/i.test(mime) ) {
      return 'html';
    }
    return 'unknown';
  }
    $.extend($.HarViewer, {
      defaults: {
	barWidth:650
      },
      prototype: {
	alert: function(msg){
	  $('<div class="alert">'+msg+'</div>').prependTo(this.root);
	},
	init: function(){
	  if ( this.HAR.log.pages.length != 1 ) {
	    this.alert('this HAR contains multiple page info !!');
	  }
	  this.drawHar(this.HAR.log.pages[0]);
	},
	drawHar: function(page){
	    $('<div class="har">' + 
	      ' <div class="pageName">'+
	      '  <span class="twisty"></span>'+
	      '  <span class="pageName">'+page.title+'</span>'+
	      ' </div>'+
	      ' <table><tbody class="hvbody"></tbody></table>'+
	      '</div>').appendTo(this.root);
	  this.hvbody = this.root.find('tbody.hvbody');
	  this.barRatio = 0.95 / this.HAR.log.pages[0].pageTimings.onLoad;
	  this.startDate=new Date(this.HAR.log.pages[0].startedDateTime).getTime();
	  var size  = 0;
	  var csize = 0;
	  for ( var i in this.HAR.log.entries ) {
	    var is_cache = this.drawEntry(this.HAR.log.entries[i]);
	    // size
	    size+=this.HAR.log.entries[i].response.content.size
	    // cache
	    if ( is_cache ) {
	      csize+=this.HAR.log.entries[i].response.content.size
	    }
	  }
	  $('<div class="last">'+
	    '<span class="summary">'+this.HAR.log.entries.length+' Requests</span>'+
	    '<span class="size">'+b2k(size)+' KB</span>'+
	    '<span class="cache">('+b2k(csize)+'  From Cache)</span>'+
	    '<span class="resp">'+m2s(this.HAR.log.pages[0].pageTimings.onContentLoad)+'s (onload:'+m2s(this.HAR.log.pages[0].pageTimings.onLoad)+'s)</span>'+
	    '</div>').appendTo(this.root.find('div.har'));

	  // JS
	  this.root.find('.twisty').click(function(ev){
	    if ( $(this).hasClass('plus') ) {
		$(this).removeClass('plus');
		$(this).parent().next().show();
	    }else {
		$(this).addClass('plus');
		$(this).parent().next().hide();
	    }
	  });
	  this.root.find('div.url').click(function(ev){
	    $(this).parent().prev().click();
	  });
	  this.hvbody.find('tr.detail  > td >  ul.tab > li').click(function(ev){
	    console.log($(this).parent());
	      $(this).parent().find('> li').removeAttr('selected');
	      $(this).attr('selected','selected');
	    var idx = $(this).attr('idx');
	      $(this).closest('td').find('> div').removeAttr('selected');
	      $(this).closest('td').find('> div[idx="'+idx+'"]').attr('selected','selected');
	  });
	},
	calcStart: function(time){
	  return (new Date(time).getTime() - this.startDate);
	},
	calcMargin: function(time){
	  return Math.floor(time*this.barRatio*this.settings.barWidth);
	},
	drawEntry: function(entry){
	  // Parse request header
	  var reqHeadersHTML='<h6>Request Headers</h6><table><tbody>';
	  for( var i in entry.request.headers){
	    if ( entry.request.headers[i].name ==='Host'){
	      var host=entry.request.headers[i].value;
	    }
	    reqHeadersHTML+='<tr><th>'+entry.request.headers[i].name+'</th><td>'+entry.request.headers[i].value+'</td></tr>';
	  }
	  reqHeadersHTML+='</tbody></table>';
	  // Parse response header
	  var caches = [];
	  var resHeadersHTML='<h6>Response Headers</h6><table><tbody>';
	  for( var i in entry.response.headers){
	    resHeadersHTML+='<tr><th>'+entry.response.headers[i].name+'</th><td>'+entry.response.headers[i].value+'</td></tr>';
	    if ( entry.response.headers[i].name === 'Expires' ){
	      caches.push({name:entry.response.headers[i].name,value:entry.response.headers[i].value});
	    }else if ( entry.response.headers[i].name === 'Etag' ){
	      caches.push({name:entry.response.headers[i].name,value:entry.response.headers[i].value});
	    }else if ( entry.response.headers[i].name === 'Vary' ){
	      caches.push({name:entry.response.headers[i].name,value:entry.response.headers[i].value});
	    }else if ( entry.response.headers[i].name === 'Cache-Control' &&
		      !entry.response.headers[i].value === 'private' &&
		      !entry.response.headers[i].value === 'no-cache'){
	      caches.push({name:entry.response.headers[i].name,value:entry.response.headers[i].value});
	    }
	  }
	  resHeadersHTML+='</tbody></table>';

	  // Parse params
	  var queryStringHTML='<table><tbody>';
	  for( var i in entry.request.queryString ){
	    queryStringHTML+='<tr><th>'+entry.request.queryString[i].name+'</th><td>'+entry.request.queryString[i].value+'</td></tr>';
	  }
	  queryStringHTML+='</tbody></table>';
	  // Parse POST
	  var postHTML='<table><tbody>';
	  if ( entry.request.postData && entry.request.postData.params ) {
	    for( var i in entry.request.postData.params ){
	      postHTML+='<tr><th>'+entry.request.postData.params[i].name+'</th><td>'+entry.request.postData.params[i].value+'</td></tr>';
	    }
	    postHTML+='</tbody></table>';
	  }
//  console.log(entry.request.url);
//  console.log(entry);
	  // Parse cache
	  var cachesHTML = ' <h6>Response</h6><table><tbody>';
	  for( var i in caches){
	    cachesHTML+='<tr><th>'+caches[i].name+'</th><td>'+caches[i].value+'</td></tr>';
	  }
	  cachesHTML+='</tbody></table>';
	  cachesHTML+= ' <h6>afterRequest</h6><table><tbody>';
	  for( var i in entry.cache.afterRequest){
	    cachesHTML+='<tr><th>'+i+'</th><td>'+entry.cache.afterRequest[i]+'</td></tr>';
	  }
	  cachesHTML+='</tbody></table>';
	  var startTime = this.calcStart(entry.startedDateTime);
	  var domM      = this.calcMargin(this.HAR.log.pages[0].pageTimings.onContentLoad);
	  var loadedM   = this.calcMargin(this.HAR.log.pages[0].pageTimings.onLoad);
	  var startM    = this.calcMargin(startTime);
	  var blockM    = this.calcMargin(entry.timings.blocked);
	  var dnsM      = this.calcMargin(entry.timings.dns);
	  var connectM  = this.calcMargin(entry.timings.connect);
	  var sendM     = this.calcMargin(entry.timings.send);
	  var waitM     = this.calcMargin(entry.timings.wait);
	  var receiveM  = this.calcMargin(entry.timings.receive);

	  var type      = mime2type(entry.response.content.mimeType);

	    $('<tr class="row" type="'+type+'">'+
	      ' <td class="padding"></td>'+
	      ' <td class="twisty plus"></td>'+
	      ' <td class="col url">'+
	      '  <div class="col url"> '+
	      '   <span class="full">'+entry.request.url+'</span>'+
	      '   <span class="href">'+entry.request.method+' '+basename(entry.request.url)+'</span>'+
	      '  </div>'+
	      ' </td>'+
	      ' <td class="col status"><div class="status '+(( caches.length || entry.cache.afterRequest )?'cached':'')+'"><span>'+entry.response.status+' '+entry.response.statusText+'</span></div></td>'+
	      ' <td class="col type"><div class="type"><span>'+entry.response.content.mimeType+'</span></div></td>'+
	      ' <td class="col domain"><div class="domain"><span>'+host+'</span></div></td>'+
	      ' <td class="col size"><div class="size"><span>'+b2k(entry.response.content.size)+' KB</span></div></td>'+
	      ' <td class="timeline">'+
	      '  <div class="timeline" style="width:'+this.settings.barWidth+'px">'+
	      '   <div class="dom"    style="margin-left:'+domM+                                              'px;"></div>'+
	      '   <div class="loaded" style="margin-left:'+loadedM+                                           'px;"></div>'+
	      '   <div class="b bar"  style="margin-left:'+startM+                                            'px;width:'+blockM+'px;"></div>'+
	      '   <div class="d bar"  style="margin-left:'+(startM+blockM)+                                   'px;width:'+dnsM+'px;"></div>'+
	      '   <div class="c bar"  style="margin-left:'+(startM+blockM+dnsM)+                              'px;width:'+connectM+'px;"></div>'+
	      '   <div class="s bar"  style="margin-left:'+(startM+blockM+dnsM+connectM)+                     'px;width:'+sendM+'px;"></div>'+
	      '   <div class="w bar"  style="margin-left:'+(startM+blockM+dnsM+connectM+sendM)+               'px;width:'+waitM+'px;"></div>'+
	      '   <div class="r bar"  style="margin-left:'+(startM+blockM+dnsM+connectM+sendM+waitM)+         'px;width:'+receiveM+'px;"></div>'+
	      '   <div class="t"      style="margin-left:'+(startM+blockM+dnsM+connectM+sendM+waitM+receiveM)+'px">'+entry.time+'ms</div>'+
	      '   <div  class="box"><div>'+
	      '    <b>+' + startTime+'ms</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Request start time since the beginning<br><br>'+
	      '    Request phases start and elapsed time relative to <br> the request start:<br>' +
	      '    <table><tbody>'+
	      '     <tr>'+
	      '      <td><div class="b bar" style="width:8px"></div></td>'+
	      '      <td><div class="base">+' + 0 + 'ms</span></td>'+
	      '      <td><div class="elaps">'+entry.timings.blocked+'ms</span></td>'+
	      '      <td><div class="remark">Blocking</div></td>'+
	      '     </tr>'+
	      '     <tr>'+
	      '      <td><div class="d bar" style="width:8px"></div></td>'+
	      '      <td><div class="base">+' + entry.timings.blocked + 'ms</span></td>'+
	      '      <td><div class="elaps">'+entry.timings.dns+'ms</span></td>'+
	      '      <td><div class="remark">DNS Lookup</div></td>'+
	      '     </tr>'+
	      '     <tr>'+
	      '      <td><div class="c bar" style="width:8px"></div></td>'+
	      '      <td><div class="base">+' + (entry.timings.blocked + entry.timings.dns) + 'ms</span></td>'+
	      '      <td><div class="elaps">'+entry.timings.connect+'ms</span></td>'+
	      '      <td><div class="remark">Connecting</div></td>'+
	      '     </tr>'+
	      '     <tr>'+
	      '      <td><div class="s bar" style="width:8px"></div></td>'+
	      '      <td><div class="base">+' + (entry.timings.blocked + entry.timings.dns+entry.timings.connect)+ 'ms</span></td>'+
	      '      <td><div class="elaps">'+entry.timings.send+'ms</span></td>'+
	      '      <td><div class="remark">Sending</div></td>'+
	      '     </tr>'+
	      '     <tr>'+
	      '      <td><div class="w bar" style="width:8px"></div></td>'+
	      '      <td><div class="base">+' + (entry.timings.blocked + entry.timings.dns+entry.timings.connect+entry.timings.send)+ 'ms</span></td>'+
	      '      <td><div class="elaps">'+entry.timings.wait+'ms</span></td>'+
	      '      <td><div class="remark">Waiting</div></td>'+
	      '     </tr>'+
	      '     <tr>'+
	      '      <td><div class="r bar" style="width:8px"></div></td>'+
	      '      <td><div class="base">+' + (entry.timings.blocked + entry.timings.dns+entry.timings.connect+entry.timings.send+entry.timings.wait)+ 'ms</span></td>'+
	      '      <td><div class="elaps">'+entry.timings.receive+'ms</span></td>'+
	      '      <td><div class="remark">Receiving</div></td>'+
	      '     </tr>'+
	      '    </tbody></table>'+
	      '    <br>'+
	      '    Event timing relative to the request start:<br>' +
	      '    <table><tbody>'+
	      '     <tr>'+
	      '      <td><div class="dom" style="width:1px;height:10px;margin-top:-3px;"></div></td>'+
	      '      <td><div class="elaps">'+(this.HAR.log.pages[0].pageTimings.onContentLoad-startTime)+'ms</span></td>'+
	      '      <td><div class="remark">DOM Loaded</div></td>'+
	      '     </tr>'+
	      '     <tr>'+
	      '      <td><div class="loaded" style="width:1px;height:10px;margin-top:-3px;"></div></td>'+
	      '      <td><div class="elaps">'+(this.HAR.log.pages[0].pageTimings.onLoad-startTime)+'ms</span></td>'+
	      '      <td><div class="remark">Page Loaded</div></td>'+
	      '     </tr>'+
	      '    </tbody></table>'+
	      '   </div></div>'+
              '  </div>'+
	      ' </td>'+
	      '</tr>').appendTo(this.hvbody);
	  // Details
	    var tab = $('<tr class="detail"><td class="padding"></td><td colspan="7">'+
	      ' <ul class="tab">'+
	      ' </ul></td></tr>').appendTo(this.hvbody).find('ul.tab');
	  // Headers
	    $('<li idx="headers" selected="selected">Headers</li>').appendTo(tab);
	    $('<div idx="headers" selected="selected">'+resHeadersHTML+reqHeadersHTML+'</div>').insertAfter(tab);
	  // Params
	  if ( entry.request.queryString.length ) {
	      $('<li idx="params" >Params</li>').appendTo(tab);
	      $('<div idx="params">'+queryStringHTML+'</div>').insertAfter(tab);
	  }
	  // POST
	  if ( entry.request.postData && entry.request.postData.params ) {
	      $('<li idx="post" >POST</li>').appendTo(tab);
	      $('<div idx="post">'+postHTML+'</div>').insertAfter(tab);
	  }
	  // Cache
	  if ( caches.length || entry.cache.afterRequest ) {
	      $('<li idx="cache" >Cache</li>').appendTo(tab);
	      $('<div idx="cache">'+cachesHTML+'</div>').insertAfter(tab);
	  }
	  //  li
	    $('<li class="last"></li>').appendTo(tab);
	  return ( caches.length  || entry.cache.afterRequest);
	  }
	}
    });
})
/*
$(function(){
  var HAR={"log":...};
<!--
 $('#har').harViewer(HAR);
-->
});
<div id="har"></div>

*/