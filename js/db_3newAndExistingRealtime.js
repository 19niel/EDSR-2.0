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
                labels: ['Makati', 'QC', 'Manila'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#30885f', '#0d6efd', '#ffc107'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                /* 🎯 FIXED THICKNESS: Dropped from 65% to 50% to make the colored rings significantly thicker */
                cutout: '50%',
                layout: {
                    padding: {
                        top: 2,
                        bottom: 2,
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

        $.ajax({
            url: '../php/get_3AccountStatusTotal.php',
            type: 'GET',
            data: { month: activeMonth },
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    const makati = parseInt(response.makati) || 0;
                    const qc = parseInt(response.qc) || 0;
                    const manila = parseInt(response.manila) || 0;
                    const total = parseInt(response.total) || 0;

                    $('#donutTotalCount').text(total);
                    $('#makatiCount').text(makati);
                    $('#qcCount').text(qc);
                    $('#manilaCount').text(manila);

                    let makatiPct = 0, qcPct = 0, manilaPct = 0;
                    if (total > 0) {
                        makatiPct = (makati / total) * 100;
                        qcPct = (qc / total) * 100;
                        manilaPct = (manila / total) * 100;
                    }

                    $('#makatiPercent').text(makatiPct.toFixed(1) + '%');
                    $('#qcPercent').text(qcPct.toFixed(1) + '%');
                    $('#manilaPercent').text(manilaPct.toFixed(1) + '%');

                    if (teamChart) {
                        teamChart.data.datasets[0].data = [makati, qc, manila];
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

    $(document).on('change', '#kpiMonthFilter, #dashboardMonthFilter', function () {
        fetchTeamDistributionRealtime();
    });

    setInterval(fetchTeamDistributionRealtime, 5000);
});