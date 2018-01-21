const distribution = require('./fixed');

const keys = Object.keys(distribution);
keys.forEach((key, userId) => {
  distribution[key].forEach((courseId, priority) => {
    console.log(`INSERT INTO bwolf.bwolfjena_core_user_course_priorities(course_id,user_id,priority) VALUES (${courseId},${userId+1},${(15-priority)});`);
  });
});