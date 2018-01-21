const fs = require('fs');
const distribution = require('./empra_praeferenz_FINAL');

const studentIds = Object.keys(distribution);

let correctDistribution = {};

studentIds.forEach(function(id) {
    let courseIds = [];
    for(i=0;i<=14;i++) {
        courseIds.unshift(distribution[id].indexOf(i)+1);
    }
    correctDistribution[id] = courseIds;
})

fs.writeFileSync('../fixed.json', JSON.stringify(correctDistribution));