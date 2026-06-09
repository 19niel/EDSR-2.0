<?php
    include ('../php/uploadFile.php');
    include ('../php/autoRedirect.php');
    include ('../php/dates.php');
    include ('../php/userList.php');
    include ('../php/categoryList.php');
    include ('../php/subcategoryList.php');
    
    // Capture the database file output cleanly to prevent raw JSON text from spilling onto the screen
    ob_start();
    include ('../php/fetchDataEditEncode.php');
    $captured_output = ob_get_clean();

    // Parse the captured JSON string data cleanly into our $row variable array
    if (!isset($row) || empty($row)) {
        $decoded_json = json_decode($captured_output, true);
        if (isset($decoded_json['success']) && $decoded_json['success'] && isset($decoded_json['data'])) {
            $row = $decoded_json['data'];
        }
    }

    // Capture the current target ID for matching history records
    $encodedMasterId = $row['id'] ?? $_GET['id'] ?? NULL;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
        <title>Edit Encode Account - E-DSR</title>
        <script src="../js/hideElement.js" defer></script>
        <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />

    </head>
    <body>
        <?php include ('header.php'); ?>

                <div class="container-fluid">
                    <div class="row">
                        <main class="col-12 col-md-10 mx-auto px-4">
                            
                            <!-- ========================================== -->
                            <!-- MODIFIED: TOP ADMINISTRATIVE ACTION BLOCK  -->
                            <!-- ========================================== -->
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
                                <h3 class="m-0 text-dark fw-bold">LID: 
                                    <span class="text-primary"><?php echo htmlspecialchars($row['LID'] ?? 'N/A'); ?></span>
                                </h3>
                                <div class="d-flex gap-2">
                                    <a href="/e-dsr/pages/search.php" class="btn btn-outline-secondary d-flex align-items-center gap-2 shadow-sm">
                                        <i class="fa-solid fa-arrow-left"></i> Back to Search
                                    </a>
                                    <!-- Changed type="submit" to type="button" to execute our password logic -->
                                    <button type="button" onclick="verifyAdminPassword()" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm">
                                        <i class="fa-solid fa-lock"></i> Admin: Save Full Account
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row py-3">
                                <form id="editEncodeForm" action="../php/editEncodeAccount.php" onsubmit="return isvalid()" method="POST" class="row g-3">
                                    <input type="hidden" name="editEncode" value="true">
                                    <input type="hidden" name="encodeId" id="encodeId" value="<?php echo htmlspecialchars($row['id'] ?? ''); ?>">
                                    
                                    <!-- NEW: Hidden flag that tells editEncodeAccount.php what to update -->
                                    <input type="hidden" name="is_admin_edit" id="isAdminEdit" value="false">

                            <script>
                                var id = new URLSearchParams(window.location.search).get('id');
                                var LID = new URLSearchParams(window.location.search).get('LID');
                                console.log("Edit Target Details:", id, LID);
                            </script>
                            
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Pipeline Information</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="sbu" class="form-label">SBU/Segment<span class="req">*</span></label>
                                        <select id="sbu" name="sbu" class="form-select" disabled required>
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php foreach ($sbuResult as $sbuRow) { 
                                                $selected = (isset($row['sbu']) && $row['sbu'] === $sbuRow['category_name']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $sbuRow['category_name']; ?>" <?php echo $selected; ?>><?php echo $sbuRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountExecutive" class="form-label">Account Executive (for review) <span class="req">*</span></label>
                                        <?php
                                        $savedExecutive = $row['accExec'] ?? $row['accountExecutive'] ?? ''; 

                                        if (($category ?? '') === 'User') { ?>
                                            <input type="text" class="form-control" id="accountExecutive" name="accountExecutive" 
                                                value="<?php echo htmlspecialchars(!empty($savedExecutive) ? $savedExecutive : ($name ?? '')); ?>" readonly required />
                                        <?php } else if (($category ?? '') === 'Manager') { ?>
                                            <select id="accountExecutive" name="accountExecutive" class="form-select" disabled required>
                                                <option value="" disabled>Choose Executive...</option>
                                                <?php 
                                                if (!empty($userArrayManager)) {
                                                    foreach ($userArrayManager as $managerUser) {
                                                        $selected = ($managerUser['name'] === $savedExecutive) ? 'selected' : '';
                                                        echo '<option value="' . htmlspecialchars($managerUser['name']) . '" ' . $selected . '>' . htmlspecialchars($managerUser['name']) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        <?php } else { ?>
                                            <select id="accountExecutive" name="accountExecutive" class="form-select" disabled required>
                                                <option value="" disabled>Choose Executive...</option>
                                                <?php 
                                                if (!empty($userArray)) {
                                                    foreach ($userArray as $adminUser) {
                                                        $selected = ($adminUser['name'] === $savedExecutive) ? 'selected' : '';
                                                        echo '<option value="' . htmlspecialchars($adminUser['name']) . '" ' . $selected . '>' . htmlspecialchars($adminUser['name']) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        <?php } ?>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="callDate" class="form-label">Date of Activity <span class="req">*</span></label>
                                        <input type="date" class="form-control" disabled required id="callDate" name="callDate" min="<?php echo $min; ?>" max="<?php echo $max; ?>" value="<?php echo htmlspecialchars($row['callDate'] ?? '');  ?>" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Client Information</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountName" class="form-label">Account Name <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="accountName" name="accountName" disabled required onchange="searchAccounts(this.value)" value="<?php echo htmlspecialchars($row['accName'] ?? ''); ?>" required/>
                                        <ul id="accountList" class="account-list"></ul>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3 ars-container">
                                        <label for="arsExpiryDate" class="form-label">ARS Expiry Date</label>
                                        <input type="date" class="form-control" id="arsExpiryDate" name="arsExpiryDate" disabled required min="<?php echo $min_expiry; ?>" value="<?php echo htmlspecialchars(($row['arsExpiryDate'] ?? '') !== '0000-00-00' ? $row['arsExpiryDate'] : ''); ?>"/>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountCategory" class="form-label">Account Category <span class="req">*</span></label>
                                        <select id="accountCategory" name="accountCategory" class="form-select" disabled required>
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php foreach ($accountCategoryResult as $accountCategoryRow) { 
                                                $selected = (isset($row['accCat']) && $row['accCat'] === $accountCategoryRow['category_name']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $accountCategoryRow['category_name']; ?>" <?php echo $selected; ?>><?php echo $accountCategoryRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3" id="existingSystemContainer">
                                        <label for="existingSystem" class="form-label">Existing System</label>
                                        <select id="existingSystem" name="existingSystem" class="form-select" disabled required>
                                            <option value="N/A" <?php echo empty($row['existingSystem']) ? 'selected' : ''; ?>>Choose...</option>
                                            <?php foreach ($existingSystemResult as $existingSystemRow) { 
                                                $selected = (isset($row['existingSystem']) && $row['existingSystem'] === $existingSystemRow['category_name']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $existingSystemRow['category_name']; ?>" <?php echo $selected; ?>><?php echo $existingSystemRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3" id="contractEndCompetitorContainer">
                                        <label for="contractEndCompetitor" class="form-label">End of Contract (Competitor)</label>
                                        <input type="date" class="form-control" id="contractEndCompetitor" name="contractEndCompetitor" disabled required min="<?php echo $min_expiry; ?>" value="<?php echo htmlspecialchars(($row['endOfContractCompetitor'] ?? '') !== '0000-00-00' ? $row['endOfContractCompetitor'] : ''); ?>" />
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="endUserType" class="form-label">Type of End-User <span class="req">*</span></label>
                                        <select id="endUserType" name="endUserType" class="form-select" disabled required>
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php foreach ($endUserTypeResult as $endUserTypeRow) { 
                                                $selected = (isset($row['endUser']) && $row['endUser'] === $endUserTypeRow['category_name']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $endUserTypeRow['category_name']; ?>" <?php echo $selected; ?>><?php echo $endUserTypeRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="segment" class="form-label">Industry <span class="req">*</span></label>
                                        <select id="segment" name="segment" class="form-select" disabled required>
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php foreach ($segmentResult as $segmentRow) { 
                                                $selected = (isset($row['industry']) && $row['industry'] == $segmentRow['id']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $segmentRow['id']; ?>" <?php echo $selected; ?>><?php echo $segmentRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="industrySubcategory" class="form-label">Industry Subcategory</label>
                                        <select id="industrySubcategory" name="industrySubcategory" class="form-select" disabled required data-saved-value="<?php echo htmlspecialchars($row['industrySubcategory'] ?? ''); ?>">
                                            <option value="N/A">Choose...</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountSource" class="form-label">Lead Source <span class="req">*</span></label>
                                        <select id="accountSource" name="accountSource" class="form-select" disabled required>
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php 
                                            if (isset($accountSourceResult)) {
                                                mysqli_data_seek($accountSourceResult, 0);
                                                while ($accountSourceRow = mysqli_fetch_assoc($accountSourceResult)) { 
                                                    $selected = (isset($row['accSource']) && $row['accSource'] == $accountSourceRow['id']) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $accountSourceRow['id']; ?>" <?php echo $selected; ?>><?php echo $accountSourceRow['category_name']; ?></option>
                                                <?php } 
                                            } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountSourceCategory" class="form-label">Lead Subcategory</label>
                                        <select id="accountSourceCategory" name="accountSourceCategory" class="form-select" disabled required data-saved-value="<?php echo htmlspecialchars($row['accSourceSubcategory'] ?? ''); ?>">
                                            <option value="N/A">Choose...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            
                         
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Client Location Details</h5>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="branch1" class="form-label">Branch <span class="req">*</span></label>
                                        <select name="branch1" class="form-control form-select" id="branch1" disabled required>
                                            <option value="" disabled>-- Select Branch --</option>
                                            <?php 
                                            $branches = ['MM', 'ANG', 'CAB', 'LAU', 'BAT', 'NAG', 'SUB', 'BAC', 'CEB', 'DUM', 'ILO', 'TAC', 'CDO', 'DAV', 'GEN', 'ZAM'];
                                            foreach($branches as $b) {
                                                $selected = (isset($row['branch1']) && $row['branch1'] === $b) ? 'selected' : '';
                                                echo "<option value=\"$b\" $selected>$b</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="region1" class="form-label">Region <span class="req">*</span></label>
                                        <select name="region1" class="form-control form-select" id="region1" disabled required>
                                            <option value="" disabled>-- Select Region --</option>
                                            <?php 
                                            $regions = ['MM', 'LUZON', 'VISAYAS', 'MINDANAO'];
                                            foreach($regions as $r) {
                                                $selected = (isset($row['region1']) && $row['region1'] === $r) ? 'selected' : '';
                                                echo "<option value=\"$r\" $selected>$r</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="address" class="form-label">Address <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address" 
                                               value="<?php echo htmlspecialchars($row['address'] ?? ''); ?>" disabled  required/>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Contact Information</h5>
                                
                                <div id="contactEntries" class="mb-3">
                                    <div class="row contact-entry g-3 mb-3 align-items-end">
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Person <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="contactPerson[]" disabled 
                                                   value="<?php echo htmlspecialchars($row['contactPerson'] ?? ''); ?>" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Person Designation <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="designation[]" disabled 
                                                   value="<?php echo htmlspecialchars($row['designation'] ?? ''); ?>" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Details <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="contactNumber[]" disabled 
                                                   value="<?php echo htmlspecialchars($row['contactNumber'] ?? ''); ?>" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Email Address <span class="req">*</span></label>
                                            <input type="email" class="form-control" name="emailAddress[]" disabled 
                                                   value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 border-top pt-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="decisionMaker" class="form-label">Decision Maker</label>
                                        <input type="text" class="form-control" id="decisionMaker" name="decisionMaker" disabled value="<?php echo htmlspecialchars($row['decisionMaker'] ?? ''); ?>" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmDesignation" class="form-label">Decision Maker Designation</label>
                                        <input type="text" class="form-control" id="dmDesignation" name="dmDesignation" disabled value="<?php echo htmlspecialchars($row['dmDesignation'] ?? ''); ?>" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmEmail" class="form-label">Decision Maker Email</label>
                                        <input type="email" class="form-control" id="dmEmail" name="dmEmail" disabled value="<?php echo htmlspecialchars($row['decisionMakerEmail'] ?? ''); ?>" />
                                    </div>
                                </div>
                            </div>


                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Project Details</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="projTitle" class="form-label">Project Title <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="projTitle" name="projTitle" disabled value="<?php echo htmlspecialchars($row['projTitle'] ?? ''); ?>" required/>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="proposedPrice" class="form-label">Proposed Price <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="proposedPrice" name="proposedPrice" disabled value="<?php echo htmlspecialchars($row['proposedPrice'] ?? ''); ?>" required/>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="paymentTerms" class="form-label">Terms of Payment <span class="req">*</span></label>
                                        <select id="paymentTerms" name="paymentTerms" class="form-select" disabled >
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php foreach ($paymentTermsResult as $paymentTermsRow) { 
                                                $selected = (isset($row['paymentTerms']) && $row['paymentTerms'] === $paymentTermsRow['category_name']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $paymentTermsRow['category_name']; ?>" <?php echo $selected; ?>><?php echo $paymentTermsRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="contractType" class="form-label">Contract Type <span class="req">*</span></label>
                                        <select id="contractType" name="contractType" class="form-select" disabled>
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php foreach ($contractTypeResult as $contractTypeRow) { 
                                                $selected = (isset($row['contactType']) && $row['contactType'] === $contractTypeRow['category_name']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $contractTypeRow['category_name']; ?>" <?php echo $selected; ?>><?php echo $contractTypeRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                  
                                    <div class="col-lg-8 col-xl-6">
                                        <label for="projectAddress" class="form-label">Project Address <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="projectAddress" name="projectAddress" disabled value="<?php echo htmlspecialchars($row['projAddress'] ?? ''); ?>" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Product and Pricing Information</h5>
                                <div id="productEntries">
                                    <?php if (!empty($row['products']) && is_array($row['products'])): ?>
                                        <?php foreach ($row['products'] as $index => $item): ?>
                                            <div class="row product-entry g-3 mb-3 align-items-end">
                                                <div class="col-md-6 col-lg-3">
                                                    <label class="form-label">Product Type <span class="req">*</span></label>
                                                    <select name="productType[]" class="form-select productType" disabled>
                                                        <option value="N/A" disabled>Choose...</option>
                                                        <?php 
                                                        mysqli_data_seek($productTypeResult, 0);
                                                        while ($productTypeRow = mysqli_fetch_assoc($productTypeResult)) { 
                                                            $selected = ($item['productTypeID'] == $productTypeRow['id']) ? 'selected' : '';
                                                        ?>
                                                            <option value="<?php echo $productTypeRow['id']; ?>" <?php echo $selected; ?>><?php echo $productTypeRow['category_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-lg-3">
                                                    <label class="form-label">Product Type Subcategory</label>
                                                    <select name="productTypeSubcategory[]" class="form-select productTypeSubcategory" disabled data-saved-value="<?php echo htmlspecialchars($item['productSubcategoryID'] ?? ''); ?>">
                                                        <option value="N/A">Choose...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-lg-3">
                                                    <label class="form-label">Device Condition <span class="req">*</span></label>
                                                    <select name="deviceCondition[]" class="form-select deviceCondition" disabled>
                                                        <option value="N/A" disabled>Choose...</option>
                                                        <?php 
                                                        mysqli_data_seek($deviceConditionResult, 0);
                                                        while ($deviceConditionRow = mysqli_fetch_assoc($deviceConditionResult)) { 
                                                            $selected = ($item['deviceConditionID'] == $deviceConditionRow['id']) ? 'selected' : '';
                                                        ?>
                                                            <option value="<?php echo $deviceConditionRow['id']; ?>" <?php echo $selected; ?>><?php echo $deviceConditionRow['category_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-lg-2">
                                                    <label class="form-label">Quantity <span class="req">*</span></label>
                                                    <input type="number" class="form-control" name="quantity[]" disabled value="<?php echo htmlspecialchars($item['quantity'] ?? '0'); ?>" required />
                                                </div>
                                                <div class="col-md-2 col-lg-1">
                                                    <button type="button" class="btn btn-outline-danger remove-entry w-100">Remove</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="row product-entry g-3 mb-3 align-items-end">
                                            <div class="col-md-6 col-lg-3">
                                                <label class="form-label">Product Type <span class="req">*</span></label>
                                                <select name="productType[]" class="form-select productType" disabled>
                                                    <option value="N/A" selected disabled>Choose...</option>
                                                    <?php 
                                                    mysqli_data_seek($productTypeResult, 0);
                                                    while ($productTypeRow = mysqli_fetch_assoc($productTypeResult)) { ?>
                                                        <option value="<?php echo $productTypeRow['id']; ?>"><?php echo $productTypeRow['category_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <label class="form-label">Product Type Subcategory</label>
                                                <select name="productTypeSubcategory[]" class="form-select productTypeSubcategory" disabled>
                                                    <option value="N/A" selected disabled>Choose...</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <label class="form-label">Device Condition <span class="req">*</span></label>
                                                <select name="deviceCondition[]" class="form-select deviceCondition" disabled>
                                                    <option value="N/A" selected disabled>Choose...</option>
                                                    <?php 
                                                    mysqli_data_seek($deviceConditionResult, 0);
                                                    while ($deviceConditionRow = mysqli_fetch_assoc($deviceConditionResult)) { ?>
                                                        <option value="<?php echo $deviceConditionRow['id']; ?>"><?php echo $deviceConditionRow['category_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-lg-2">
                                                <label class="form-label">Quantity <span class="req">*</span></label>
                                                <input type="number" class="form-control" name="quantity[]" disabled />
                                            </div>
                                            <div class="col-md-2 col-lg-1">
                                                <button type="button" class="btn btn-outline-danger remove-entry w-100">Remove</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addProductEntry">Add Another Product</button>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Progress Updates</h5>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="progressDate" class="form-label">Date of Progress</label>
                                        <input type="date" class="form-control" id="progressDate" name="progressDate" min="<?php echo $min_expiry; ?>" value="<?php echo htmlspecialchars($row['progressDate'] ?? ''); ?>"/>
                                    </div>
                                  
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountStatus" class="form-label">Account Status <span class="req">*</span></label>
                                        <select id="accountStatus" name="accountStatus" class="form-select" required>
                                            <option value="N/A" disabled>Choose...</option>
                                            <?php foreach ($accountstatusResult as $accountstatusRow) { 
                                                $selected = (isset($row['accStatus']) && $row['accStatus'] == $accountstatusRow['id']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $accountstatusRow['id']; ?>" <?php echo $selected; ?>><?php echo $accountstatusRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="reasonSubcategory" class="form-label">Reason Subcategory</label>
                                        <select id="reasonSubcategory" name="reasonSubcategory" class="form-select" data-saved-value="<?php echo htmlspecialchars($row['reasonSubcategory'] ?? ''); ?>">
                                            <option value="N/A">Choose...</option>
                                        </select>
                                    </div>
                                   
                                    <div class="col-md-12 col-xl-6">
                                        <label for="remarks" class="form-label">Remarks <span class="req">*</span></label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="2" required><?php echo htmlspecialchars($row['remarks'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="estimatedDelivery" class="form-label">Estimated Delivery</label>
                                        <select id="estimatedDelivery" name="estimatedDelivery" class="form-select">
                                            <option value="" disabled>Choose Month...</option>
                                            <?php 
                                            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                                            foreach ($months as $m) {
                                                $selected = (isset($row['estimatedDelivery']) && $row['estimatedDelivery'] === $m) ? 'selected' : '';
                                                echo '<option value="'.$m.'" '.$selected.'>'.$m.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="deliveryDateContainer" style="display: none;">
                                        <label for="deliveryDate" class="form-label">Delivery Date</label>
                                        <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" min="<?php echo $min_expiry; ?>" value="<?php echo htmlspecialchars($row['deliveryDate'] ?? ''); ?>" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="contractEndContainer" style="display: none;">
                                        <label for="contractEnd" class="form-label">Contract End</label>
                                        <input type="date" class="form-control" id="contractEnd" name="contractEnd" min="<?php echo $min_expiry; ?>" value="<?php echo htmlspecialchars($row['contractEnd'] ?? ''); ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 border-top pt-3 mt-4 d-flex justify-content-end gap-2 mb-5">
                                <a href="/e-dsr/pages/search.php" class="btn btn-secondary px-4 shadow-sm">Cancel</a>
                                <button type="button" onclick="submitLogOnly()" class="btn btn-success px-4 shadow-sm">Save Log Entry Only</button>
                            </div>


                            <!-- ========================================================= -->
                            <!-- CONNECTED SEGMENT: ENCODED PROGRESS HISTORY LOGS TAB      -->
                            <!-- ========================================================= -->
                            <div class="card p-4 shadow-sm mb-4 border-top border-primary border-3 bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa-solid fa-clock-history fs-5"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-dark fw-bold m-0" style="letter-spacing: -0.02em;">Account Progress History</h5>
                                            <p class="text-muted small m-0 mt-0.5">Chronological record of status changes and administrative logs</p>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.72rem; font-weight: 600;">
                                        <i class="fa-solid fa-list-ol me-1"></i> Audit Trail
                                    </span>
                                </div>

                                <div class="progress-timeline-wrapper px-2" style="max-height: 480px; overflow-y: auto; position: relative;">
                                    <?php 
                                    if (!empty($encodedMasterId)) {
                                        include('../php/db_conn.php');
                                        
                                        $historySql = "SELECT el.*, ac.category_name as status_name 
                                                    FROM encoded_logs el 
                                                    LEFT JOIN categories ac ON el.accountStatusID = ac.id 
                                                    WHERE el.encodedID = '$encodedMasterId' 
                                                    ORDER BY el.created_at DESC";
                                        
                                        $historyResult = mysqli_query($conn, $historySql);
                                        
                                        if ($historyResult && mysqli_num_rows($historyResult) > 0) {
                                            while ($log = mysqli_fetch_assoc($historyResult)) {
                                                // Pre-format dates and text safely
                                                $logDate = !empty($log['progressDate']) ? date('M d, Y', strtotime($log['progressDate'])) : 'N/A';
                                                $createdAt = !empty($log['created_at']) ? date('m/d/Y h:i A', strtotime($log['created_at'])) : 'N/A';
                                                
                                                // Determine Status Display String Mappings
                                                if (!empty($log['status_name'])) {
                                                    $statusText = htmlspecialchars($log['status_name']);
                                                } elseif (!empty($log['accountStatusID'])) {
                                                    $statusText = "Status ID: " . htmlspecialchars($log['accountStatusID']);
                                                } else {
                                                    $statusText = "Unknown Status";
                                                }

                                                $remarks = !empty(trim($log['remarks'])) ? htmlspecialchars($log['remarks']) : '<em>No explicit remarks recorded.</em>';
                                                $subcategory = !empty($log['reasonSubcategory']) ? trim($log['reasonSubcategory']) : '';
                                                
                                                // Dynamic styling classes matching status types
                                                $statusBadgeClass = 'bg-secondary text-white';
                                                $timelineNodeColor = '#6c757d';
                                                
                                                if (strpos(strtolower($statusText), 'won') !== false || $log['accountStatusID'] == '347') {
                                                    $statusBadgeClass = 'bg-success text-white';
                                                    $timelineNodeColor = '#198754';
                                                } elseif (strpos(strtolower($statusText), 'lost') !== false || $log['accountStatusID'] == '348' || strpos(strtolower($statusText), 'drop') !== false || $log['accountStatusID'] == '349') {
                                                    $statusBadgeClass = 'bg-danger text-white';
                                                    $timelineNodeColor = '#dc3545';
                                                } elseif (strpos(strtolower($statusText), 'nego') !== false || $log['accountStatusID'] == '346') {
                                                    $statusBadgeClass = 'bg-info text-dark';
                                                    $timelineNodeColor = '#0dcaf0';
                                                } elseif (strpos(strtolower($statusText), 'quali') !== false || $log['accountStatusID'] == '345') {
                                                    $statusBadgeClass = 'bg-primary text-white';
                                                    $timelineNodeColor = '#0d6efd';
                                                }
                                    ?>
                                                <div class="timeline-item d-flex mb-4" style="position: relative;">
                                                    <div class="timeline-line" style="position: absolute; left: 15px; top: 30px; bottom: -30px; width: 2px; background-color: #e9ecef; z-index: 1;"></div>
                                                    
                                                    <div class="timeline-node rounded-circle shadow-sm d-flex align-items-center justify-content-center text-white" 
                                                        style="width: 32px; height: 32px; background-color: <?php echo $timelineNodeColor; ?>; z-index: 2; flex-shrink: 0;">
                                                        <i class="fa-solid fa-circle-dot" style="font-size: 0.65rem;"></i>
                                                    </div>
                                                    
                                                    <div class="timeline-content-card border rounded-3 p-3 ms-3 flex-grow-1 bg-light shadow-xs" style="border-color: #e9ecef !important;">
                                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge rounded-pill fw-bold tracking-wide text-uppercase <?php echo $statusBadgeClass; ?>" style="font-size: 0.68rem; padding: 0.35em 0.8em;">
                                                                    <?php echo $statusText; ?>
                                                                </span>
                                                                <span class="text-secondary small fw-medium">
                                                                    <i class="fa-regular fa-calendar me-1"></i><?php echo $logDate; ?>
                                                                </span>
                                                            </div>
                                                            <small class="text-muted fw-normal" style="font-size: 0.68rem;">
                                                                <i class="fa-solid fa-fingerprint me-1"></i>Logged: <?php echo $createdAt; ?>
                                                            </small>
                                                        </div>

                                                        <div class="text-dark bg-white border rounded p-2 mb-2 small shadow-sm" style="font-size: 0.75rem; border-color: #f1f3f5 !important; border-left: 3px solid #dee2e6 !important;">
                                                            <?php echo $remarks; ?>
                                                        </div>

                                                        <div class="row g-2 pt-1">
                                                            <?php if (!empty($subcategory) && strtolower($subcategory) !== 'n/a'): ?>
                                                            <div class="col-6 col-md-4 col-xl-3">
                                                                <div class="text-muted text-uppercase" style="font-size: 0.60rem; font-weight: 700; letter-spacing: 0.03em;">Reason Subcategory</div>
                                                                <div class="text-dark fw-semibold" style="font-size: 0.72rem;"><?php echo htmlspecialchars($subcategory); ?></div>
                                                            </div>
                                                            <?php endif; ?>

                                                            <?php if (!empty(trim($log['estimatedDelivery'])) && $log['estimatedDelivery'] !== '0000-00-00' && strtolower(trim($log['estimatedDelivery'])) !== 'null'): ?>
                                                            <div class="col-6 col-md-4 col-xl-3">
                                                                <div class="text-muted text-uppercase" style="font-size: 0.60rem; font-weight: 700; letter-spacing: 0.03em;">Est. Delivery</div>
                                                                <div class="text-success fw-semibold" style="font-size: 0.72rem;">
                                                                    <i class="fa-solid fa-truck-loading me-1"></i><?php echo htmlspecialchars($log['estimatedDelivery']); ?>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>

                                                            <?php if (!empty($log['deliveryDate']) && $log['deliveryDate'] !== '0000-00-00' && strtolower(trim($log['deliveryDate'])) !== 'null'): ?>
                                                            <div class="col-6 col-md-4 col-xl-3">
                                                                <div class="text-muted text-uppercase" style="font-size: 0.60rem; font-weight: 700; letter-spacing: 0.03em;">Actual Delivery</div>
                                                                <div class="text-primary fw-semibold" style="font-size: 0.72rem;">
                                                                    <i class="fa-solid fa-box-open me-1"></i><?php echo date('m/d/Y', strtotime($log['deliveryDate'])); ?>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>

                                                            <?php if (!empty($log['contractEndDate']) && $log['contractEndDate'] !== '0000-00-00' && strtolower(trim($log['contractEndDate'])) !== 'null'): ?>
                                                            <div class="col-6 col-md-4 col-xl-3">
                                                                <div class="text-muted text-uppercase" style="font-size: 0.60rem; font-weight: 700; letter-spacing: 0.03em;">Contract End</div>
                                                                <div class="text-danger fw-semibold" style="font-size: 0.72rem;">
                                                                    <i class="fa-solid fa-file-contract me-1"></i><?php echo date('m/d/Y', strtotime($log['contractEndDate'])); ?>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                    <?php 
                                            }
                                            echo '<style>.timeline-item:last-child .timeline-line { display: none !important; }</style>';
                                        } else {
                                            echo '<div class="text-center py-4 text-muted small"><i class="fa-solid fa-folder-open fs-4 d-block mb-2 text-black-50"></i>No historical progress log entries found.</div>';
                                        }
                                        mysqli_close($conn);
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- ========================================== -->
                            <!-- NEW: BOTTOM USER PROGRESS ENTRY LOG ACTION -->
                            <!-- ========================================== -->

                        </form> <!-- This is your existing form closing tag -->
                    </div>
                </main>
            </div>
        </div>

        <script>

         var userCategory = '<?php echo $category; ?>';
    var userName = '<?php echo $name; ?>';
    var userArrayManager = <?php echo $userArrayManagerJson; ?>;
    var userArrayAdmin = <?php echo $userArrayAdminJson; ?>;
    var category = "<?php echo $category; ?>";

    // =========================================================
    // ACTION ROUTING FOR VISUAL LOCKING & PERMISSIONS
    // =========================================================
    
    function verifyAdminPassword() {
        // If already unlocked, this button acts as the final save submission
        if (document.getElementById('isAdminEdit').value === "true") {
            if (typeof isvalid === "function" && !isvalid()) {
                return false;
            }
            document.getElementById('editEncodeForm').submit();
            return;
        }

        // Prompt for admin password check to unlock fields
        var verifyCheck = prompt("Enter administrative authorization password to unlock master fields:");
        
        if (verifyCheck === "admin123") { // System verification password string
            // 1. Set the hidden tracking variable flag to true
            document.getElementById('isAdminEdit').value = "true";
            
            // 2. TARGET ALL FORM CONTROLS: This guarantees that loops, product arrays, 
            // and hardcoded disabled text arrays are cleanly forced open.
            var allFormControls = document.querySelectorAll('#editEncodeForm input, #editEncodeForm select, #editEncodeForm textarea');
            allFormControls.forEach(function(element) {
                element.disabled = false;
                element.removeAttribute('disabled');
            });
            
            // 3. Transform the top button visually to indicate it is now ready to save
            var adminBtn = document.querySelector("button[onclick='verifyAdminPassword()']");
            adminBtn.className = "btn btn-primary d-flex align-items-center gap-2 shadow-sm";
            adminBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Master Updates';
            
            alert("Authorization Successful: All master fields, product lists, and contact arrays are now unlocked for editing. Click this button again to commit updates.");
        } else if (verifyCheck !== null) {
            alert("Unauthorized: Incorrect administrative verification password.");
        }
    }

    function submitLogOnly() {
        document.getElementById('isAdminEdit').value = "false";
        
        // Strip validation requirements off the locked upper elements so browser doesn't block log insertion
        var requiredElements = document.querySelectorAll('#editEncodeForm [required]');
        requiredElements.forEach(function(element) {
            if (!element.closest('.card').innerHTML.includes('Progress Updates')) {
                element.removeAttribute('required');
                // Ensure disabled fields don't cause HTML5 validation blocks
                element.removeAttribute('disabled'); 
            }
        });

        document.getElementById('editEncodeForm').submit();
    }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="../js/toggleDateFields.js"></script>
        <script src="../js/handleIndustryChange.js"></script>
        <script src="../js/handleAccountSourceChange.js"></script>
        <script src="../js/handleRegionChange.js"></script>
        <script src="../js/addProducts.js"></script>
        <script src="../js/handleAccountCategoryChange.js"></script>
        <script src="../js/handleProductTypeChange.js"></script>
        <script src="../js/handleAccountStatusChange.js"></script>
        <script src="../js/encodeAutofill.js"></script>
        <script src="../js/encode/prefillForm.js"></script>
        <script src="../js/hideElement.js"></script>
        <script src="../js/ph-address-selector.js"></script>
        <script src="../js/handleBranchToRegion.js"></script>
    </body>
</html>