<?php
$currentMonthIndex = date('m');
?>

<div class="main-content-card p-4 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">Pipeline Funnel</h6>
            <span class="badge text-muted fw-medium border-secondary-subtle bg-white px-2 shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.70rem; height: 26px; border-radius: 6px; border: 1px solid var(--border-color);">
                <i class="fa-solid fa-circle-dot text-success me-1 small"></i> Live
            </span>
        </div>
        <hr class="my-2 text-black-50">
    </div>

    <div class="pipeline-container flex-grow-1 d-flex flex-column justify-content-center position-relative" style="min-height: 250px;">
        <!-- ApexCharts Funnel Container -->
        <div id="pipelineFunnelChart" class="w-100 h-100"></div>
    </div>

    <!-- Funnel Status Breakdown Grid -->
    <div class="mt-3 border-top pt-3">
        <div class="row g-2 text-center">
            <div class="col">
                <div class="small fw-semibold text-primary" style="font-size: 0.72rem;">Qualified</div>
                <div class="fw-bold mt-1" style="font-size: 0.8rem; color: var(--text-primary);" id="funnelQty-345">0 Accs</div>
                <div class="text-muted" style="font-size: 0.72rem;" id="funnelVal-345">₱0.00</div>
            </div>
            <div class="col border-start">
                <div class="small fw-semibold text-info" style="font-size: 0.72rem;">Negotiation</div>
                <div class="fw-bold mt-1" style="font-size: 0.8rem; color: var(--text-primary);" id="funnelQty-346">0 Accs</div>
                <div class="text-muted" style="font-size: 0.72rem;" id="funnelVal-346">₱0.00</div>
            </div>
            <div class="col border-start">
                <div class="small fw-semibold text-success" style="font-size: 0.72rem;">Won</div>
                <div class="fw-bold mt-1" style="font-size: 0.8rem; color: var(--text-primary);" id="funnelQty-230">0 Accs</div>
                <div class="text-muted" style="font-size: 0.72rem;" id="funnelVal-230">₱0.00</div>
            </div>
            <div class="col border-start">
                <div class="small fw-semibold text-danger" style="font-size: 0.72rem;">Lost</div>
                <div class="fw-bold mt-1" style="font-size: 0.8rem; color: var(--text-primary);" id="funnelQty-348">0 Accs</div>
                <div class="text-muted" style="font-size: 0.72rem;" id="funnelVal-348">₱0.00</div>
            </div>
            <div class="col border-start">
                <div class="small fw-semibold text-secondary" style="font-size: 0.72rem;">Dropped</div>
                <div class="fw-bold mt-1" style="font-size: 0.8rem; color: var(--text-primary);" id="funnelQty-349">0 Accs</div>
                <div class="text-muted" style="font-size: 0.72rem;" id="funnelVal-349">₱0.00</div>
            </div>
        </div>
    </div>
</div>