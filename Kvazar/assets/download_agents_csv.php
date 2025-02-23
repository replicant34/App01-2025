<?php
require_once '../config/db_connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="agents.csv"');

$output = fopen('php://output', 'w');

// Update the CSV headers to include all columns
fputcsv($output, [
    'Company Type', 'Full Company Name', 'Short Company Name', 'Contract Type', 
    'Contract Number', 'Contract Date', 'INN', 'KPP', 'OGRN', 
    'Physical Address', 'Legal Address', 'Bank Name', 'BIK', 
    'Settlement Account', 'Correspondent Account', 'Contact Person', 
    'Contact Person Position', 'Contact Person Phone', 'Contact Person Email', 
    'Head Position', 'Head Name'
]);

// Fetch all columns from the Clients table
$stmt = $pdo->query("SELECT Company_type, Full_Company_name, Short_Company_name, Contract_type, Contract_number, Contract_date, INN, KPP, OGRN, Physical_address, Legal_address, Bank_name, BIK, Settlement_account, Correspondent_account, Contact_person, Contact_person_position, Contact_person_phone, Contact_person_email, Head_position, Head_name FROM Agents");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
?> 