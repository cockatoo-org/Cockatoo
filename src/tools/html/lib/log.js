//var sys = require('sys');
//var sys = require('util');
var fs = require('fs');
var path = require('path');
var common = require(__dirname + '/common.js');

var PRE_LEN =  6;
var KEY_LEN = 30;
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
    process.stderr.write(e.stack);
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

function crawl_callback_object(){
}
crawl_callback_object.prototype = {
  suffix    : '\n',
  buffer    : '',
  prefix      : function (path,value,cyclic,in_array,objid){
    var key = path[path.length-1];
    if ( ! key ){
      key = '(ROOT)';
    }else{
      key = key[0];
    }
    return padding(key,KEY_LEN+(path.length*2),1) + ' : '
  },
  cb_undefined  : function (path,value,cyclic,in_array,objid){
    this.cb_string(path,value,cyclic,in_array,objid);
  },
  cb_null       : function (path,value,cyclic,in_array,objid){
    this.cb_string(path,value,cyclic,in_array,objid);
  },
  cb_string    : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    var type  = typeof(value);
    if ( value && value.toString) {
      var lines = value.toString().split('\n');
      for ( var i in lines ) {
	if ( i == 0 ) {
	  this.buffer += prefix + '(' + padding(type,TYPE_LEN) + '  '+padding('',NAME_LEN)+') : ' + lines[i] + this.suffix;
	}else{
	  this.buffer += padding('',KEY_LEN+(path.length*2),1)  + '    ' + padding('',TYPE_LEN) + '  '+padding('',NAME_LEN)+  '    ' + lines[i] + this.suffix;
	}
      }
    }else{
      this.buffer += prefix + '(' + padding(type,TYPE_LEN) + '  '+padding('',NAME_LEN)+') : ' + value + this.suffix;
    }
  },
  cb_function  : function (path,value,cyclic,in_array,objid){
    this.cb_string(path,value,cyclic,in_array,objid);
  },
  cb_other  : function (path,value,cyclic,in_array,objid){
    this.cb_string(path,value,cyclic,in_array,objid);
  },
  cb_date    : function (path,value,cyclic,in_array,objid){
    return this.cb_object(path,value,cyclic,in_array,objid);
  },
  cb_regexp  : function (path,value,cyclic,in_array,objid){
    return this.cb_object(path,value,cyclic,in_array,objid);
  },
  cb_array  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    if ( cyclic ) {
      this.buffer += prefix + '(' + padding('ref#object#' + objid,TYPE_LEN) + '> ' + padding(value.constructor.name,NAME_LEN) + ') : ' + value.length + this.suffix;
    }else{
      this.buffer += prefix + '(' + padding('object#' + objid,TYPE_LEN) + '> ' + padding(value.constructor.name,NAME_LEN) + ') : ' + value.length + this.suffix;
    }
    return !cyclic;
  },
  cb_hash   : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    if ( cyclic ) {
      this.buffer += prefix + '(' + padding('ref#object#' + objid,TYPE_LEN) + '> ' + padding(value.constructor.name,NAME_LEN) + ')' + this.suffix;
    }else{
      this.buffer += prefix + '(' + padding('object#' + objid,TYPE_LEN) + '> ' + padding(value.constructor.name,NAME_LEN) + ')' + this.suffix;
    }
    return !cyclic;
  },
  cb_object  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    if ( cyclic ) {
      this.buffer += prefix + '(' + padding('ref#object#' + objid,TYPE_LEN) + '> ' + padding(value.constructor.name,NAME_LEN) + ') = ' + value + this.suffix;
    }else{
      this.buffer += prefix + '(' + padding('object#' + objid,TYPE_LEN) + '> ' + padding(value.constructor.name,NAME_LEN) + ') = ' + value + this.suffix;
    }
    return !cyclic;
  },
  cb_leave_array  : function (path,value,cyclic,in_array,objid){
  },
  cb_leave_hash  : function (path,value,cyclic,in_array,objid){
  },
}
exports.crawl_callback = crawl_callback_object;

exports.dump = function(pre,msg,data){
  process.stderr.write(padding(pre,PRE_LEN) + ' : ===== ' + (msg?padding(msg,URL_LEN):'') + '=====\n');
  if ( typeof(data)==='object') {
    callback = new crawl_callback_object;
    callback.cb_function = undefined;
    common.crawl_object(data,callback);
    process.stderr.write(callback.buffer+'\n');
  }else{
    process.stderr.write(padding('',KEY_LEN,1) + ' : ' + data+'\n'); 
  }
}
//---------------------------------
// utilities
//---------------------------------
function out(pre,url,selector,msg,body,l){
  var msg = padding(pre,PRE_LEN) + ' : ' + padding(url,URL_LEN) + ' ; ' + padding(msg,MSG_LEN) + '  ; ' + padding(selector,SEL_LEN) + (body?'  ; '+body:'');
  process.stderr.write(msg+'\n');
  if ( l ) { // log
    var fp = fs.openSync(log_file,'a+');
    fs.writeSync(fp,msg+'\n',null);
    fs.closeSync(fp);
  }
}
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