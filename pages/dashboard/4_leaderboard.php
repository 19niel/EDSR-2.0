<?php
// E-DSR Dashboard Panel - Top Sales Executive Layout
?>

<div class="main-content-card p-2 shadow-sm d-flex flex-column h-100 w-100">
    <div class="w-100 mb-1">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0" style="font-size: 0.68rem;">
                <i class="fa-solid fa-trophy me-1 text-warning"></i>Top 5 Sales (Qualified and Nego)
            </h6>
            <span class="badge text-muted border px-2 py-0.5 shadow-sm bg-white" style="font-size: 0.58rem; font-weight: 600; border-color: var(--border-color) !important;">Rankings</span>
        </div>
        <hr class="my-1 text-black-50" style="opacity: 0.15;">
    </div>

    <!-- Static container at 120px height to stay consistent with other panels -->
    <div class="d-flex flex-column justify-content-center flex-grow-1 position-relative" style="min-height: 120px; height: 120px;">
        <canvas id="leaderboardChart"></canvas>
    </div>
</div>