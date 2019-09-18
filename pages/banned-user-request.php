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
            <?php include '../components/banned-user-request.html'; ?>
        </div>
        <?php include '../components/footer.html'; ?>

        <script type="text/javascript">
            const userTable = document.querySelector('#tbody-users');
            const addForm = document.querySelector('#addUserForm');
            const updateForm = document.querySelector('#updateUserForm');
            let bannedStatus;
            let bannedText;
            let banElement;
            // Loading all data
            database.collection('users').where('isReport', '==', 5).onSnapshot(snapshot => {
                let changes = snapshot.docChanges();
                console.log(snapshot);
                changes.forEach(change => {
                    
                    if (change.type == "added") {
                        
                        if(change.doc.data().isReport == 5 && !change.doc.data().isBanned){
                            console.log(change.doc.data());
                            if (!change.doc.data().isBanned) {
                                bannedText = 'Not Banned';          
                            }
                            else{
                                bannedText = 'Banned';
                                console.log(bannedText);
                            }
                                renderElement(change.doc);
                        }
                    }else if (change.type === "modified") {
                            console.log(change.doc.data());
                            if(change.doc.data().isBanned){
                                let tr = userTable.querySelector('[data-id=' + change.doc.id + ']');
                                userTable.removeChild(tr);
                            }else if(change.doc.data().isReport == 5 && !change.doc.data().isBanned){
                                if (!change.doc.data().isBanned) {
                                    bannedText = 'Not Banned';          
                                }
                                else{
                                    bannedText = 'Banned';
                                    console.log(bannedText);
                                }
                                renderElement(change.doc);
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
                            <span class="user-span${doc.id}">
                                <a class="btn manipulateUser red" id="unban" onclick="manipulateUserStatus('${doc.id}', ${doc.data().isBanned})">
                                    <i class="material-icons">priority_high</i>
                                 </a>
                            </span>
                            
                        </td>
                    </tr>
                `);
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
            // let deleteUser = (id) => {
            //     Swal.fire({
            //       title: `Are you sure you want to delete this user`,
            //       type: 'warning',
            //       showCancelButton: true,
            //       confirmButtonColor: '#3085d6',
            //       cancelButtonColor: '#d33',
            //     }).then((result) => {
            //       if (result.value) {
            //         database.collection('users').doc(id).delete().then(data => {
            //             Swal.fire(
            //               '',
            //               `You have successfully deleted`,
            //               'success'
            //             )
            //         });
            //       }
            //     })
            // }

            // Banning and Unbanning user
            let manipulateUserStatus = (id, doc) => {
                console.log(doc);
                let status = doc ? false : true;

                Swal.fire({
                  title: `Are you sure you want to 'banned' this user`,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                }).then((result) => {
                    console.log(result);
                  if (result.value) {
                    database.collection('users').doc(id).update({
                        isBanned: status
                    }).then(data => {
                        Swal.fire(
                          '',
                          `You have successfully banned`,
                          'success'
                        )
                    });
                  }
                })
            }
        </script>
    </body>
</html>