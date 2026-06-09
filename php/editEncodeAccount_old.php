<?php
include('db_conn.php');

$sql1 = "SELECT * FROM encoded WHERE is_deleted = 0";
$accountResult = mysqli_query($conn, $sql1);

if (isset($_POST['editEncode'])) {
    $id = $_POST['encodeId'] ?? NULL; // Master account reference row ID
    
    // NEW: Check if this submission is authorized to modify full master data
    $isAdminEdit = isset($_POST['is_admin_edit']) && $_POST['is_admin_edit'] === 'true';

    // Catching progress log updates (required for both actions)
    $accountStatus = $_POST['accountStatus'] ?? NULL;
    $reasonSubcategory = $_POST['reasonSubcategory'] ?? NULL; 
    // $reminderDate = !empty($_POST['reminderDate']) ? $_POST['reminderDate'] : NULL; 
    $remarks = $_POST['remarks'] ?? NULL; // This acts as the log remarks/update text
    $estimatedDelivery = $_POST['estimatedDelivery'] ?? NULL; 
    $deliveryDate = $_POST['deliveryDate'] ?? NULL;
    $contractEnd = $_POST['contractEnd'] ?? NULL;

    $masterUpdateSuccess = true;

    // ====================================================
    // CONDITION 1: EXECUTE FULL MASTER RECORD SQL IF ADMIN
    // ====================================================
    if ($isAdminEdit) {
        $sbu = $_POST['sbu'] ?? NULL;
        $accountExecutive = $_POST['accountExecutive'] ?? NULL;
        $callDate = $_POST['callDate'] ?? NULL;
        $accountName = $_POST['accountName'] ?? NULL;
        $arsExpiryDate = $_POST['arsExpiryDate'] ?? NULL;
        $endUser = $_POST['endUserType'] ?? NULL;
        $segment = $_POST['segment'] ?? NULL;
        $industrySubcategory = $_POST['industrySubcategory'] ?? NULL; 
        $accountCategory = $_POST['accountCategory'] ?? NULL;
        $accountSource = $_POST['accountSource'] ?? NULL;
        $accountSourceCategory = $_POST['accountSourceCategory'] ?? NULL; 
        $contactPerson = $_POST['contactPerson'] ?? NULL;
        $designation = $_POST['designation'] ?? NULL;
        $contactNumber = $_POST['contactNumber'] ?? NULL;
        $emailAddress = $_POST['emailAddress'] ?? NULL;
        $decisionMaker = $_POST['decisionMaker'] ?? NULL;
        $dmDesignation = $_POST['dmDesignation'] ?? NULL;
        $dmEmail = $_POST['dmEmail'] ?? NULL;
        $productType = $_POST['productType'] ?? NULL;
        $productTypeSubcategory = $_POST['productTypeSubcategory'] ?? NULL; 
        $quantity = $_POST['quantity'] ?? NULL;
        $proposedPrice = $_POST['proposedPrice'] ?? NULL;
        $paymentTerms = $_POST['paymentTerms'] ?? NULL;
        $contractType = $_POST['contractType'] ?? NULL;
        $callNature = $_POST['callNature'] ?? NULL;
        $reason = !empty($_POST['reason']) ? $_POST['reason'] : NULL; 
        $followUpAction = $_POST['followUpAction'] ?? NULL;
        $existingSystem = $_POST['existingSystem'] ?? NULL;
        $contractEndCompetitor = $_POST['contractEndCompetitor'] ?? NULL;
        $region = $_POST['region'] ?? NULL;
        $province = $_POST['province'] ?? NULL;
        $city = $_POST['city'] ?? NULL;
        $barangay = $_POST['barangay'] ?? NULL;
        $address = $_POST['address'] ?? NULL;

        // Fetch branch and department details
        $sql = "SELECT branch, dept FROM users WHERE name = '$accountExecutive' AND is_deleted = 0";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $branch = $row['branch'] ?? NULL;
        $department = $row['dept'] ?? NULL;

        $masterSql = "UPDATE encoded 
                SET
                sbu = '$sbu',
                accExec = '$accountExecutive', 
                branch = '$branch',
                dept = '$department',
                callDate = '$callDate', 
                accName = '$accountName',
                arsExpiryDate = '$arsExpiryDate',
                endUser = '$endUser', 
                segment = '$segment',
                industrySubcategory = '$industrySubcategory',
                accCat = '$accountCategory', 
                accSource = '$accountSource', 
                accountSourceCategory = '$accountSourceCategory',
                contactPerson = '$contactPerson', 
                designation = '$designation', 
                contactNumber = '$contactNumber', 
                email = '$emailAddress', 
                decisionMaker = '$decisionMaker',
                dmDesignation = '$dmDesignation',
                decisionMakerEmail = '$dmEmail',
                productType = '$productType',
                productTypeSubcategory = '$productTypeSubcategory',
                quantity = '$quantity',
                proposedPrice = '$proposedPrice',
                paymentTerms = '$paymentTerms',
                contactType = '$contractType',
                callNature = '$callNature',
                accStatus = '$accountStatus',
                reason = " . ($reason === NULL ? "NULL" : "'$reason'") . ",
                deliveryDate = '$deliveryDate',
                endOfContract = '$contractEnd',
                remarks = '$remarks', 
                actionFollow = '$followUpAction',
                existingSystem = '$existingSystem',
                endOfContractCompetitor = '$contractEndCompetitor',
                region = '$region',
                province = '$province',
                city = '$city',
                barangay = '$barangay',
                address = '$address',
                reasonSubcategory = '$reasonSubcategory',
              
                WHERE id = '$id';";
                
        $masterUpdateSuccess = mysqli_query($conn, $masterSql);
    } else {
        // If not an admin edit, simply sync the core progress status on the parent row tracker
        if (!empty($id)) {
            $syncSql = "UPDATE encoded SET accStatus = '$accountStatus' WHERE id = '$id';";
            mysqli_query($conn, $syncSql);
        }
    }

    //   reminderDate = " . ($reminderDate === NULL ? "NULL" : "'$reminderDate'") . "

    // ====================================================
    // CONDITION 2: WRITE NEW PROGRESS SNAPSHOT TO LOGS
    // ====================================================
    if ($masterUpdateSuccess) {
        if (!empty($id)) {
            $logSql = "INSERT INTO encoded_logs (
                encodedID, 
                progressDate, 
                accountStatusID, 
                reasonSubcategoryID, 
                remarks, 
                estimatedDelivery, 
                deliveryDate, 
                contractEndDate
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $logStmt = mysqli_prepare($conn, $logSql);

            if ($logStmt) {
                // Map out individual metrics cleanly. If date is empty string, parse as NULL
                $logStatusID  = (!empty($accountStatus) && $accountStatus !== 'N/A') ? (int)$accountStatus : NULL;
                $logSubcatID  = (!empty($reasonSubcategory) && $reasonSubcategory !== 'N/A') ? (int)$reasonSubcategory : NULL;
                // $logProgDate  = !empty($reminderDate) ? $reminderDate : date('Y-m-d'); // fallback to today if reminder is missing
                $logRemarks   = !empty($remarks) ? $remarks : NULL;
                $logEstDel    = !empty($estimatedDelivery) ? $estimatedDelivery : NULL;
                $logDelDate   = !empty($deliveryDate) ? $deliveryDate : NULL;
                $logConEnd    = !empty($contractEnd) ? $contractEnd : NULL;

                mysqli_stmt_bind_param(
                    $logStmt, 
                    "isiissss", 
                    $id, 
                    $logProgDate, 
                    $logStatusID, 
                    $logSubcatID, 
                    $logRemarks, 
                    $logEstDel, 
                    $logDelDate, 
                    $logConEnd
                );
                mysqli_stmt_execute($logStmt);
                mysqli_stmt_close($logStmt);
            }
        }

        // Send confirmation notice dynamically based on validation action
        $alertMessage = $isAdminEdit 
            ? "Account Master Record and Progress History Log Updated Successfully." 
            : "Progress History Activity Log Entry Added Successfully.";

        echo '<script>
                alert("' . $alertMessage . '");
                window.location.href = "/e-dsr/pages/search.php";
              </script>';
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>