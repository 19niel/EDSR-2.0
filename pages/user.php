<?php
include('../php/autoRedirect.php');
include('../php/userpagination.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="E-DSR User Management — Admin interface to view, modify, and delete user accounts.">

    <!-- Anti-flash: apply saved theme before render -->
    <script>
    (function(){
        var t = localStorage.getItem('edsr-theme');
        if (!t) t = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
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
    <link rel="stylesheet" href="../css/sidebar.css" />
    <link rel="stylesheet" href="../css/table.css" />

    <title>Users — E-DSR</title>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Main Content -->
            <main class="col-12 col-xl-11 mx-auto">
                
                <!-- Section 1: Page Header & Action Controls -->
                <div class="d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom flex-wrap gap-3">
                    <div>
                        <h3 class="m-0 fw-bold tracking-tight" style="color:var(--text-primary);">Users</h3>
                        <p class="text-muted small m-0 mt-1">Manage user accounts, roles, statuses, and login sessions.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary px-3 fw-medium d-flex align-items-center gap-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#addUser">
                            <i class="fa fa-plus"></i> Add User
                        </button>
                    </div>
                </div>

                <!-- Modals -->
                <?php include('./modals/addUser.php'); ?>
                <?php include('./modals/editUser.php'); ?>

                <!-- Section 2: Users Table presentation box -->
                <div class="main-content-card shadow-sm overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0 modern-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 140px;">Actions</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Department</th>
                                    <th>Category</th>
                                    <th>Last Log in</th>
                                    <th>Status</th>
                                    <th>Branch</th>
                                    <th>Last Encoded</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $latestEncoded = [];

                                while ($encodedRow = mysqli_fetch_assoc($encodedList)) {
                                    $latestEncoded[$encodedRow['accexec_id']] = $encodedRow; // Keyed by user ID
                                } ?>
                                <?php while ($row = mysqli_fetch_assoc($userList)) { // Fetch paginated user list ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-2 justify-content-center">
                                                <!-- Edit Button -->
                                                <button class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 6px;" onclick="editUser(<?php echo $row['id']; ?>)" title="Edit User">
                                                    <i class="fa fa-pen" style="font-size: 0.8rem;"></i>
                                                </button>
                                                
                                                <!-- Delete Button -->
                                                <button class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 6px;" onclick="return confirm('Confirm Delete?') ? window.location.href='../php/delete.php?deleteUserId=<?php echo $row['id']; ?>' : null;" title="Delete User">
                                                    <i class="fa fa-trash" style="font-size: 0.8rem;"></i>
                                                </button>
                                                
                                                <!-- Status Toggle Button -->
                                                <button onclick="updateStatus('<?php echo $row['id']; ?>', '<?php echo $row['stat']; ?>')" 
                                                    class="btn btn-sm d-flex align-items-center justify-content-center <?php echo $row['stat'] == 'online' ? 'btn-primary' : 'btn-outline-secondary'; ?>" 
                                                    style="width: 30px; height: 30px; border-radius: 6px;" title="Toggle Status">
                                                    <?php echo $row['stat'] == 'online' ? '<i class="fa-solid fa-check" style="font-size: 0.8rem;"></i>' : '<i class="fa-solid fa-x" style="font-size: 0.8rem;"></i>'; ?>
                                                </button>
                                            </div>
                                        </td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['dept']; ?></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $row['log_at']; ?></td>
                                        <td>
                                            <span class="badge <?php echo $row['stat'] === 'online' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary text-white'; ?> px-2 py-1">
                                                <?php echo htmlspecialchars($row['stat']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row['branch']; ?></td>
                                        <td>
                                            <?php 
                                                $lastEncoded = $latestEncoded[$row['id']] ?? null;
                                                echo $lastEncoded ? $lastEncoded['created_at'] : '—'; 
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- End of main-content-card -->

                <!-- Section 3: Pagination Wrapper -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4 mb-5">
                    <ul class="pagination shadow-sm rounded-3 overflow-hidden">
                        <?php
                        if ($current_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . '"><i class="fa fa-chevron-left fs-7"></i></a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link"><i class="fa fa-chevron-left fs-7"></i></span></li>';
                        }

                        for ($page = 1; $page <= $total_pages; $page++) {
                            $active_class = ($current_page == $page) ? 'active' : '';
                            echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
                        }

                        if ($current_page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . '"><i class="fa fa-chevron-right fs-7"></i></a></li>';
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="../js/reveal.js"></script>
    <script type="text/javascript" src="../js/edit.js"></script>
    <script>
        function updateStatus(id, status) {
            var newStatus = status === 'online' ? 'offline' : 'online'; // Toggle status
            $.ajax({
                url: "../php/update.php",
                type: "POST",
                data: { id: id, status: newStatus },
                success: function (response) {
                    location.reload();
                },
                error: function (xhr, id, error) {
                    console.error("Failed to update status:", error);
                }
            });
        }
    </script>
    </body>
</html>
