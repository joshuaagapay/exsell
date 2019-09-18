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

        </div>
        <?php include '../components/footer.html'; ?>

         <script type="text/javascript">
            let login = localStorage.getItem('firstLogin');
            if (login) {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 1500
                })

                Toast.fire({
                  type: 'success',
                  title: 'Signed in successfully'
                }).then(() => {
                    localStorage.removeItem('firstLogin');
                })
            }
        </script>
    </body>
</html>