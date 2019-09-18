// Your web app's Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyD_f7EoqGZ4iwALyIvVSllURthoQtioYTU",
    authDomain: "exsell-50cf1.firebaseapp.com",
    databaseURL: "https://exsell-50cf1.firebaseio.com",
    projectId: "exsell-50cf1",
    storageBucket: "exsell-50cf1.appspot.com",
    messagingSenderId: "658477238630",
    appId: "1:658477238630:web:153af40739b6a081"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const auth = firebase.auth();
const database = firebase.firestore();
const storage = firebase.storage();

// Initialize Materialize
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    M.Modal.init(elems);
});