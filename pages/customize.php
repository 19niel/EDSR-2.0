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
        
        <!-- Bootstrap CSS for styling -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
        
        <!-- Font Awesome CSS for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Custom CSS for sidebar and table -->
        <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
        <link rel="stylesheet" href="/e-dsr/css/table.css" />
        
        <title>E-DSR - Category Page</title>
        
        <!-- Inline CSS for table border styling -->
        <style>.table>:not(:last-child)>:last-child>* { border-bottom-color: inherit; }</style>
        
        <!-- JavaScript to embed PHP variable into JavaScript -->
        <script>var category = "<?php echo $category; ?>";</script>
        
        <!-- External JavaScript libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
        <script src="../js/editEncode.js" defer></script>
    </head>
    <body>
        <!-- Include header -->
        <?php include('header.php'); ?>
        
        <div class="container-fluid">
            <div class="row">
                <main class="col-12 col-md-10 mx-auto px-4">
                    <!-- Page header with title and add category button -->
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap">
                        <h3 class="m-0">Categories</h3>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategory">Add Category</button>
                        </div>
                    </div>
                    
                    <!-- Include modal for adding category -->
                    <?php include('./modals/addCategory.php') ?>
                    
                    <!-- Table to display categories -->
                    <div class="table-responsive py-3">
                        <table id="largeTable" class="table table-sm table-striped table-hover align-middle">
                            <thead class="bg-light">
                                <tr><th>Action</th><th>Field</th><th>Category Name</th></tr>
                            </thead>
                            <tbody>
                                <!-- Loop through categories and display each one in a table row -->
                                <?php while ($category_result_row = mysqli_fetch_assoc($categoryResult)) { ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- Button to delete category, with confirmation prompt -->
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete Category?') ? window.location.href='../php/delete.php?category_id=<?php echo $category_result_row['id']; ?>&category_name=<?php echo $category_result_row['category_name']; ?>' : null;">
                                                    <i class="fa fa-trash" style="color: white"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td><?php echo $category_result_row['field']; ?></td>
                                        <td><?php echo $category_result_row['category_name']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for navigating through pages of categories -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                            </li>
                            <?php for ($page = 1; $page <= $total_pages; $page++) { ?>
                                <li class="page-item <?php if ($current_page == $page) echo 'active'; ?>">
                                    <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                            </li>
                        </ul>
                    </nav>
                </main>
            </div>
        </div>
        
        <!-- Bootstrap JavaScript bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- JavaScript function to redirect to a different page with a given ID -->
        <script>function redirectToPHPPage(id) { window.location.href = '/e-dsr/php/accountSelect.php?id=' + id; }</script>
        
        <!-- JavaScript to embed PHP variable into JavaScript -->
        <script>var category = "<?php echo $category; ?>";</script>
        
        <!-- External JavaScript files -->
        <script src="../js/hideElement.js"></script>
        <script type="text/javascript" src="../js/autoFill.js"></script>
        <script type="text/javascript" src="../js/download.js"></script>
    </body>
</html>