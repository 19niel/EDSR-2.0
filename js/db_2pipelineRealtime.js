/**
 * E-DSR Dashboard - Pipeline Funnel Real-Time Population Engine
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
                data: [0, 0, 0, 0, 0]
            }],
            chart: {
                type: 'bar',
                height: 250,
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
                    borderRadius: 4,
                    horizontal: true,
                    barHeight: '80%',
                    isFunnel: true,
                    distributed: true
                },
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opt) {
                    const accs = opt.w.config.series[0].extraAccounts[opt.dataPointIndex] || 0;
                    return opt.w.globals.labels[opt.dataPointIndex] + ': ' + accs + ' Accs | ' + formatCurrency(val);
                },
                dropShadow: { enabled: false },
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            xaxis: {
                categories: ['Qualified', 'Negotiation', 'Won', 'Lost', 'Dropped'],
                labels: { show: false },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { show: false }
            },
            legend: { show: false },
            tooltip: {
                y: {
                    formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
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

    function fetchPipelineFunnelMetrics(selectedMonth = 'current') {
        console.log(`%c[Pipeline Engine] Fetching pipeline metrics for filter: ${selectedMonth}`, 'color: #0dcaf0; font-weight: bold;');

        $.ajax({
            url: '../php/get_2PipelineData.php',
            type: 'GET',
            data: { month: selectedMonth },
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

        // Keys mapping: 345=Qualified, 346=Negotiation, 230=Won, 348=Lost, 349=Dropped
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
            extraAccounts: accounts // Custom field for formatter
        }]);

        // --- UPDATE KPI SUMMARY CARDS ---
        const wonVolume = parseFloat(data['230']?.volume || 0);
        const pipeVolume = parseFloat(data['345']?.volume || 0) + parseFloat(data['346']?.volume || 0);
        const activeAccs = parseInt(data['345']?.accounts || 0) + parseInt(data['346']?.accounts || 0);
        
        const wonAccs = parseInt(data['230']?.accounts || 0);
        const lostAccs = parseInt(data['348']?.accounts || 0);
        const droppedAccs = parseInt(data['349']?.accounts || 0);
        const closedAccs = wonAccs + lostAccs + droppedAccs;
        
        let winRate = 0;
        if (closedAccs > 0) {
            winRate = (wonAccs / closedAccs) * 100;
        }

        const elTotalWon = document.getElementById('summaryTotalWon');
        const elPipeline = document.getElementById('summaryPipeline');
        const elActive = document.getElementById('summaryActiveAccs');
        const elWinRate = document.getElementById('summaryWinRate');

        if (elTotalWon) elTotalWon.innerText = formatCurrency(wonVolume);
        if (elPipeline) elPipeline.innerText = formatCurrency(pipeVolume);
        if (elActive) elActive.innerText = activeAccs;
        if (elWinRate) elWinRate.innerText = winRate.toFixed(1) + '%';
    }

    // Initialize chart and data
    initFunnelChart();
    fetchPipelineFunnelMetrics('current');

    // Attach listening interceptors
    $('#kpiMonthFilter').on('change', function () {
        const pickedMonth = $(this).val();
        fetchPipelineFunnelMetrics(pickedMonth);
    });

    // Handle theme toggle
    document.addEventListener('edsrThemeChange', function(e) {
        if (pipelineChart) {
            pipelineChart.updateOptions({
                theme: { mode: e.detail.theme }
            });
        }
    });

    // Expose engine trigger handles into global application windows
    window.refreshPipelineFunnel = fetchPipelineFunnelMetrics;
});