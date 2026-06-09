<?php
include('../php/autoRedirect.php');
include('../php/accountList.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/search.css">
    <title>E-DSR - Search Page</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col-12 col-md-10 mx-auto px-4">
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap">
                    <h3 class="m-0">Search Records</h3>
                    <div class="d-flex gap-2">
                        <a href="../php/exportAllData.php?accountExecutiveSearch=<?php echo urlencode($accountExecutive ?? ''); ?>&accountName=<?php echo urlencode($accountName ?? ''); ?>&callDate=<?php echo urlencode($callDate ?? ''); ?>&callDateStart=<?php echo urlencode($callDateStart ?? ''); ?>&callDateEnd=<?php echo urlencode($callDateEnd ?? ''); ?>" class="btn btn-outline-primary">Download All</a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchAccount">Search Filter</button>
                    </div>
                </div>

                <?php include('./modals/searchFilter.php') ?>

                <div class="table-responsive py-3">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Action</th>
                                <th>Lead ID</th>
                                <th>Project Title</th>
                                <th>Client Name</th>
                                <th>Creation Date</th>
                                <th>Proposed Amount</th>
                                <th>Status</th>
                                <th>Est. Delivery</th> <th>Progress Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($accountResult)) { 
                                foreach ($accountResult as $row) { ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-success btn-sm" onclick="redirectToPHPPage(<?php echo $row['id']; ?>)">
                                                    <i class="fa fa-pen text-white"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Confirm Delete?') ? window.location.href='../php/delete.php?deleteAccountId=<?php echo $row['id']; ?>' : null;">
                                                    <i class="fa fa-trash text-white"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['LID'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['projTitle'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars(ucwords(strtolower($row['accName'] ?? ''))); ?></td>
                                        <td><?php echo htmlspecialchars($row['callDate'] ?? 'N/A'); ?></td>
                                        <td>₱<?php echo number_format((float)($row['proposedPrice'] ?? 0), 2); ?></td>
                                        <td>
                                            <span>
                                                <?php 
                                                echo htmlspecialchars($row['status_name'] ?? $row['accStatus'] ?? 'N/A'); 
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                <?php echo htmlspecialchars($row['estimatedDelivery'] ?? 'N/A'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['progressDate'] ?? 'N/A'); ?></td>
                                    </tr>
                                <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">No records found matching your filters.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <?php
                    $max_pages_to_show = 10;
                    $start_page = max(1, ($current_page ?? 1) - floor($max_pages_to_show / 2));
                    $end_page = min(($total_pages ?? 1), $start_page + $max_pages_to_show - 1);

                    if ($end_page - $start_page + 1 < $max_pages_to_show) {
                        $start_page = max(1, $end_page - $max_pages_to_show + 1);
                    }
                ?>

                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php
                        $filter_query_string = '';
                        $filters = ['accountExecutiveSearch', 'accountName', 'callDate', 'callDateStart', 'callDateEnd'];

                        foreach ($filters as $filter) {
                            if (!empty($_GET[$filter])) {
                                $filter_query_string .= '&' . $filter . '=' . urlencode($_GET[$filter]);
                            }
                        }

                        if (($current_page ?? 1) > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . $filter_query_string . '">&laquo;</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
                        }

                        for ($page = $start_page; $page <= $end_page; $page++) {
                            $active_class = (($current_page ?? 1) == $page) ? 'active' : '';
                            echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?page=' . $page . $filter_query_string . '">' . $page . '</a></li>';
                        }

                        if (($current_page ?? 1) < ($total_pages ?? 1)) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . $filter_query_string . '">&raquo;</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
                        }
                        ?>
                    </ul>
                </nav>
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