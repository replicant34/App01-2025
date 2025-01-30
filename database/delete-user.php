<?php 
    $data = $_POST;
    $user_id = (int) $data['user_id'];
    $first_name = $data['f_name'];
    $last_name = $data['l_name'];

    include('connection.php'); // Include database connection

    try {

        // Use prepared statement to insert data
        $command = "DELETE FROM users WHERE id=($user_id)";

        include('connection.php'); // Include database connection

        $conn->exec($command);

        echo json_encode([
            'success' => true,
            'message' => $first_name . ' ' . $last_name . ' deleted'
        ]);

    } catch (PDOException $e) {

        echo json_encode([
            'success' => false,
            'message' => 'Error processing your request'
        ]);
    }
?>