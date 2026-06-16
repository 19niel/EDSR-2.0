<?php
$currentMonthIndex = date('m');
?>

<div class="main-content-card p-4 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">Projects Won By Team</h6>
        </div>
        <hr class="my-2 text-black-50">
    </div>

    <div class="flex-grow-1 d-flex flex-column justify-content-center" style="min-height: 220px;">
        
        <div class="donut-outer-wrapper my-auto">
            <div class="donut-svg-container">
                <svg viewBox="0 0 100 100" class="donut-svg-matrix">
                    <circle class="donut-track-bg" cx="50" cy="50" r="40"></circle>
                    
                    <circle id="donut-track-makati" class="donut-segment-track" stroke="#30885f" cx="50" cy="50" r="40"></circle>
                    
                    <circle id="donut-track-qc" class="donut-segment-track" stroke="#0d6efd" cx="50" cy="50" r="40"></circle>
                    
                    <circle id="donut-track-manila" class="donut-segment-track" stroke="#ffc107" cx="50" cy="50" r="40"></circle>
                </svg>
                
                <div class="donut-center-metric text-center">
                    <span class="donut-total-count d-block fw-bold" id="donutTotalCount">0</span>
                    <span class="text-uppercase text-muted tracking-wide" style="font-size: 0.55rem; font-weight: 700;">Total Won</span>
                </div>
            </div>
        </div>

        <div class="donut-metric-footer mt-auto pt-3">
            <div class="w-100 d-flex justify-content-between align-items-center" style="gap: 4px;">
                
                <div class="text-center flex-grow-1 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <div style="width: 7px; height: 7px; background-color: #30885f; border-radius: 2px;"></div>
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Makati</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <h6 class="fw-bold m-0 text-dark small" id="makatiCount">0</h6>
                        <span class="text-muted fw-bold" id="makatiPercent" style="font-size: 0.65rem;">0.0%</span>
                    </div>
                </div>

                <div class="text-center flex-grow-1 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <div style="width: 7px; height: 7px; background-color: #0d6efd; border-radius: 2px;"></div>
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">QC</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <h6 class="fw-bold m-0 text-dark small" id="qcCount">0</h6>
                        <span class="text-muted fw-bold" id="qcPercent" style="font-size: 0.65rem;">0.0%</span>
                    </div>
                </div>

                <div class="text-center flex-grow-1">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <div style="width: 7px; height: 7px; background-color: #ffc107; border-radius: 2px;"></div>
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Manila</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <h6 class="fw-bold m-0 text-dark small" id="manilaCount">0</h6>
                        <span class="text-muted fw-bold" id="manilaPercent" style="font-size: 0.65rem;">0.0%</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>