{
"etag":"\"c023a050-5721-4c87-22a9dc92a7d3ab0d\"",
"type":"text/javascript",
"exp":"3600",
"desc":"",
"data":"// Extend string\r
String.prototype.replaceAll = function (org, dest){  \r
  return this.split(org).join(dest);  \r
}  \r
// Reverse string\r
String.prototype.reverse = function (){\r
  return this.split('').reverse().join('')\r
}\r
",
"_u":"js/cockatoo.js"
}