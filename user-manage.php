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

    <!-- Add critical CSS inline to prevent FOUC -->
    <style>
        body {
            opacity: 0;
            transition: opacity 0.3s ease;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #74ebd5, #9face6);
            background-attachment: fixed;
        }
        .page-container {
            visibility: hidden;
        }
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #74ebd5, #9face6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>

    <!-- Load CSS files before the content -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <!-- Add loading indicator -->
    <div class="loading">
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

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
                <div class="export-buttons" style="margin-top: 15px;">
                    <button class="btn btn-success" id="exportCSV">Export to CSV</button>
                    <button class="btn btn-success" id="exportExcel">Export to Excel</button>
                </div>
                <div id="pagination-controls"></div>
                <p class="userCount"><?= count($users) ?> Users</p>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="./js/jquery-3.7.1.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="./js/sideBar.js"></script>
    <script src="./js/pagination.js"></script>

    <!-- Add this script at the end -->
    <script>
        // Hide loading and show content when everything is loaded
        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
            document.querySelector('.page-container').style.visibility = 'visible';
            document.querySelector('.loading').style.display = 'none';
        });
    </script>

    <!-- Your existing script -->
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

                    // Export to CSV
                    if (targetElement.id === 'exportCSV') {
                        e.preventDefault();
                        exportTableToCSV('user_data.csv');
                    }

                    // Export to Excel
                    if (targetElement.id === 'exportExcel') {
                        e.preventDefault();
                        exportTableToExcel('user_data.xlsx');
                    }
                });
            },

            // Function to export table to CSV
            exportTableToCSV = function(filename) {
                const table = document.getElementById('user-table');
                const rows = table.querySelectorAll('tr');
                let csv = [];
                
                for (let i = 0; i < rows.length; i++) {
                    const row = [], cols = rows[i].querySelectorAll('td, th');
                    
                    for (let j = 0; j < cols.length; j++) {
                        // Clean the text content and wrap in quotes if contains comma
                        let data = cols[j].textContent.replace(/(\r\n|\n|\r)/gm, '').trim();
                        if (data.includes(',')) data = `"${data}"`;
                        row.push(data);
                    }
                    csv.push(row.join(','));
                }

                // Download CSV file
                const csvContent = csv.join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                if (navigator.msSaveBlob) { // IE 10+
                    navigator.msSaveBlob(blob, filename);
                } else {
                    link.href = URL.createObjectURL(blob);
                    link.download = filename;
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            },

            // Function to export table to Excel
            exportTableToExcel = function(filename) {
                const table = document.getElementById('user-table');
                const wb = XLSX.utils.table_to_book(table, {sheet: "Users"});
                XLSX.writeFile(wb, filename);
            }
        }

        var script = new script;
        script.initialize();
    </script>
</body>
</html>