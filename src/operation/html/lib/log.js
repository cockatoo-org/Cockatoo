//var sys = require('sys');
var sys = require('util');
var fs = require('fs');
var path = require('path');
var common = require(__dirname + '/common.js');

var PRE_LEN =  6;
var KEY_LEN = 12;
var URL_LEN = 65;
var SEL_LEN = 20;
var MSG_LEN = 18;
var TYPE_LEN= 13;
var NAME_LEN= 10;

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

exports.crawl_callback = function(output){
  if ( output === undefined ) output = sys.puts;
  function hasMember(val) {
    for ( var i in val ) {
      return true;
    }
    return false;
  }
  return function ( key , value , path, objid , cyclic ) {
    if ( value === undefined ) {
      output(common.padding(key,KEY_LEN+(path.length*2),1) + ' : ('+common.padding('undefined',TYPE_LEN) + '  '+common.padding('',NAME_LEN)+') : ' + value);
    } else if (value === null ) {
      output(common.padding(key,KEY_LEN+(path.length*2),1) + ' : ('+common.padding('object#' + objid,TYPE_LEN) + '  '+common.padding('',NAME_LEN)+') : ' + value);
    } else if ( cyclic ) {
      output(common.padding(key,KEY_LEN+(path.length*2),1) + ' : ('+common.padding('ref#object#' + objid,TYPE_LEN) + '> ' + common.padding(value.constructor.name,NAME_LEN) + ')' );
    } else if ( typeof(value) === 'object'  ) {
      if ( ! hasMember(value) ) {
	output(common.padding(key,KEY_LEN+(path.length*2),1) + ' : (' + common.padding('object#' + objid,TYPE_LEN) + '> ' + common.padding(value.constructor.name,NAME_LEN) + ') : ' + value );
      }else{
	output(common.padding(key,KEY_LEN+(path.length*2),1) + ' : (' + common.padding('object#' + objid,TYPE_LEN) + '> ' + common.padding(value.constructor.name,NAME_LEN) + ')' );
      }
    } else {
      var type  = typeof(value);
      var lines = value.toString().split('\n');
      for ( var i in lines ) {
	if ( i == 0 ) {
	  output(common.padding(key,KEY_LEN+(path.length*2),1) + ' : (' + common.padding(type,TYPE_LEN) + '  '+common.padding('',NAME_LEN)+') : ' + lines[i]);
	}else{
	  output(common.padding('',KEY_LEN+(path.length*2),1)  + '    ' + common.padding('',TYPE_LEN) + '  '+common.padding('',NAME_LEN)+  '    ' + lines[i]);
	}
      }
    }
    return true;
  };
}

exports.dump = function(pre,msg,data,igns){
  sys.puts(common.padding(pre,PRE_LEN) + ' : ===== ' + (msg?common.padding(msg,URL_LEN):'') + '=====');
  if ( typeof(data)==='object') {
    common.crawl_object(data,this.crawl_callback(sys.puts),igns);
  }else{
    sys.puts(common.padding('',KEY_LEN,1) + ' : ' + data); 
  }
}
//---------------------------------
// utilities
//---------------------------------
function out(pre,url,selector,msg,body,l){
  var msg = common.padding(pre,PRE_LEN) + ' : ' + common.padding(url,URL_LEN) + ' ; ' + common.padding(msg,MSG_LEN) + '  ; ' + common.padding(selector,SEL_LEN) + (body?'  ; '+body:'');
  sys.puts(msg);
  if ( l ) { // log
    var fp = fs.openSync(log_file,'a+');
    fs.writeSync(fp,msg+'\n',null);
    fs.closeSync(fp);
  }
}


