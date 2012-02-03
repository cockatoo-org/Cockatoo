#!/usr/bin/env node

var sys = require('sys');
var fs = require('fs');
var url = require('url');
var querystring = require('querystring');
var http = require('http');
var jsdom = require('jsdom').jsdom;

if (process.argv.length <= 3) {
  sys.puts('Usage: node minimize.js jquery-path');
  process.exit(1);
}
var jquery = process.argv[2];
var type   = process.argv[3];
process.stdin.resume();
process.stdin.setEncoding('utf8');
data = '';
process.stdin.on('data', function (chunk) {
  data = data + chunk;
});

function cssCompaction(text) {
  text = text.replace(/\s*\r?\n\s*/g, '');
  return ' ' + text + ' ';
}
function jsCompaction(text) {
  text = text.replace(/\s*\r?\n\s*/g, '\n');
  return text;
}
function htmlCompaction(text) {
  text = text.replace(/\s+/g, ' ');
      //text = text.replace(/\n/g, '');
  return (text.length==1)?'':text;
}
function commentCompaction(text) {
  return '';
}

process.stdin.on('end', function () {
  if ( type == 'html' ) {
    data = '<HTML id="HTML"><BODY><BODY id="__ROOT__">' + data + '</BODY></BODY></HTML>';
    document = jsdom(data,null,{
      features:{
	FetchExternalResources : false,
	ProcessExternalResources : false,
	  "MutationEvents"           : '2.0',
	  "QuerySelector"            : false
      }
    });
    window = document.createWindow();
    jsdom.jQueryify(window, jquery, function (window, $) {
      function drawHTML(e){
	sys.print(e.html());
      }
      
      function childNodes ( node,textCallback , commentCallback ){
	children = node.get(0).childNodes;
	var deleted = new Array();
	for ( i in children ){
	  child = children[i]
	  sys.puts('CHILD:' + child.nodeName);
	  sys.puts('VALUE:' + child.nodeValue);
	  if ( child.nodeName == '#text' ) {
	    child.nodeValue = textCallback(child.nodeValue)
	  }else if( child.nodeName == '#comment' ) {
	    child.nodeValue = commentCallback(child.nodeValue)
	    if ( child.nodeValue == '' ) {
	      deleted.unshift(i);
	    }
	  }else{ // do nothing...
	  }
	}
	for (d in deleted ) {
	  i = deleted[d];
	  node.get(0).removeChild(children[i]);
	}
      }
      node = $('#__ROOT__');
    //sys.puts('ALL:' + node.html().length);
	$('style').each(function (){
	  childNodes( $(this),cssCompaction,cssCompaction);
	//sys.puts('CSS:(' + ($(this).html()).length + ')' + $(this).html());
	});
	$('script').each(function (){
	  childNodes( $(this),jsCompaction,jsCompaction);
	//sys.puts('JS:(' + ($(this).html()).length + ')' + $(this).html());
	});
      node.find('*').not('html').not('body').not('script').not('style').not('pre').not('xmp').not('plaintext').each(function(){
	pre = $(this).html().length
	childNodes( $(this),htmlCompaction,commentCompaction);
	// sys.puts($(this).get(0).nodeName + ' :(' + pre + ' => ' + ($(this).html()).length + ')' + $(this).html());
      });
      drawHTML(node);
    //sys.puts('ALL:' + node.html().length);
    });
  //sys.puts("END");
  }else if ( type == 'js') {
    sys.print(jsCompaction(data));
  }else if ( type == 'css') {
    sys.print(cssCompaction(data));
  }else if ( type == 'raw_html') {
    sys.print(htmlCompaction(data));
  }
});

// node compaction.js <test.html