const express = require('express');
const bodyParser = require('body-parser');
const distributeStudents = require('./distributeStudents');

const app = express();

const PORT = process.env.PORT || 80

app.use(bodyParser.json({limit: '50mb'})); // for parsing application/json
app.use(bodyParser.urlencoded({ extended: true })); // for parsing application/x-www-form-urlencoded

app.get('/', function(req, res) {
    res.send('Only accepting post requests: {' +
        'courses: { "courseid1": {min: int - minimum participants, "max": int -maximum participants},' +
        'elections: { "studentid": [array of course ids ordered in the students favor], "studentid2" : [], ...},' +
        'params: {lowest: int - the minimal allowed course rank, weights: [array of numbers representing the weights for course choices]}');
});

app.post('/', function (req, res) {
    input = req.body;
    result = distributeStudents(input.courses,input.elections, input.params);
    res.json(result);
});

app.listen(PORT, function () {
    console.log(`Course distribution algorithm listening on port ${ PORT }!`);
});