<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: index.php');
    $_SESSION['table'] = 'users';
    $user = $_SESSION['user']; 
    $users = include('database/showUsers.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account Page</title>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="page-container">
        <!-- Navigation Button ☰ -->
        <?php include('partials/sidebar-navigation.php') ?>
        <!-- Logout Button -->
        <?php include('partials/logoutBtn.php') ?>
        <!-- Sidebar -->
        <?php include('partials/sidebar.php') ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="table-container">
                <h2>User Data Table</h2>
                <table id="user-table">
                    <thead>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <?php foreach($users as $index => $user){ ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td class="firstName"><?= $user['first_name'] ?></td>
                                    <td class="lastName"><?= $user['last_name'] ?></td>
                                    <td class="email"><?= $user['email'] ?></td>
                                    <td><?= date('d F Y', strtotime($user['created_at']))?></td>
                                    <td><?= date('d F Y', strtotime($user['updated_at']))?></td>
                                    <td class="actionColumn">
                                        <a href="" class="edit" data-userid="<?= $user['id']?>"><i class="fa fa-pencil" ></i>Edit</a>
                                        <a href="" class="deleteUser" data-userid="<?= $user['id']?>" data-fname="<?= $user['first_name']?>" data-lname="<?= $user['last_name']?>"><i class="fa fa-trash"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </thead>
                </table>
                <div id="pagination-controls"></div>
                <p class="userCount"><?= count($users) ?> Users</p>
            </div>
        </main>
    </div>
<script src="./js/sideBar.js"> </script>
<script src="./js/jquery-3.7.1.js"> </script>
<script src="./js/pagination.js"> </script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<link rel="stylesheet" href="./css/dashboard.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>
<script>
    function script(){

        this.initialize = function(){
            this.registerEvents();
        },

        this.registerEvents = function(){
            document.addEventListener('click', function(e){
                targetElement = e.target;
                classList = e.target.classList;

                // DELETE USER
                        if (classList.contains('deleteUser')) {
                            e.preventDefault();
                            userId = targetElement.dataset.userid;
                            fname = targetElement.dataset.fname;
                            lname = targetElement.dataset.lname;
                            fullName = fname + ' ' + lname;

                            if (window.confirm('Delete user ' + fullName + '?')) {
                                $.ajax({
                                    method: 'POST',
                                    data: {
                                        user_id: userId,
                                        f_name: fname,
                                        l_name: lname,
                                    },
                                    url: 'database/delete-user.php',
                                    dataType: 'json',
                                    success: function (data) {
                                        alert(data.message); // Show confirmation message
                                        if (data.success) {
                                            location.reload(); // Reload after successful deletion
                                        }
                                    }
                                });
                            }
                        }

                        // EDIT USER
                        if (classList.contains('edit')) {
                            e.preventDefault();

                            firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
                            lastName = targetElement.closest('tr').querySelector('td.lastName').innerHTML;
                            email = targetElement.closest('tr').querySelector('td.email').innerHTML;
                            userId = targetElement.dataset.userid;

                            let formContent = `
                                <form>
                                    <div class="form-group">
                                        <label for="firstName">First name:</label>
                                        <input type="text" class="form-control" id="firstName" value="${firstName}">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Last name:</label>
                                        <input type="text" class="form-control" id="lastName" value="${lastName}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email address:</label>
                                        <input type="email" class="form-control" id="emailUpdate" value="${email}">
                                    </div>
                                </form>`;

                            BootstrapDialog.show({
                                title: `Update ${firstName} ${lastName}`,
                                message: formContent,
                                buttons: [
                                    {
                                        label: 'Cancel',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    },
                                    {
                                        label: 'Save Changes',
                                        cssClass: 'btn-primary',
                                        action: function (dialog) {
                                            $.ajax({
                                                method: 'POST',
                                                data: {
                                                    userId: userId,
                                                    f_name: document.getElementById('firstName').value,
                                                    l_name: document.getElementById('lastName').value,
                                                    email: document.getElementById('emailUpdate').value,
                                                },
                                                url: 'database/update-user.php',
                                                dataType: 'json',
                                                success: function (data) {
                                                    alert(data.message); // Show success message
                                                    if (data.success) {
                                                        location.reload(); // Reload after update
                                                    }
                                                }
                                            });
                                            dialog.close();
                                        }
                                    }
                                ]
                            });
                        }

                }


        )}

        };

    var script = new script;
    script.initialize();
</script>

</body>
</html>