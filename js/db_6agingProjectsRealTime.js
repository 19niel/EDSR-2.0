/**
 * E-DSR Dashboard - Aging Accounts Paginated Compact Real-Time Table Engine
 */

$(document).ready(function () {

    let allAgingDataCache = [];
    let currentTablePage = 1;
    const recordsPerPage = 5;

    function fetchAgingProjectMetrics() {
        $.ajax({
            url: '../php/get_6agingProjectsData.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    allAgingDataCache = response.data;
                    currentTablePage = 1;
                    displayPaginatedTableRows();
                } else {
                    console.error("[Aging Table Engine] Server Exception:", response.error_message);
                    $('#aging-projects-table-body').html('<tr><td colspan="3" class="text-center text-danger py-3">Error loading rows</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error("[Aging Table Engine] Connection failure:", error);
                $('#aging-projects-table-body').html('<tr><td colspan="3" class="text-center text-danger py-3">Connection Error</td></tr>');
            }
        });
    }

    function displayPaginatedTableRows() {
        const $tbody = $('#aging-projects-table-body');
        $tbody.empty();

        const totalRecords = allAgingDataCache.length;

        if (totalRecords === 0) {
            $tbody.html('<tr><td colspan="3" class="text-center py-4 text-muted">No aging accounts found matching current thresholds.</td></tr>');
            $('#aging-table-pagination-info').text('Showing 0-0 of 0');
            $('#aging-table-pagination-controls').empty();
            return;
        }

        const totalPages = Math.ceil(totalRecords / recordsPerPage);
        if (currentTablePage > totalPages) currentTablePage = totalPages;

        const startIndex = (currentTablePage - 1) * recordsPerPage;
        const endIndex = Math.min(startIndex + recordsPerPage, totalRecords);
        const paginatedData = allAgingDataCache.slice(startIndex, endIndex);

        // Build HTML output to match segment 5
        let rowsHtml = '';
        $.each(paginatedData, function (index, item) {
            rowsHtml += `
                <tr>
                    <td class="fw-semibold text-dark">
                        <a href="/e-dsr/pages/editEncode.php?id=${encodeURIComponent(item.id)}" class="text-decoration-none text-danger border-bottom border-danger border-opacity-10 pb-0.5">
                            ${item.LID}
                        </a>
                    </td>
                    <td>
                        <div class="text-ellipsis-aging fw-medium text-secondary" title="${item.accName}">
                            ${item.accName}
                        </div>
                    </td>
                    <td class="text-end text-muted font-monospace" style="font-size: 0.70rem;">
                        ${item.progressDate}
                    </td>
                </tr>`;
        });

        $tbody.html(rowsHtml);

        // Render dynamic context values matching segment 5 strings
        $('#aging-table-pagination-info').text(`Showing ${startIndex + 1}-${endIndex} of ${totalRecords}`);
        renderPaginationButtons(totalPages);
    }

    function renderPaginationButtons(totalPages) {
        const $buttonsContainer = $('#aging-table-pagination-controls');
        $buttonsContainer.empty();

        if (totalPages <= 1) return;

        // Previous Arrow Link
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

        // Active page indicator count view
        const $currentIndicator = $(`<li class="page-item active"><span class="page-link py-0.5">${currentTablePage}/${totalPages}</span></li>`);
        $buttonsContainer.append($currentIndicator);

        // Next Arrow Link
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

    // Run immediately on page load
    fetchAgingProjectMetrics();

    // Standard engine binding connection for real-time dashboard poll cycles
    window.refreshAgingProjectsTable = function () {
        fetchAgingProjectMetrics();
    };
});