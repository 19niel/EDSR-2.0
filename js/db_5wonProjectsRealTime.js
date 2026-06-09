/**
 * E-DSR Dashboard - Won Projects Paginated Compact Real-Time Table Engine
 */

$(document).ready(function () {

    let allWonDataCache = [];
    let currentTablePage = 1;
    const recordsPerPage = 5;

    // 🎯 Compact value abbreviator to stop text breaks or horizontal overflows
    function formatShortCurrency(value) {
        if (value === 0) return '₱0';
        
        const symbols = [
            { value: 1E6, suffix: 'M' },
            { value: 1E3, suffix: 'K' }
        ];
        
        for (let i = 0; i < symbols.length; i++) {
            if (value >= symbols[i].value) {
                // Returns ₱1M or ₱12.3K without breaking width containers
                return '₱' + (value / symbols[i].value).toFixed(value % symbols[i].value === 0 ? 0 : 1) + symbols[i].suffix;
            }
        }
        
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            maximumFractionDigits: 0
        }).format(value);
    }

    function fetchWonProjectTableMetrics(selectedMonth = 'current') {
        $.ajax({
            url: '../php/get_5wonProjectsData.php', 
            type: 'GET',
            data: { month: selectedMonth },
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    allWonDataCache = response.data;
                    currentTablePage = 1; 
                    displayPaginatedTableRows();
                } else {
                    console.error("[Won Table Engine] Server Exception:", response.error_message);
                    $('#won-projects-table-body').html('<tr><td colspan="4" class="text-center text-danger py-2">Error loading rows</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error("[Won Table Engine] Connection error:", error);
                $('#won-projects-table-body').html('<tr><td colspan="4" class="text-center text-danger py-2">Connection Error</td></tr>');
            }
        });
    }

    function displayPaginatedTableRows() {
        const $tbody = $('#won-projects-table-body');
        $tbody.empty();

        const totalRecords = allWonDataCache.length;

        if (totalRecords === 0) {
            $tbody.html('<tr><td colspan="4" class="text-center text-muted py-3">No won records found for this month.</td></tr>');
            $('#won-table-pagination-info').text('Showing 0-0 of 0');
            setupPaginationInterfaceButtons(0);
            return;
        }

        const totalPages = Math.ceil(totalRecords / recordsPerPage);
        if (currentTablePage > totalPages) currentTablePage = totalPages;
        if (currentTablePage < 1) currentTablePage = 1;

        const startIndex = (currentTablePage - 1) * recordsPerPage;
        const endIndex = Math.min(startIndex + recordsPerPage, totalRecords);

        const pageRecords = allWonDataCache.slice(startIndex, endIndex);

        // 🎯 Injected dynamic row reference pointer pathways targeting editEncode profiles
        pageRecords.forEach(row => {
            const targetUrl = `editEncode.php?id=${row.id}`;
            const rowHtml = `
                <tr style="cursor: pointer;">
                    <td class="py-1">
                        <a href="${targetUrl}" class="text-decoration-none d-block">
                            <div class="text-ellipsis-won fw-medium text-dark" title="${row.accExec}">${row.accExec}</div>
                        </a>
                    </td>
                    <td class="py-1">
                        <a href="${targetUrl}" class="text-decoration-none d-block">
                            <div class="text-ellipsis-won text-secondary" title="${row.accName}">${row.accName}</div>
                        </a>
                    </td>
                    <td class="py-1 text-nowrap">
                        <a href="${targetUrl}" class="text-decoration-none d-block text-muted" style="font-size: 0.68rem;">
                            ${row.progressDate}
                        </a>
                    </td>
                    <td class="py-1 text-end fw-bold text-nowrap">
                        <a href="${targetUrl}" class="text-decoration-none d-block text-success">
                            ${formatShortCurrency(row.proposedPrice)}
                        </a>
                    </td>
                </tr>
            `;
            $tbody.append(rowHtml);
        });

        $('#won-table-pagination-info').text(`Showing ${startIndex + 1}-${endIndex} of ${totalRecords}`);
        setupPaginationInterfaceButtons(totalPages);
    }

    function setupPaginationInterfaceButtons(totalPages) {
        const $buttonsContainer = $('#won-table-pagination-buttons');
        $buttonsContainer.empty();

        if (totalPages <= 1) return; 

        // Previous Button
        const prevDisabled = currentTablePage === 1 ? 'disabled' : '';
        const $prevBtn = $(`<li class="page-item ${prevDisabled}"><a class="page-link" href="#" aria-label="Previous">&laquo;</a></li>`);
        if (currentTablePage > 1) {
            $prevBtn.find('a').on('click', function (e) {
                e.preventDefault();
                currentTablePage--;
                displayPaginatedTableRows();
            });
        }
        $buttonsContainer.append($prevBtn);

        // Active indicator frame
        const $currentIndicator = $(`<li class="page-item active"><span class="page-link py-0.5">${currentTablePage}/${totalPages}</span></li>`);
        $buttonsContainer.append($currentIndicator);

        // Next Button
        const nextDisabled = currentTablePage === totalPages ? 'disabled' : '';
        const $nextBtn = $(`<li class="page-item ${nextDisabled}"><a class="page-link" href="#" aria-label="Next">&raquo;</a></li>`);
        if (currentTablePage < totalPages) {
            $nextBtn.find('a').on('click', function (e) {
                e.preventDefault();
                currentTablePage++;
                displayPaginatedTableRows();
            });
        }
        $buttonsContainer.append($nextBtn);
    }

    fetchWonProjectTableMetrics('current');

    $('#kpiMonthFilter').on('change', function () {
        const pickedMonth = $(this).val();
        fetchWonProjectTableMetrics(pickedMonth);
    });

    window.refreshWonProjectsTable = fetchWonProjectTableMetrics;
});