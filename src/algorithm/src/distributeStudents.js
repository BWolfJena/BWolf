const _ = require('lodash');
const lpsolve = require('lp_solve');

module.exports = function distributeStudents(courses, elections, params) {

  var Row = lpsolve.Row;
  const studentIds = _.keys(elections)
  elections = _.values(elections)
  const n = elections.length; // Number of students
  const m = _.keys(courses).length; // Nuber of courses

  var lp = new lpsolve.LinearProgram(); // create linear program
  var X = []; // 2-dimensional array of variables
  for (i = 0; i <= n - 1; i++) {
    X.push([]);
    for (j = 0; j <= m - 1; j++) {
      X[i].push(lp.addColumn(`x_${i + 1},${j + 1}`, false, true)); // false, true for binary
    }
  }

  const weights = params.weights;
  var objective = new Row();
  for (i = 0; i <= n - 1; i++) {
    for (j = 0; j <= m - 1; j++) {
      var preference = elections[i].indexOf(j + 1);
      //x_ij (c_ij - beta/n * (c_ij - ))
      objective.Add(X[i][j], weights[preference]);
    }
  }

  // Set objective for lp
  lp.setObjective(objective, false);

  const lower = 6;

  for (i = 0; i <= n - 1; i++) {
    var row = new Row();
    for (j = 0; j < lower; j++) {
      row.Add(X[i][elections[i][j] - 1], 1);
    }
    lp.addConstraint(row, 'EQ', 0, `Stundent ${i + 1} exactly one course`);
    var row = new Row();
    for (j = lower; j <= m - 1; j++) {
      row.Add(X[i][elections[i][j] - 1], 1);
    }
    lp.addConstraint(row, 'EQ', 1, `Stundent ${i + 1} no lower course`);
  }

  for (j = 0; j <= m - 1; j++) {
    var row = new Row();
    for (i = 0; i <= n - 1; i++) {
      row.Add(X[i][j], 1);
    }
    lp.addConstraint(row, 'LE', 12, `Course ${j + 1} max students`);
    lp.addConstraint(row, 'GE', 0, `Course ${j + 1} min students`);
  }

  lp.setVerbose(0);


  const solverResult = lp.solve();
  if (solverResult.code != 0) {
    return {
      error: "Could not distribute students with given parameters",
      solverResult: solverResult,
      program: lp.dumpProgram(),
    }
  }
  result = {};
  result.objective = lp.getObjectiveValue();
  result.students = {}
  resultPrefs = [];
  histData = {};

  for (i = 0; i <= n - 1; i++) {
    for (j = 0; j <= m - 1; j++) {
      if (lp.get(X[i][j]) == '1') {
        var course = elections[i].indexOf(j + 1) + 1;
        result.students[studentIds[i]] = course;
        if (histData[course]) {
          histData[course]++;
        } else {
          histData[course] = 1;
        }
        resultPrefs.push(course);
      }
    }
  }
  result.min = _.min(resultPrefs);
  result.mean = _.mean(resultPrefs);
  result.histPreferences = histData;

  return result;
}