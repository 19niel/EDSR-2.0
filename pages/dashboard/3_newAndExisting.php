<?php
$currentMonthIndex = date('m');
?>

<div class="main-content-card p-2 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-1">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0" style="font-size: 0.68rem;">Projects Won By Team</h6>
        </div>
        <hr class="my-1 text-black-50" style="opacity: 0.15;">
    </div>

    <div class="flex-grow-1 d-flex flex-column justify-content-center position-relative" style="min-height: 120px; height: 120px;">
        <div style="position: relative; height: 120px; width: 100%;">
            <canvas id="teamWonChart"></canvas>
            <div class="position-absolute top-50 start-50 translate-middle text-center" style="pointer-events: none;">
                <span class="d-block fw-bold text-dark" id="donutTotalCount" style="font-size: 1.25rem; line-height: 2;">0</span>
                <span class="text-uppercase text-muted tracking-wide" style="font-size: 0.55rem; font-weight: 700;">Total Won</span>
            </div>
        </div>
    </div>

    <div class="donut-metric-footer mt-1 pt-1 border-top">
        <div class="w-100 d-flex align-items-center justify-content-between gap-1">
            
            <div class="text-center flex-grow-1">
                <div class="d-flex align-items-center justify-content-center gap-1 mb-0.5">
                    <div style="width: 7px; height: 7px; background-color: #30885f; border-radius: 2px;"></div>
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.58rem;">Makati</span>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-1" style="line-height: 1;">
                    <h6 class="fw-bold m-0 text-dark small" id=\"makatiCount\" style=\"font-size: 0.72rem;\">0</h6>
                    <span class="text-muted fw-bold" id="makatiPercent" style="font-size: 0.58rem;">0.0%</span>
                </div>
            </div>

            <div class="text-center flex-grow-1 border-start">
                <div class="d-flex align-items-center justify-content-center gap-1 mb-0.5">
                    <div style="width: 7px; height: 7px; background-color: #0d6efd; border-radius: 2px;"></div>
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.58rem;">QC/Ortigas</span>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <h6 class="fw-bold m-0 text-dark small" id="qcCount" style="font-size: 0.72rem;">0</h6>
                    <span class="text-muted fw-bold" id="qcPercent" style="font-size: 0.58rem;">0.0%</span>
                </div>
            </div>

            <div class="text-center flex-grow-1 border-start">
                <div class="d-flex align-items-center justify-content-center gap-1 mb-0.5">
                    <div style="width: 7px; height: 7px; background-color: #ffc107; border-radius: 2px;"></div>
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.58rem;">Manila</span>
                </div>
                <div class="d-flex align-items-center justify-content-center gap-1" style="line-height: 1;">
                    <h6 class="fw-bold m-0 text-dark small" id="manilaCount" style="font-size: 0.72rem;">0</h6>
                    <span class="text-muted fw-bold" id="manilaPercent" style="font-size: 0.58rem;">0.0%</span>
                </div>
            </div>

        </div>
    </div>
</div>