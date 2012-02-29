var sys = require('util');
var fs = require('fs');
var path = require('path');

exports.common = function(){
  return this;
}
exports.mkdirp = function(dir){
  var parent = path.dirname(dir);
  if ( ! path.existsSync(parent) ) {
    mkdirp(parent);
  }
  if ( ! path.existsSync(dir) ) {
    fs.mkdirSync(dir,'755');
  }
}
  
/** Example
var z = {
  a : 'A',
  b : { 
    c : 'C',
    d : [1,2,3]
  },
  e : 'E',
  u : undefined,
  n : null,
  N : null
};
*/
exports.crawl_object = function ( data , callback , igns , path , done ) {
  function is_callback( type ){
    for ( var i in igns ){
      if ( igns[i] === type ) {
        return false;
      }
    }
    return true;
  }
  if ( igns === undefined )  igns = [];
  if ( done === undefined )  done = [];
  if ( path === undefined )  path = [];
  if ( done.length === 0 ){
    callback('',data,path,0); // root object
    done.push(data);
  }
  for ( var i in data ){
    path.push(i);
    var type = typeof(data[i]);
    if ( type === 'object') {
      function check_done(){
	for ( var no in done ) {
	  if ( done[no] === data[i] ) { 
	    if ( is_callback(type)){
	      callback(i,data[i],path,no,true);
	    }
	    return false;
	  }
	}
	return true;
      }
      if ( ! check_done() ) {
	path.pop(i);
	continue;
      }
      done.push(data[i]);
      var objid = done.length;
      if ( is_callback(type)){
	if ( callback(i,data[i],path,objid) ) {
	  this.crawl_object ( data[i],callback,igns,path ,done );
	}
      }else{
	this.crawl_object ( data[i],callback,igns,path ,done );
      }
    }else{
      if ( is_callback(type)){
	callback(i,data[i],path,undefined);
      }
    }
    path.pop(i);
  }
}


// Extend string
String.prototype.replaceAll = function (org, dest){  
  return this.split(org).join(dest);  
}  
