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

    function initLeaderboardChart() {
        const ctx = document.getElementById('leaderboardChart');
        if (!ctx) return;

        const theme = document.documentElement.getAttribute('data-theme') || 'light';
        const isDark = theme === 'dark';
        const textColor = isDark ? '#F8FAFC' : '#0F172A';

        /* 🎯 RE-ADDED PLUGIN: Custom text rendering to show values on the right edge of bars */
        const barLabelsPlugin = {
            id: 'barLabels',
            afterDatasetsDraw(chart) {
                const { ctx } = chart;
                ctx.save();
                ctx.font = 'bold 10px Inter, sans-serif';
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                ctx.fillStyle = currentTheme === 'dark' ? '#F8FAFC' : '#0F172A';
                ctx.textAlign = 'left';
                ctx.textBaseline = 'middle';

                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((bar, index) => {
                        const value = dataset.data[index];
                        if (value !== undefined && value !== null) {
                            const formattedValue = formatCurrencyShorthand(value);
                            /* Adds a crisp 6px offset past the edge of the horizontal bar fill */
                            ctx.fillText(formattedValue, bar.x + 6, bar.y);
                        }
                    });
                });
                ctx.restore();
            }
        };

        leaderboardChart = new Chart(ctx, {
            type: 'bar',
            plugins: [barLabelsPlugin], // Activated the tracking labels plugin
            data: {
                labels: [],
                datasets: [{
                    label: 'Amount',
                    data: [],
                    backgroundColor: 'rgba(13, 110, 253, 0.85)',
                    borderColor: '#0d6efd',
                    borderWidth: 0,
                    borderRadius: 3,
                    borderSkipped: false,
                    barThickness: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                layout: {
                    padding: {
                        top: 4,
                        bottom: 4,
                        left: 8, // Ample room on the left to avoid names clipping out
                        right: 40 // Safe zone clearance buffer to stop shorthand numbers from hitting the right edge
                    }
                },
                scales: {
                    x: {
                        display: false,
                        grid: { display: false }
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: textColor,
                            mirror: false,
                            padding: 4,
                            font: {
                                fontFamily: 'Inter, sans-serif',
                                size: 9,
                                weight: '600'
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1E293B' : '#FFFFFF',
                        titleColor: isDark ? '#F8FAFC' : '#0F172A',
                        bodyColor: isDark ? '#CBD5E1' : '#475569',
                        borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function (context) {
                                return ' Total Pipeline: ' + formatShortCurrency(context.raw);
                            }
                        }
                    }
                }
            }
        });
    }

    function fetchLeaderboardMetrics(month) {
        $.ajax({
            url: '../php/get_4LeaderboardData.php',
            type: 'GET',
            data: { month: month },
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    renderLeaderboardLayout(response.data);
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

    document.addEventListener('edsrThemeChange', function (e) {
        if (leaderboardChart) {
            const isDark = e.detail.theme === 'dark';
            leaderboardChart.options.scales.y.ticks.color = isDark ? '#F8FAFC' : '#0F172A';
            leaderboardChart.update();
        }
    });

    setInterval(function () {
        const pickedMonth = $('#kpiMonthFilter').val() || 'current';
        fetchLeaderboardMetrics(pickedMonth);
    }, 5000);
});