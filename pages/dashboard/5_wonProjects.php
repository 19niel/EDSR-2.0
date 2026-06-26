<?php
// E-DSR Dashboard Panel - Recently Won Projects Dynamic Auto-Scaling Layout
?>

<style>
.won-table-fontSize { 
    font-size: 0.65rem !important; 
}
/* 🎯 SCROLL FIX: Enforced clean scaling to guarantee the layout matches the template footprint */
.won-table-container { 
    height: 165px !important; 
    max-height: 165px !important; 
    overflow-y: auto; 
    overflow-x: hidden; 
}
/* 🎯 DATA RENDERING FIX: Standardized single-line clipping classes to keep row strings visible */
.text-ellipsis-won {
    display: block;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.won-compact-head th {
    padding-top: 0.35rem !important;
    padding-bottom: 0.35rem !important;
    font-size: 0.62rem !important;
    font-weight: 700;
}
.won-table-fontSize tbody td {
    padding-top: 0.3rem !important;
    padding-bottom: 0.3rem !important;
}
.pagination-sm-override .page-link { 
    padding: 0.05rem 0.25rem !important; 
    font-size: 0.58rem !important; 
    color: var(--success, #198754); 
    background-color: var(--surface);
    border-color: var(--border-color);
}
.pagination-sm-override .page-item.active .page-link { 
    background-color: var(--success, #198754); 
    border-color: var(--success, #198754); 
    color: #fff; 
}
</style>

<div class="main-content-card p-2 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-1">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-success tracking-wider fw-bold small m-0" style="font-size: 0.68rem;">
                <i class="fa-solid fa-circle-check me-1"></i>Recently Won Projects
            </h6>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5" style="font-size: 0.55rem; font-weight: 600; border-radius: 4px;">Live Table</span>
        </div>
        <hr class="my-1 text-black-50" style="opacity: 0.15;">
    </div>

    <!-- 🎯 SAFE COLUMN MATRIX: Allocates clean block percentages so AJAX rows render perfectly -->
    <div class="table-responsive won-table-container flex-grow-1">
        <table class="table table-sm table-hover align-middle won-table-fontSize mb-0" style="table-layout: fixed; width: 100%;">
            <thead class="table-light text-secondary sticky-top won-compact-head">
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

    <div class="d-flex justify-content-between align-items-center mt-1 pt-1 border-top w-100">
        <div class="text-muted" id="won-table-pagination-info" style="font-size: 0.58rem !important; font-weight: 500;">Showing 0-0 of 0</div>
        <nav aria-label="Won projects internal navigation">
            <ul class="pagination pagination-sm pagination-sm-override m-0" id="won-table-pagination-buttons"></ul>
        </nav>
    </div>
</div>