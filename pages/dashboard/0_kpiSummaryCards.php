<!-- Row with tight grid gap and compact bottom margin -->
<div class="row g-2 mb-2">
    
    <!-- Card 1: Total Won Volume -->
    <div class="col-12 col-sm-6 col-xl-3">
        <!-- Kept py-1.5 to guarantee the card height stays short -->
        <div class="edsr-card py-1.5 px-2 shadow-sm d-flex align-items-center h-100" style="min-height: 46px;">
            <!-- Icon container -->
            <div class="rounded-circle d-flex justify-content-center align-items-center bg-success-subtle flex-shrink-0" style="width: 32px; height: 32px;">
                <i class="fa-solid fa-peso-sign text-success" style="font-size: 0.75rem;"></i>
            </div>
            <!-- FIXED SPACING: Added ms-2 to push the text layout over cleanly without adding height -->
            <div class="overflow-hidden ms-2" style="line-height: 1.1;">
                <p class="text-muted fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.58rem;">Total Won</p>
                <span class="fw-bold text-dark text-truncate d-block" id="summaryTotalWon" style="font-size: 0.90rem;">₱0.00</span>
            </div>
        </div>
    </div>
    
    <!-- Card 2: Pipeline Value -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="edsr-card py-1.5 px-2 shadow-sm d-flex align-items-center h-100" style="min-height: 46px;">
            <div class="rounded-circle d-flex justify-content-center align-items-center bg-info-subtle flex-shrink-0" style="width: 32px; height: 32px;">
                <i class="fa-solid fa-filter text-info" style="font-size: 0.75rem;"></i>
            </div>
            <div class="overflow-hidden ms-2" style="line-height: 1.1;">
                <p class="text-muted fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.58rem;">Pipeline Value</p>
                <span class="fw-bold text-dark text-truncate d-block" id="summaryPipeline" style="font-size: 0.90rem;">₱0.00</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Active Accounts -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="edsr-card py-1.5 px-2 shadow-sm d-flex align-items-center h-100" style="min-height: 46px;">
            <div class="rounded-circle d-flex justify-content-center align-items-center bg-primary-subtle flex-shrink-0" style="width: 32px; height: 32px;">
                <i class="fa-solid fa-users text-primary" style="font-size: 0.75rem;"></i>
            </div>
            <div class="overflow-hidden ms-2" style="line-height: 1.1;">
                <p class="text-muted fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.58rem;">Active Accs</p>
                <span class="fw-bold text-dark text-truncate d-block" id="summaryActiveAccs" style="font-size: 0.90rem;">0</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Win Rate -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="edsr-card py-1.5 px-2 shadow-sm d-flex align-items-center h-100" style="min-height: 46px;">
            <div class="rounded-circle d-flex justify-content-center align-items-center bg-warning-subtle flex-shrink-0" style="width: 32px; height: 32px;">
                <i class="fa-solid fa-chart-line text-warning" style="font-size: 0.75rem;"></i>
            </div>
            <div class="overflow-hidden ms-2" style="line-height: 1.1;">
                <p class="text-muted fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.58rem;">Win Rate</p>
                <span class="fw-bold text-dark text-truncate d-block" id="summaryWinRate" style="font-size: 0.90rem;">0.0%</span>
            </div>
        </div>
    </div>
    
</div>