/**
 * E-DSR Dashboard - Account Categories Split-Direction Donut Engine
 */

$(document).ready(function () {
    
    function fetchAccountCategoryMetrics(selectedMonth = 'current') {
        console.log(`%c[SQL Debug] Fetching account categories for filter: ${selectedMonth}`, 'color: #0d6efd; font-weight: bold;');
        
        $.ajax({
            url: '../php/get_3AccountStatusTotal.php',
            type: 'GET',
            data: { month: selectedMonth },
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    renderDonutMetrics(response);
                }
            },
            error: function (xhr, status, error) {
                console.error("[SQL Debug] Critical Network Failure reaching the PHP file:", error);
            }
        });
    }

    function renderDonutMetrics(data) {
        const existingCount = parseInt(data.existing) || 0;
        const newCount = parseInt(data.new) || 0;
        const totalCount = parseInt(data.total) || 0;

        // Calculate direct percentages based on total covered data
        const existingPercent = totalCount > 0 ? (existingCount / totalCount) * 100 : 0;
        const newPercent = totalCount > 0 ? (newCount / totalCount) * 100 : 0;

        // 1. Inject textual counter nodes
        $('#donutTotalCount').text(totalCount);
        $('#footerExistingCount').text(existingCount);
        $('#footerNewCount').text(newCount);
        
        $('#footerExistingPercent').text(`${existingPercent.toFixed(1)}%`);
        $('#footerNewPercent').text(`${newPercent.toFixed(1)}%`);

        // 2. Circumference definition matching radius 40
        const circumference = 251.20; 

        if (totalCount === 0) {
            $('#donut-track-existing').css('stroke-dashoffset', circumference);
            $('#donut-track-new').css('stroke-dashoffset', circumference);
            return;
        }

        // 3. Compute simple individual stroke adjustments
        const existingDashOffset = circumference - (existingPercent / 100) * circumference;
        const newDashOffset = circumference - (newPercent / 100) * circumference;
        
        // Apply values to elements—CSS handles the directions perfectly
        $('#donut-track-existing').css('stroke-dashoffset', existingDashOffset);
        $('#donut-track-new').css('stroke-dashoffset', newDashOffset);
    }

    // Initialize layout execution loop
    fetchAccountCategoryMetrics('current');

    $('#kpiMonthFilter').on('change', function () {
        const pickedMonth = $(this).val();
        fetchAccountCategoryMetrics(pickedMonth);
    });

    window.refreshCategoryDonut = fetchAccountCategoryMetrics;
});