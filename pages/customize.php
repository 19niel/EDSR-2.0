<?php
    // Include necessary PHP files for auto redirection, fetching category list, and adding a new category.
    include('../php/autoRedirect.php');
    include('../php/categoryList.php');
    include('../php/addCategory.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="E-DSR Category Management — Admin interface to view, modify, and delete dropdown selections.">

    <!-- Anti-flash: apply saved theme before render -->
    <script>
    (function(){
        var t = localStorage.getItem('edsr-theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
        document.documentElement.setAttribute('data-bs-theme', t);
        window.EDSR_THEME = t;
    })();
    </script>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.2 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Theme & App CSS -->
    <link rel="stylesheet" href="../css/theme.css" />
    <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
    <link rel="stylesheet" href="/e-dsr/css/table.css" />
    
    <title>E-DSR - Category Page</title>
    
    <!-- Shared component styling wrappers -->
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
        }
        .main-content-card {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }
        .table thead th {
            background-color: var(--table-header-bg) !important;
            color: var(--text-secondary) !important;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid var(--table-border) !important;
        }
        .table tbody td {
            padding: 12px 16px;
            font-size: 0.875rem;
            color: var(--text-primary) !important;
            border-bottom: 1px solid var(--table-border) !important;
        }
        .table-hover tbody tr:hover {
            background-color: var(--table-row-hover) !important;
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
    
    <!-- JavaScript Initialization Properties -->
    <script>var category = "<?php echo $category; ?>";</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="../js/editEncode.js" defer></script>
</head>
<body>
    <!-- Include header -->
    <?php include('header.php'); ?>
    
    <div class="container-fluid py-4">
        <div class="row">
            <main class="col-12 col-xl-11 mx-auto">
                
                <!-- Section 1: Page Header & Action Controls -->
                <div class="d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom flex-wrap gap-3">
                    <div>
                        <h3 class="m-0 fw-bold tracking-tight text-dark">Categories</h3>
                        <p class="text-muted small m-0 mt-1">Manage global context tags, clean field drop items, and record parameters.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary px-3 fw-medium d-flex align-items-center gap-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#addCategory">
                            <i class="fa fa-plus"></i> Add Category
                        </button>
                    </div>
                </div>
                
                <!-- Include modal for adding category -->
                <?php include('./modals/addCategory.php') ?>
                
                <!-- Section 2: Records Presentation Card Canvas Box -->
                <div class="main-content-card shadow-sm overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0 modern-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 110px;">Action</th>
                                    <th>Field Group</th>
                                    <th>Category Label Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                                    while ($category_result_row = mysqli_fetch_assoc($categoryResult)) { ?>
                                        <tr>
                                            <td class="text-center">
                                                <div class="d-inline-flex justify-content-center">
                                                    <!-- Re-styled clean Action Button -->
                                                    <button class="btn btn-light border action-btn btn-sm text-danger" title="Delete Category" onclick="return confirm('Delete Category?') ? window.location.href='../php/delete.php?category_id=<?php echo $category_result_row['id']; ?>&category_name=<?php echo urlencode($category_result_row['category_name']); ?>' : null;">
                                                        <i class="fa fa-trash-alt fs-7"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 text-uppercase fw-semibold" style="font-size:0.75rem;"><?php echo htmlspecialchars($category_result_row['field']); ?></span></td>
                                            <td><span class="text-dark fw-medium"><?php echo htmlspecialchars($category_result_row['category_name']); ?></span></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5">
                                            <div class="py-4">
                                                <i class="fa fa-folder-open d-block mb-3 text-secondary opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-semibold text-dark m-0">No Categories Present</h5>
                                                <p class="text-muted small mt-1 mb-0">Get started by creating a dropdown option category classification label string.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Section 3: Modernized Pagination Architecture Wrapper -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4 mb-5">
                    <ul class="pagination shadow-sm rounded-3 overflow-hidden">
                        <!-- Previous Page Arrow -->
                        <?php if ($current_page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $current_page - 1; ?>"><i class="fa fa-chevron-left fs-7"></i></a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><span class="page-link"><i class="fa fa-chevron-left fs-7"></i></span></li>
                        <?php endif; ?>

                        <!-- Page Elements Loop -->
                        <?php for ($page = 1; $page <= $total_pages; $page++) { 
                            $active_class = ($current_page == $page) ? 'active' : ''; ?>
                            <li class="page-item <?php echo $active_class; ?>"><a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                        <?php } ?>

                        <!-- Next Page Arrow -->
                        <?php if ($current_page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $current_page + 1; ?>"><i class="fa fa-chevron-right fs-7"></i></a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><span class="page-link"><i class="fa fa-chevron-right fs-7"></i></span></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <!-- Unified Script Handlers -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>function redirectToPHPPage(id) { window.location.href = '/e-dsr/php/accountSelect.php?id=' + id; }</script>
    <script src="../js/hideElement.js"></script>
    <script type="text/javascript" src="../js/autoFill.js"></script>
    <script type="text/javascript" src="../js/download.js"></script>
</body>
</html>