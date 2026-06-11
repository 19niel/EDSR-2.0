<style>
.won-table-fontSize { font-size: 0.70rem !important; }
.won-table-container { height: 165px; overflow-y: auto; }
.text-ellipsis-won {
    max-width: 95px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.pagination-sm-override .page-link { padding: 0.15rem 0.4rem; font-size: 0.65rem; color: #198754; }
.pagination-sm-override .page-item.active .page-link { background-color: #198754; border-color: #198754; color: #fff; }
</style>

<div class="main-content-card p-3 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-success tracking-wider fw-bold small m-0">
                <i class="fa-solid fa-circle-check me-2"></i>Recently Won Projects
            </h6>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.65rem; font-weight: 600; border-radius: 4px;">Live Table</span>
        </div>
        <hr class="my-2 text-black-50">
    </div>

    <div class="table-responsive won-table-container flex-grow-1">
        <table class="table table-sm table-hover align-middle won-table-fontSize mb-0">
            <thead class="table-light text-secondary sticky-top">
                <tr>
                    <th style="width: 30%;">Sales Exec</th>
                    <th style="width: 32%;">Client Name</th>
                    <th style="width: 20%;">Date</th>
                    <th class="text-end" style="width: 18%;">Amount</th>
                </tr>
            </thead>
            <tbody id="won-projects-table-body">
                <tr>
                    <td colspan="4" class="text-center py-3 text-muted">Loading project records...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top w-100">
        <div class="text-muted won-table-fontSize" id="won-table-pagination-info">Showing 0-0 of 0</div>
        <nav aria-label="Won projects internal navigation">
            <ul class="pagination pagination-sm pagination-sm-override m-0" id="won-table-pagination-buttons"></ul>
        </nav>
    </div>
</div>