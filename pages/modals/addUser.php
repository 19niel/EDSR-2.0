<!-- Modal for adding a new user -->
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <!-- Centered modal dialog of large size -->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to add a new user -->
            <div class="modal-body">
                <!-- Form for adding a new user, uses POST method and validates input before submission -->
                <form class="row g-3" action="../php/addUser.php" onsubmit="return isvalid()" method="POST">
                    <!-- Input field for the user's name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required />
                    </div>
                    <!-- Dropdown to select the branch -->
                    <div class="col-md-6">
                        <label for="branch" class="form-label">Branch</label>
                        <select id="branch" name="branch" class="form-select" required>
                            <option value="N/A" selected disabled>Choose...</option>
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
                    <!-- Input field for the username -->
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required />
                    </div>
                    <!-- Input field for the password with show/hide functionality -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required />
                            <button class="btn btn-outline-secondary" type="button" id="showPasswordBtn" onmousedown="showPassword()" onmouseup="hidePassword()">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Dropdown to select the department -->
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <select id="department" name="department" class="form-select" required>
                            <option value="N/A" selected disabled>Choose...</option>
                            <option value="OP Sales - PP">OP Sales - PP</option>
                            <option value="OP Sales - MFP/RISO">OP Sales - MFP/RISO</option>
                            <option value="OP Consumables">OP Consumables</option>
                            <option value="CSD">CSD</option>
                            <option value="Furniture">Furniture</option>
                            <option value="UIC">UIC</option>
                            <option value="MIS">MIS</option>
                            <option value="Food and Beverages">Food and Beverages</option>
                        </select>
                    </div>
                    <!-- Dropdown to select the category -->
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="N/A" selected disabled>Choose...</option>
                            <option value="User">User</option>
                            <option value="Manager">Manager</option>
                            <option value="VP">VP</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <!-- Dropdown to select the sub-department -->
                    <div class="col-md-6">
                        <label for="subDepartment" class="form-label">Sub Department</label>
                        <select id="subDepartment" name="subDepartment" class="form-select">
                            <option value="N/A" selected disabled>Choose...</option>
                            <option value="PP SALES">PP SALES</option>
                            <option value="OP MFP(NORTH)">OP MFP(NORTH)</option>
                            <option value="OP MFP(SOUTH)">OP MFP(SOUTH)</option>
                            <option value="OP RISO">OP RISO</option>
                            <option value="ENTERPRISE">ENTERPRISE</option>
                            <option value="OP CONSUMABLES SALES">OP CONSUMABLES SALES</option>
                            <option value="FURNITURE">FURNITURE</option>
                            <option value="RENTAL SALES - MAKATI/BGC">RENTAL SALES - MAKATI/BGC</option>
                            <option value="RENTAL SALES - SOUTH MANILA">RENTAL SALES - SOUTH MANILA</option>
                            <option value="RENTAL SALES - QC/ORTIGAS">RENTAL SALES - QC/ORTIGAS</option>
                            <option value="BRANCH - LA UNION">BRANCH - LA UNION</option>
                            <option value="BRANCH - ANGELES">BRANCH - ANGELES</option>
                            <option value="BRANCH - CABANATUAN">BRANCH - CABANATUAN</option>
                            <option value="BRANCH - BACOLOD">BRANCH - BACOLOD</option>
                            <option value="BRANCH - CEBU">BRANCH - CEBU</option>
                            <option value="BRANCH - ILO-ILO">BRANCH - ILO-ILO</option>
                            <option value="BRANCH - CDO">BRANCH - CDO</option>
                            <option value="BRANCH - DUMAGUETE">BRANCH - DUMAGUETE</option>
                            <option value="BRANCH - GENSAN">BRANCH - GENSAN</option>
                            <option value="Food and Beverages">Food and Beverages</option>
                        </select>
                    </div>
                    <!-- Dropdown to select the role -->
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select">
                            <option value="N/A" selected disabled>Choose...</option>
                            <option value="User">User</option>
                            <option value="Team Leader">Team Leader</option>
                            <option value="Manager">Manager</option>
                            <option value="Account Executive">Account Executive</option>
                            <option value="Senior Manager">Senior Manager</option>
                            <option value="General Manager">General Manager</option>
                            <option value="Sales Executive">Sales Executive</option>
                            <option value="Sales Executive (Supervisor)">Sales Executive (Supervisor)</option>
                            <option value="Assistant Manager">Assistant Manager</option>
                        </select>
                    </div>
                    <!-- Dropdown to select if the password should be changed -->
                    <div class="col-md-6">
                        <label for="passwordChange" class="form-label">Change Password?</label>
                        <select id="passwordChange" name="passwordChange" class="form-select" required>
                            <option value="N/A" selected disabled>Choose...</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <!-- Submit button to add the user -->
                    <div class="col-12">
                        <button name="addUser" id="addUser" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>