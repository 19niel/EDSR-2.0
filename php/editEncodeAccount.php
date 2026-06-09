<?php
include('db_conn.php');

$sql1 = "SELECT * FROM encoded WHERE is_deleted = 0";
$accountResult = mysqli_query($conn, $sql1);

if (isset($_POST['editEncode'])) {
    $id = $_POST['encodeId'] ?? NULL; // Master account reference row ID
    
    // Check if this submission is authorized to modify full master data
    $isAdminEdit = isset($_POST['is_admin_edit']) && $_POST['is_admin_edit'] === 'true';

    // Catching progress log updates (required for both actions)
    // 🎯 ADDED: progressDate & estimatedDelivery form inputs
    $progressDate = !empty($_POST['progressDate']) ? $_POST['progressDate'] : date('Y-m-d');
    $accountStatus = $_POST['accountStatus'] ?? NULL;
    $reasonSubcategory = $_POST['reasonSubcategory'] ?? NULL; 
    $remarks = $_POST['remarks'] ?? NULL; // This acts as the log remarks/update text
    $estimatedDelivery = !empty($_POST['estimatedDelivery']) ? $_POST['estimatedDelivery'] : NULL; 
    $deliveryDate = !empty($_POST['deliveryDate']) ? $_POST['deliveryDate'] : NULL;
    $contractEnd = !empty($_POST['contractEnd']) ? $_POST['contractEnd'] : NULL;

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
        
        // FIX: Extract index [0] to resolve Array to string conversion warnings
        $contactPerson = isset($_POST['contactPerson'][0]) ? $_POST['contactPerson'][0] : ($_POST['contactPerson'] ?? NULL);
        $designation   = isset($_POST['designation'][0]) ? $_POST['designation'][0] : ($_POST['designation'] ?? NULL);
        $contactNumber = isset($_POST['contactNumber'][0]) ? $_POST['contactNumber'][0] : ($_POST['contactNumber'] ?? NULL);
        $emailAddress  = isset($_POST['emailAddress'][0]) ? $_POST['emailAddress'][0] : ($_POST['emailAddress'] ?? NULL);
        
        $decisionMaker = $_POST['decisionMaker'] ?? NULL;
        $dmDesignation = $_POST['dmDesignation'] ?? NULL;
        $dmEmail = $_POST['dmEmail'] ?? NULL;
        
        // FIX: Extract index [0] for multi-row array product selections
        $productType            = isset($_POST['productType'][0]) ? $_POST['productType'][0] : ($_POST['productType'] ?? NULL);
        $productTypeSubcategory = isset($_POST['productTypeSubcategory'][0]) ? $_POST['productTypeSubcategory'][0] : ($_POST['productTypeSubcategory'] ?? NULL); 
        $quantity               = isset($_POST['quantity'][0]) ? $_POST['quantity'][0] : ($_POST['quantity'] ?? NULL);
        
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

        $region1 = $_POST['region1'] ?? NULL;
        $branch1 = $_POST['branch1'] ?? NULL;
        $address = $_POST['address'] ?? NULL;

        // Fetch branch and department details
        $sql = "SELECT branch, dept FROM users WHERE name = '$accountExecutive' AND is_deleted = 0";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $branch = $row['branch'] ?? NULL;
        $department = $row['dept'] ?? NULL;

        // 🎯 MASTER EDIT UPDATE: Added your requested progress column fields directly to the string query injection layer
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
                deliveryDate = " . ($deliveryDate === NULL ? "NULL" : "'$deliveryDate'") . ",
                endOfContract = " . ($contractEnd === NULL ? "NULL" : "'$contractEnd'") . ",
                remarks = '$remarks', 
                actionFollow = '$followUpAction',
                existingSystem = '$existingSystem',
                endOfContractCompetitor = '$contractEndCompetitor',
                region = '$region',
                province = '$province',
                city = '$city',
                barangay = '$barangay',
                region1 = '$region1',
                branch1 = '$branch1',
                address = '$address',
                reasonSubcategory = '$reasonSubcategory',
                
                -- Synchronize new core progress properties into the master context row
                progressDate = '$progressDate',
                estimatedDelivery = " . ($estimatedDelivery === NULL ? "NULL" : "'$estimatedDelivery'") . "
                WHERE id = '$id';";
                
        $masterUpdateSuccess = mysqli_query($conn, $masterSql);
    } else {
        // If not an admin edit, sync the core progress tracks back up into the parent layout row tracker layer
        if (!empty($id)) {
            $syncSql = "UPDATE encoded 
                        SET 
                            accStatus = '$accountStatus',
                            reasonSubcategory = '$reasonSubcategory',
                            remarks = '$remarks',
                            progressDate = '$progressDate',
                            estimatedDelivery = " . ($estimatedDelivery === NULL ? "NULL" : "'$estimatedDelivery'") . ",
                            deliveryDate = " . ($deliveryDate === NULL ? "NULL" : "'$deliveryDate'") . ",
                            endOfContract = " . ($contractEnd === NULL ? "NULL" : "'$contractEnd'") . "
                        WHERE id = '$id';";
            mysqli_query($conn, $syncSql);
        }
    }

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
                
                // Use user-provided progressDate value if it exists, otherwise use today's timestamp
                $logProgDate  = !empty($progressDate) ? $progressDate : date('Y-m-d'); 
                
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

        // NEW REDIRECT: Appends the current entity dynamic ID back onto the query string parameters
        echo '<script>
                alert("' . $alertMessage . '");
                window.location.href = "/e-dsr/pages/editEncode.php?id=' . urlencode($id) . '";
              </script>';
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>