<!DOCTYPE html>
<html lang="en">
<head>
  <title>Firebase CRUD</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

  <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js"></script>

  <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-analytics.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js"></script>

{{-- <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js"></script> --}}



</head>
<body>

  <h1>Users</h1>







  <form id="addUser"> 
    <input type="text" name="name" placeholder="Name" id="name" required>
    <br>
    <br>
    <input type="text" name="email" placeholder="Email" id="email" required>
    <br> 
    <br>
    <br>
    <br>

  </form>

    <button type="button" name="submit" id="submitUser">Submit</button>
    <script>


          $(document).ready(function() {
              $("#submitUser").on('click', function() {
                  console.log("Click");
                  var name = $("#name").val();
                  var email = $("#email").val();
                  console.log(name);
                  console.log(email);
                  const firebaseConfig = {
                      apiKey: "{{config('services.firebase.apiKey')}}",
                      authDomain: "{{config('services.firebase.authDomain')}}",
                      databaseURL: "{{config('services.firebase.databaseURL')}}",
                      projectId: "{{config('services.firebase.projectId')}}",
                      storageBucket: "{{config('services.firebase.storageBucket')}}",
                      messagingSenderId: "{{config('services.firebase.messagingSenderId')}}",
                      appId: "{{config('services.firebase.appId')}}",
                      measurementId: "{{config('services.firebase.measurementId')}}"
                  };
                  console.log("apiKey :- "+firebaseConfig.apiKey);
                  console.log("authDomain :- "+firebaseConfig.authDomain);
                  console.log("projectId :- "+firebaseConfig.projectId);
                  console.log("storageBucket :- "+firebaseConfig.storageBucket);
                  console.log("messagingSenderId :- "+firebaseConfig.messagingSenderId);
                  console.log("appId :- "+firebaseConfig.appId);
                  console.log("measurementId :- "+firebaseConfig.measurementId);
                  console.log("databaseURL :- "+firebaseConfig.databaseURL);
                  // Initialize Firebase
                  firebase.initializeApp(firebaseConfig);
                  console.log("FIREBASE INITIALIZE");
                  firebase.analytics();
                  console.log("FIREBASE FIREBASE");

                  var databaseUser = firebase.database();
                  console.log("DATABASE USER");
                  var user = {
                    name: name,
                    email: email
                    };
                    console.log("USER");
                  databaseUser.ref('users').set(user);
                  console.log("Success");



              });
          });
  


    // var database = firebase.database();

    // $("#submitUser").on('click',function () {
    //     console.log("Success Click");
    //     // var name = $("#name").val();
    //     // var email = $("#email").val();
    //     // console.log(name);
    //     // console.log(email);
    // });

</script>
</body>
</html>