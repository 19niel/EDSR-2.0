<?php
// Get the current month numeric index format with leading zero (e.g., "05" for May, "06" for June)
$currentMonthIndex = date('m');
?>

<div class="main-content-card p-4 shadow-sm text-center d-flex flex-column h-100 w-100" style="container-type: inline-size;">
    <div class="w-100 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">EMR Sales Meter</h6>
            <select class="form-select form-select-sm w-auto py-0 px-2 text-muted fw-medium border-secondary-subtle shadow-sm style-select small" id="kpiMonthFilter" style="font-size: 0.75rem; height: 28px; border-radius: 6px;" onchange="updateSalesMeterRealtime()">
                <option value="all">All Time</option>
                <option value="current" selected>Current Month</option> 
                
                <optgroup label="Months">
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
                </optgroup>

                <optgroup label="Quarters">
                    <option value="Q1">Q1 (Jan-Mar)</option>
                    <option value="Q2">Q2 (Apr-Jun)</option>
                    <option value="Q3">Q3 (Jul-Sep)</option>
                    <option value="Q4">Q4 (Oct-Dec)</option>
                </optgroup>

                <option value="custom">Custom Date Range</option>
            </select>
        </div>

        <div id="customDateRange" class="mt-2" style="display:none; text-align: right;">
            <input type="date" id="dateFrom" class="form-control form-control-sm d-inline-block w-auto" style="font-size: 0.7rem; height: 28px;">
            <input type="date" id="dateTo" class="form-control form-control-sm d-inline-block w-auto" style="font-size: 0.7rem; height: 28px;">
            <button class="btn btn-sm btn-primary py-0" style="font-size: 0.7rem; height: 28px;" onclick="updateSalesMeterRealtime()">Go</button>
        </div>

        <hr class="my-2 text-black-50">
    </div>
    
    <div class="flex-grow-1 d-flex flex-column justify-content-center w-100 mx-auto" style="max-width: 400px;">
        <div class="gauge-outer-container">
            <div class="gauge-target-line"></div>
            <div class="gauge-wrapper">
                <div class="gauge-body"></div>
                <div class="gauge-needle"></div>
                <div class="gauge-center-cap"></div>
            </div>
        </div>
        
        <div class="gauge-value-display fw-bold text-dark mt-2">0.0%</div>
        <div class="text-muted  fw-medium mt-1 small" id="metricSubtextDisplay">
            Calculating real-time sales volume values...
        </div>
    </div>
</div>