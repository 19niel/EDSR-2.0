/**
 * E-DSR Bar + Line Daily Calls Chart — v2.0
 * Theme-aware: reads EDSR_THEME and listens for edsrThemeChange to update colors.
 */

document.addEventListener("DOMContentLoaded", function () {
    var myModal = new bootstrap.Modal("#updateGraphModal");

    // ─── Theme-aware color palettes ──────────────────────────────────────────
    function getChartColors() {
        var isDark = (document.documentElement.getAttribute('data-theme') === 'dark');
        return {
            barBg:       isDark ? 'rgba(96, 165, 250, 0.22)'  : 'rgba(37, 99, 235, 0.15)',
            barBorder:   isDark ? 'rgba(96, 165, 250, 0.85)'  : 'rgba(37, 99, 235, 0.85)',
            lineBorder:  isDark ? 'rgba(251, 146, 60, 0.9)'   : 'rgba(220, 38, 38, 0.9)',
            gridColor:   isDark ? 'rgba(248, 250, 252, 0.07)' : 'rgba(15, 23, 42, 0.07)',
            tickColor:   isDark ? '#94A3B8' : '#64748B',
        };
    }

    var barLineChart = null;

    // ─── Data fetch & chart update ────────────────────────────────────────────
    function fetchData() {
        var callDateStart = document.getElementById("callDateStart").value;
        var callDateEnd   = document.getElementById("callDateEnd").value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../php/graphData.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var requestData =
            "callDateStart=" + encodeURIComponent(callDateStart) +
            "&callDateEnd="  + encodeURIComponent(callDateEnd);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var responseData = JSON.parse(xhr.responseText);
                        if (Array.isArray(responseData) && responseData.length > 0) {
                            var labels = responseData.map(function (item) { return item.callDate; });
                            var barData  = responseData.map(function (item) { return parseInt(item.rowCount, 10); });
                            var lineData = responseData.map(function (item) { return parseInt(item.statusCount, 10); });

                            var totalCallCount   = responseData.reduce(function (s, i) { return s + parseInt(i.rowCount, 10); }, 0);
                            var totalActualCount = responseData.reduce(function (s, i) { return s + parseInt(i.actualCount, 10); }, 0);
                            var totalCloseCount  = responseData.reduce(function (s, i) { return s + parseInt(i.closeCount, 10); }, 0);

                            var conversionRate = totalCloseCount > 0
                                ? (totalCloseCount / totalActualCount) * 100 : 0;

                            barLineChart.data.labels = labels;
                            barLineChart.data.datasets[0].data = barData;
                            barLineChart.data.datasets[1].data = lineData;

                            document.getElementById("callCountSpan").innerHTML          = totalCallCount;
                            document.getElementById("actualCountSpan").innerHTML        = totalActualCount;
                            document.getElementById("actualClosedCountSpan").innerHTML  = totalCloseCount;
                            document.getElementById("conversionSpan").innerHTML         = conversionRate.toFixed(2) + "%";

                            myModal.hide();
                            barLineChart.update();
                        } else {
                            console.error("Invalid response format:", responseData);
                        }
                    } catch (error) {
                        console.error("Error parsing response:", error);
                    }
                } else {
                    console.error("Error fetching data. Status:", xhr.status);
                }
            }
        };

        if (callDateStart && callDateEnd) {
            xhr.send(requestData);
        } else {
            xhr.send();
        }
    }

    // ─── Apply theme colors to existing chart ────────────────────────────────
    function applyChartTheme() {
        if (!barLineChart) return;
        var c = getChartColors();
        barLineChart.data.datasets[0].backgroundColor = c.barBg;
        barLineChart.data.datasets[0].borderColor      = c.barBorder;
        barLineChart.data.datasets[1].borderColor      = c.lineBorder;

        barLineChart.options.scales.x.grid.color     = c.gridColor;
        barLineChart.options.scales.x.ticks.color    = c.tickColor;
        barLineChart.options.scales.y.grid.color     = c.gridColor;
        barLineChart.options.scales.y.ticks.color    = c.tickColor;

        barLineChart.update('none'); // 'none' = no animation, instant
    }

    // ─── Form submit listener ────────────────────────────────────────────────
    var updateGraphForm = document.getElementById("updateGraphForm");
    updateGraphForm.addEventListener("submit", function (event) {
        event.preventDefault();
        fetchData();
    });

    // ─── Chart initialization ────────────────────────────────────────────────
    var c = getChartColors();
    var chartData = {
        labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"],
        datasets: [
            {
                label: "Calls",
                backgroundColor: c.barBg,
                borderColor:     c.barBorder,
                borderWidth: 1.5,
                borderRadius: 4,
                data: [5, 10, 15, 7, 20],
                type: "bar",
            },
            {
                label: "Closed Calls",
                borderColor:  c.lineBorder,
                borderWidth:  2,
                pointRadius:  3,
                pointHoverRadius: 5,
                tension: 0.3,
                fill: false,
                data: [10, 5, 8, 15, 12],
                type: "line",
            },
        ],
    };

    var ctx = document.getElementById("barLineChart").getContext("2d");
    barLineChart = new Chart(ctx, {
        type: "bar",
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: c.tickColor,
                        font: { family: "'Inter', sans-serif", size: 12 },
                    },
                },
            },
            scales: {
                x: {
                    grid:  { color: c.gridColor },
                    ticks: { color: c.tickColor, font: { family: "'Inter', sans-serif", size: 11 } },
                },
                y: {
                    beginAtZero: true,
                    grid:  { color: c.gridColor },
                    ticks: { color: c.tickColor, font: { family: "'Inter', sans-serif", size: 11 } },
                },
            },
        },
    });

    // Fetch initial data
    fetchData();

    // ─── Re-theme when toggle fires ──────────────────────────────────────────
    document.addEventListener('edsrThemeChange', function () {
        applyChartTheme();
    });
});
