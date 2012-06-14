//var sys = require('sys');
var sys    = require('util');
var fs     = require('fs');
var path   = require('path');
var common = require(__dirname + '/common.js');
var serializer = require(__dirname + '/serializer.js');

var fetch_list_file;
var fetch_list_c;

//---------------------------------
// constructor 
//---------------------------------
exports.fetch_list = function(file) { 
  if ( file )  fetch_list_file = path.resolve(file);
  return this;
}

// setter
exports.file = function(file) { 
  fetch_list_file = file;
  return this;
}

exports.init = function() {
  try {
    common.mkdirp(path.dirname(fetch_list_file));
  }catch(e){
    sys.puts(e.stack);
    process.exit(1); // fatal
  }
  fetch_list_c = {};
  return this;
}

//---------------------------------
// operator
//---------------------------------
exports.load = function () {
//  fetch_list_c = JSON.parse(fs.readFileSync(fetch_list_file));
  fetch_list_c = serializer.deserialize(fs.readFileSync(fetch_list_file));
}

exports.check = function (url,status) {
  if ( url in fetch_list_c ) {
    return fetch_list_c[url];
  }
  this.change(url,status);
  return null;
}

exports.change = function (url,status) {
  fetch_list_c[url] = status;
//  fs.writeFileSync(fetch_list_file,JSON.stringify(fetch_list_c,null,1));
  fs.writeFileSync(fetch_list_file,serializer.serialize(fetch_list_c));
}

exports.timeout = function (url) {
  if ( typeof(fetch_list_c[url]) != 'number' ){
    this.change(url,'TIMEOUT');
    return true;
  }
  return false;
}

//---------------------------------
// utilities
//---------------------------------
