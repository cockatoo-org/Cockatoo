//var sys = require('sys');
//var sys    = require('util');
var fs     = require('fs');
var path   = require('path');
var common = require(__dirname + '/common.js');
var serializer = require(__dirname + '/serializer.js');

var fetch_queue_file;
var fetch_queue_q;
//---------------------------------
// constructor 
//---------------------------------
exports.fetch_queue = function(file) { 
  if ( file )  fetch_queue_file = path.resolve(file);
  return this;
}

// setter
exports.file = function(file) { 
  fetch_queue_file = file;
  return this;
}

exports.init = function() {
  try {
    common.mkdirp(path.dirname(fetch_queue_file));
  }catch(e){
    process.stderr.write(e.stack);
    process.exit(1); // fatal
  }
  fetch_queue_q = [];
  return this;
}

//---------------------------------
// operator
//---------------------------------
exports.load = function () {
  try { 
//    fetch_queue_q = JSON.parse(fs.readFileSync(fetch_queue_file));
    fetch_queue_q = serializer.deserialize(fs.readFileSync(fetch_queue_file));
  }catch(e){
    // nothing to do
  }
}

exports.push = function ( url , headers , test , referer ) {
  fetch_queue_q.push({URL:url,TEST:test,REFERER:referer});
  save();
}

exports.pop  = function () {
  var ret = fetch_queue_q.pop();
  save();
  return ret;
}

exports.length = function () {
  return fetch_queue_q.length;
}

//---------------------------------
// utilities
//---------------------------------

function save () {
  if ( fetch_queue_q.length ) {
//    fs.writeFileSync(fetch_queue_file,JSON.stringify(fetch_queue_q,null,1));
    fs.writeFileSync(fetch_queue_file,serializer.serialize(fetch_queue_q));
  }else{
    fs.unlinkSync(fetch_queue_file);
  }
}
