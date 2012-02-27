//var sys = require('sys');
var sys    = require('util');
var fs     = require('fs');
var path   = require('path');
var common = require(__dirname + '/common.js');

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
    sys.puts(e.stack);
    process.exit(1); // fatal
  }
  fetch_queue_q = [];
  return this;
}

//---------------------------------
// operator
//---------------------------------
exports.load = function () {
  fetch_queue_q = JSON.parse(fs.readFileSync(fetch_queue_file));
}

exports.push = function ( url , test , remark ) {
  fetch_queue_q.push({URL:url,TEST:test,REMARK:remark});
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
    fs.writeFileSync(fetch_queue_file,JSON.stringify(fetch_queue_q));
  }else{
    fs.unlinkSync(fetch_queue_file);
  }
}
