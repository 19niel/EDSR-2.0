document.addEventListener("DOMContentLoaded", function () {
    const monthFilterEl = document.getElementById('kpiMonthFilter');

    // Perform an immediate fetch execution as soon as the DOM finishes building
    updateSalesMeterRealtime();

    // Long-polling interval loop to check for database updates every 5 seconds
    setInterval(updateSalesMeterRealtime, 5000);

    // 🔄 TRIGGER IMMEDIATE DATABASE RE-QUERY WHEN DROPDOWN VALUE CHANGES
    if (monthFilterEl) {
        monthFilterEl.addEventListener('change', function () {
            updateSalesMeterRealtime();
        });
    }
});

function formatCurrencyShorthand(value) {
    const num = parseFloat(value);
    if (isNaN(num) || num <= 0) return "0";
    if (num >= 1000000) {
        return (num / 1000000).toFixed(num % 1000000 === 0 ? 0 : 1) + 'M';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(num % 1000 === 0 ? 0 : 1) + 'K';
    }
    return num.toString();
}

function updateSalesMeterRealtime() {
    const targetGoal = window.dashboardConfig && window.dashboardConfig.salesTarget 
                       ? window.dashboardConfig.salesTarget 
                       : 5000000.00;

    // 🎯 Dynamically reads whatever option PHP selected automatically on page boot
    const monthFilterEl = document.getElementById('kpiMonthFilter');
    const selectedPeriod = monthFilterEl ? monthFilterEl.value : 'current';

    fetch(`../php/get_1KpiSalesTotal.php?period=${selectedPeriod}`)
        .then(response => response.json())
        .then(res => {
            if (res && res.success) {
                const totalSales = res.totalSales;
                
                let percentage = (totalSales / targetGoal) * 100;
                let cappedPercentage = Math.min(percentage, 200);

                let needleRotation = -90 + (cappedPercentage * 1.125);
                let fillSweepDegrees = cappedPercentage * 1.125; 

                const bodyEl = document.querySelector('.gauge-body');
                const needleEl = document.querySelector('.gauge-needle');
                const targetLineEl = document.querySelector('.gauge-target-line');
                const displayValueEl = document.querySelector('.gauge-value-display');
                const textMetricEl = document.getElementById('metricSubtextDisplay');

                if (!bodyEl || !needleEl) return;

                if (targetLineEl) {
                    const dynamicLabelText = formatCurrencyShorthand(targetGoal);
                    targetLineEl.setAttribute('data-target', dynamicLabelText);
                }

                bodyEl.style.setProperty('--body-fill-sweep', `${fillSweepDegrees}deg`);
                needleEl.style.transform = `translateX(-50%) rotate(${needleRotation}deg)`;
                
                if (displayValueEl) {
                    displayValueEl.innerHTML = `${percentage.toFixed(1)}%`;
                }
                if (textMetricEl) {
                    textMetricEl.innerHTML = `Won Amount: <strong>₱${totalSales.toLocaleString('en-US', {minimumFractionDigits: 2})}</strong> / ₱${targetGoal.toLocaleString('en-US', {maximumFractionDigits: 0})}`;
                }

                if (displayValueEl) displayValueEl.className = "gauge-value-display";

                if (percentage < 45.00) {
                    bodyEl.style.setProperty('--bar-color', '#dc3545');
                    if (displayValueEl) displayValueEl.classList.add('text-state-low');
                } else if (percentage >= 45.00 && percentage < 100.00) {
                    bodyEl.style.setProperty('--bar-color', '#fd7e14');
                    if (displayValueEl) displayValueEl.classList.add('text-state-mid');
                } else {
                    bodyEl.style.setProperty('--bar-color', '#198754');
                    if (displayValueEl) displayValueEl.classList.add('text-state-high');
                }
            }
        })
        .catch(err => console.error("Error updating real-time KPI sales tracking array:", err));
}