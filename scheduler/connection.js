const mysql = require('mysql');
const options = {
  host: 'localhost',
  user: 'root',
  password: 'root',
  database: 'covid_project',
};
const connection = mysql.createConnection(options);
let activeConnection;

function connect() {
  return new Promise((resolve, reject) => {
    if (activeConnection) {
      return resolve(activeConnection);
    }
    connection.connect((err) => {
      if (err) {
        return reject('error connecting: ' + err);
      }
      activeConnection = connection;
      resolve(activeConnection);
    });
  });
}
module.exports = { connect };