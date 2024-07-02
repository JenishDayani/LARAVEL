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


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="user-list"></tbody>
    </table>


        <!-- Button trigger modal -->
    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModel">
    Edit
    </button> --}}

    <!-- Modal -->
    <form id="userUpdateForm">
        <div class="modal fade" id="updateModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="updateBody">
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateUserButton">Update</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-app.js";
        import {
            getDatabase,
            ref,
            push,
            onValue,
            update,
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
                    <td>
                        <button type="button" class="btn btn-primary updateUserButton" data-toggle="modal" data-target="#updateModel" data-id="${userId}">
                            Edit
                        </button>
                    </td>
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

        let updateUserId = 0;
        // Edit User
        $("body").on("click", ".updateUserButton", function() {
            updateUserId = $(this).attr("data-id");
            onValue(ref(database,'users/' + updateUserId), (snapshot) => {
                const userData = snapshot.val();
                console.log(userData.name);
                console.log(userData.email);
                
                
                const updateBody = document.getElementById("updateBody");
                updateBody.innerHTML = `
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="${userData.name}" id="updateName" />
                    <br /><br />
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="${userData.email}" id="updateEmail" />
                    <br /><br />
                `;
            });
        });

        $("body").on("click", "#updateUserButton", function() {
            const name = document.getElementById("updateName").value;
            const email = document.getElementById("updateEmail").value;
            console.log(name);
            console.log(email);
            console.log(updateUserId);

            update(ref(database, 'users/' + updateUserId), {
                name: name,
                email: email,
            })
            .then(() => {
                console.log("User updated successfully.");
                $("#updateModel").modal('hide');
            })
            .catch((error) => {
                console.error("Error updating user: ", error);
            });
        });


        // Delete User

    </script>
</body>
</html>