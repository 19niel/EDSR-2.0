<?php
$currentMonthIndex = date('m');
?>

<div class="col-12 col-md-6 col-lg-4"> 
    <div class="main-content-card p-4 shadow-sm">
        <div class="w-100 mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">Pipeline Funnel</h6>
                <span class="badge text-muted fw-medium border-secondary-subtle bg-white px-2 shadow-sm d-flex align-items-center justify-content-center" style=\"font-size: 0.70rem; height: 26px; border-radius: 6px; border: 1px solid #dee2e6;\">
                    <i class="fa-solid fa-circle-dot text-success me-1 small"></i> Live
                </span>
            </div>
            <hr class="my-2 text-black-50">
        </div>

        <div class="pipeline-container mt-2">
            
            <div class="funnel-row-wrapper">
                <div class="progress funnel-bar-track shadow-sm">
                    <div class="funnel-text-overlay" id="label-345">0 Accs | ₱0.00</div>
                    <div id="bar-345" class="progress-bar bg-funnel-qualified" style="width: 0%"></div>
                </div>
            </div>

            <div class="funnel-row-wrapper">
                <div class="progress funnel-bar-track shadow-sm">
                    <div class="funnel-text-overlay" id="label-346">0 Accs | ₱0.00</div>
                    <div id="bar-346" class="progress-bar bg-funnel-negotiation" style="width: 0%"></div>
                </div>
            </div>

            <div class="funnel-row-wrapper">
                <div class="progress funnel-bar-track shadow-sm">
                    <div class="funnel-text-overlay" id="label-230">0 Accs | ₱0.00</div>
                    <div id="bar-230" class="progress-bar bg-funnel-won" style="width: 0%"></div>
                </div>
            </div>

            <div class="funnel-row-wrapper">
                <div class="progress funnel-bar-track shadow-sm">
                    <div class="funnel-text-overlay" id="label-348">0 Accs | ₱0.00</div>
                    <div id="bar-348" class="progress-bar bg-funnel-lost" style="width: 0%"></div>
                </div>
            </div>

            <div class="funnel-row-wrapper">
                <div class="progress funnel-bar-track shadow-sm">
                    <div class="funnel-text-overlay" id="label-349">0 Accs | ₱0.00</div>
                    <div id="bar-349" class="progress-bar bg-funnel-dropped" style="width: 0%"></div>
                </div>
            </div>

        </div>

        <div class="funnel-legend-container mt-3" style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">
            <div class="d-flex align-items-center gap-1"><div style="width:8px;height:8px;background:#0d6efd;"></div> Quali</div>
            <div class="d-flex align-items-center gap-1"><div style="width:8px;height:8px;background:#0dcaf0;"></div> Nego</div>
            <div class="d-flex align-items-center gap-1"><div style="width:8px;height:8px;background:#198754;"></div> Won</div>
            <div class="d-flex align-items-center gap-1"><div style="width:8px;height:8px;background:#dc3545;"></div> Lost</div>
            <div class="d-flex align-items-center gap-1"><div style="width:8px;height:8px;background:#6c757d;"></div> Drop</div>
        </div>
    </div>
</div>