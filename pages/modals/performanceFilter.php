<!-- Modal for updating performance data -->
<div class="modal fade" id="updatePerformanceModal" tabindex="-1" aria-labelledby="updatePerformanceLabel" aria-hidden="true">
    <!-- Centered modal dialog of medium size -->
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="updatePerformanceLabel">Performance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to update performance data -->
            <div class="modal-body">
                <!-- Form for updating performance data, uses POST method -->
                <form id="updatePerformanceForm" action="performance.php" method="POST">
                    <div class="row g-3">
                        <!-- Dropdown to select the account executive -->
                        <div class="col-md-12">
                            <!-- <label for="accExec" class="form-label">Account Executive</label>
                            <select name="accExec" id="accExec" class="form-select">
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($user_list_array as $employee): ?>
                                <option value="<?= htmlspecialchars($employee) ?>"><?= htmlspecialchars($employee) ?></option>
                                <?php endforeach; ?>
                            </select> -->

                            <!-- Dropdown to select the department -->
                            <label for="department" class="form-label">Department</label>
                            <select id="department" name="department" class="form-select">
                                <option value="N/A" selected disabled>Choose...</option>
                                <option value="OP Sales - PP">OP Sales - PP</option>
                                <option value="OP Sales - MFP/RISO">OP Sales - MFP/RISO</option>
                                <option value="OP Consumables">OP Consumables</option>
                                <option value="Rental">Rental</option>
                                <option value="Furniture">Furniture</option>
                            </select>

                            <!-- Dropdown to select the business unit -->
                            <label for="businessUnit" class="form-label">Business Unit</label>
                            <select name="businessUnit" id="businessUnit" class="form-select">
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($unit_list as $unit): ?>
                                <option value="<?= $unit ?>"><?= $unit ?></option>
                                <?php endforeach; ?>
                            </select>

                            <!-- Dropdown to select the scope (daily, weekly, monthly, yearly) -->
                            <label for="scopeSelect" class="form-label">Scope</label>
                            <select name="scope" id="scopeSelect" class="form-select">
                                <option value="N/A" selected disabled>Choose...</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>

                            <!-- Input field to select the start date of the call -->
                            <label for="callDateStart" class="form-label">Date of Call From</label>
                            <input type="date" class="form-control" id="callDateStart" name="callDateStart">

                            <!-- Input field to select the end date of the call -->
                            <label for="callDateEnd" class="form-label">Date of Call To</label>
                            <input type="date" class="form-control" id="callDateEnd" name="callDateEnd">

                            <!-- Submit button to update the performance data -->
                            <button name="updatePerformance" id="updatePerformance" type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>