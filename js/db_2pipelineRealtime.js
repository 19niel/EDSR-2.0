/**
 * E-DSR Dashboard - Pipeline Funnel Real-Time Population Engine (UPSCALED & RESPONSIVE)
 */

$(document).ready(function () {
    let pipelineChart = null;

    function formatCurrency(value) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

    function initFunnelChart() {
        const theme = document.documentElement.getAttribute('data-theme') || 'light';
        const isDark = theme === 'dark';

        const options = {
            series: [{
                name: "Volume",
                data: [0, 0, 0, 0, 0],
                extraAccounts: [0, 0, 0, 0, 0]
            }],
            chart: {
                type: 'bar',
                height: 160, // UPSCALED: Raised standard rendering box depth from 250 for a larger dashboard footprint
                background: 'transparent',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            theme: {
                mode: isDark ? 'dark' : 'light'
            },
            colors: ['#0d6efd', '#0dcaf0', '#198754', '#dc3545', '#6c757d'],
            plotOptions: {
                bar: {
                    borderRadius: 4, // UPSCALED: Smoother curved edges matching modern card views
                    horizontal: true,
                    barHeight: '80%', // UPSCALED: Thickened data bars visually for emphasis
                    distributed: true,
                    colors: {
                        backgroundBarColors: isDark ? ['rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)'] : ['rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)'],
                        backgroundBarOpacity: 1,
                        backgroundBarRadius: 4
                    }
                },
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                formatter: function (val, opt) {
                    const seriesObj = opt.w.config.series[0];
                    const accs = (seriesObj && seriesObj.extraAccounts) ? (seriesObj.extraAccounts[opt.dataPointIndex] || 0) : 0;

                    // 🎯 CALCULATION: Sum all account counts to get the total
                    const totalAccs = seriesObj.extraAccounts.reduce((a, b) => a + b, 0);

                    // 🎯 CALCULATION: Get percentage
                    const percent = totalAccs > 0 ? Math.round((accs / totalAccs) * 100) : 0;

                    // 🎯 OUTPUT: X Accs | Y%
                    return accs + ' Accs | ' + percent + '%';
                },
                offsetX: 8,
                dropShadow: { enabled: false },
                style: {
                    fontSize: '10px',
                    fontWeight: 700,
                    colors: [isDark ? '#f8fafc' : '#0f172a']
                }
            },
            xaxis: {
                categories: ['Qualified', 'Negotiation', 'Won', 'Lost', 'Dropped'],
                labels: { show: false },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    show: true,
                    style: {
                        colors: isDark ? '#cbd5e1' : '#475569',
                        fontSize: '11px', // UPSCALED: Bumped category header row labels from 12px to 14px
                        fontFamily: 'Inter, sans-serif',
                        fontWeight: 700   // UPSCALED: Clearer contrast definitions
                    }
                }
            },
            grid: {
                show: false
            },
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: function (value, { series, seriesIndex, dataPointIndex, w }) {
                        return formatCurrency(value);
                    }
                }
            }
        };

        const chartElement = document.querySelector("#pipelineFunnelChart");
        if (chartElement) {
            pipelineChart = new ApexCharts(chartElement, options);
            pipelineChart.render();
        }
    }

    function fetchPipelineFunnelMetrics(selectedMonth = null) {
        if (!selectedMonth) {
            const monthFilterEl = document.getElementById('kpiMonthFilter');
            selectedMonth = monthFilterEl ? monthFilterEl.value : 'current';
        }
        console.log(`%c[Pipeline Engine] Fetching pipeline metrics for filter: ${selectedMonth}`, 'color: #0dcaf0; font-weight: bold;');

        let requestData = { month: selectedMonth };
        if (selectedMonth === 'custom') {
            const dateFromEl = document.getElementById('dateFrom');
            const dateToEl = document.getElementById('dateTo');
            requestData.dateFrom = dateFromEl ? dateFromEl.value : '';
            requestData.dateTo = dateToEl ? dateToEl.value : '';
        }

        $.ajax({
            url: '../php/get_2PipelineData.php',
            type: 'GET',
            data: requestData,
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    updatePipelineFunnelLayout(response.data);
                } else {
                    console.error("[Pipeline Engine] Server processing execution exception flagged:", response.error_message);
                }
            },
            error: function (xhr, status, error) {
                console.error("[Pipeline Engine] Fatal connection timeout or network routing drop:", error);
            }
        });
    }

    function updatePipelineFunnelLayout(data) {
        if (!data || !pipelineChart) return;

        const order = ['345', '346', '230', '348', '349'];

        const volumes = [];
        const accounts = [];

        order.forEach(id => {
            if (data[id]) {
                volumes.push(parseFloat(data[id].volume) || 0);
                accounts.push(parseInt(data[id].accounts) || 0);
            } else {
                volumes.push(0);
                accounts.push(0);
            }
        });

        pipelineChart.updateSeries([{
            name: "Volume",
            data: volumes,
            extraAccounts: accounts
        }]);

        // --- UPDATE KPI SUMMARY CARDS ---
        // Sum all relevant categories: 345 (Qualified), 346 (Negotiation), 230 (Won), 348 (Lost), 349 (Dropped)
        const allIds = ['345', '346', '230', '348', '349'];

        let totalVolume = 0;
        let totalAccs = 0;

        allIds.forEach(id => {
            if (data[id]) {
                totalVolume += parseFloat(data[id].volume || 0);
                totalAccs += parseInt(data[id].accounts || 0);
            }
        });

        // Specific values for cards
        const wonVolume = parseFloat(data['230']?.volume || 0);
        const wonAccs = parseInt(data['230']?.accounts || 0);
        const lostAccs = parseInt(data['348']?.accounts || 0);
        const droppedAccs = parseInt(data['349']?.accounts || 0);

        const closedAccs = wonAccs + lostAccs + droppedAccs;
        let winRate = closedAccs > 0 ? (wonAccs / closedAccs) * 100 : 0;

        const elTotalWon = document.getElementById('summaryTotalWon');
        const elPipeline = document.getElementById('summaryPipeline'); // Displays sum of all
        const elActive = document.getElementById('summaryActiveAccs');  // Displays sum of all
        const elWinRate = document.getElementById('summaryWinRate');

        if (elTotalWon) elTotalWon.innerText = formatCurrency(wonVolume);
        if (elPipeline) elPipeline.innerText = formatCurrency(totalVolume); // Updated
        if (elActive) elActive.innerText = totalAccs;                      // Updated
        if (elWinRate) elWinRate.innerText = winRate.toFixed(1) + '%';

        // --- UPDATE FUNNEL LEGEND GRID ---
        order.forEach(id => {
            const qtyEl = document.getElementById(`funnelQty-${id}`);
            const valEl = document.getElementById(`funnelVal-${id}`);
            const qtyVal = data[id] ? parseInt(data[id].accounts) || 0 : 0;
            const volVal = data[id] ? parseFloat(data[id].volume) || 0 : 0;
            if (qtyEl) qtyEl.innerText = qtyVal + (qtyVal === 1 ? ' Acc' : ' Accs');
            if (valEl) valEl.innerText = formatCurrency(volVal);
        });
    }

    // Initialize chart and data
    initFunnelChart();
    fetchPipelineFunnelMetrics();

    // Attach listening interceptors
    document.addEventListener('kpiFilterUpdated', function (e) {
        fetchPipelineFunnelMetrics(e.detail.period);
    });

    // Handle theme toggle
    document.addEventListener('edsrThemeChange', function (e) {
        if (pipelineChart) {
            const isDark = e.detail.theme === 'dark';
            pipelineChart.updateOptions({
                theme: { mode: e.detail.theme },
                plotOptions: {
                    bar: {
                        colors: {
                            backgroundBarColors: isDark ? ['rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)', 'rgba(255,255,255,0.05)'] : ['rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)', 'rgba(0,0,0,0.03)']
                        }
                    }
                },
                dataLabels: {
                    style: {
                        colors: [isDark ? '#f8fafc' : '#0f172a']
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: isDark ? '#cbd5e1' : '#475569'
                        }
                    }
                }
            });
        }
    });

    window.refreshPipelineFunnel = fetchPipelineFunnelMetrics;
});