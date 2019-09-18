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
            <?php include '../components/sub-category-content.html'; ?>
        </div>
        <?php include '../components/footer.html'; ?>

        <script type="text/javascript">
            const subcategoryTable = document.querySelector('#subcategory-tbody');
            const addForm = document.querySelector('#add-subcategory-form');
            const updateForm = document.querySelector('#update-subcategory-form');

            // Loading all category data
            database.collection('category').get()
            .then(function(querySnapshot) {
                querySnapshot.forEach((doc) => {
                    
                // doc.data() is never undefined for query doc snapshots
                    $('#select-category').append(`
                        <option id="${doc.id}" value="${doc.data().categoryName}">${doc.data().categoryName}</option>
                    `);
                    
                    $('#update-select-category').append(`
                        <option id="${doc.id}" value="${doc.data().categoryName}" selected>${doc.data().categoryName}</option>
                    `);  
                });
            });


            // Loading all data
            database.collectionGroup('sub-category').onSnapshot(snapshot => {
                let changes = snapshot.docChanges();
                changes.forEach(change => {
						
                    if (change.type == "added") {
                        renderElement(change.doc)
                    } else if (change.type == "removed") {
                        let tr = subcategoryTable.querySelector(`[data-id="${change.doc.id}"]`);
                        subcategoryTable.removeChild(tr);
                    } else if (change.type === "modified") {
                        let name = document.querySelector(`.category${change.doc.id}`);
                        let subName = document.querySelector(`.category-name${change.doc.id}`);
                        name.textContent = change.doc.data().categoryName;
                        subName.textContent = change.doc.data().subCategoryName;
                    }
                })
            });

            // Render data to tr elements
            let renderElement = (doc) => {
                    
                    $(subcategoryTable).append(`
                        <tr data-id="${doc.id}">
                            <td class="category${doc.id}">${doc.data().categoryName}</td>
                            <td class="category-name${doc.id}">${doc.data().subCategoryName}</td>
                            <td>
                                <a class="btn amber modal-trigger" href="#update-subcategory" onclick="updateCategory('${doc.id}','${doc.data().categoryName}')">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a class="btn red" onclick="deleteSubCategory('${doc.id}','${doc.data().categoryName}')">
                                    <i class="material-icons">delete</i>
                                </a>
                            </td>
                        </tr>
                    `);

              
            }

            // Saving data
            addForm.addEventListener('submit', (e) => {
                e.preventDefault();

								let select = document.getElementById('select-category');
								let id = select.options[select.selectedIndex].id;

                database.collection('category').doc(id).collection('sub-category').add({
                    categoryName: addForm.category.value,
                    subCategoryName: addForm.subcategory_name.value,
                }).then(data => {
                    Swal.fire(
                      '',
                      `You have successfully added`,
                      'success'
                    ).then(() => {                                                                                                                                                    
                        addForm.subcategory_name.value = '';
                        addForm.category.value = '';
                    })
                });
            });


            // Updating data
            updateForm.addEventListener('submit', (e) => {
                e.preventDefault();

								let select = document.getElementById('update-select-category');
								let ids = select.options[select.selectedIndex].id;
                let id = updateForm.update_subcategory_name.getAttribute('data-id');

                database.collection('category').doc(ids).collection('sub-category').doc(id).update({
                    categoryName: updateForm.updateCategory.value,
                    subCategoryName: updateForm.update_subcategory_name.value,
                }).then(data => {
                    Swal.fire(
                      '',
                      `You have successfully updated`,
                      'success'
                    )
                });
            })


            // Open Update Modal
            let updateCategory = (id,categoryName) => {
							
							let select = document.getElementById('update-select-category');
							select.setAttribute("disabled", false);

							database.collection('category').where('categoryName', '==', categoryName).get().then(snapshot => {
									snapshot.forEach(doc => {
										let categoryId = doc.id;
										console.log(id);
										database.collection('category').doc(categoryId).collection('sub-category').doc(id).get().then(snapshot => {
										
											updateForm.updateCategory.value = snapshot.data().categoryName;
                    	updateForm.update_subcategory_name.value = snapshot.data().subCategoryName;
                    	updateForm.update_subcategory_name.setAttribute('data-id', id);
                		})
										
									});
		
								});
            }


            // Deleting Data
            let deleteSubCategory = (id,categoryName) => {


							database.collection('category').where('categoryName', '==', categoryName).get().then(snapshot => {
									snapshot.forEach(doc => {
										let categoryId = doc.id;
										
										Swal.fire({
                  title: `Are you sure you want to delete this sub category`,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                }).then((result) => {
                  if (result.value) {
                    database.collection('category').doc(categoryId).collection('sub-category').doc(id).delete().then(data => {
                        Swal.fire(
                          '',
                          `You have successfully deleted`,
                          'success'
                        )
                    });
                  }
                })
										
									});
		
								});
            }

        </script>
    </body>
</html>