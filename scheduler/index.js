const { Connection } = require('./connection');
const queries = require('./queries');

async function scheduler() {
  const connection = new Connection();
  try {
    await connection.connect();
    const unavailOffers = await connection.query(
      queries['patientsWithStatus.txt'],
      [['completed', 'pending']],
    );
    console.log('Results', unavailOffers);
    const {
      bookedPatients = '',
      bookedAppointments = '',
    } = unavailOffers[0] || {};

    const availPatients = await connection.query(
      queries['patientsPreferred.txt'],
      [bookedPatients.split(',')],
    );
    console.log('availPatients', availPatients);

    const availAppoints = await connection.query(
      queries['availAppoints.txt'],
      [bookedAppointments.split(',')],
    );
    console.log('availAppoints', availAppoints);


    // now for each patient in order, we need to go through the avail
    // appointments are figure out which ones meet their needs.

  } catch (err) {
    console.error(err);
  }
  await connection.close();

}

scheduler();