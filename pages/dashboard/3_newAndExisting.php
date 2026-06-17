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

    <div class="flex-grow-1 d-flex flex-column justify-content-center position-relative" style="min-height: 220px;">
        <div style="position: relative; height: 290px; width: 100%;">
            <canvas id="teamWonChart"></canvas>
            <div class="position-absolute top-50 start-50 translate-middle text-center" style="pointer-events: none;">
                <span class="d-block fw-bold text-dark" id="donutTotalCount" style="font-size: 2rem; line-height: 1;">0</span>
                <span class="text-uppercase text-muted tracking-wide" style="font-size: 0.90rem; font-weight: 700;">Total Won</span>
            </div>
        </div>

        <div class="donut-metric-footer mt-auto pt-3">
            <div class="w-100 d-flex justify-content-between align-items-center" style="gap: 4px;">
                
                <div class="text-center flex-grow-1 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <div style="width: 12px; height: 7px; background-color: #30885f; border-radius: 2px;"></div>
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Makati</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <h6 class="fw-bold m-0 text-dark small" id="makatiCount">0</h6>
                        <span class="text-muted fw-bold" id="makatiPercent" style="font-size: 0.65rem;">0.0%</span>
                    </div>
                </div>

                <div class="text-center flex-grow-1 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <div style="width: 12px; height: 7px; background-color: #0d6efd; border-radius: 2px;"></div>
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">QC</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <h6 class="fw-bold m-0 text-dark small" id="qcCount">0</h6>
                        <span class="text-muted fw-bold" id="qcPercent" style="font-size: 0.65rem;">0.0%</span>
                    </div>
                </div>

                <div class="text-center flex-grow-1">
                    <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                        <div style="width: 12px; height: 7px; background-color: #ffc107; border-radius: 2px;"></div>
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