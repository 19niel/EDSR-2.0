<?php
include 'db_conn.php';
require 'C:\xampp\htdocs\e-dsr\vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['uploadFileButton'])) {
    $fileName = $_FILES['uploadFile']['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedExt = ['xls', 'csv', 'xlsx'];

    if (in_array($fileExt, $allowedExt)) {
        $inputFileName = $_FILES['uploadFile']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $totalId = 0;
        $errorOccurred = false; // Track if an error occurred

        foreach ($data as $index => $row) {
            // Skip the first row (headers)
            if ($index === 0) {
                continue;
            }

            if (!empty($row[0])) {
                $totalId++;

                // Extract data from the row
                $accExec = $row[0];
                $callDate = $row[1];
                $accName = $row[2];
                $endUser = $row[3];
                $segment = $row[4];
                $industry = $row[5];
                $accCat = $row[6];
                $accSource = $row[7];
                $address = $row[8];
                $area = $row[9];
                $contactPerson = $row[10];
                $designation = $row[11];
                $contactNumber = $row[12];
                $email = $row[13];
                $decisionMaker = $row[14];
                $dmNumber = $row[15];
                $dmDesignation = $row[16];
                $existingSystem = $row[17];
                $contractDateStart = $row[18];
                $contractDateEnd = $row[19];
                $proposedSystem = $row[20];
                $proposedPrice = $row[21];
                $paymentTerms = $row[22];
                $contactType = $row[23];
                $callNature = $row[24];
                $accStatus = $row[25];
                $whatTranspired = $row[26];
                $actionFollow = $row[27];

                // Retrieve branch and department information
                $branchQuery = "SELECT branch, dept FROM users WHERE name LIKE '%$accExec%' AND is_deleted = 0";
                $branchResult = mysqli_query($conn, $branchQuery);
                $branchData = mysqli_fetch_assoc($branchResult);

                if (isset($branchData['branch'])) {
                    $branchValue = $branchData['branch'];
                    $deptValue = $branchData['dept'];

                    // Insert data into the database
                    $sql = "INSERT INTO encoded (
                                accExec, branch, callDate, accName, endUser, segment, industry, accCat, accSource, address, area, 
                                contactPerson, designation, contactNumber, email, decisionMaker, dmNumber, dmDesignation, existingSystem, 
                                startContractDate, endContractDate, proposedSystem, proposedPrice, paymentTerms, contactType, callNature, 
                                accStatus, whatTranspired, actionFollow, dept
                            ) VALUES (
                                '$accExec', '$branchValue', '$callDate', '$accName', '$endUser', '$segment', '$industry', '$accCat', '$accSource', 
                                '$address', '$area', '$contactPerson', '$designation', '$contactNumber', '$email', '$decisionMaker', '$dmNumber', 
                                '$dmDesignation', '$existingSystem', '$contractDateStart', '$contractDateEnd', '$proposedSystem', '$proposedPrice', 
                                '$paymentTerms', '$contactType', '$callNature', '$accStatus', '$whatTranspired', '$actionFollow', '$deptValue'
                            );";
                    mysqli_query($conn, $sql);
                } else {
                    $errorOccurred = true; // Mark an error if branch info is missing
                }
            }
        }

        // Provide feedback to the user
        if ($errorOccurred) {
            echo '<script>alert("Error: Unable to retrieve branch information.");</script>';
        } else {
            echo '<script>alert("File Uploaded Successfully.");</script>';
        }
    } else {
        echo '<script>alert("Invalid File Type.");</script>';
    }
}
?>
