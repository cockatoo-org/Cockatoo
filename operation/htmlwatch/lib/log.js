//var sys = require('sys');
var sys = require('util');
var fs = require('fs');
var path = require('path');
var common = require(__dirname + '/common.js');

var PRE_LEN =  6;
var KEY_LEN = 20;
var URL_LEN = 65;
var SEL_LEN = 20;
var MSG_LEN = 18;
var TYPE_LEN= 14;

var LV_DEBUG   = 20;
var LV_TRACE   = 15;
var LV_INFO    = 10;
var LV_STATUS  =  8;
var LV_MESSAGE =  6;
var LV_OK      =  4;
var LV_ECHO    =  2;
var LV_ERROR   =  0;

var log_file;
var log_lv     = LV_INFO;

//---------------------------------
// constructor 
//---------------------------------
exports.log = function(file,lv) { 
  if ( file )  log_file = path.resolve(file);
  if ( lv )    log_lv   = lv;
  return this;
}

// setter
exports.file = function(file) { 
  log_file = file;
  return this;
}
// setter
exports.lv = function(lv) { 
  log_lv   = lv;
  return this;
}

exports.init = function() {
  try {
    common.mkdirp(path.dirname(log_file));
  }catch(e){
    sys.puts(e.stack);
    process.exit(1); // fatal
  }
  this.echo('===== LOG ====',log_lv,path.basename(log_file),log_file);
  return this;
}

//---------------------------------
// outputers
//---------------------------------
exports.debug = function(msg,data,ign) { 
  if ( log_lv >= LV_DEBUG ) {
    this.dump('D',msg,data,ign);
  }
}
exports.trace = function(msg,data) { 
  if ( log_lv >= LV_TRACE ) {
    this.dump('T',msg,data,['function']);
  }
}
exports.info = function(msg,data) { 
  if ( log_lv >= LV_INFO ) {
    this.dump('I',msg,data,['function']);
  }
}
exports.status = function(url,selector,msg,body) { 
  if ( log_lv >= LV_STATUS ) {
    out('STATUS',url,selector,msg,body);
  }
}
exports.message = function(url,selector,msg,body) { 
  if ( log_lv >= LV_MESSAGE ) {
    out('MSG',url,selector,msg,body);
  }
}
exports.ok = function(url,selector,msg,body) { 
  if ( log_lv >= LV_OK ) {
    out('OK',url,selector,msg,body,1);
  }
}
exports.echo = function(url,selector,msg,body) { 
  if ( log_lv >= LV_ECHO ) {
    out('ECHO',url,selector,msg,body);
  }
}
exports.error = function(url,selector,msg,body) { 
  if ( log_lv >= LV_ERROR ) {
    out('ERROR',url,selector,msg,body,1);
  }
}

function crawl_callback( key , value , path, objid , cyclic ) {
  if ( value === undefined ) {
    sys.puts(padding(key,KEY_LEN+(path.length*2),1) + ' : ('+padding('undefined',TYPE_LEN)+') : ');
  } else if (value === null ) {
    sys.puts(padding(key,KEY_LEN+(path.length*2),1) + ' : ('+padding('object#' + objid,TYPE_LEN) + ') : ' + value);
  } else if ( cyclic ) {
    sys.puts(padding(key,KEY_LEN+(path.length*2),1) + ' : ('+padding('ref#object#' + objid,TYPE_LEN) + ')');
  } else if ( objid === undefined ) {
    var type  = typeof(value);
    if ( value.toString !== undefined ) {
      var lines = value.toString().split('\n');
      for ( var i in lines ) {
	if ( i == 0 ) {
	  sys.puts(padding(key,KEY_LEN+(path.length*2),1) + ' : (' + padding(type,TYPE_LEN) + ') : ' + lines[i]);
	}else{
	  sys.puts(padding('',KEY_LEN+(path.length*2),1)  + '    ' + padding('',TYPE_LEN) + '    ' + lines[i]);
	}
      }
    }else{
      sys.puts(padding(key,KEY_LEN+(path.length*2),1) + ' : (' + padding(type,TYPE_LEN) + ') : ' + value);
    }
  } else {
    sys.puts(padding(key,KEY_LEN+(path.length*2),1) + ' : (' + padding('object#' + objid,TYPE_LEN) + ') : ');
  }
  return true;
}

exports.dump = function(pre,msg,data,igns){
  sys.puts(padding(pre,PRE_LEN) + ' : ===== ' + (msg?padding(msg,URL_LEN):'') + '=====');

  if ( typeof(data)==='object') {
    common.crawl_object(data,crawl_callback,igns);
  }else{
    sys.puts(padding('',KEY_LEN,1) + ' : ' + data); 
  }
}
//---------------------------------
// utilities
//---------------------------------
function padding(str,n,r){
  var strlen = 0;
  for( var c in str){
    strlen += (escape(str[c]).length<4)?1:2;
  }
  if ( strlen < n ){
    var p = n-strlen;
    for ( var i = 0 ; i < p ; i++ ) {
      if ( r ) 	str  = ' ' + str;
      else      str += ' ';
    }
  }
  return str;
}
function out(pre,url,selector,msg,body,l){
  var msg = padding(pre,PRE_LEN) + ' : ' + padding(url,URL_LEN) + ' ; ' + padding(msg,MSG_LEN) + '  ; ' + padding(selector,SEL_LEN) + (body?'  ; '+body:'');
  sys.puts(msg);
  if ( l ) { // log
    var fp = fs.openSync(log_file,'a+');
    fs.writeSync(fp,msg+'\n',null);
    fs.closeSync(fp);
  }
}


