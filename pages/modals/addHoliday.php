<!-- Modal for adding a holiday -->
<div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayLabel" aria-hidden="true">
    <!-- Centered modal dialog of medium size -->
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="holidayLabel">Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to add a holiday -->
            <div class="modal-body">
                <!-- Form for adding holiday, uses POST method -->
                <form class="row g-3" id="holidayForm" action="" method="POST">
                    <!-- Dropdown to select the branch -->
                    <div class="col-md-12">
                        <label for="branch" class="form-label">Branch</label>
                        <select class="form-select" id="branch" name="branch" required>
                            <option value="" disabled selected>Select Branch</option>
                            <option value="All">All</option>
                            <option value="Main Office">Main Office</option>
                            <option value="Angeles">Angeles</option>
                            <option value="Batangas">Batangas</option>
                            <option value="Cabanatuan">Cabanatuan</option>
                            <option value="La Union">La Union</option>
                            <option value="Naga">Naga</option>
                            <option value="Subic">Subic</option>
                            <option value="Bacolod">Bacolod</option>
                            <option value="Cebu">Cebu</option>
                            <option value="Dumaguete">Dumaguete</option>
                            <option value="Iloilo">Iloilo</option>
                            <option value="Tacloban">Tacloban</option>
                            <option value="Cagayan De Oro">Cagayan De Oro</option>
                            <option value="Davao">Davao</option>
                            <option value="Gensan">Gensan</option>
                            <option value="Zamboanga">Zamboanga</option>
                        </select>
                    </div>
                    <!-- Input field to select the date of the holiday -->
                    <div class="col-md-12">
                        <label for="holidayDate" class="form-label">Date of Holiday From</label>
                        <input type="date" class="form-control" id="holidayDate" name="holidayDate" required />
                    </div>
                    <!-- Submit button to add the holiday -->
                    <div class="col-md-12">
                        <button name="holiday" id="holiday" type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>