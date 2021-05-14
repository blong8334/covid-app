const fs = require('fs');
const path = require('path');

const results = fs.readdirSync(__dirname) || [];
const fileName = path.basename(__filename);

results.reduce((exps, name) => {
  if (name !== fileName) {
    exps[name] = fs.readFileSync(path.resolve(__dirname, name), { encoding: 'utf8' });
  }
  return exps;
}, module.exports);