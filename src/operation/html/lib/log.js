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

function crawl_callback_object(){
}
crawl_callback_object.prototype = {
  suffix    : '\n',
  buffer    : '',
  prefix      : function (path,value,cyclic,in_array,objid){
    var key = path[path.length-1];
    if ( ! key ){
      key = '(ROOT)';
    }
    return common.padding(key,KEY_LEN+(path.length*2),1) + ' : '
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
	  this.buffer += prefix + '(' + common.padding(type,TYPE_LEN) + '  '+common.padding('',NAME_LEN)+') : ' + lines[i] + this.suffix;
	}else{
	  this.buffer += common.padding('',KEY_LEN+(path.length*2),1)  + '    ' + common.padding('',TYPE_LEN) + '  '+common.padding('',NAME_LEN)+  '    ' + lines[i] + this.suffix;
	}
      }
    }else{
      this.buffer += prefix + '(' + common.padding(type,TYPE_LEN) + '  '+common.padding('',NAME_LEN)+') : ' + value + this.suffix;
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
    return this.cb_hash(path,value,cyclic,in_array,objid);
  },
  cb_hash   : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + '(' + common.padding('object#' + objid,TYPE_LEN) + '> ' + common.padding(value.constructor.name,NAME_LEN) + ')' + this.suffix;
    return true;
  },
  cb_object  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + '(' + common.padding('object#' + objid,TYPE_LEN) + '> ' + common.padding(value.constructor.name,NAME_LEN) + ') = ' + value + this.suffix;
    return false;
  },
  cb_leave_array  : function (path,value,cyclic,in_array,objid){
  },
  cb_leave_hash  : function (path,value,cyclic,in_array,objid){
  },
}
exports.crawl_callback = crawl_callback_object;

exports.dump = function(pre,msg,data,igns){
  sys.puts(common.padding(pre,PRE_LEN) + ' : ===== ' + (msg?common.padding(msg,URL_LEN):'') + '=====');
  if ( typeof(data)==='object') {
    callback = new crawl_callback_object;
    common.crawl_object(data,callback,igns);
    sys.puts(callback.buffer);
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


