<style>
/* Aging Projects Paginated Compact Dashboard Table Rules */
.aging-table-fontSize {
    font-size: 0.70rem !important;
}
.aging-table-container {
    min-height: 165px; /* Keeps card height perfectly balanced with Cell 5 */
}
.text-ellipsis-aging {
    max-width: 145px; /* Balanced limits to keep text single-lined */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.pagination-sm-override-aging .page-link {
    padding: 0.15rem 0.4rem;
    font-size: 0.65rem;
    color: #dc3545; /* Crimson accent styling to denote warning/aging risk */
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

/* 🎯 Interactive Row Hover Highlights for Aging/Attention Required items */
#aging-projects-table-body tr {
    transition: background-color 0.15s ease-in-out;
}
#aging-projects-table-body tr:hover {
    background-color: rgba(220, 53, 69, 0.06) !important;
}
#aging-projects-table-body tr td a {
    padding: 2px 0;
}
</style>

<div class="main-content-card p-3 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">
                <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Aging Accounts
            </h6>
            <span class="badge bg-danger-subtle text-danger border px-2 py-0.5" style="font-size: 0.65rem; font-weight: 600; border-radius: 4px;">Attention Required</span>
        </div>
        <hr class="my-1.5 text-black-50">
    </div>

    <div class="table-responsive aging-table-container flex-grow-1">
        <table class="table table-sm table-hover align-middle aging-table-fontSize mb-0">
            <thead class="table-light text-secondary">
                <tr>
                    <th style="width: 25%;">LID</th>
                    <th style="width: 50%;">Client Name</th>
                    <th class="text-end" style="width: 25%;">Last Update</th>
                </tr>
            </thead>
            <tbody id="aging-projects-table-body">
                <tr>
                    <td colspan="3" class="text-center py-3 text-muted">Loading aging accounts...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
        <div class="text-muted aging-table-fontSize" id="aging-table-pagination-info">
            Showing 0-0 of 0
        </div>
        <nav aria-label="Aging projects internal navigation">
            <ul class="pagination pagination-sm pagination-sm-override-aging m-0" id="aging-table-pagination-buttons">
            </ul>
        </nav>
    </div>
</div>