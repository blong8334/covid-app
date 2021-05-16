const { Connection } = require('./connection');
const queries = require('./queries');

const pi180 = Math.PI / 180;

async function scheduler() {
  const connection = new Connection();
  booking: try {
    await connection.connect();
    const unavailOffers = await connection.query(
      queries['patientsWithStatus.txt'],
      [['completed', 'pending']],
    );
    let {
      bookedPatients,
      bookedAppointments,
    } = unavailOffers[0];
    if (!bookedPatients) {
      bookedPatients = '';
    }
    if (!bookedAppointments) {
      bookedAppointments = '';
    }
    const availPatients = await connection.query(
      queries['patientsPreferred.txt'],
      [bookedPatients.split(',')],
    );
    if (!availPatients || !availPatients.length) {
      console.log('No patients to book');
      break booking;
    }

    const availAppoints = await connection.query(
      queries['availAppoints.txt'],
      [bookedAppointments.split(',')],
    );
    if (!availAppoints || !availAppoints.length) {
      console.log('No appointments available');
      break booking;
    }
    const patientOfferMap = getOfferMap(availPatients, availAppoints);
    const best = findBestApptCombo(patientOfferMap);
    let offer_date = Date.now();
    const deadline_date = new Date(offer_date + 1000 * 60 * 60 * 24 * 7); // one week later.
    offer_date = new Date(offer_date);
    const offers = best.map(({ appoint_id, patient_id }) => {
      const results = [
        patient_id,
        appoint_id,
        '"pending"',
        connection.escape(offer_date),
        connection.escape(deadline_date),
      ].join(',');
      return `(${results})`;
    });
    if (offers.length) {
      await connection.query(queries['vaccineOffers.txt'] + ' ' + offers.join(','));
      console.log(`Succesfully offered ${offers.length} appointments`);
    } else {
      console.log('no offers matched');
    }
  } catch (err) {
    console.error(err);
  } finally {
    await connection.close();
  }
}

function getOfferMap(availPatients, availAppoints) {
  return availPatients.reduce((map, patient) => {
    const {
      patient_id,
      patient_location: { x: pat_x, y: pat_y },
      avail,
      max_travel,
    } = patient;
    const availMap = avail.split(',').reduce((results, slot) => {
      const [day, starttime, endtime] = slot.split(';');
      if (!results[day]) {
        results[day] = [];
      }
      results[day].push({ starttime, endtime });
      return results;
    }, {});
    const matches = availAppoints.reduce((matches, appt) => {
      const availForDay = availMap[new Date(appt.appoint_date).getDay()];
      if (!availForDay) {
        return matches;
      }
      const { provider_location: { x: pro_x, y: pro_y } } = appt;
      const distance = distanceInMiles(pat_x, pro_x, pat_y, pro_y);
      if (distance > max_travel) {
        return matches;
      }
      availForDay.forEach((avail) => {
        if (avail.starttime <= appt.appoint_time && avail.endtime > appt.appoint_time) {
          matches.push(appt);
        }
      });
      return matches;
    }, []);
    map[patient_id] = matches;
    return map;
  }, {});
}

function findBestApptCombo(appointMap, results = [], bookedMap = {}) {
  let cache2 = [];
  let updated = false;
  const keys = Object.keys(appointMap);
  for (let i = 0; i < keys.length; i++) {
    const patient_id = keys[i];
    const appoints = appointMap[patient_id];
    if (!appoints) {
      continue;
    }
    let cache1 = [];
    for (let j = 0; j < appoints.length; j++) {
      const { appoint_id } = appoints[j];
      if (bookedMap[appoint_id]) {
        continue;
      }
      const resultsCopy = results.slice(0);
      resultsCopy.push({ appoint_id, patient_id });
      const res = findBestApptCombo(
        { ...appointMap, [patient_id]: null },
        resultsCopy,
        { ...bookedMap, [appoint_id]: patient_id },
      );
      if (res.length > cache1.length) {
        updated = true;
        cache1 = res;
      }
    }
    if (cache1.length > cache2.length) {
      updated = true;
      cache2 = cache1;
    }
  }
  return updated ? cache2 : results;
}

// https://www.geeksforgeeks.org/program-distance-two-points-earth/
function distanceInMiles(lat1, lat2, lon1, lon2) {
  lon1 = lon1 * pi180;
  lon2 = lon2 * pi180;
  lat1 = lat1 * pi180;
  lat2 = lat2 * pi180;
  const dlon = lon2 - lon1;
  const dlat = lat2 - lat1;
  const a = Math.pow(Math.sin(dlat / 2), 2)
    + Math.cos(lat1) * Math.cos(lat2)
    * Math.pow(Math.sin(dlon / 2), 2);
  const c = 2 * Math.asin(Math.sqrt(a));
  return (c * 3956);
}

module.exports = {
  scheduler,
};