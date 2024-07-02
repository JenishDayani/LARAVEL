<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FIREBASE CRUD</title>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"
    ></script>
</head>
<body>
    <h1>User</h1>

    <form id="addUser">
        <label for="name">Name:</label>
        <input type="text" name="name" placeholder="Name" id="name" />
        <br /><br />
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Email" id="email" />
        <br /><br />
        <button type="submit" name="submit" id="submitUser">Submit</button>
    </form>

    <h2>Users:</h2>
    <table id="user-table" border  ="10px">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody id="user-list"></tbody>
    </table>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-app.js";
        import {
            getDatabase,
            ref,
            push,
            onValue,
        } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-database.js";

        const firebaseConfig = {
            apiKey: "{{config('services.firebase.apiKey')}}",
            authDomain: "{{config('services.firebase.authDomain')}}",
            databaseURL: "{{config('services.firebase.databaseURL')}}",
            projectId: "{{config('services.firebase.projectId')}}",
            storageBucket: "{{config('services.firebase.storageBucket')}}",
            messagingSenderId: "{{config('services.firebase.messagingSenderId')}}",
            appId: "{{config('services.firebase.appId')}}",
            measurementId: "{{config('services.firebase.measurementId')}}",
        };

        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);

        // View User
        const userListElement = document.getElementById("user-list");
        onValue(ref(database, "users"), (snapshot) => {
            userListElement.innerHTML = "";
            snapshot.forEach((childSnapshot) => {
                const userId = childSnapshot.key;
                const userData = childSnapshot.val();
                const tableRow = document.createElement("tr");
                tableRow.innerHTML = `
                    <td>${userId}</td>
                    <td>${userData.name}</td>
                    <td>${userData.email}</td>
                `;
                userListElement.appendChild(tableRow);
            });
        });

        // Add User
        document.getElementById("addUser").addEventListener("submit", function (event) {
            event.preventDefault();
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;

            push(ref(database, "users"), {
                name: name,
                email: email,
            })
               .then(() => {
                    console.log("Data saved successfully.");
                    document.getElementById("addUser").reset();
                })
               .catch((error) => {
                    console.error("Error saving data: ", error);
                });
        });
    </script>
</body>
</html>