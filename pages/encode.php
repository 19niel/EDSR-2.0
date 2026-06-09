<?php
    include ('../php/uploadFile.php');
    include ('../php/autoRedirect.php');
    include ('../php/dates.php');
    include ('../php/userList.php');
    include ('../php/categoryList.php');
    include ('../php/subcategoryList.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
        <link rel="stylesheet" href="/e-dsr/css/encode.css" />

        <title>Encode - E-DSR</title>
        <script src="../js/hideElement.js" defer></script>
    </head>
    <body>
        <?php include ('header.php'); ?>

        <!-- Sidebar -->
        <div class="container-fluid">
            <div class="row">
                <!-- Main Content -->
                <main class="col-12 col-md-10 mx-auto px-4">
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
                        <h3 class="m-0 text-primary fw-bold">Encode Account</h3>
                    </div>
                    
                    <div class="row py-3">
                        <form action="../php/encodeAccount.php" onsubmit="return isvalid()" method="POST">
                            
                            <!-- Segment 1: Pipeline Information -->
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Pipeline Information</h5>
                                <div class="row g-3">
                                    <!-- SBU -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="sbu" class="form-label">SBU/Segment<span class="req"> *</span></label>
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
                                        <div id="accountExecutiveContainer"></div>
                                    </div>

                                    <!-- Date of Activity -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="callDate" class="form-label">Date of Activity <span class="req">*</span></label>
                                        <input type="date" class="form-control" id="callDate" required name="callDate" min="<?php echo $min; ?>" max="<?php echo $max; ?>" />
                                    </div>
                                </div>
                            </div>

                            <hr class="text-secondary my-4">

                            <!-- Segment 2: Client Information -->
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Client Information</h5>
                                <div class="row g-3">
                                    <!-- Account Name -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountName" class="form-label">Account Name <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="accountName" required name="accountName" onchange="searchAccounts(this.value)" />
                                        <ul id="accountList" class="account-list"></ul>
                                    </div>

                                    <!-- ARS Expiry Date -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="arsExpiryDate" class="form-label">ARS Expiry Date<span class="req">*</span></label>
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
                                        <select id="existingSystem" name="existingSystem" class="form-select">
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($existingSystemResult as $existingSystemRow) { ?>
                                                <option value="<?php echo $existingSystemRow['category_name']; ?>"><?php echo $existingSystemRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- End of Contract (For Competitor) (conditionally displayed) -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="contractEndCompetitorContainer" style="display: none;">
                                        <label for="contractEndCompetitor" class="form-label">End of Contract (For Competitor)</label>
                                        <input type="date" class="form-control" id="contractEndCompetitor" name="contractEndCompetitor" min="<?php echo $min_expiry; ?>" />
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
                                        <select id="segment" name="segment" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php foreach ($segmentResult as $segmentRow) { ?>
                                                <option value="<?php echo $segmentRow['id']; ?>"><?php echo $segmentRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Industry Subcategory -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="industrySubcategory" class="form-label">Industry Subcategory</label>
                                        <select id="industrySubcategory" name="industrySubcategory" class="form-select">
                                            <option value="N/A" selected disabled>Choose...</option>
                                        </select>
                                    </div>

                                    <!-- Source of Account -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountSource" class="form-label">Lead Source<span class="req">*</span></label>
                                        <select id="accountSource" name="accountSource" class="form-select" required>
                                            <option value="N/A" selected disabled>Choose...</option>
                                            <?php while ($accountSourceRow = mysqli_fetch_assoc($accountSourceResult)) { ?>
                                                <option value="<?php echo $accountSourceRow['id']; ?>"><?php echo $accountSourceRow['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Source of Account Subcategory -->
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="accountSourceCategory" class="form-label">Lead Subcategory</label>
                                        <select id="accountSourceCategory" name="accountSourceCategory" class="form-select" disabled>
                                            <option value="N/A" selected disabled>Choose...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="text-secondary my-4">

                            <!-- Segment 3: Client Location Details -->
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Client Location Details</h5>
                                
                                <!-- Top Row: Branch and Region -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="branch1" class="form-label">Branch <span class="req">*</span></label>
                                        <select name="branch1" class="form-control form-select" id="branch1" required>
                                            <option value="" disabled selected>-- Select Branch --</option>
                                            <option value="MM">MM</option>
                                            <option value="ANG">ANG</option>
                                            <option value="CAB">CAB</option>
                                            <option value="LAU">LAU</option>
                                            <option value="BAT">BAT</option>
                                            <option value="NAG">NAG</option>
                                            <option value="SUB">SUB</option>
                                            <option value="BAC">BAC</option>
                                            <option value="CEB">CEB</option>
                                            <option value="DUM">DUM</option>
                                            <option value="ILO">ILO</option>
                                            <option value="TAC">TAC</option>
                                            <option value="CDO">CDO</option>
                                            <option value="DAV">DAV</option>
                                            <option value="GEN">GEN</option>
                                            <option value="ZAM">ZAM</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="region1" class="form-label">Region <span class="req">*</span></label>
                                        <select name="region1" class="form-control form-select" id="region1" required>
                                            <option value="" disabled selected>-- Select Region --</option>
                                            <option value="MM">MM</option>
                                            <option value="LUZON">LUZON</option>
                                            <option value="VISAYAS">VISAYAS</option>
                                            <option value="MINDANAO">MINDANAO</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Bottom Row: Address (Separate Line) -->
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="address" class="form-label">Address <span class="req">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Enter full street address" required/>
                                    </div>
                                </div>
                            </div>

                            <hr class="text-secondary my-4">

                            <!-- Segment 4: Contact Information Section -->
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Contact Information</h5>
                                
                                <!-- Container for dynamic contact rows -->
                                <div id="contactEntries" class="mb-3">
                                    <div class="row contact-entry g-3 mb-3 align-items-end">
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Person <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="contactPerson[]" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Person Designation <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="designation[]" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Contact Details <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="contactNumber[]" required />
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Email Address <span class="req">*</span></label>
                                            <input type="email" class="form-control" name="emailAddress[]" required />
                                        </div>
                                        <div class="col-12 text-end contact-remove-container" style="display: none;">
                                            <button type="button" class="btn btn-danger btn-sm remove-contact"><i class="fa fa-trash"></i> Remove Contact</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addContactEntry">Add Another Contact</button>
                                    </div>
                                </div>

                                <!-- Decision Maker Details -->
                                <div class="row g-3 border-top pt-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="decisionMaker" class="form-label">Decision Maker</label>
                                        <input type="text" class="form-control" id="decisionMaker" name="decisionMaker" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmDesignation" class="form-label">Decision Maker Designation</label>
                                        <input type="text" class="form-control" id="dmDesignation" name="dmDesignation" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="dmEmail" class="form-label">Decision Maker Email</label>
                                        <input type="email" class="form-control" id="dmEmail" name="dmEmail" />
                                    </div>
                                </div>
                            </div>

                            <hr class="text-secondary my-4">

                            <!-- Segment 5: Project Details -->
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

                            <hr class="text-secondary my-4">

                            <!-- Segment 6: Product and Pricing Information -->
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Product and Pricing Information</h5>
                                <div id="productEntries">
                                    <div class="row product-entry g-3 mb-3 align-items-end">
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">Product Type <span class="req">*</span></label>
                                            <select name="productType[]" class="form-select productType" required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <?php while ($productTypeRow = mysqli_fetch_assoc($productTypeResult)) { ?>
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
                                                <?php while ($deviceConditionRow = mysqli_fetch_assoc($deviceConditionResult)) { ?>
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
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addProductEntry">Add Another Product</button>
                                    </div>
                                </div>
                            </div>

                            <hr class="text-secondary my-4">

                            <!-- Segment 7: Progress Updates -->
                            <div class="card p-4 shadow-sm mb-4">
                                <h5 class="text-secondary fw-semibold mb-3">Progress Updates</h5>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label for="progressDate" class="form-label">Date of Progress</label>
                                        <input type="date" class="form-control" id="progressDate" name="progressDate" min="<?php echo $min_expiry; ?>"/>
                                    </div>
                                  
                                    <div class="col-md-6 col-lg-4 col-xl-3">
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
                                        <select id="reasonSubcategory" name="reasonSubcategory" class="form-select" disabled>
                                            <option value="N/A" selected disabled>Choose...</option>
                                        </select>
                                    </div>
                                   
                                    <div class="col-md-12 col-xl-6">
                                        <label for="remarks" class="form-label">Remarks <span class="req">*</span></label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="2" required></textarea>
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
                                </div>

                                <div class="row g-3">
                                    <!-- Conditional Elements -->
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="deliveryDateContainer" style="display: none;">
                                        <label for="deliveryDate" class="form-label">Delivery Date</label>
                                        <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" min="<?php echo $min_expiry; ?>" />
                                    </div>
                                    <div class="col-md-6 col-lg-4 col-xl-3" id="contractEndContainer" style="display: none;">
                                        <label for="contractEnd" class="form-label">Contract End</label>
                                        <input type="date" class="form-control" id="contractEnd" name="contractEnd" min="<?php echo $min_expiry; ?>" />
                                    </div>
                                </div>
                            </div>

                            <!-- Form Action Button -->
                            <div class="col-12 mt-4 mb-5 text-end">
                                <button name="encodeAccount" id="encodeAccount" type="submit" class="btn btn-primary px-5 btn-lg shadow-sm">Submit Form</button>
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
        <script src="../js/addProducts.js"></script>
        <script src="../js/addContacts.js"></script>
        <script src="../js/handleAccountCategoryChange.js"></script>
        <script src="../js/handleProductTypeChange.js"></script>
        <script src="../js/handleAccountStatusChange.js"></script>
        <script src="../js/encodeAutofill.js"></script>
        <script src="../js/encode/prefillForm.js"></script>
        <script src="../js/hideElement.js"></script>
        <script type="text/javascript" src="../js/accExec.js"></script>
        <script type="text/javascript" src="../js/autoFill.js"></script>
        <script src="../js/ph-address-selector.js"></script>
        <script src="../js/handleBranchToRegion.js"></script>
    </body>
</html>