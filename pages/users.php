<!doctype html>
<html lang="en">
    <head>
        <title>DASHBOARD</title>
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="../css/materialize.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="utf-8">
    </head>
    <style>
        .newbody{
            margin-left:25%;
        }
    </style>
    <body>
        <div id="sidebar">
            <?php include '../components/sidebar.html'; ?>
        </div>
        <div id="content">
            <?php include '../components/users-content.html'; ?>
        </div>
        <?php include '../components/footer.html'; ?>

        <script type="text/javascript">
            const userTable = document.querySelector('#tbody-users');
            const addForm = document.querySelector('#addUserForm');
            const updateForm = document.querySelector('#updateUserForm');
            let bannedStatus;
            let bannedText = 'adaas';
            let banElement;
            // Loading all data
            database.collection('users').onSnapshot(snapshot => {
                let changes = snapshot.docChanges();
                changes.forEach(change => {
                    
                    if (change.type == "added") {
                        if (change.doc.data().isBanned) {
                            bannedStatus = 0;
                            bannedText = 'Banned';
                            // banElement = `<a class="btn manipulateUser red" id="ban" onclick="manipulateUserStatus('${change.doc.id}', 1)">
                            //         <i class="material-icons">block</i>
                            //       </a>`;
                        }else{
                            bannedStatus = 1;
                            bannedText = 'Not Banned';
                            
                        }
                        // else if (change.doc.data().isBanned) {
                        //     bannedStatus = 1;
                        //     bannedText = 'Banned';
                        //     banElement = `<a class="btn manipulateUser red" id="unban" onclick="manipulateUserStatus('${change.doc.id}', 0)">
                        //             <i class="material-icons">thumb_up</i>
                        //           </a>`;
                        // }

                        renderElement(change.doc)
                    } else if (change.type == "removed") {
                        let tr = userTable.querySelector('[data-id=' + change.doc.id + ']');
                        userTable.removeChild(tr);
                    } else if (change.type === "modified") {

                       
                        let name = document.querySelector(`.user-name${change.doc.id}`);
                        // let status = document.querySelector(`.user-status${change.doc.id}`);
                        let img = document.querySelector(`.user-image${change.doc.id} img`);
                        let ban = document.querySelector(`.user-banned${change.doc.id}`);
                        let span = document.querySelector(`.user-span${change.doc.id}`);

                        name.textContent = `${change.doc.data().firstName} ${ change.doc.data().lastName}`;
                        // status.textContent = change.doc.data().online;
                       
                        img.setAttribute('src', change.doc.data().imageUrl);

                        if (change.doc.data().isBanned) {
                            bannedStatus = 0;
                            ban.textContent = 'Banned';
                            // span.innerHTML = `<a class="btn manipulateUser red" id="ban" onclick="manipulateUserStatus('${change.doc.id}', 1)">
                            //         <i class="material-icons">block</i>
                            //       </a>`;

                        } else {
                            bannedStatus = 1;
                            ban.textContent = 'Not Banned';
                            // span.innerHTML = `<a class="btn manipulateUser red" id="unban" onclick="manipulateUserStatus('${change.doc.id}', 0)">
                            //         <i class="material-icons">thumb_up</i>
                            //       </a>`;
                        }
                    }
                })
            });

            // Render data to tr elements
            let renderElement = (doc) => {
                $(userTable).append(`
                    <tr data-id="${doc.id}">
                        <td class="user-image${doc.id}"><img width="50" height="50" src="${doc.data().imageUrl}" /></td>
                        <td class="user-name${doc.id}" >${doc.data().firstName} ${doc.data().lastName}</td>
                        <td class="user-status${doc.id}">${doc.data().status}</td>
                        <td class="user-banned${doc.id}">${bannedText}</td>
                        <td>
                            <a class="btn primary" onclick="redirectTo('users-profile.php?id=${doc.id}')">
                                <i class="material-icons">pageview</i>
                            </a>
                        </td>
                    </tr>
                `);
            }

            function redirectTo(sUrl) {
                window.open(sUrl);
            }

            // <span class="user-span${doc.id}">
            //                     ${banElement}
            //                 </span>

            // Saving data
            // addForm.addEventListener('submit', (e) => {
            //     e.preventDefault();
            //     let file = addForm.imageUrl.files[0];
            //     let storageRef = storage.ref('profile_images/' + file.name);

            //     let task = storageRef.put(file).then(snapshot => {
            //         snapshot.ref.getDownloadURL().then(function(downloadURL) {
            //             database.collection('users').add({
            //                 firstName: addForm.firstName.value,
            //                 lastName: addForm.lastName.value,
            //                 imageUrl: downloadURL,
            //                 online: true
            //             }).then(data => {
            //                 Swal.fire(
            //                   '',
            //                   `You have successfully added a user`,
            //                   'success'
            //                 ).then(() => {                                                                    
            //                     addForm.firstName.value = '';
            //                     addForm.firstName.value = '';
            //                 })
            //             });
            //         });
            //     });
            // });

            // // Updating data
            // updateForm.addEventListener('submit', (e) => {
            //     e.preventDefault();
            //     let id = updateForm.firstName.getAttribute('data-id');
            //     let firstName = updateForm.firstName.value;
            //     let lastName = updateForm.lastName.value;
            //     let file = updateForm.imageUrl.files[0];
            //     if (typeof file != "undefined") {
            //         let storageRef = storage.ref('profile_images/' + file.name);
            //         let task = storageRef.put(file).then(snapshot => {
            //             snapshot.ref.getDownloadURL().then(function(downloadURL) {
            //                 database.collection('users').doc(id).update({
            //                     firstName,
            //                     lastName,
            //                     imageUrl: downloadURL
            //                 }).then(data => {
            //                     Swal.fire(
            //                       '',
            //                       `You have successfully updated`,
            //                       'success'
            //                     ).then(() => {                                    
            //                         updateForm.firstName.value = firstName;
            //                         updateForm.lastName.value = lastName;
            //                         updateForm.imageUrl.value = '';
            //                     })
            //                 });
            //             });
            //         });
            //     } else {
            //         database.collection('users').doc(id).update({
            //             firstName,
            //             lastName,
            //         }).then(data => {
            //             Swal.fire(
            //               '',
            //               `You have successfully updated`,
            //               'success'
            //             ).then(() => {

            //                 updateForm.firstName.value = firstName;
            //                 updateForm.lastName.value = lastName;
            //                 updateForm.imageUrl.value = '';
            //             })
            //         });
            //     }
            // })

            // // Open Update Modal
            // let updateUser = (id) => {
            //     database.collection('users').doc(id).get().then(snapshot => {
            //         updateForm.firstName.value = snapshot.data().firstName;
            //         updateForm.lastName.value = snapshot.data().lastName;
            //         updateForm.firstName.setAttribute('data-id', id);
            //         updateForm.lastName.setAttribute('data-id', id);
            //     })
            // }

            // Deleting Data
            let deleteUser = (id) => {
                Swal.fire({
                  title: `Are you sure you want to delete this user`,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                }).then((result) => {
                  if (result.value) {
                    database.collection('users').doc(id).delete().then(data => {
                        Swal.fire(
                          '',
                          `You have successfully deleted`,
                          'success'
                        )
                    });
                  }
                })
            }

            // Banning and Unbanning user
            let manipulateUserStatus = (id, type) => {
                Swal.fire({
                  title: `Are you sure you want to Banned this user`,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                }).then((result) => {
                  if (result.value) {
                    database.collection('users').doc(id).update({
                        isBanned: result.value
                    }).then(data => {
                        let x = bannedStatus ? 'banned' : 'unbanned';
                        Swal.fire(
                          '',
                          `You have successfully ${x}`,
                          'success'
                        )
                    });
                  }
                })
            }
        </script>
    </body>
</html>