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
        <style>
            .req { color: red; margin-left: 3px; }
            .account-list { list-style: none; padding: 0; margin: 0; position: absolute; background: white; border: 1px solid #ccc; width: 90%; z-index: 1000; display: none; }
            .account-list li { padding: 8px; cursor: pointer; }
            .account-list li:hover { background-color: #f0f0f0; }
        </style>
    </head>
    <body>
        <?php include ('header.php'); ?>

        <div class="container-fluid">
            <div class="row">
                <main class="col-12 col-md-10 mx-auto px-4">
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
                        <h3 class="m-0 text-dark fw-bold">LID: 
                            <span class="text-primary"><?php echo htmlspecialchars($row['LID'] ?? 'N/A'); ?></span>
                        </h3>
                    </div>
                    
                    <div class="row py-3">
                        <form action="../php/editEncodeAccount.php" onsubmit="return isvalid()" method="POST" class="row g-3">
                            <input type="hidden" name="editEncode" value="true">
                            <input type="hidden" name="encodeId" id="encodeId" value="<?php echo htmlspecialchars($row['id'] ?? ''); ?>">

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
                                        <select id="sbu" name="sbu" class="form-select" required>
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
                                            <select id="accountExecutive" name="accountExecutive" class="form-select" required>
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
                                            <select id="accountExecutive" name="accountExecutive" class="form-select" required>
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
                                        <input type="date" class="form-control" id="callDate" name="callDate" min="<?php echo $min; ?>" max="<?php echo $max; ?>" value="<?php echo htmlspecialchars($row['callDate'] ?? ''); ?>" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Client Information</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountName" class="form-label">Account Name <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="accountName" name="accountName" onchange="searchAccounts(this.value)" value="<?php echo htmlspecialchars($row['accName'] ?? ''); ?>" required/>
                                        <ul id="accountList" class="account-list"></ul>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3 ars-container">
                                        <label for="arsExpiryDate" class="form-label">ARS Expiry Date</label>
                                        <input type="date" class="form-control" id="arsExpiryDate" name="arsExpiryDate" min="<?php echo $min_expiry; ?>" value="<?php echo htmlspecialchars(($row['arsExpiryDate'] ?? '') !== '0000-00-00' ? $row['arsExpiryDate'] : ''); ?>"/>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountCategory" class="form-label">Account Category <span class="req">*</span></label>
                                        <select id="accountCategory" name="accountCategory" class="form-select" required>
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
                                        <select id="existingSystem" name="existingSystem" class="form-select">
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
                                        <input type="date" class="form-control" id="contractEndCompetitor" name="contractEndCompetitor" min="<?php echo $min_expiry; ?>" value="<?php echo htmlspecialchars(($row['endOfContractCompetitor'] ?? '') !== '0000-00-00' ? $row['endOfContractCompetitor'] : ''); ?>" />
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="endUserType" class="form-label">Type of End-User <span class="req">*</span></label>
                                        <select id="endUserType" name="endUserType" class="form-select" required>
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
                                        <select id="segment" name="segment" class="form-select" required>
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
                                        <select id="industrySubcategory" name="industrySubcategory" class="form-select" data-saved-value="<?php echo htmlspecialchars($row['industrySubcategory'] ?? ''); ?>">
                                            <option value="N/A">Choose...</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountSource" class="form-label">Lead Source <span class="req">*</span></label>
                                        <select id="accountSource" name="accountSource" class="form-select" required>
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
                                        <select id="accountSourceCategory" name="accountSourceCategory" class="form-select" data-saved-value="<?php echo htmlspecialchars($row['accSourceSubcategory'] ?? ''); ?>">
                                            <option value="N/A">Choose...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Client Location Details</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="region" class="form-label">Region <span class="req">*</span></label>
                                        <select name="region" class="form-control form-select" id="region" required data-saved="<?php echo htmlspecialchars($row['region'] ?? ''); ?>"></select>
                                        <input type="hidden" name="region_text" id="region-text" value="<?php echo htmlspecialchars($row['region_text'] ?? ''); ?>" />
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3" id="provinceContainer">
                                        <label for="province" class="form-label">Province <span class="req">*</span></label>
                                        <select name="province" class="form-control form-select" id="province" required data-saved="<?php echo htmlspecialchars($row['province'] ?? ''); ?>"></select>
                                        <input type="hidden" name="province_text" id="province-text" value="<?php echo htmlspecialchars($row['province_text'] ?? ''); ?>" />
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="city" class="form-label">City / Municipality <span class="req">*</span></label>
                                        <select name="city" class="form-control form-select" id="city" required data-saved="<?php echo htmlspecialchars($row['city'] ?? ''); ?>"></select>
                                        <input type="hidden" name="city_text" id="city-text" value="<?php echo htmlspecialchars($row['city_text'] ?? ''); ?>" />
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3" id="barangayContainer">
                                        <label for="barangay" class="form-label">Barangay</label>
                                        <select name="barangay" class="form-control form-select" id="barangay" data-saved="<?php echo htmlspecialchars($row['barangay'] ?? ''); ?>"></select>
                                        <input type="hidden" name="barangay_text" id="barangay-text" value="<?php echo htmlspecialchars($row['barangay_text'] ?? ''); ?>" />
                                    </div>

                                    <div class="col-lg-8 col-xl-6">
                                        <label for="address" class="form-label">Address <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($row['address'] ?? ''); ?>" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Contact Information</h5>
                                
                                <div id="contactEntries" class="mb-3">
                                    <div class="row contact-entry g-3 mb-3 align-items-end">
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Person <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="contactPerson[]" value="<?php echo htmlspecialchars($row['contactPerson'] ?? ''); ?>" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Person Designation <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="designation[]" value="<?php echo htmlspecialchars($row['designation'] ?? ''); ?>" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Details <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="contactNumber[]" value="<?php echo htmlspecialchars($row['contactNumber'] ?? ''); ?>" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Email Address <span class="req">*</span></label>
                                            <input type="email" class="form-control" name="emailAddress[]" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 border-top pt-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="decisionMaker" class="form-label">Decision Maker</label>
                                        <input type="text" class="form-control" id="decisionMaker" name="decisionMaker" value="<?php echo htmlspecialchars($row['decisionMaker'] ?? ''); ?>" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmDesignation" class="form-label">Decision Maker Designation</label>
                                        <input type="text" class="form-control" id="dmDesignation" name="dmDesignation" value="<?php echo htmlspecialchars($row['dmDesignation'] ?? ''); ?>" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmEmail" class="form-label">Decision Maker Email</label>
                                        <input type="email" class="form-control" id="dmEmail" name="dmEmail" value="<?php echo htmlspecialchars($row['decisionMakerEmail'] ?? $row['dmEmail'] ?? ''); ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Project Details</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="projTitle" class="form-label">Project Title <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="projTitle" name="projTitle" value="<?php echo htmlspecialchars($row['projTitle'] ?? ''); ?>" required/>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="proposedPrice" class="form-label">Proposed Price <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="proposedPrice" name="proposedPrice" value="<?php echo htmlspecialchars($row['proposedPrice'] ?? ''); ?>" required/>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="paymentTerms" class="form-label">Terms of Payment <span class="req">*</span></label>
                                        <select id="paymentTerms" name="paymentTerms" class="form-select" required>
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
                                        <select id="contractType" name="contractType" class="form-select" required>
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
                                        <input type="text" class="form-control" id="projectAddress" name="projectAddress" value="<?php echo htmlspecialchars($row['projAddress'] ?? ''); ?>" required/>
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
                                                    <select name="productType[]" class="form-select productType" required>
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
                                                    <select name="productTypeSubcategory[]" class="form-select productTypeSubcategory" data-saved-value="<?php echo htmlspecialchars($item['productSubcategoryID'] ?? ''); ?>">
                                                        <option value="N/A">Choose...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-lg-3">
                                                    <label class="form-label">Device Condition <span class="req">*</span></label>
                                                    <select name="deviceCondition[]" class="form-select deviceCondition" required>
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
                                                    <input type="number" class="form-control" name="quantity[]" value="<?php echo htmlspecialchars($item['quantity'] ?? '0'); ?>" required />
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
                                                <select name="productType[]" class="form-select productType" required>
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
                                                <select name="deviceCondition[]" class="form-select deviceCondition" required>
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
                                                <input type="number" class="form-control" name="quantity[]" required />
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

                            <div class="col-12 mt-4 mb-5 text-end">
                                <button name="encodeAccount" id="encodeAccount" type="submit" class="btn btn-primary px-5 btn-lg shadow-sm">Save Form Updates</button>
                            </div>
                        </form>
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
    </body>
</html>