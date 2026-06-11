<?php
// Get the current month numeric index format with leading zero (e.g., "05" for May, "06" for June)
$currentMonthIndex = date('m');
?>

<div class="main-content-card p-4 shadow-sm text-center d-flex flex-column h-100 w-100">
    <div class="w-100 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">KPI Sales Meter</h6>
            <select class="form-select form-select-sm w-auto py-0 px-2 text-muted fw-medium border-secondary-subtle shadow-sm style-select" id="kpiMonthFilter" style="font-size: 0.75rem; height: 26px; border-radius: 6px;">
                <option value="all">All Time</option>
                <option value="current" selected>Current Month</option> 
                <option value="01" <?php echo ($currentMonthIndex === '01') ? 'selected' : ''; ?>>January</option>
                <option value="02" <?php echo ($currentMonthIndex === '02') ? 'selected' : ''; ?>>February</option>
                <option value="03" <?php echo ($currentMonthIndex === '03') ? 'selected' : ''; ?>>March</option>
                <option value="04" <?php echo ($currentMonthIndex === '04') ? 'selected' : ''; ?>>April</option>
                <option value="05" <?php echo ($currentMonthIndex === '05') ? 'selected' : ''; ?>>May</option>
                <option value="06" <?php echo ($currentMonthIndex === '06') ? 'selected' : ''; ?>>June</option>
                <option value="07" <?php echo ($currentMonthIndex === '07') ? 'selected' : ''; ?>>July</option>
                <option value="08" <?php echo ($currentMonthIndex === '08') ? 'selected' : ''; ?>>August</option>
                <option value="09" <?php echo ($currentMonthIndex === '09') ? 'selected' : ''; ?>>September</option>
                <option value="10" <?php echo ($currentMonthIndex === '10') ? 'selected' : ''; ?>>October</option>
                <option value="11" <?php echo ($currentMonthIndex === '11') ? 'selected' : ''; ?>>November</option>
                <option value="12" <?php echo ($currentMonthIndex === '12') ? 'selected' : ''; ?>>December</option>
            </select>
        </div>
        <hr class="my-2 text-black-50">
    </div>
    
    <div class="flex-grow-1 d-flex flex-column justify-content-center" style="min-height: 220px;">
        <div class="gauge-outer-container" style="position: relative; width: 240px; height: 120px; margin: 10px auto 10px auto;">
            <div class="gauge-target-line"></div>
            <div class="gauge-wrapper" style="position: relative; width: 240px; height: 120px; overflow: hidden; margin: 0;">
                <div class="gauge-body"></div>
                <div class="gauge-needle"></div>
                <div class="gauge-center-cap"></div>
            </div>
        </div>
        
        <div class="gauge-value-display fw-bold text-dark mt-2" style="font-size: 1.25rem;">0.0%</div>
        <div class="text-muted small fw-medium mt-1" id="metricSubtextDisplay">
            Calculating real-time sales volume values...
        </div>
    </div>
</div>