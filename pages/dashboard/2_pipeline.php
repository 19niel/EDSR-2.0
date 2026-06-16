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
</div>