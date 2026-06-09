<!-- Modal for adding an event or training -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModal" aria-hidden="true">
    <!-- Centered modal dialog of medium size -->
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="eventModal">Event/Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to add an event or training -->
            <div class="modal-body">
                <!-- Form for adding event or training, uses POST method -->
                <form class="row g-3" id="eventForm" action="" method="POST">
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
                    <!-- Dropdown to select the type (event, training, meeting) -->
                    <div class="col-md-12">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="event">Event</option>
                            <option value="training">Training</option>
                            <option value="meeting">Meeting</option>
                        </select>
                    </div>
                    <!-- Dropdown to select the duration of the event or training -->
                    <div class="col-md-12">
                        <label for="duration" class="form-label">Duration</label>
                        <select class="form-select" id="duration" name="duration" required>
                            <option value="" disabled selected>Select Duration</option>
                            <option value="1">1 hr</option>
                            <option value="2">2 hrs</option>
                            <option value="3">3 hrs</option>
                            <option value="4">4 hrs</option>
                            <option value="5">5 hrs</option>
                            <option value="6">6 hrs</option>
                            <option value="7">7 hrs</option>
                            <option value="8">8 hrs</option>
                        </select>
                    </div>
                    <!-- Input field to select the date of the event or training -->
                    <div class="col-md-12">
                        <label for="date" class="form-label">Date of Event/Training</label>
                        <input type="date" class="form-control" id="date" name="date" required />
                    </div>
                    <!-- Submit button to add the event or training -->
                    <div class="col-md-12">
                        <button name="event" id="event" type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>