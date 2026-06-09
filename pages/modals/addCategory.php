<!-- Modal for adding a new category -->
<div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <!-- Centered modal dialog of medium size -->
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to add a category -->
            <div class="modal-body">
                <!-- Form with validation and POST method -->
                <form class="row g-3" action="" onsubmit="return isValid()" method="POST">
                    <!-- Dropdown to select the category field -->
                    <div class="form-floating col-md-12 mb-2">
                        <select class="form-select" name="field">
                            <option disabled selected value="">Select Field...</option>
                            <option value="Account Category">Account Category</option>
                            <option value="Segment">Segment</option>
                            <option value="Industry">Industry</option>
                            <option value="Source of Account">Source of Account</option>
                            <option value="Contract Type">Contract Type</option>
                            <option value="Terms of Payment">Terms of Payment</option>
                            <option value="Nature of Call">Nature of Call</option>
                            <option value="Account Status">Account Status</option>
                            <option value="SBU">SBU</option>
                            <option value="Type of End-User">Type of End-User</option>
                            <option value="Product Type">Product Type</option>
                            <option value="Existing System">Existing System</option>
                        </select>
                        <label class="ms-2 form-control-placeholder" for="field">Field</label>
                    </div>
                    <!-- Input field for the category name -->
                    <div class="form-floating col-md-12 mb-2">
                        <input type="text" class="form-control" name="category" placeholder="Category Name" />
                        <label class="ms-2 form-control-placeholder" for="category">Category Name</label>
                    </div>
                    <!-- Submit button to add the category -->
                    <div class="col-md-12 mt-5">
                        <button id="add_category_button" name="add_category_button" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>