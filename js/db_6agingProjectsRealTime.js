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
                    $('#aging-projects-table-body').html('<tr><td colspan="3" class="text-center text-danger py-2">Error loading rows</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error("[Aging Table Engine] Connection failure:", error);
                $('#aging-projects-table-body').html('<tr><td colspan="3" class="text-center text-danger py-2">Connection Error</td></tr>');
            }
        });
    }

    function displayPaginatedTableRows() {
        const $tbody = $('#aging-projects-table-body');
        $tbody.empty();

        const totalRecords = allAgingDataCache.length;

        if (totalRecords === 0) {
            $tbody.html('<tr><td colspan="3" class="text-center text-muted py-3">No aging records found.</td></tr>');
            $('#aging-table-pagination-info').text('Showing 0-0 of 0');
            setupPaginationInterfaceButtons(0);
            return;
        }

        const totalPages = Math.ceil(totalRecords / recordsPerPage);
        if (currentTablePage > totalPages) currentTablePage = totalPages;
        if (currentTablePage < 1) currentTablePage = 1;

        const startIndex = (currentTablePage - 1) * recordsPerPage;
        const endIndex = Math.min(startIndex + recordsPerPage, totalRecords);

        const pageData = allAgingDataCache.slice(startIndex, endIndex);

        // 🎯 Injected dynamic row reference pointer pathways targeting editEncode profiles using item.LID
        pageData.forEach(item => {
            const targetUrl = `editEncode.php?id=${item.LID}`;
            const rowHtml = `
                <tr style="cursor: pointer;">
                    <td class="py-1 fw-bold">
                        <a href="${targetUrl}" class="text-decoration-none d-block text-danger">
                            #${item.LID}
                        </a>
                    </td>
                    <td class="py-1">
                        <a href="${targetUrl}" class="text-decoration-none d-block">
                            <div class="text-ellipsis-aging text-dark" title="${item.accName}">${item.accName}</div>
                        </a>
                    </td>
                    <td class="py-1 text-end text-nowrap">
                        <a href="${targetUrl}" class="text-decoration-none d-block text-muted" style="font-size: 0.68rem;">
                            <i class="fa-regular fa-calendar me-1"></i>${item.progressDate}
                        </a>
                    </td>
                </tr>
            `;
            $tbody.append(rowHtml);
        });

        // Track pagination text parameters
        $('#aging-table-pagination-info').text(`Showing ${startIndex + 1}-${endIndex} of ${totalRecords}`);
        setupPaginationInterfaceButtons(totalPages);
    }

    function setupPaginationInterfaceButtons(totalPages) {
        const $buttonsContainer = $('#aging-table-pagination-buttons');
        $buttonsContainer.empty();

        if (totalPages <= 1) return; 

        // Previous Arrow
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

        // Active page count state view badge
        const $currentIndicator = $(`<li class="page-item active"><span class="page-link py-0.5">${currentTablePage}/${totalPages}</span></li>`);
        $buttonsContainer.append($currentIndicator);

        // Next Arrow
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

    // Initialize layout population immediately on execution load loop
    fetchAgingProjectMetrics();

    // Standard engine connection for dashboard background poll executions
    window.refreshAgingProjectsTable = fetchAgingProjectMetrics;
});