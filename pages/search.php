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
    <script src="../js/editEncode.js" defer></script>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-12 col-md-10 mx-auto px-4">
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap">
                    <h3 class="m-0">Search</h3>
                    <div class="d-flex gap-2">
                        <a href="../php/exportAllData.php?accountExecutiveSearch=<?php echo urlencode($accountExecutive); ?>&accountName=<?php echo urlencode($accountName); ?>&callDate=<?php echo urlencode($callDate); ?>&callDateStart=<?php echo urlencode($callDateStart); ?>&callDateEnd=<?php echo urlencode($callDateEnd); ?>" class="btn btn-outline-primary">Download All</a>
                        <!-- <button id="exportButton" onclick="exportToExcel()" type="button" class="btn btn-outline-primary">Download</button> -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchAccount">Search Accounts</button>
                        <button type="button" class="btn btn-secondary" onclick="toggleColumns()">Toggle Columns</button>
                    </div>
                </div>

                <!-- Modal for Searching Accounts -->
                <?php include('./modals/searchFilter.php') ?>

                <!-- Search Results Table -->
                <div class="table-responsive py-3">
                    <table id="largeTable" class="table table-sm table-striped table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Action</th>
                                <th>Account Executive</th>
                                <th>Account Name</th>
                                <th>Call Date</th>
                                <th>End User</th>
                                <th>Address</th>
                                <th>Area</th>
                                <th>Account Category</th>
                                <th>Segment</th>
                                <th>Industry</th>
                                <th>Account Source</th>
                                <th>Contact Person</th>
                                <th>Designation</th>
                                <th>Contact Number</th>
                                <th>Email Address</th>
                                <th>Decision Maker</th>
                                <th>DM Contact Number</th>
                                <th>DM Designation</th>
                                <th>Existing System</th>
                                <th>Contract Type</th>
                                <th>Contract Start Date</th>
                                <th>Contract End Date</th>
                                <th>Proposed System</th>
                                <th>Proposed Price</th>
                                <th>Payment Terms</th>
                                <th>Call Nature</th>
                                <th>Account Status</th>
                                <th>Follow Up Action</th>
                                <th>What Transpired</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accountResult as $row) { ?>
                                <tr>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success btn-sm editButton" onclick="redirectToPHPPage(<?php echo $row['id']; ?>)">
                                                <i class="fa fa-pen" style="color: white"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm deleteButton" onclick="return confirm('Confirm Delete?') ? window.location.href='../php/delete.php?deleteAccountId=<?php echo $row['id']; ?>' : null;">
                                                <i class="fa fa-trash" style="color: white"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><?php echo $row['accExec']; ?></td>
                                    <td><?php echo ucwords(strtolower($row['accName'])); ?></td>
                                    <td><?php echo $row['callDate']; ?></td>
                                    <td><?php echo $row['endUser']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['area']; ?></td>
                                    <td><?php echo $row['accCat']; ?></td>
                                    <td><?php echo $row['segment']; ?></td>
                                    <td><?php echo $row['industry']; ?></td>
                                    <td><?php echo $row['accSource']; ?></td>
                                    <td><?php echo $row['contactPerson']; ?></td>
                                    <td><?php echo $row['designation']; ?></td>
                                    <td><?php echo $row['contactNumber']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['decisionMaker']; ?></td>
                                    <td><?php echo $row['dmNumber']; ?></td>
                                    <td><?php echo $row['dmDesignation']; ?></td>
                                    <td><?php echo $row['existingSystem']; ?></td>
                                    <td><?php echo $row['contactType']; ?></td>
                                    <td><?php echo $row['startContractDate']; ?></td>
                                    <td><?php echo $row['endContractDate']; ?></td>
                                    <td><?php echo $row['proposedSystem']; ?></td>
                                    <td><?php echo $row['proposedPrice']; ?></td>
                                    <td><?php echo $row['paymentTerms']; ?></td>
                                    <td><?php echo $row['callNature']; ?></td>
                                    <td><?php echo $row['accStatus']; ?></td>
                                    <td><?php echo $row['actionFollow']; ?></td>
                                    <td><?php echo $row['whatTranspired']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <?php
                    // Determine the range of pages to show
                    $max_pages_to_show = 10;

                    // Calculate the start and end pages dynamically
                    $start_page = max(1, $current_page - floor($max_pages_to_show / 2));
                    $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);

                    // Adjust start page if near the end of the range
                    if ($end_page - $start_page + 1 < $max_pages_to_show) {
                        $start_page = max(1, $end_page - $max_pages_to_show + 1);
                    }
                    ?>


                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php
                        // Build the query string for filters
                        $filter_query_string = '';
                        $filters = ['accountExecutiveSearch', 'accountName', 'callDate', 'callDateStart', 'callDateEnd'];

                        foreach ($filters as $filter) {
                            if (!empty($_GET[$filter])) {
                                $filter_query_string .= '&' . $filter . '=' . urlencode($_GET[$filter]);
                            }
                        }

                        // Previous Button
                        if ($current_page > 1) {
                            $prev_page = $current_page - 1;
                            echo '<li class="page-item">
                                    <a class="page-link" href="?page=' . $prev_page . $filter_query_string . '" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
                        }

                        // Page Number Links
                        for ($page = $start_page; $page <= $end_page; $page++) {
                            $active_class = ($current_page == $page) ? 'active' : '';
                            echo '<li class="page-item ' . $active_class . '">
                                    <a class="page-link" href="?page=' . $page . $filter_query_string . '">' . $page . '</a>
                                </li>';
                        }

                        // Next Button
                        if ($current_page < $total_pages) {
                            $next_page = $current_page + 1;
                            echo '<li class="page-item">
                                    <a class="page-link" href="?page=' . $next_page . $filter_query_string . '" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
                        }
                        ?>
                    </ul>
                </nav>



            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function redirectToPHPPage(id) {
            window.location.href = '/e-dsr/php/accountSelect.php?id=' + id;
        }
    </script>
    <script>
        var category = "<?php echo $category; ?>";
    </script>
    <script src="../js/hideElement.js"></script>
    <script type="text/javascript" src="../js/autoFill.js"></script>
    <script type="text/javascript" src="../js/download.js"></script>
    <script type="text/javascript" src="../js/toggleColumns.js"></script>
    <script>
        var isDownloadRestricted = <?php echo $is_download_restricted ? 'true' : 'false'; ?>;
    </script>
    <script type="text/javascript" src="../js/downloadRestriction.js"></script>
</body>
</html>
