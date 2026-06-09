/**
 * E-DSR Dashboard - Team Leaderboard Real-Time Engine
 */

$(document).ready(function () {

    function formatShortCurrency(value) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            maximumFractionDigits: 0
        }).format(value);
    }

    function fetchLeaderboardMetrics(selectedMonth = 'current') {
        $.ajax({
            url: '../php/get_4LeaderboardData.php',
            type: 'GET',
            data: { month: selectedMonth },
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    renderLeaderboardLayout(response.data);
                } else {
                    console.error("[Leaderboard Engine] Execution exception:", response.error_message);
                }
            },
            error: function (xhr, status, error) {
                console.error("[Leaderboard Engine] Network communication drop:", error);
            }
        });
    }

    function renderLeaderboardLayout(executors) {
        const $container = $('#leaderboard-chart-container');
        $container.empty();

        if (!executors || executors.length === 0) {
            $container.html('<div class="text-center text-muted small py-4">No active pipeline records found</div>');
            return;
        }

        // 1. Identify highest volume baseline value (first item due to SQL sorting layout)
        const highestVolume = parseFloat(executors[0].amount) || 0;

        // 2. Iterate and generate graph row elements dynamically
        executors.forEach(exec => {
            const currentAmount = parseFloat(exec.amount) || 0;
            
            // Proportional layout scaling width mapping logic matching image specifications
            const calculateWidth = highestVolume > 0 ? (currentAmount / highestVolume) * 100 : 0;
            
            // Use slightly compressed styling scales if values are completely empty
            const barWidth = calculateWidth > 0 ? Math.max(calculateWidth, 3) : 0; 

            const dynamicRowHtml = `
                <div class="leaderboard-row">
                    <div class="leaderboard-label" title="${exec.name}">${exec.name}</div>
                    <div class="leaderboard-bar-container">
                        <div class="leaderboard-bar-fill" style="width: ${barWidth}%;"></div>
                        <div class="leaderboard-value">${formatShortCurrency(currentAmount)}</div>
                    </div>
                </div>
            `;
            $container.append(dynamicRowHtml);
        });
    }

    // Initialize module actions immediately on system loading sequence execution loops
    fetchLeaderboardMetrics('current');

    // Handle updates when changing dashboard calendar dropdown menus
    $('#kpiMonthFilter').on('change', function () {
        const pickedMonth = $(this).val();
        fetchLeaderboardMetrics(pickedMonth);
    });

    window.refreshLeaderboard = fetchLeaderboardMetrics;
});