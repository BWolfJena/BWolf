const _ = require('lodash');
const lpsolve = require('lp_solve');

module.exports = function distributeStudents(courses, elections, params) {

  let Row = lpsolve.Row;
  const studentIds = _.keys(elections);
  const courseIds = _.keys(courses);
  const weights = params.weights;
  const lowest = params.lowest;
  if (lowest >= courseIds.length) {
    return {
      error: 'The lowest given priority is higher than the highest priority'
    }
  }

  let lp = new lpsolve.LinearProgram(); // create linear program
  let X = {}; // 2-dimensional array of letiables
  let objective = new Row();
  studentIds.forEach(function (studentId) {
    X[studentId] = {};
    courseIds.forEach(function (courseId) {
      X[studentId][courseId] = lp.addColumn(`x_${studentId},${courseId}`, false, true);
      let preference = elections[studentId].indexOf(parseInt(courseId));
      console.log(preference, weights[preference]);
      objective.Add(X[studentId][courseId], weights[preference]);
    });
  });

  lp.setObjective(objective, false); // Set objective for lp (false for max)


  studentIds.forEach(function (studentId) {
    const reversedElections = elections[studentId].reverse();

    // Forbid courses with lower priority / preference than lowest
    if (lowest > 0) {
      let row = new Row();
      reversedElections.slice(0, lowest - 1).forEach(function (courseId) {
        row.Add(X[studentId][courseId], 1);
      });
      lp.addConstraint(row, 'EQ', 0, `Stundent ${studentId} no lower preference`);
    }

    // Force to chose exactly one course for a student
    let row = new Row();
    reversedElections.slice(lowest).forEach(function (courseId) {
      row.Add(X[studentId][courseId], 1);
    });
    lp.addConstraint(row, 'EQ', 1, `Stundent ${studentId} exactly one course`);
  });

  courseIds.forEach(function (courseId) {
    let row = new Row();
    studentIds.forEach(function (studentId) {
      row.Add(X[studentId][courseId], 1);
    });
    lp.addConstraint(row, 'LE', courses[courseId].max, `Course ${courseId} max students`);
    lp.addConstraint(row, 'GE', courses[courseId].min, `Course ${courseId} min students`);
  });

  lp.setVerbose(0);
  const solverResult = lp.solve();
  if (solverResult.code != 0) {
    return {
      error: "Could not distribute students with given parameters",
      solverResult: solverResult,
      program: lp.dumpProgram(),
    }
  }

  result = {
    objective: lp.getObjectiveValue(),
    program: lp.dumpProgram(),
    students: {}
  };

  resultPrefs = [];
  histData = {};

  studentIds.forEach(function (studentId) {
    courseIds.forEach(function (courseId) {
      if (lp.get(X[studentId][courseId]) == '1') {
        let preference = elections[studentId].indexOf(parseInt(courseId)) + 1;
        result.students[studentId] = courseId;
        if (histData[preference]) {
          histData[preference]++;
        } else {
          histData[preference] = 1;
        }
        resultPrefs.push(preference);
      }
    });
  });

  result.min = _.min(resultPrefs);
  result.mean = _.mean(resultPrefs);
  result.histPreferences = histData;

  return result;
}