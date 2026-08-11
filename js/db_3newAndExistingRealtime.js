/**
 * E-DSR Dashboard - Projects Won By Team (Chart.js)
 */
$(document).ready(function () {
    if ($('#donutTotalCount').length === 0) return;

    let teamChart = null;

    function initTeamChart() {
        const ctx = document.getElementById('teamWonChart');
        if (!ctx) return;

        teamChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Makati', 'QC', 'Manila', 'Calabarzon'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#30885f', '#0d6efd', '#ffc107', '#6f42c1'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                /* 🎯 FIXED THICKNESS: Dropped from 65% to 50% to make the colored rings significantly thicker */
                cutout: '70%',
                layout: {
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 0,
                        right: 0
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' ' + context.label + ': ' + context.raw + ' Projects';
                            }
                        }
                    }
                }
            }
        });
    }

    function fetchTeamDistributionRealtime() {
        const activeMonth = $('#kpiMonthFilter').val() || $('#dashboardMonthFilter').val() || 'current';

        let requestData = { month: activeMonth };
        if (activeMonth === 'custom') {
            const dateFromEl = document.getElementById('dateFrom');
            const dateToEl = document.getElementById('dateTo');
            requestData.dateFrom = dateFromEl ? dateFromEl.value : '';
            requestData.dateTo = dateToEl ? dateToEl.value : '';
        }

        $.ajax({
            url: '../php/get_3AccountStatusTotal.php',
            type: 'GET',
            data: requestData,
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    const makati = parseInt(response.makati) || 0;
                    const qc = parseInt(response.qc) || 0;
                    const manila = parseInt(response.manila) || 0;
                    const calabarzon = parseInt(response.calabarzon) || 0;
                    const total = parseInt(response.total) || 0;

                    $('#donutTotalCount').text(total);
                    $('#makatiCount').text(makati);
                    $('#qcCount').text(qc);
                    $('#manilaCount').text(manila);
                    $('#calabarzonCount').text(calabarzon);

                    let makatiPct = 0, qcPct = 0, manilaPct = 0, calabarzonPct = 0;
                    if (total > 0) {
                        makatiPct = (makati / total) * 100;
                        qcPct = (qc / total) * 100;
                        manilaPct = (manila / total) * 100;
                        calabarzonPct = (calabarzon / total) * 100;
                    }

                    $('#makatiPercent').text(makatiPct.toFixed(1) + '%');
                    $('#qcPercent').text(qcPct.toFixed(1) + '%');
                    $('#manilaPercent').text(manilaPct.toFixed(1) + '%');
                    $('#calabarzonPercent').text(calabarzonPct.toFixed(1) + '%');

                    if (teamChart) {
                        teamChart.data.datasets[0].data = [makati, qc, manila, calabarzon];
                        teamChart.update();
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("[Team Engine] Connection drop updating real-time feeds: ", error);
            }
        });
    }

    initTeamChart();
    fetchTeamDistributionRealtime();

    document.addEventListener('kpiFilterUpdated', function () {
        fetchTeamDistributionRealtime();
    });

    $(document).on('change', '#dashboardMonthFilter', function () {
        fetchTeamDistributionRealtime();
    });

    setInterval(fetchTeamDistributionRealtime, 5000);
});