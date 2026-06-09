<!-- Modal for updating graph data -->
<div class="modal fade" id="updateGraphModal" tabindex="-1" aria-labelledby="updateGraphLabel" aria-hidden="true">
    <!-- Centered modal dialog -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal header containing the title and close button -->
            <div class="modal-header">
                <h5 class="modal-title" id="updateGraphLabel">Update Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body containing the form to update graph data -->
            <div class="modal-body">
                <!-- Form for updating graph data, uses POST method -->
                <form class="row g-3" id="updateGraphForm" method="POST">
                    <!-- Input field to select the start date of the call -->
                    <div class="col-12">
                        <label for="callDateStart" class="form-label">Date of Call From</label>
                        <input type="date" class="form-control" id="callDateStart" name="callDateStart" />
                    </div>
                    <!-- Input field to select the end date of the call -->
                    <div class="col-12">
                        <label for="callDateEnd" class="form-label">Date of Call To</label>
                        <input type="date" class="form-control" id="callDateEnd" name="callDateEnd" />
                    </div>
                    <!-- Submit button to update the graph data -->
                    <div class="col-12 text-end">
                        <button name="updateGraph" id="updateGraph" type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>