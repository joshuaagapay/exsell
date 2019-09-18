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
            <?php include '../components/category-content.html'; ?>
        </div>
        <?php include '../components/footer.html'; ?>

        <script type="text/javascript">
            const categoryTable = document.querySelector('#tbody-category');
            const addForm = document.querySelector('#addCategory');
            const updateForm = document.querySelector('#update-category-form');

            let url = window.location.href;
            let id = url.substring(url.lastIndexOf('=') + 1);
            console.log(id);

            // Loading all data
            database.collection('category').onSnapshot(snapshot => {
                let changes = snapshot.docChanges();
                changes.forEach(change => {
                    if (change.type == "added") {
                        renderElement(change.doc)
                    } else if (change.type == "removed") {
                        let tr = categoryTable.querySelector('[data-id=' + change.doc.id + ']');
                        categoryTable.removeChild(tr);
                    } else if (change.type === "modified") {
                        let name = document.querySelector(`.category-name${change.doc.id}`);
                        let img = document.querySelector(`.category-image${change.doc.id} img`);
                        name.textContent = change.doc.data().categoryName;
                        img.setAttribute('src', change.doc.data().categoryImageUrl);
                    }
                    console.log(change.doc.id);
                })
            });

            // Render data to tr elements
            let renderElement = (doc) => {
                $(categoryTable).append(`
                    <tr data-id="${doc.id}">
                        <td class="category-image${doc.id}"><img width="50" height="50" src="${doc.data().categoryImageUrl}" /></td>
                        <td class="category-name${doc.id}">${doc.data().categoryName}</td>
                        <td>
                            <a class="btn amber modal-trigger" href="#updatecategory" onclick="updateCategory('${doc.id}')">
                                <i class="material-icons">edit</i>
                            </a>
                            <a class="btn red" onclick="deleteCategory('${doc.id}')">
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                `);
            }

            // Saving data
            addForm.addEventListener('submit', (e) => {
                e.preventDefault();
                let file = addForm.category_image.files[0];
                let storageRef = storage.ref('categoryImages/' + file.name);

                let task = storageRef.put(file).then(snapshot => {
                    snapshot.ref.getDownloadURL().then(function(downloadURL) {
                        database.collection('category').add({
                            categoryName: addForm.category_name.value,
                            categoryImageUrl: downloadURL
                        }).then(data => {
                            Swal.fire(
                              '',
                              `You have successfully added`,
                              'success'
                            ).then(() => {                                                                                                        
                                addForm.category_name.value = '';
                                addForm.category_image.value = '';
                            })
                        });
                    });
                });
            });

            // Updating data
            updateForm.addEventListener('submit', (e) => {
                e.preventDefault();
                let id = updateForm.categoryName.getAttribute('data-id');
                let categoryName = updateForm.categoryName.value;
                let file = updateForm.category_image.files[0];
                if (typeof file != "undefined") {
                    let storageRef = storage.ref('categoryImages/' + file.name);
                    let task = storageRef.put(file).then(snapshot => {
                        snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            database.collection('category').doc(id).update({
                                categoryImageUrl: downloadURL,
                                categoryName
                            }).then(data => {
                                Swal.fire(
                                  '',
                                  `You have successfully updated`,
                                  'success'
                                ).then(() => {                                                                        
                                    updateForm.categoryName.value = categoryName;
                                    updateForm.category_image.value = '';
                                })
                            });
                        });
                    });
                } else {
                    database.collection('category').doc(id).update({
                        categoryName
                    }).then(data => {
                        Swal.fire(
                          '',
                          `You have successfully updated`,
                          'success'
                        ).then(() => {                                                                        
                            updateForm.categoryName.value = categoryName;
                            updateForm.category_image.value = '';
                        })
                    });
                }
            })

            // Open Update Modal
            let updateCategory = (id) => {
                database.collection('category').doc(id).get().then(snapshot => {
                    updateForm.categoryName.value = snapshot.data().categoryName;
                    updateForm.categoryName.setAttribute('data-id', id);
                })
            }
           
            // Deleting Data
            let deleteCategory = (id) => {
                Swal.fire({
                  title: `Are you sure you want to delete this category`,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                }).then((result) => {
                  if (result.value) {
                    database.collection('category').doc(id).delete().then(data => {
                        Swal.fire(
                          '',
                          `You have successfully deleted`,
                          'success'
                        )
                    });
                  }
                })
            }
        </script>
    </body>
</html>