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
                <form class="row g-3" action="" method="GET" onsubmit="return isvalid()">
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
                        <label for="accountName" class="form-label">Account Name</label>
                        <input type="text" class="form-control" id="accountName" name="accountName" oninput="searchAccounts(this.value)">
                        <ul id="accountList" class="list-group"></ul>
                    </div>
                    <!-- Input field to select the specific date of the call -->
                    <div class="col-md-6">
                        <label for="callDate" class="form-label">Date of Call</label>
                        <input type="date" class="form-control" id="callDate" name="callDate">
                    </div>
                    <!-- Input field to select the start date range for the call -->
                    <div class="col-md-6">
                        <label for="callDateStart" class="form-label">Date of Call From</label>
                        <input type="date" class="form-control" id="callDateStart" name="callDateStart">
                    </div>
                    <!-- Input field to select the end date range for the call -->
                    <div class="col-md-6">
                        <label for="callDateEnd" class="form-label">Date of Call To</label>
                        <input type="date" class="form-control" id="callDateEnd" name="callDateEnd">
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