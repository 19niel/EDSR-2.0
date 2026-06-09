<?php
include('db_conn.php');

if (isset($_POST['encodeAccount'])) {

    // =========================
    // MAIN FORM VALUES
    // =========================
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

    // =========================
    // CONTACT DETAILS
    // =========================
    $contactPerson = $_POST['contactPerson'][0] ?? NULL;
    $designation = $_POST['designation'][0] ?? NULL;
    $contactNumber = $_POST['contactNumber'][0] ?? NULL;
    $emailAddress = $_POST['emailAddress'][0] ?? NULL;

    // =========================
    // DECISION MAKER
    // =========================
    $decisionMaker = $_POST['decisionMaker'] ?? NULL;
    $dmDesignation = $_POST['dmDesignation'] ?? NULL;
    $dmEmail = $_POST['dmEmail'] ?? NULL;

    // =========================
    // PROJECT DETAILS
    // =========================
    $projTitle = $_POST['projTitle'] ?? NULL;
    $proposedPrice = $_POST['proposedPrice'] ?? NULL;
    $paymentTerms = $_POST['paymentTerms'] ?? NULL;
    $contractType = $_POST['contractType'] ?? NULL;

    // FIXED:
    $projAddress = $_POST['projectAddress'] ?? NULL;

    // =========================
    // PROGRESS DETAILS
    // =========================
    $callNature = $_POST['callNature'] ?? 'N/A';

    $accountStatus = $_POST['accountStatus'] ?? NULL;

    // FIXED:
    $reason = $_POST['remarks'] ?? NULL;

    $deliveryDate = $_POST['deliveryDate'] ?? NULL;

    $contractEnd = $_POST['contractEnd'] ?? NULL;

    // FIXED:
    $remarks = $_POST['remarks'] ?? NULL;

    $reasonSubcategory = $_POST['reasonSubcategory'] ?? NULL;

    $progressDate = $_POST['progressDate'] ?? NULL;

    // =========================
    // OTHER DETAILS
    // =========================
    $existingSystem = $_POST['existingSystem'] ?? NULL;

    $contractEndCompetitor = $_POST['contractEndCompetitor'] ?? NULL;

    // =========================
    // ADDRESS
    // =========================
    $region = $_POST['region'] ?? NULL;
    $province = $_POST['province'] ?? NULL;
    $city = $_POST['city'] ?? NULL;
    $barangay = $_POST['barangay'] ?? NULL;
    $address = $_POST['address'] ?? NULL;

    // =========================
    // USER DETAILS
    // =========================
    $branch = NULL;
    $department = NULL;

    $userQuery = "SELECT branch, dept 
                  FROM users 
                  WHERE name = ? 
                  AND is_deleted = 0 
                  LIMIT 1";

    $userStmt = mysqli_prepare($conn, $userQuery);

    mysqli_stmt_bind_param($userStmt, "s", $accountExecutive);

    mysqli_stmt_execute($userStmt);

    $userResult = mysqli_stmt_get_result($userStmt);

    if ($userRow = mysqli_fetch_assoc($userResult)) {
        $branch = $userRow['branch'];
        $department = $userRow['dept'];
    }

    mysqli_stmt_close($userStmt);

    // =========================
    // INSERT ENCODED
    // =========================
    $sql = "INSERT INTO encoded (
        sbu,
        accExec,
        branch,
        dept,
        callDate,

        accName,
        arsExpiryDate,
        accCat,
        existingSystem,
        endOfContractCompetitor,

        endUser,
        industry,
        industrySubcategory,
        accSource,
        accountSourceCategory,

        region,
        province,
        city,
        barangay,
        address,

        contactPerson,
        designation,
        contactNumber,
        email,
        decisionMaker,

        dmDesignation,
        decisionMakerEmail,
        projTitle,
        proposedPrice,
        paymentTerms,

        contactType,
        projAddress,
        callNature,
        accStatus,
        reason,

        deliveryDate,
        endOfContract,
        remarks,
        segment,
        reasonSubcategory,

        progressDate
    ) VALUES (
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?
    )";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssssssssssssssssssssssssssssssssss",

        $sbu,
        $accountExecutive,
        $branch,
        $department,
        $callDate,

        $accountName,
        $arsExpiryDate,
        $accountCategory,
        $existingSystem,
        $contractEndCompetitor,

        $endUser,
        $segment,
        $industrySubcategory,
        $accountSource,
        $accountSourceCategory,

        $region,
        $province,
        $city,
        $barangay,
        $address,

        $contactPerson,
        $designation,
        $contactNumber,
        $emailAddress,
        $decisionMaker,

        $dmDesignation,
        $dmEmail,
        $projTitle,
        $proposedPrice,
        $paymentTerms,

        $contractType,
        $projAddress,
        $callNature,
        $accountStatus,
        $reason,

        $deliveryDate,
        $contractEnd,
        $remarks,
        $segment,
        $reasonSubcategory,

        $progressDate
    );

    $execute = mysqli_stmt_execute($stmt);

    if (!$execute) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $encodedID = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    // =========================
    // INSERT PRODUCT DETAILS
    // =========================
    $productTypes = $_POST['productType'] ?? [];
    $subcategories = $_POST['productTypeSubcategory'] ?? [];
    $deviceConditions = $_POST['deviceCondition'] ?? [];
    $quantities = $_POST['quantity'] ?? [];

    if (!empty($productTypes)) {

        $productSql = "INSERT INTO product_details (
            encodedID,
            productTypeID,
            productSubcategoryID,
            deviceConditionID,
            quantity
        ) VALUES (?, ?, ?, ?, ?)";

        $productStmt = mysqli_prepare($conn, $productSql);

        foreach ($productTypes as $index => $productTypeID) {

            $subcategoryID = $subcategories[$index] ?? NULL;
            $conditionID = $deviceConditions[$index] ?? NULL;
            $quantity = $quantities[$index] ?? 0;

            mysqli_stmt_bind_param(
                $productStmt,
                "iiiii",
                $encodedID,
                $productTypeID,
                $subcategoryID,
                $conditionID,
                $quantity
            );

            mysqli_stmt_execute($productStmt);
        }

        mysqli_stmt_close($productStmt);
    }

    echo '
    <script>
        alert("Account and Products Added Successfully.");
        window.location.href = "/e-dsr/pages/encode.php";
    </script>
    ';

    exit();
}
?>