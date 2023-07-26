// creating express instance
var express = require("express");
var app = express();
 
// creating http instance
var http = require("http").createServer(app);

// create body parser instance
var bodyParser = require("body-parser");

// create sql instance
var sql = require("mssql");
 
// enable URL encoded for POST requests
app.use(bodyParser.urlencoded());
 
// enable headers required for POST request
app.use(function (request, result, next) {
    result.setHeader("Access-Control-Allow-Origin", "*");
    next();
});

app.set("Content-Security-Policy", "default-src 'self'");
 
var dbConfig = {
    user:  "sa",
    password: "BIR159!@#po",
    server: "juliolopez",
    database: "scao", 
    "options": {
        "encrypt": false,
        "enableArithAbort": true
    },
};

var traceyConfig = {
    user:  "sa",
    password: "BIR159!@#po",
    server: "{{ env('TRACEY_IP') }}",
    database: "Tracey", 
    "options": {
        "encrypt": false,
        "enableArithAbort": true
    },
};

app.get("/get-active-server-stats", function (request, result) {
    // get all messages from database
    console.log(decodeURIComponent(request.query.ServerId))
    sql.connect(traceyConfig).then(() => {
        return sql.query`SELECT TOP 1 * FROM ServerStats WHERE ServerId=${ decodeURIComponent(request.query.ServerId) } AND created_at BETWEEN ${ decodeURIComponent(request.query.From) } AND ${ decodeURIComponent(request.query.To) } ORDER BY created_at DESC`
    }).then(res => {
        result.end(JSON.stringify(res['recordset']));
    }).catch(err => {
        console.log(err);
    })
});

app.get("/check", function (request, result) {
    // get all messages from database
    result.end("OK");
});

// start the server
http.listen(3000, function () {
   console.log("Server started");
});