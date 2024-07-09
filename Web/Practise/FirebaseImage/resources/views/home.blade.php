<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
            
  <title>Home Page</title>
</head>
<body>
    <h1>Hello</h1>
    
  <form>
    <div class="file-field input-field">
      <div class="btn">
        <span>File</span>
        <input type="file" accept="image/*" onchange="uploadImage(event)">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
    </div>
    
  <button class="btn waves-effect waves-light" type="submit" name="action">Submit
    <i class="material-icons right">send</i>
  </button>
  </form>
  
  <script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyDMXsLHrCvJg1I_-xGBMccIfXiKvlEc7IM",
    authDomain: "laravelimagedemo.firebaseapp.com",
    projectId: "laravelimagedemo",
    storageBucket: "laravelimagedemo.appspot.com",
    messagingSenderId: "750043227965",
    appId: "1:750043227965:web:fdd5d1e685ec49da880754",
    measurementId: "G-SYVC1P92CQ"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);


  
</script>
<script type="module">
  
  import { firebase } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-database.js";
  function uploadImage(e)
    {
      console.log(e.target.files[0]);
      console.log(firebase)
    }
</script>
</body>
</html>