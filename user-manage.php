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
    <link rel="stylesheet" href="./css/dashboard.css">
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
                <table>
                    <thead>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $index => $user){ ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $user['first_name'] ?></td>
                                    <td><?= $user['last_name'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= date('d F Y', strtotime($user['created_at']))?></td>
                                    <td><?= date('d F Y', strtotime($user['updated_at']))?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </thead>
                </table>
                <p class="userCount"><?= count($users) ?> Users</p>
            </div>
        </main>
    </div>
    <script>
   var sideBarIsOpen = true;
    // Add event listener
    toggleBtn.addEventListener('click', (event) => {
        event.preventDefault();

        if(sideBarIsOpen){
            sidebar.style.width = '70px';
            sidebar.style.transition = '0.3s all';
            navigationcontainer.style.left = '6%';
            headerlogo.style.width = '70px';
            sidebartitle.style.fontSize = '14px';
            username.style.fontSize = '12px';
            
            buttontexts = document.getElementsByClassName('buttontext');
            for(var i=0; i < buttontexts.length; i++){
                buttontexts[i].style.display = 'none';
            }
            sideBarIsOpen = false;
        } else {
            sidebar.style.width = '250px';
            navigationcontainer.style.left = '15%';
            headerlogo.style.width = '100px';
            sidebartitle.style.fontSize = '24px';
            username.style.fontSize = '18px';
            
            
            buttontexts = document.getElementsByClassName('buttontext');
            for(var i=0; i < buttontexts.length; i++){
                buttontexts[i].style.display = 'inline-block';
            }
            sideBarIsOpen = true;
        }
    });
</script>