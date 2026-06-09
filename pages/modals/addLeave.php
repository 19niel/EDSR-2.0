<!-- Modal for adding leave data -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveLabel" aria-hidden="true">
    <!-- Centered modal dialog of medium size -->
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="leaveLabel">Leave Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to add leave data -->
            <div class="modal-body">
                <!-- Form for adding leave data, uses POST method -->
                <form class="row g-3" id="leaveForm" action="" method="POST">
                    <!-- Dropdown to select the employee name -->
                    <div class="col-md-12">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <select class="form-select" id="employeeName" name="employeeName" required>
                            <option value="" disabled selected>Select Employee</option>
                            <?php foreach ($employee_list_result as $employee_list_row) { ?>
                            <option value="<?php echo $employee_list_row['name'] ?>"><?php echo $employee_list_row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- Dropdown to select the duration of the leave (whole day or half day) -->
                    <div class="col-md-12">
                        <label for="leaveDuration" class="form-label">Duration</label>
                        <select class="form-select" id="leaveDuration" name="leaveDuration" required>
                            <option value="" disabled selected>Duration</option>
                            <option value="1">Whole Day</option>
                            <option value="0.5">Half Day</option>
                        </select>
                    </div>
                    <!-- Input field to select the date of the leave -->
                    <div class="col-md-12">
                        <label for="leaveDate" class="form-label">Date of Leave</label>
                        <input type="date" class="form-control" id="leaveDate" name="leaveDate" required />
                    </div>
                    <!-- Submit button to add the leave data -->
                    <div class="col-md-12">
                        <button name="leave" id="leave" type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>