<?php
$currentMonthIndex = date('m');
?>

<div class="main-content-card p-2 shadow-sm d-flex flex-column h-100 w-100" style="container-type: inline-size;">
    <div class="w-100 mb-1">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0" style="font-size: 0.68rem;">Pipeline Funnel</h6>
            <span class="badge text-muted fw-medium border-secondary-subtle bg-white px-2 shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.62rem; height: 20px; border-radius: 50px; border: 1px solid var(--border-color);">
                <i class="fa-solid fa-circle-dot text-success me-1" style="font-size: 0.45rem;"></i> Live
            </span>
        </div>
        <hr class="my-1 text-black-50" style="opacity: 0.15;">
    </div>

    <div class="pipeline-container flex-grow-1 d-flex flex-column justify-content-center position-relative" style="min-height: 120px; height: 120px;">
        <div id="pipelineFunnelChart" class="w-100 h-100"></div>
    </div>

    <div class="mt-1 border-top pt-1">
        <div class="row g-1 text-center">
            <div class="col" style="line-height: 1.1;">
                <div class="fw-semibold text-primary" style="font-size: 0.62rem;">Qualified</div>
                <div class="fw-bold mt-0.5" id="funnelQty-345" style="font-size: 0.72rem;">0 Accs</div>
                <div class="text-muted" id="funnelVal-345" style="font-size: 0.58rem;">₱0.00</div>
            </div>
            <div class="col border-start" style="line-height: 1.1;">
                <div class="fw-semibold text-info" style="font-size: 0.62rem;">Negotiation</div>
                <div class="fw-bold mt-0.5" id="funnelQty-346" style="font-size: 0.72rem;">0 Accs</div>
                <div class="text-muted" id="funnelVal-346" style="font-size: 0.58rem;">₱0.00</div>
            </div>
            <div class="col border-start" style="line-height: 1.1;">
                <div class="fw-semibold text-success" style="font-size: 0.62rem;">Won</div>
                <div class="fw-bold mt-0.5" id="funnelQty-230" style="font-size: 0.72rem;">0 Accs</div>
                <div class="text-muted" id="funnelVal-230" style="font-size: 0.58rem;">₱0.00</div>
            </div>
            <div class="col border-start" style="line-height: 1.1;">
                <div class="fw-semibold text-danger" style="font-size: 0.62rem;">Lost</div>
                <div class="fw-bold mt-0.5" id="funnelQty-348" style="font-size: 0.72rem;">0 Accs</div>
                <div class="text-muted" id="funnelVal-348" style="font-size: 0.58rem;">₱0.00</div>
            </div>
            <div class="col border-start" style="line-height: 1.1;">
                <div class="fw-semibold text-secondary" style="font-size: 0.62rem;">Dropped</div>
                <div class="fw-bold mt-0.5" id="funnelQty-349" style="font-size: 0.72rem;">0 Accs</div>
                <div class="text-muted" id="funnelVal-349" style="font-size: 0.58rem;">₱0.00</div>
            </div>
        </div>
    </div>
</div>