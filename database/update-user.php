<?php 
    $data = $_POST;
    $user_id = (int) $data['userId'];
    $first_name = $data['f_name'];
    $last_name = $data['l_name'];
    $email = $data['email'];
    $updated_at = date('Y-m-d H:i:s');

    try {
        $sql = "UPDATE users SET email=?, first_name=?, last_name=?, updated_at=? WHERE id=?";

        include('connection.php'); // Include database connection

        $conn->prepare($sql)->execute([$email, $first_name, $last_name, $updated_at, $user_id]);

            echo json_encode([
                'success' => true,
                'message' => $first_name . ' ' . $last_name . ' updated'
            ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error processing your request'
        ]);
    }
?>