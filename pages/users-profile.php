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
            <?php include '../components/users-profile.html'; ?>
        </div>
        <?php include '../components/footer.html'; ?>

        

        <script type="text/javascript">
           $(document).ready(function() {
                $('.modal').modal();
            });
						
            const remnantTable = document.querySelector('#tbody-remnants');
						const remModal = document.querySelector('#remModal');
            const buyModal = document.querySelector('#buyModal');
            const sellModal = document.querySelector('#sellModal');
            const bidModal = document.querySelector('#bidModal');
            const userProfile = document.querySelector('#user-profile');
            const addForm = document.querySelector('#addUserForm');
            const updateForm = document.querySelector('#updateUserForm');
            let onlineStatus;
            let bannedText;
            let banElement;

            let url = window.location.href;
            let id = url.substring(url.lastIndexOf('=') + 1);
            
            // Loading all data
            database.collection('users').doc(id).onSnapshot(snapshot => {
        
              (!snapshot.data().isBanned) ? bannedText = 'Not Banned' : bannedText = 'Banned';
          
              (snapshot.data().online) ? onlineStatus = 'offline' : onlineStatus = 'online';
              
              renderElement(snapshot);
                
            });

            database.collection('remnants').where('userId', '==', id).onSnapshot(snapshot => {
              let remnantsList;
              snapshot.docs.forEach(doc => {
                
                remnantsList += `<tr data-id="${doc.id}">
										              <td class="user-image${doc.id}"><img width="50" height="50" src="${doc.data().imageUrl[0]}" /></td>
										              <td class="user-name${doc.id}">${doc.data().title}</td>
										              <td>
											              <a data-target="remModal" class="modal-trigger btn primary" onclick="remnantsToModal('${doc.id}')">
												              <i class="material-icons">search</i>
											              </a>
										              </td>
									              </tr>`
              })
              renderRemnants(remnantsList);
              
            });

            // Render data to tr elements
            let renderElement = (doc) => {
                
                $(userProfile).empty();
                $(userProfile).append(`
                      <div class="user-profile-details" style="width: 34%; position: relative; left: 30%; top: 10px">
                          <div class ="row">
                              <div class="col m12">
                                <img class="responsive-img" style="height: 304%; width: 304%" src="${doc.data().imageUrl}" /><br>
                              </div>
                              <div class="col m12">
                                <p style="margin: 0; text-align: center; font-size: 25px"><strong>${doc.data().firstName}&nbsp;${doc.data().lastName}</strong></p></br>
                                <div class="divider"></div>
                              </div>  
                              <div class="col m6" style="margin: 0">
                                <p style="margin: 0"><strong style="float: left"><span class="">Status:</span></strong></p>
                              </div>
                              <div class="col m6" style="margin: 0">
                                <p style="margin: 0"><span style="">${doc.data().status}</span></p>
                              </div>
                              <div class="col m6" style="margin: 0">
                                <p style="margin: 0"><strong style="float: left"><span class="">Wallet:</span></strong></p>
                              </div>
                              <div class="col m6" style="margin: 0">
                                <p style="margin: 0"><span style="">${doc.data().wallet}php</span></p></br>
                              </div>
                              
                              <div class="col m4" style="margin: 0">
                                <span class="user-span${doc.id}">
                                  <a data-target="buyModal" class="modal-trigger btn-small" id="buying-history" onclick="renderToBuyModal('${doc.id}')">        
                                    Buying History
                                  </a>
                                </span>
                              </div>
                              <div class="col m4" style="margin: 0">
                                <span class="user-span${doc.id}">
                                  <a data-target="sellModal" class="modal-trigger btn-small" id="selling-history" onclick="renderToSellModal('${doc.id}')">  
                                    Selling History
                                  </a>
                                </span>
                              </div>
                              <div class="col m4" style="margin: 0">
                                <span class="user-span${doc.id}">
                                  <a data-target="bidModal" class="modal-trigger btn-small" id="bidding-history" onclick="renderToBidModal('${doc.id}')">  
                                    Bidding History
                                  </a>
                                </span>
                              </div>            
                          </div>
                      </div>
                `);
            }

            let renderRemnants = (remnants) => {
                  $(remnantTable).empty();
                  $(remnantTable).append(remnants);
								}
						
						let remnantsToModal = (id) => {
							
							database.collection('remnants').doc(id).onSnapshot(snapshot => {
								$(remModal).empty();
								$(remModal).append(` 
                	<div class="modal-content">
										<h4>User Remnants Details</h4>
										<div class ="row">
                      <div class="col m12">
                        <img class="responsive-img" style="height: 304%; width: 304%" src="${snapshot.data().imageUrl}" /><br>
                      </div>
                      <div class="col m12">
                        <p style="margin: 0; text-align: center; font-size: 25px"><strong>${snapshot.data().title}</strong></p></br>
                      	<div class="divider"></div>
                      </div>  
                      <div class="col m6" style="margin: 0">
                        <p style="margin: 0"><strong style="float: left"><span class="">Type:</span></strong></p>
                      </div>
                      <div class="col m6" style="margin: 0">
                        <p style="margin: 0"><span style="">${snapshot.data().type}</span></p>
                      </div>
                      <div class="col m6" style="margin: 0">
                        <p style="margin: 0"><strong style="float: left"><span class="">Meet Up:</span></strong></p>
                      </div>
                      <div class="col m6" style="margin: 0">
                        <p style="margin: 0"><span style="">${snapshot.data().meetup}</span></p></br>
                      </div>
                    </div>
                  	
                	</div>
                	<div class="modal-footer">
                  	<a class="modal-close waves-effect waves-green btn-flat">Close</a>
                	</div>
              	`);
							});
						}


            //render data to Buying History Modal
            let renderToBuyModal = (id) => {
							$('#tbody-buy').empty();
							$(buyModal).empty();

							database.collection('users').doc(id).collection('orders').onSnapshot(snapshot => {
								snapshot.docs.forEach(doc => {
									
                  let remnantID = doc.data().remnantId;
                  let subTotal = doc.data().subTotal;
                  let timeStamp = doc.data().timeStamp;
                  let quantity = doc.data().quantity;
                  console.log(doc);
                    
										  $('#tbody-buy').append(`
											  <tr data-id="${doc.id}">
												  <td class="notif-name${doc.id}">${remnantID}</td>
												  <td class="notif-name${doc.id}">${quantity}</td>
                          <td class="notif-name${doc.id}">${subTotal}</td>
												  <td class="notif-name${doc.id}">${timeStamp.toDate()}</td>
											  </tr>
										  `);
									  
                  
								})
                
							});

              $(buyModal).append(` 
                <div class="modal-content">
                  <h4>User Buying History</h4>
                  <table class = "highlight centered" id="table-buyer-list" style = "margin-top:50px;">
										<thead>
											<tr>
												<th>REMNANT ID</th>
												<th>QUANTITY</th>
                        <th>SUB TOTAL</th>
                        <th>DATE</th>
											</tr>
										</thead>
										<tbody id="tbody-buy">
										</tbody>
									</table>
                </div>
                <div class="modal-footer">
                  <a class="modal-close waves-effect waves-green btn-flat">Close</a>
                </div>
                `);
            }
            
            //render data to Selling History Modal
            let renderToSellModal = (id) => {
              $('#tbody-sell').empty();
							$(sellModal).empty();

                database.collection('remnants').where('userId', '==', id).onSnapshot(snapshot => {
                  snapshot.docs.forEach(doc => {
                    if(doc.data().type == 'Fixed Price'){
                      console.log(doc.data().title)
                        $('#tbody-sell').append(`
											      <tr data-id="${doc.id}">
												      <td class="notif-name${doc.id}">${doc.data().title}</td>
												      <td class="notif-name${doc.id}">${doc.data().description}</td>
                              <td class="notif-name${doc.id}">${doc.data().price}php</td>
                              <td class="notif-name${doc.id}">${doc.data().timeStamp.toDate()}</td>
											      </tr>
										    `);
                    }
                  })
                })

              $(sellModal).append(` 
                <div class="modal-content">
                  <h4>User Selling History</h4>
                  <table class = "highlight centered" id="table-seller-list" style = "margin-top:50px;">
										<thead>
											<tr>
                        <th>REMNANT NAME</th>
                        <th>DESCRIPTION</th>
												<th>PRICE</th>
												<th>DATE</th>
											</tr>
										</thead>
										<tbody id="tbody-sell">
										</tbody>
									</table>
                </div>
                <div class="modal-footer">
                  <a class="modal-close waves-effect waves-green btn-flat">Close</a>
                </div>
                `);
            }

            

            //render data to Bidding History Modal
            let renderToBidModal = async (id) => {
              let bidAmount;
              let timeStamp;
              let bidderFname;
              let bidderLname;
              let remnantName;
              let userId;

							$('#tbody-bid').empty();
							$(bidModal).empty();
             
              database.collection('remnants').where('userId', '==', id).onSnapshot(snapshot => {
                snapshot.docs.forEach(doc => {
                  remnantName = doc.data().title;
                  database.collection('remnants').doc(doc.id).collection('bidders').onSnapshot(snapshot => {
                    snapshot.docs.forEach(doc => {
                      bidAmount = doc.data().bidAmount;
                      timeStamp = doc.data().timeStamp.toDate;
                      userId = doc.data().userId;
                      console.log(bidAmount);
                      database.collection('users').doc(userId).onSnapshot(snapshot => {
                        console.log(snapshot.data().firstName);
                        	$('#tbody-bid').append(`
											      <tr data-id="${doc.id}">
												      <td class="notif-name${doc.id}">${snapshot.data().firstName}&nbsp;${snapshot.data().lastName}</td>
												      <td class="notif-name${doc.id}">${bidAmount}</td>
                              <td class="notif-name${doc.id}">${remnantName}</td>
												      <td class="notif-name${doc.id}">${timeStamp}</td>
											      </tr>
										      `);
                      })
                    })
                  })
                })
              })

              $(bidModal).append(` 
                <div class="modal-content">
                  <h4>User Bidding History</h4>
                  <table class = "highlight centered" id="table-bidder-list" style = "margin-top:50px;">
										<thead>
											<tr>
												<th>BIDDER NAME</th>
												<th>AMOUNT</th>
                        <th>REMNANT NAME</th>
                        <th>DATE</th>
											</tr>
										</thead>
										<tbody id="tbody-bid">
										</tbody>
									</table>
                </div>
                <div class="modal-footer">
                  <a class="modal-close waves-effect waves-green btn-flat">Close</a>
                </div>
                `);
            }

            // Banning and Unbanning user
            let manipulateUserStatus = (id, type) => {
                let i = !type ? 'unbanned' : 'ban';

                Swal.fire({
                  title: `Are you sure you want to ${i} this user`,
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                }).then((result) => {
                  if (result.value) {
                    database.collection('users').doc(id).update({
                        isBanned: type
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



