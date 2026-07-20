<!-- Modal for searching accounts -->
<div class="modal fade" id="searchAccount" tabindex="-1" aria-labelledby="searchAccountLabel" aria-hidden="true">
    <!-- Centered modal dialog of large size -->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="searchAccountLabel">Search Accounts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to search accounts -->
            <div class="modal-body">
                <!-- Form for searching accounts, uses GET method and validates input before submission -->
                <form class="row g-3" action="" method="GET">
                    <!-- Dropdown to select the account executive -->
                    <div class="col-12">
                        <label for="accountExecutiveSearch" class="form-label">Account Executive</label>
                        <select class="form-control" id="accountExecutiveSearch" name="accountExecutiveSearch" onchange="fillFormFields(this)">
                            <option value="">Select Account Executive</option>
                            <!-- Options go here -->
                        </select>
                    </div>
                    <!-- Input field to search account names with suggestions -->
                    <div class="col-12">
                        <label for="accountName" class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="accountName" name="accountName" value="<?php echo htmlspecialchars($_GET['accountName'] ?? ''); ?>" oninput="searchAccounts(this.value)">
                        <ul id="accountList" class="list-group"></ul>
                    </div>
                    <!-- Input field to select the start date range for the call -->
                    <div class="col-md-6">
                        <label for="callDateStart" class="form-label">Creation Date From</label>
                        <input type="date" class="form-control" id="callDateStart" name="callDateStart" value="<?php echo htmlspecialchars($_GET['callDateStart'] ?? ''); ?>">
                    </div>
                    <!-- Input field to select the end date range for the call -->
                    <div class="col-md-6">
                        <label for="callDateEnd" class="form-label">Creation Date To</label>
                        <input type="date" class="form-control" id="callDateEnd" name="callDateEnd" value="<?php echo htmlspecialchars($_GET['callDateEnd'] ?? ''); ?>">
                    </div>
                    <!-- Input field to select the start date range for progress -->
                    <div class="col-md-6">
                        <label for="progressDateStart" class="form-label">Progress Date From</label>
                        <input type="date" class="form-control" id="progressDateStart" name="progressDateStart" value="<?php echo htmlspecialchars($_GET['progressDateStart'] ?? ''); ?>">
                    </div>
                    <!-- Input field to select the end date range for progress -->
                    <div class="col-md-6">
                        <label for="progressDateEnd" class="form-label">Progress Date To</label>
                        <input type="date" class="form-control" id="progressDateEnd" name="progressDateEnd" value="<?php echo htmlspecialchars($_GET['progressDateEnd'] ?? ''); ?>">
                    </div>
                    <!-- Dropdown for Account Status -->
                    <div class="col-md-6">
                        <label for="accStatus" class="form-label">Account Status</label>
                        <select class="form-control" id="accStatus" name="accStatus">
                            <option value="">All Statuses</option>
                            <?php 
                            if (isset($accountstatusResult)) {
                                mysqli_data_seek($accountstatusResult, 0);
                                while ($statusRow = mysqli_fetch_assoc($accountstatusResult)) { 
                                    $selected = (isset($_GET['accStatus']) && $_GET['accStatus'] == $statusRow['id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $statusRow['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($statusRow['category_name']); ?></option>
                            <?php 
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Dropdown for Estimated Delivery -->
                    <div class="col-md-6">
                        <label for="estimatedDelivery" class="form-label">Estimated Delivery</label>
                        <select class="form-control" id="estimatedDelivery" name="estimatedDelivery">
                            <option value="">All Months</option>
                            <?php 
                            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                            foreach ($months as $m) {
                                $selected = (isset($_GET['estimatedDelivery']) && $_GET['estimatedDelivery'] == $m) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $m; ?>" <?php echo $selected; ?>><?php echo $m; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- Submit button to perform the search -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>