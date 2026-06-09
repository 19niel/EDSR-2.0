<?php
    include ('../php/uploadFile.php');
    include ('../php/autoRedirect.php');
    include ('../php/dates.php');
    include ('../php/userList.php');
    include ('../php/categoryList.php');
    include ('../php/subcategoryList.php');
    include ('../php/fetchDataEditEncode.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
        <title>Edit Encode - E-DSR</title>

        <script src="../js/hideElement.js" defer></script>
    </head>
    <body>
        <?php include ('header.php'); ?>

        <!-- Sidebar -->
        <div class="container-fluid">
            <div class="row">
                <!-- Main Content -->
                <main class="col-12 col-md-10 mx-auto px-4">
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <h3 class="m-0">LID:
                        <span class="m-0">
                                <?php include ('../php/getLidHeader.php'); ?>
                        </span></h3>
                    </div>
                    <div class="row g-3 py-3">
                        <form class="row g-3" action="../php/editEncodeAccount.php" onsubmit="return isvalid()" method="POST">
                            <input type="hidden" name="editEncode" value="true">
                            <script>
                                var id = new URLSearchParams(window.location.search).get('id');
                                var LID = new URLSearchParams(window.location.search).get('LID');
                                console.log(id);
                                console.log(LID);
                            </script>
                            <input type="hidden" name="encodeId" id="encodeId" value="">
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="mt-3">Pipeline Information</h5>
                                <div class="row">
                                    <!-- SBU -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="sbu" class="form-label">SBU/Segment<span class="req">*</span></label>
                                        <select id="sbu" name="sbu" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($sbuResult as $sbuRow) { ?>
                                                <option value="<?php echo $sbuRow['category_name']; ?>"><?php echo $sbuRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- Account Executive -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountExecutive" class="form-label">Account Executive (for review) <span class="req">*</span></label>
                                        
                                        <?php
                                        // Get the saved Account Executive value from the database record 
                                        // (Adjust '$row' to match your actual fetched record variable name, e.g., $editRow['accountExecutive'])
                                        $savedExecutive = $row['accountExecutive'] ?? ''; 

                                        // CASE 1: Regular User -> Show a read-only input field with their name
                                        if (($category ?? '') === 'User') { ?>
                                            <input type="text" class="form-control" id="accountExecutive" name="accountExecutive" 
                                                value="<?php echo htmlspecialchars(!empty($savedExecutive) ? $savedExecutive : ($name ?? '')); ?>" readonly required />
                                        
                                        <?php 
                                        // CASE 2: Manager -> Show a dropdown populated with their department's users
                                        } else if (($category ?? '') === 'Manager') { ?>
                                            <select id="accountExecutive" name="accountExecutive" class="form-select form-control" required>
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

                                        <?php 
                                        // CASE 3: Admin (or fallback) -> Show a dropdown with ALL users
                                        } else { ?>
                                            <select id="accountExecutive" name="accountExecutive" class="form-select form-control" required>
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
                                    <!-- Date of Activity -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="callDate" class="form-label">Date of Activity <span class="req">*</span></label>
                                        <input type="date" class="form-control" id="callDate" name="callDate" min="<?php echo $min; ?>" max="<?php echo $max; ?>"  required/>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="mt-3">Client Information</h5>
                                <div class="row">
                                    <!-- Account Name -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountName" class="form-label">Account Name <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="accountName" name="accountName" onchange="searchAccounts(this.value)"  required/>
                                        <ul id="accountList" class="account-list"></ul>
                                    </div>
                                    <!-- ARS Expiry Date -->
                                    <div class="col-md-6 col-lg-4 col-xl-3 ars-container">
                                        <label for="arsExpiryDate" class="form-label">ARS Expiry Date</label>
                                        <input type="date" class="form-control" id="arsExpiryDate" name="arsExpiryDate" min="<?php echo $min_expiry; ?>"/>
                                    </div>
                                    <!-- Account Category -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountCategory" class="form-label">Account Category <span class="req">*</span></label>
                                        <select id="accountCategory" name="accountCategory" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($accountCategoryResult as $accountCategoryRow) { ?>
                                                <option value="<?php echo $accountCategoryRow['category_name']; ?>"><?php echo $accountCategoryRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- Existing System (conditionally displayed) -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="existingSystemContainer" style="display: none;">
                                        <label for="existingSystem" class="form-label">Existing System</label>
                                        <select id="existingSystem" name="existingSystem" class="form-select" >
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($existingSystemResult as $existingSystemRow) { ?>
                                                <option value="<?php echo $existingSystemRow['category_name']; ?>"><?php echo $existingSystemRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- End of Contract (For Competitor) (conditionally displayed) -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="contractEndCompetitorContainer" style="display: none;">
                                        <label for="contractEndCompetitor" class="form-label">End of Contract (For Competitor)</label>
                                        <input type="date" class="form-control" id="contractEndCompetitor" name="contractEndCompetitor" min="<?php echo $min_expiry; ?>"  />
                                    </div>
                                    <!-- Type of End-User -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="endUserType" class="form-label">Type of End-User <span class="req">*</span></label>
                                        <select id="endUserType" name="endUserType" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($endUserTypeResult as $endUserTypeRow) { ?>
                                                <option value="<?php echo $endUserTypeRow['category_name']; ?>"><?php echo $endUserTypeRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- Industry -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="segment" class="form-label">Industry <span class="req">*</span></label>
                                        <select id="segment" name="segment" class="form-select" required >
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($segmentResult as $segmentRow) { ?>
                                                <option value="<?php echo $segmentRow['id']; ?>"><?php echo $segmentRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            document.querySelector("form").addEventListener("submit", function (event) {
                                                const segmentValue = document.getElementById("segment").value;
                                                console.log("Selected Segment:", segmentValue);
                                            });
                                            });
                                    </script>
                                    <!-- Industry Subcategory -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="industrySubcategory" class="form-label">Industry Subcategory</label>
                                        <select id="industrySubcategory" name="industrySubcategory" class="form-select" >
                                            <option value="N/A" selected disabled>Choose...</option>
                                        </select>
                                    </div>
                                    <!-- Source of Account -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountSource" class="form-label">Lead Source <span class="req">*</span></label>
                                        <select id="accountSource" name="accountSource" class="form-select" required >
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php while ($accountSourceRow = mysqli_fetch_assoc($accountSourceResult)) { ?>
                                                <option value="<?php echo $accountSourceRow['id']; ?>"><?php echo $accountSourceRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- Source of Account Subcategory -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountSourceCategory" class="form-label">Lead Subcategory</label>
                                        <select id="accountSourceCategory" name="accountSourceCategory" class="form-select" disabled >
                                            <option value="N/A" selected disabled>Choose...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="mt-3">Client Location Details</h5>
                                <div class="row">
                                    <!-- Region -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="region" class="form-label">Region <span class="req">*</span></label>
                                        <select name="region" class="form-control form-control-md" id="region" required></select>
                                        <input type="hidden" class="form-control form-select form-control-md" name="region_text" id="region-text"  />
                                    </div>
                                    <!-- Province -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="provinceContainer">
                                        <label for="province" class="form-label">Province <span class="req">*</span></label>
                                        <select name="province" class="form-control form-control-md" id="province" required></select>
                                        <input type="hidden" class="form-control form-select form-control-md" name="province_text" id="province-text"  />
                                    </div>
                                    <!-- City / Municipality -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="city" class="form-label">City / Municipality <span class="req">*</span></label>
                                        <select name="city" class="form-control form-control-md" id="city" required></select>
                                        <input type="hidden" class="form-control form-select form-control-md" name="city_text" id="city-text"  />
                                    </div>
                                    <!-- Barangay -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="barangayContainer">
                                        <label for="barangay" class="form-label">Barangay</label>
                                        <select name="barangay" class="form-control form-control-md" id="barangay"></select>
                                        <input type="hidden" class="form-control form-select form-control-md" name="barangay_text" id="barangay-text"  />
                                    </div>
                                    <!-- Full Address -->
                                    <div class="col-lg-8 col-xl-6">
                                        <label for="address" class="form-label">Address <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address"  required/>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="mt-3">Contact Information</h5>
                                <div class="row">
                                    <!-- Contact Person Details -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="contactPerson" class="form-label">Contact Person <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="contactPerson" name="contactPerson"  required/>
                                    </div>
                                    <!-- Contact Person Designation -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="designation" class="form-label">Contact Person Designation <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="designation" name="designation" required />
                                    </div>
                                    <!-- Contact Details -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="contactNumber" class="form-label">Contact Details <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" required />
                                    </div>
                                    <!-- Email Address -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="emailAddress" class="form-label">Email Address <span class="req">*</span></label>
                                        <input type="email" class="form-control" id="emailAddress" name="emailAddress" required />
                                    </div>
                                    <!-- Decision Maker Details -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="decisionMaker" class="form-label">Decision Maker</label>
                                        <input type="text" class="form-control" id="decisionMaker" name="decisionMaker"  />
                                    </div>
                                    <!-- Decision Maker Designation -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmDesignation" class="form-label">Decision Maker Designation</label>
                                        <input type="text" class="form-control" id="dmDesignation" name="dmDesignation"  />
                                    </div>
                                    <!-- Decision Maker Email -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmEmail" class="form-label">Decision Maker Email</label>
                                        <input type="email" class="form-control" id="dmEmail" name="dmEmail"  />
                                    </div>
                                </div>
                            </div>


                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Project Details</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="projTitle" class="form-label">Project Title <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="projTitle" name="projTitle" required/>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="proposedPrice" class="form-label">Proposed Price <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="proposedPrice" name="proposedPrice" required/>
                                    </div>

                                    <!-- Terms of Payment -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="paymentTerms" class="form-label">Terms of Payment <span class="req">*</span></label>
                                        <select id="paymentTerms" name="paymentTerms" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($paymentTermsResult as $paymentTermsRow) { ?>
                                                <option value="<?php echo $paymentTermsRow['category_name']; ?>"><?php echo $paymentTermsRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="contractType" class="form-label">Contract Type <span class="req">*</span></label>
                                        <select id="contractType" name="contractType" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($contractTypeResult as $contractTypeRow) { ?>
                                                <option value="<?php echo $contractTypeRow['category_name']; ?>"><?php echo $contractTypeRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                  
                                    <div class="col-lg-8 col-xl-6">
                                        <label for="projectAddress" class="form-label">Project Address <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="projectAddress" name="projectAddress" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="mt-3">Product and Pricing Information</h5>
                                <div class="row">
                                    <!-- Product Information -->
                                    <div id="productEntries" class="col-md-12">
                                        <div class="row product-entry">
                                            <!-- Product Information -->
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <label class="form-label">Product Type <span class="req">*</span></label>
                                                <select name="productType[]" class="form-select productType" required>
                                                    <option value="N/A" selected disabled>Choose...</option>
                                                    <?php while ($productTypeRow = mysqli_fetch_assoc($productTypeResult)) { ?>
                                                        <option value="<?php echo $productTypeRow['id']; ?>"><?php echo $productTypeRow['category_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!-- Product Type Subcategory -->
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <label class="form-label">Product Type Subcategory</label>
                                                <select name="productTypeSubcategory[]" class="form-select productTypeSubcategory" disabled>
                                                    <option value="N/A" selected disabled>Choose...</option>
                                                </select>
                                            </div>
                                            <!-- Brand New or Remac Product -->
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <label class="form-label">Device Condition <span class="req">*</span></label>
                                                <select name="deviceCondition[]" class="form-select deviceCondition" required>
                                                    <option value="N/A" selected disabled>Choose...</option>
                                                    <?php while ($deviceConditionRow = mysqli_fetch_assoc($deviceConditionResult)) { ?>
                                                        <option value="<?php echo $deviceConditionRow['id']; ?>"><?php echo $deviceConditionRow['category_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!-- Total Quantity -->
                                            <div class="col-md-6 col-lg-4 col-xl-2">
                                                <label class="form-label">Quantity <span class="req">*</span></label>
                                                <input type="number" class="form-control" name="quantity[]" required />
                                            </div>
                                            <!-- Remove Entry Button -->
                                            <div class="col-md-2 col-lg-4 col-xl-1 d-grid align-items-end">
                                                <button type="button" class="btn btn-danger remove-entry btn-block">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Add More Button -->
                                    <div class="col-md-12 mt-3 mb-2">
                                        <button type="button" class="btn btn-primary" id="addProductEntry">Add Another Product</button>
                                    </div>

                                </div>
                            </div>

                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="mt-3">Progress Updates</h5>
                                <div class="row">

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="progressDate" class="form-label">Date of Progress</label>
                                        <input type="date" class="form-control" id="progressDate" name="progressDate" min="<?php echo $min_expiry; ?>"/>
                                    </div>

                                    <!-- Account Status -->
                                    <div class="col-md-6 col-lg-4 col-xl-2">
                                        <label for="accountStatus" class="form-label">Account Status <span class="req">*</span></label>
                                        <select id="accountStatus" name="accountStatus" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($accountstatusResult as $accountstatusRow) { ?>
                                                <option value="<?php echo $accountstatusRow['id']; ?>"><?php echo $accountstatusRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="reasonSubcategory" class="form-label">Reason Subcategory</label>
                                        <select id="reasonSubcategory" name="reasonSubcategory" class="form-select" disabled >
                                            <option value="N/A" selected disabled>Choose...</option>
                                        </select>
                                    </div>

                                    <!-- Remarks -->
                                    <div class="col-lg-8 col-xl-5">
                                        <label for="whatTranspired" class="form-label">Remarks <span class="req">*</span></label>
                                        <textarea class="form-control" id="whatTranspired" name="whatTranspired" required></textarea>
                                    </div>

                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="estimatedDelivery" class="form-label">Estimated Delivery</label>
                                        <select id="estimatedDelivery" name="estimatedDelivery" class="form-select">
                                            <option value="" selected disabled>Choose Month...</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                    </div>

                                    <!-- Delivery Date (conditionally displayed) -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="deliveryDateContainer" style="display: none;">
                                        <label for="deliveryDate" class="form-label">Delivery Date</label>
                                        <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" min="<?php echo $min_expiry; ?>"  />
                                    </div>
                                    <!-- Contract End (conditionally displayed) -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="contractEndContainer" style="display: none;">
                                        <label for="contractEnd" class="form-label">Contract End</label>
                                        <input type="date" class="form-control" id="contractEnd" name="contractEnd" min="<?php echo $min_expiry; ?>"  />
                                    </div>
                                    <!-- Follow-up Action  -->
                             
                                        
                              
                                </div>
                            </div>

                            <div class="col-12">
                                <button name="encodeAccount" id="encodeAccount" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </main>

                       <input type="text" id="followUpAction" class="d-none" disabled />
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
        <script src="../js/toggleDataFields.js"></script>
        <script src="../js/handleIndustryChange.js"></script>
        <script src="../js/handleAccountSourceChange.js"></script>
        <script src="../js/handleRegionChange.js"></script>
        <script src="../js/addProducts.js"></script>
        <script src="../js/handleAccountCategoryChange.js"></script>
        <script src="../js/handleProductTypeChange.js"></script>
        <script src="../js/handleAccountStatusChange.js"></script>
        <script src="../js/encode/prefillForm.js"></script>
        <!-- <script src="../js/handleReasonContainer.js"></script> -->
        <script src="../js/hideElement.js"></script>
        <script src="../js/ph-address-selector.js"></script>
        
    </body>
</html>