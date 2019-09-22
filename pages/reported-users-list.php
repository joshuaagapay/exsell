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
            <?php include '../components/reported-users-list.html'; ?>
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
            database.collection('users').where('isReport', '>', 0).onSnapshot(snapshot => {
                let changes = snapshot.docChanges();
                
                changes.forEach(change => {
                    
                    if (change.type == "added") {
                        
                        if(change.doc.data().isReport > 0){
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
                            }else if(change.doc.data().isReport > 0){
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
                
                $(userTable).empty();
                $(userTable).append(`
                    <tr data-id="${doc.id}">
                        <td class="user-image${doc.id}"><img width="50" height="50" src="${doc.data().imageUrl}" /></td>
                        <td class="user-name${doc.id}" >${doc.data().firstName} ${doc.data().lastName}</td>
                        <td class="user-status${doc.id}">${doc.data().status}</td>
                        <td class="user-banned${doc.id}">${bannedText}</td>
                        <td class="user-report${doc.id}">${doc.data().isReport}</td>
                    </tr>
                `);
            }

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