/**
 * E-DSR Dashboard - Team Leaderboard Real-Time Engine (Chart.js)
 */

$(document).ready(function () {
    let leaderboardChart = null;

    function formatShortCurrency(value) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            maximumFractionDigits: 0
        }).format(value);
    }

    function initLeaderboardChart() {
        const ctx = document.getElementById('leaderboardChart');
        if (!ctx) return;

        const theme = document.documentElement.getAttribute('data-theme') || 'light';
        const isDark = theme === 'dark';
        const textColor = isDark ? '#F8FAFC' : '#0F172A';
        const gridColor = isDark ? 'rgba(248, 250, 252, 0.05)' : 'rgba(15, 23, 42, 0.05)';

        leaderboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Volume (PHP)',
                    data: [],
                    backgroundColor: '#0d6efd',
                    borderRadius: 4,
                    barThickness: 24,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { show: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return formatShortCurrency(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        display: false,
                        grid: { display: false }
                    },
                    y: {
                        grid: {
                            display: true,
                            color: gridColor,
                            drawBorder: false,
                        },
                        ticks: {
                            color: textColor,
                            font: { family: 'Inter', size: 11, weight: '600' }
                        }
                    }
                }
            }
        });
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
        if (!leaderboardChart) return;

        if (!executors || executors.length === 0) {
            leaderboardChart.data.labels = [];
            leaderboardChart.data.datasets[0].data = [];
            leaderboardChart.update();
            return;
        }

        const labels = [];
        const data = [];

        executors.forEach(exec => {
            labels.push(exec.name);
            data.push(parseFloat(exec.amount) || 0);
        });

        leaderboardChart.data.labels = labels;
        leaderboardChart.data.datasets[0].data = data;
        leaderboardChart.update();
    }

    initLeaderboardChart();
    fetchLeaderboardMetrics('current');

    $('#kpiMonthFilter').on('change', function () {
        const pickedMonth = $(this).val();
        fetchLeaderboardMetrics(pickedMonth);
    });

    document.addEventListener('edsrThemeChange', function(e) {
        if (leaderboardChart) {
            const isDark = e.detail.theme === 'dark';
            leaderboardChart.options.scales.y.ticks.color = isDark ? '#F8FAFC' : '#0F172A';
            leaderboardChart.options.scales.y.grid.color = isDark ? 'rgba(248, 250, 252, 0.05)' : 'rgba(15, 23, 42, 0.05)';
            leaderboardChart.update();
        }
    });

    window.refreshLeaderboard = fetchLeaderboardMetrics;
});