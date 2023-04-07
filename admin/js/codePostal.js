// const codePostalInput = document.getElementById('codePostal');
// const villeInput = document.getElementById('ville');

// codePostalInput.addEventListener('input', () => {
//   const codePostal = codePostalInput.value;
//   const url = `https://nominatim.openstreetmap.org/search?q=${codePostal}&format=json&addressdetails=1&limit=1`;
//   fetch(url)
//     .then(response => response.json())
//     .then(data => {
//       const ville = data[0].address.city;
//       villeInput.value = ville;
//     })
//     .catch(error => console.error(error));
// });


const codePostalInput = document.getElementById('codePostal');
const villeInput = document.getElementById('ville');
const username = 'KumaFR';

codePostalInput.addEventListener('input', () => {
  const codePostal = codePostalInput.value;
  const url = `http://api.geonames.org/findNearbyPostalCodesJSON?postalcode=${codePostal}&country=FR&radius=0&username=${username}`;
  fetch(url)
    .then(response => response.json())
    .then(data => {
      const ville = data.postalCodes[0].adminName3;
      villeInput.value = ville;
    })
    .catch(error => console.error(error));
});


//JVvJbZWYCrSyziQT3QYcdnkyHbYN5OWF