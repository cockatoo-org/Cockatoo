var sys = require('util');
var common = require(__dirname + '/common.js');

exports.serializer = function() {
  return this;
}

var callback_object = function(){
}
callback_object.prototype = {
  indent    : '',
  suffix    : '\n',
  buffer    : '',
  prefix      : function (path,value,cyclic,in_array,objid){
    if ( cyclic ) {
      throw 'Cannot serialize (cyclic object) ! ';
    }
    var key = path[path.length-1];
    var prefix = this.indent;
    if ( key && ! in_array ){
      prefix += '\'' + key[0] + '\' : ';
    }
    return prefix;
  },
  cb_undefined  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + 'undefined,' + this.suffix;
  },
  cb_null       : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + 'null,' + this.suffix;
  },
  cb_string    : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + '"' + value + '",' + this.suffix;
  },
  cb_function  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    var lines = value.toString().split('\n');
    for ( var i in lines ) {
      if ( i == 0 ){
	this.buffer += prefix + lines[i];
      }else{
	this.buffer += this.indent + lines[i];
      }
      this.buffer += '\n';
    }
    this.buffer = this.buffer.replace(/\n$/,''); 
    this.buffer += ',' + this.suffix;
  },
  cb_other  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + value + ',' + this.suffix;
  },
  cb_date    : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + 'new Date(\''+value.toString()+'\'),' + this.suffix;
  },
  cb_regexp  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    var flg = ((value.ignoreCase)?'i':'')+((value.multiline)?'m':'')+((value.global)?'g':'');
    this.buffer += prefix + 'new RegExp(\''+value.source+'\',\''+flg+'\'),' + this.suffix;
  },
  cb_array  : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    if ( value.length ) {
      this.buffer += prefix + '['  + this.suffix;
      return true;
    }else{
      this.buffer += prefix + '[],' + this.suffix;
      return false;
    }
  },
  cb_hash   : function (path,value,cyclic,in_array,objid){
    var prefix = this.prefix(path,value,cyclic,in_array,objid);
    this.buffer += prefix + '{'  + this.suffix;
    this.indent += '  ';
    return true;
  },
  cb_object  : function (path,value,cyclic,in_array,objid){
    throw "Unknown type : " + typeof(value);
  },
  cb_leave_array  : function (path,value,cyclic,in_array,objid){
    this.buffer = this.buffer.replace(/,\n$/,'\n'); 
    this.buffer += this.indent + '],' + this.suffix;
  },
  cb_leave_hash  : function (path,value,cyclic,in_array,objid){
    this.indent = this.indent.replace(/  $/,''); 
    this.buffer = this.buffer.replace(/,\n$/,'\n'); 
    this.buffer += this.indent + '},' + this.suffix;
  },
}

exports.serialize = function(data){
  var callback = new callback_object;
  common.crawl_object(data,callback);
  callback.buffer = callback.buffer.replace(/,\n$/,'\n'); 
  return callback.buffer;
}
exports.deserialize = function(str){
  eval('var ret = ' + str + ';');
  return ret;
}

exports.dump = function(data){
  sys.puts(this.serialize(data));
}
