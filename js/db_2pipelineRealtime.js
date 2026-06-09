/**
 * E-DSR Dashboard - Pipeline Funnel Real-Time Population Engine
 */

$(document).ready(function () {

    function formatCurrency(value) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
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
        if (!data) return;

        // 1. Identify the maximum accounts count to establish 100% relative baseline widths
        let maxAccounts = 0;
        Object.keys(data).forEach(id => {
            const accCount = parseInt(data[id].accounts) || 0;
            if (accCount > maxAccounts) {
                maxAccounts = accCount;
            }
        });

        // 2. Cycle loop calculations through metrics to apply geometric layout styles smoothly
        Object.keys(data).forEach(id => {
            const segment = data[id];
            const volume = parseFloat(segment.volume) || 0;
            const accounts = parseInt(segment.accounts) || 0;

            // Generate scale bars relative to the highest record count across stages
            const barWidthPercent = maxAccounts > 0 ? (accounts / maxAccounts) * 100 : 0;

            const $barElement = $(`#bar-${id}`);
            const $labelElement = $(`#label-${id}`);

            if ($barElement.length) {
                // Instantly scales width layout dimensions beautifully 
                $barElement.css('width', `${barWidthPercent}%`);
            }

            if ($labelElement.length) {
                const formattedVol = formatCurrency(volume);
                $labelElement.html(`${accounts} Accs &nbsp;|&nbsp; ${formattedVol}`);
            }
        });
    }

    // Initialize data connection sequence immediately on dashboard initialization
    fetchPipelineFunnelMetrics('current');

    // Attach listening interceptors onto global dashboard selection components
    $('#kpiMonthFilter').on('change', function () {
        const pickedMonth = $(this).val();
        fetchPipelineFunnelMetrics(pickedMonth);
    });

    // Expose engine trigger handles into global application windows
    window.refreshPipelineFunnel = fetchPipelineFunnelMetrics;
});