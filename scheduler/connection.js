const mysql = require('mysql');
const options = {
  host: 'localhost',
  user: 'root',
  password: 'root',
  database: 'covid_project',
};

class Connection {
  constructor() {
    this.connection = mysql.createConnection(options);
  }
  escape(...args) {
    return this.connection.escape(...args);
  }
  connect() {
    return new Promise((resolve, reject) => {
      this.connection.connect((err) => {
        if (err) {
          return reject('error connecting: ' + err);
        }
        console.log(`Connected to ${options.database}`);
        resolve();
      });
    });
  }
  query(queryString, values) {
    return new Promise((resolve, reject) => {
      this.connection.query(queryString, values, (err, results) => {
        if (err) {
          return reject(err);
        }
        resolve(results);
      });
    });
  }
  close() {
    return new Promise((resolve, reject) => {
      this.connection.end((err) => {
        if (err) {
          return reject(err);
        }
        resolve();
      })
    });
  }
}

module.exports = { Connection };