<style>
/* Aging Projects Paginated Compact Dashboard Table Rules */
.aging-table-fontSize {
    font-size: 0.65rem !important;
}
/* Standardized height matching the recently won projects container */
.aging-table-container {
    height: 140px !important;
    max-height: 140px !important;
    overflow-y: auto;
    overflow-x: hidden;
}
/* FIXED: Dropped hardcoded max-width for fluid expansion */
.text-ellipsis-aging {
    display: block;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.aging-compact-head th {
    padding-top: 0.35rem !important;
    padding-bottom: 0.35rem !important;
    font-size: 0.62rem !important;
    font-weight: 700;
}
.aging-table-fontSize tbody td {
    padding-top: 0.3rem !important;
    padding-bottom: 0.3rem !important;
}
.pagination-sm-override-aging .page-link {
    padding: 0.05rem 0.25rem !important;
    font-size: 0.58rem !important;
    color: #dc3545;
    border-color: #dee2e6;
}
.pagination-sm-override-aging .page-item.active .page-link {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}
.pagination-sm-override-aging .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

#aging-projects-table-body tr {
    transition: background-color 0.15s ease;
}
#aging-projects-table-body tr:hover {
    background-color: rgba(220, 53, 69, 0.05) !important;
}
</style>

<div class="card h-100 shadow-sm border-0 dynamic-panel-card p-3 d-flex flex-column">
    <div class="w-100">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0 d-flex align-items-center fw-bold text-dark text-uppercase tracking-wider" style="font-size: 0.8rem;">
                <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>
                <span id="aging-title-text">Aging Accounts</span>
            </h6>
            <span class="badge bg-danger-subtle text-danger border px-2 py-0.5" style="font-size: 0.65rem; font-weight: 600; border-radius: 4px;">Attention Required</span>
        </div>
        <hr class="my-1 text-black-50" style="opacity: 0.15;">
    </div>

    <div class="table-responsive aging-table-container flex-grow-1">
        <table class="table table-sm table-hover align-middle aging-table-fontSize mb-0" style="table-layout: fixed; width: 100%;">
            <thead class="table-light text-secondary sticky-top aging-compact-head">
                <tr>
                    <th style="width: 25%;">LID</th>
                    <th style="width: 50%;">Client Name</th>
                    <th class="text-end" style="width: 25%;">Last Update</th>
                </tr>
            </thead>
            <tbody id="aging-projects-table-body">
                <tr>
                    <td colspan="3" class="text-center py-3 text-muted">
                        <div class="spinner-border spinner-border-sm text-danger me-2" role="status"></div>Loading aging accounts...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-1 pt-1 border-top w-100">
        <div class="text-muted" id="aging-table-pagination-info" style="font-size: 0.58rem !important; font-weight: 500;">
            Showing 0-0 of 0
        </div>
        <nav aria-label="Aging projects internal navigation">
            <ul class="pagination pagination-sm pagination-sm-override-aging mb-0" id="aging-table-pagination-controls">
                </ul>
        </nav>
    </div>
</div>