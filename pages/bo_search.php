<?php
include('../php/autoRedirect.php');
include('../php/accountList.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5.3.2 & Google Fonts Integration -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/search.css">
    <title>E-DSR - Search Records</title>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .main-content-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        .modern-search-input {
            border-radius: 8px 0 0 8px !important;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
        }
        .modern-search-input:focus {
            border-color: #86b7fe;
            box-shadow: none;
        }
        .search-group-btn {
            border-radius: 0 8px 8px 0 !important;
        }
        .table modern-table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody td {
            padding: 14px 16px;
            font-size: 0.9rem;
            color: #495057;
            border-bottom: 1px solid #efefef;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.15s ease-in-out;
        }
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-1px);
        }
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 30px;
            letter-spacing: 0.3px;
        }
        .pagination .page-link {
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 8px 16px;
            margin: 0 2px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <main class="col-12 col-xl-11 mx-auto">
                
                <!-- Section 1: Dashboard Header & Action Controls -->
                <div class="d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom flex-wrap gap-3">
                    <div>
                        <h3 class="m-0 fw-bold tracking-tight text-dark">Search Records</h3>
                        <p class="text-muted small m-0 mt-1">Manage, filter, and extract digital support and account registry history.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="../php/exportAllData.php?globalSearch=<?php echo urlencode($_GET['globalSearch'] ?? ''); ?>&accountExecutiveSearch=<?php echo urlencode($accountExecutive ?? ''); ?>&accountName=<?php echo urlencode($accountName ?? ''); ?>&callDate=<?php echo urlencode($callDate ?? ''); ?>&callDateStart=<?php echo urlencode($callDateStart ?? ''); ?>&callDateEnd=<?php echo urlencode($callDateEnd ?? ''); ?>" class="btn btn-white border border-secondary-subtle btn-light px-3 fw-medium d-flex align-items-center gap-2 shadow-sm rounded-3">
                            <i class="fa fa-download text-secondary"></i> Export Dataset
                        </a>
                        <button type="button" class="btn btn-primary px-3 fw-medium d-flex align-items-center gap-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#searchAccount">
                            <i class="fa fa-sliders-h"></i> Advanced Filters
                        </button>
                    </div>
                </div>

                <!-- Section 2: Inline Extended Search Bar Framework -->
                <div class="mb-4">
                    <form action="" method="GET" class="d-flex gap-2 w-100" style="max-width: 600px;">
                        <?php
                        $modal_filters = ['accountExecutiveSearch', 'accountName', 'callDate', 'callDateStart', 'callDateEnd'];
                        foreach ($modal_filters as $mf) {
                            if (!empty($_GET[$mf])) {
                                echo '<input type="hidden" name="' . htmlspecialchars($mf) . '" value="' . htmlspecialchars($_GET[$mf]) . '">';
                            }
                        }
                        ?>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-white text-muted border-end-0 ps-3">
                                <i class="fa fa-search text-secondary opacity-75"></i>
                            </span>
                            <input type="text" name="globalSearch" class="form-control border-start-0 modern-search-input py-2.5" placeholder="Search Lead ID, Client Name, Project Title..." value="<?php echo htmlspecialchars($_GET['globalSearch'] ?? ''); ?>">
                            <?php if (!empty($_GET['globalSearch'])): ?>
                                <a href="bo_search.php" class="btn btn-white border border-start-0 border-end-0 d-flex align-items-center justify-content-center text-muted px-2" title="Clear Search">
                                    <i class="fa fa-times-circle"></i>
                                </a>
                            <?php endif; ?>
                            <button class="btn btn-primary px-4 fw-medium search-group-btn" type="submit">Search</button>
                        </div>
                    </form>
                </div>

                <?php include('./modals/searchFilter.php') ?>

                <!-- Section 3: Records Presentation Canvas Box -->
                <div class="main-content-card shadow-sm overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0 modern-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 110px;">Actions</th>
                                    <th>Lead ID</th>
                                    <th>Project Title</th>
                                    <th>Sales Executive</th>
                                    <th>Client Name</th>
                                    <th>Creation Date</th>
                                    <th>Proposed Amount</th>
                                    <th>Status</th>
                                    <th>Est. Delivery</th> 
                                    <th>Progress Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($accountResult && mysqli_num_rows($accountResult) > 0) { 
                                    foreach ($accountResult as $row) { 
                                        // Contextual Color badging mapping logic structure
                                        $raw_status = strtolower($row['status_name'] ?? $row['accStatus'] ?? '');
                                        $badge_context = 'bg-secondary text-white';
                                        
                                        if (str_contains($raw_status, 'won') || str_contains($raw_status, 'approved') || str_contains($raw_status, 'closed')) {
                                            $badge_context = 'bg-success-subtle text-success border border-success-subtle';
                                        } elseif (str_contains($raw_status, 'pending') || str_contains($raw_status, 'progress')) {
                                            $badge_context = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                                        } elseif (str_contains($raw_status, 'lost') || str_contains($raw_status, 'drop')) {
                                            $badge_context = 'bg-danger-subtle text-danger border border-danger-subtle';
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <div class="d-inline-flex gap-1.5">
                                                    <button class="btn btn-light border action-btn btn-sm text-success" title="Edit Record" onclick="redirectToPHPPage(<?php echo $row['id']; ?>)">
                                                        <i class="fa fa-pencil-alt fs-7"></i>
                                                    </button>
                                                    <button class="btn btn-light border action-btn btn-sm text-danger" title="Remove Record" onclick="return confirm('Are you sure you want to delete this record?') ? window.location.href='../php/delete.php?deleteAccountId=<?php echo $row['id']; ?>' : null;">
                                                        <i class="fa fa-trash-alt fs-7"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span class="text-dark fw-semibold tracking-wider"><?php echo htmlspecialchars($row['LID'] ?? 'N/A'); ?></span></td>
                                            <td><div class="text-dark fw-medium" style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($row['projTitle'] ?? 'N/A'); ?></div></td>
                                            <td><div class="text-dark fw-medium" style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($row['accExec'] ?? 'N/A'); ?></div></td>
                                            <td><span class="text-secondary"><?php echo htmlspecialchars(ucwords(strtolower($row['accName'] ?? ''))); ?></span></td>
                                            <td><span class="text-muted font-monospace"><?php echo htmlspecialchars($row['callDate'] ?? 'N/A'); ?></span></td>
                                            <td><span class="fw-semibold text-dark">₱<?php echo number_format((float)($row['proposedPrice'] ?? 0), 2); ?></span></td>
                                            <td>
                                                <span class="badge status-badge text-uppercase <?php echo $badge_context; ?>">
                                                    <?php echo htmlspecialchars($row['status_name'] ?? $row['accStatus'] ?? 'N/A'); ?>
                                                </span>
                                            </td>
                                            <td><span class="text-muted"><?php echo htmlspecialchars($row['estimatedDelivery'] ?? 'N/A'); ?></span></td>
                                            <td><span class="text-muted"><?php echo htmlspecialchars($row['progressDate'] ?? 'N/A'); ?></span></td>
                                        </tr>
                                    <?php } 
                                } else { ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">
                                            <div class="py-4">
                                                <i class="fa fa-inbox d-block mb-3 text-secondary opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-semibold text-dark m-0">No Matching Records Found</h5>
                                                <p class="text-muted small mt-1 mb-0">Try clarifying your terms or resetting active filter properties.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 4: Modernized Pagination Architecture Wrapper -->
                <?php
                    $max_pages_to_show = 8;
                    $start_page = max(1, $current_page - floor($max_pages_to_show / 2));
                    $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);

                    if (($end_page - $start_page + 1) < $max_pages_to_show) {
                        $start_page = max(1, $end_page - $max_pages_to_show + 1);
                    }
                ?>

                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4 mb-5">
                    <ul class="pagination shadow-sm rounded-3 overflow-hidden">
                        <?php
                        $filter_query_string = '';
                        $filters = ['accountExecutiveSearch', 'accountName', 'callDate', 'callDateStart', 'callDateEnd', 'globalSearch'];

                        foreach ($filters as $f) {
                            if (!empty($_GET[$f])) {
                                $filter_query_string .= '&' . $f . '=' . urlencode($_GET[$f]);
                            }
                        }

                        if ($current_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . $filter_query_string . '"><i class="fa fa-chevron-left fs-7"></i></a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link"><i class="fa fa-chevron-left fs-7"></i></span></li>';
                        }

                        for ($page = $start_page; $page <= $end_page; $page++) {
                            $active_class = ($current_page == $page) ? 'active' : '';
                            echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?page=' . $page . $filter_query_string . '">' . $page . '</a></li>';
                        }

                        if ($current_page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . $filter_query_string . '"><i class="fa fa-chevron-right fs-7"></i></a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link"><i class="fa fa-chevron-right fs-7"></i></span></li>';
                        }
                        ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        function redirectToPHPPage(id) {
            window.location.href = './editEncode.php?id=' + id;
        }
        var isDownloadRestricted = <?php echo isset($is_download_restricted) && $is_download_restricted ? 'true' : 'false'; ?>;
    </script>
    <script type="text/javascript" src="../js/downloadRestriction.js"></script>
</body>
</html>