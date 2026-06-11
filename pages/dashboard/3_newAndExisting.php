<?php
// Get the current month numeric index format with leading zero
$currentMonthIndex = date('m');
?>

<div class="main-content-card p-4 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">New and Existing</h6>
        </div>
        <hr class="my-2 text-black-50">
    </div>

    <div class="flex-grow-1 d-flex flex-column justify-content-center" style="min-height: 220px;">
        
        <div class="donut-outer-wrapper my-auto">
            <div class="donut-svg-container">
                <svg viewBox="0 0 100 100" class="donut-svg-matrix">
                    <circle class="donut-track-bg" cx="50" cy="50" r="40"></circle>
                    
                    <circle id="donut-track-existing" class="donut-segment-track stroke-existing" cx="50" cy="50" r="40"></circle>
                    
                    <circle id="donut-track-new" class="donut-segment-track stroke-new" cx="50" cy="50" r="40"></circle>
                </svg>
                
                <div class="donut-center-metric text-center">
                    <span class="donut-total-count d-block fw-bold" id="donutTotalCount">0</span>
                    <span class="text-uppercase text-muted tracking-wide" style="font-size: 0.55rem; font-weight: 700;">Total</span>
                </div>
            </div>
        </div>

        <div class="donut-metric-footer mt-auto">
            <div class="donut-footer-split border-end text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="legend-square bg-cat-new"></div>
                    <h5 class="fw-bold m-0 text-dark" id="footerNewCount">0</h5>
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.60rem;">New</span>
                    <span class="text-muted small fw-bold ms-1" id="footerNewPercent">0.0%</span>
                </div>
            </div>

            <div class="donut-footer-split text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="legend-square bg-cat-existing"></div>
                    <h5 class="fw-bold m-0 text-dark" id="footerExistingCount">0</h5>
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.60rem;">Existing</span>
                    <span class="text-muted small fw-bold ms-1" id="footerExistingPercent">0.0%</span>
                </div>
            </div>
        </div>
        
    </div>
</div>