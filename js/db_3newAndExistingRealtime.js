/**
 * E-DSR Dashboard - Projects Won By Team SVG Donut Segment Engine
 */
$(document).ready(function () {
    // Gracefully exit if the target dashboard panel anchor node is absent
    if ($('#donutTotalCount').length === 0) return;

    // Standard geometric circle circumference tracking for Radius=40 (2 * Math.PI * 40)
    const circumference = 251.20;

    function fetchTeamDistributionRealtime() {
        // Automatically sync filter parameters with your global dropdown layout elements[cite: 17]
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

                    // 1. Render raw local count strings directly to the card elements[cite: 17]
                    $('#donutTotalCount').text(total);
                    $('#makatiCount').text(makati);
                    $('#qcCount').text(qc);
                    $('#manilaCount').text(manila);

                    // 2. Compute float percentage shares cleanly[cite: 17]
                    let makatiPct = 0, qcPct = 0, manilaPct = 0;
                    if (total > 0) {
                        makatiPct = (makati / total) * 100;
                        qcPct = (qc / total) * 100;
                        manilaPct = (manila / total) * 100;
                    }

                    // Write percentage metrics to the UI view placeholders[cite: 17]
                    $('#makatiPercent').text(makatiPct.toFixed(1) + '%');
                    $('#qcPercent').text(qcPct.toFixed(1) + '%');
                    $('#manilaPercent').text(manilaPct.toFixed(1) + '%');

                    // 3. SVG Layout Offset Rendering Block[cite: 17]
                    if (total === 0) {
                        $('#donut-track-makati').css('stroke-dasharray', `0 ${circumference}`);
                        $('#donut-track-qc').css('stroke-dasharray', `0 ${circumference}`);
                        $('#donut-track-manila').css('stroke-dasharray', `0 ${circumference}`);
                        return;
                    }

                    // Compute clean circle length mappings relative to percentage metrics[cite: 17]
                    const makatiStroke = (makatiPct / 100) * circumference;
                    const qcStroke = (qcPct / 100) * circumference;
                    const manilaStroke = (manilaPct / 100) * circumference;

                    // 🎯 FIX: Track cumulative running offset manually to prevent zero-value overlapping layers
                    let currentOffset = 0;

                    // --- MAKATI SEGMENT ---
                    if (makatiStroke > 0) {
                        $('#donut-track-makati').show().css({
                            'stroke-dasharray': `${makatiStroke} ${circumference}`,
                            'stroke-dashoffset': currentOffset
                        });
                        currentOffset -= makatiStroke; // Move cursor for next segment
                    } else {
                        $('#donut-track-makati').hide();
                    }

                    // --- QC SEGMENT ---
                    if (qcStroke > 0) {
                        $('#donut-track-qc').show().css({
                            'stroke-dasharray': `${qcStroke} ${circumference}`,
                            'stroke-dashoffset': currentOffset
                        });
                        currentOffset -= qcStroke; // Move cursor for next segment
                    } else {
                        $('#donut-track-qc').hide();
                    }

                    // --- MANILA SEGMENT ---
                    if (manilaStroke > 0) {
                        $('#donut-track-manila').show().css({
                            'stroke-dasharray': `${manilaStroke} ${circumference}`,
                            'stroke-dashoffset': currentOffset
                        });
                    } else {
                        $('#donut-track-manila').hide();
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("[Team Engine] Connection drop updating real-time feeds: ", error);
            }
        });
    }

    // Initialize layout population engine sequence on compilation[cite: 17]
    fetchTeamDistributionRealtime();

    // Catch changes on both global dropdown configurations[cite: 17]
    $(document).on('change', '#kpiMonthFilter, #dashboardMonthFilter', function () {
        fetchTeamDistributionRealtime();
    });

    // Auto-refresh metrics pool every 5 seconds[cite: 17]
    setInterval(fetchTeamDistributionRealtime, 5000);
});