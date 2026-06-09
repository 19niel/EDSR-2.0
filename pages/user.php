<?php
include('../php/autoRedirect.php');
include('../php/userpagination.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="../css/sidebar.css" />
    <link rel="stylesheet" href="/e-dsr/css/table.css" />
    <title>Users - E-DSR</title>
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"
    ></script>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-12 col-md-10 mx-auto px-4">
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap">
                    <h3 class="m-0">Users</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">
                        Add User
                    </button>
                </div>
                <div class="row g-3 py-3">
                    <!-- Modal for Adding User -->
                    <?php include('./modals/addUser.php'); ?>
                    <?php include('./modals/editUser.php'); ?>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Action</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Last Log in</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Branch</th>
                                    <th scope="col">Last Encoded</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $latestEncoded = [];

                                while ($encodedRow = mysqli_fetch_assoc($encodedList)) {
                                    $latestEncoded[$encodedRow['accexec_id']] = $encodedRow; // Keyed by user ID
                                } ?>
                                <?php while ($row = mysqli_fetch_assoc($userList)) { // Fetch paginated user list ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- Edit Button -->
                                                <button class="btn btn-success btn-sm" onclick="editUser(<?php echo $row['id']; ?>)">
                                                    <i class="fa fa-pen" style="color: white"></i>
                                                </button>
                                                
                                                <!-- Delete Button -->
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Confirm Delete?') ? window.location.href='../php/delete.php?deleteUserId=<?php echo $row['id']; ?>' : null;">
                                                    <i class="fa fa-trash" style="color: white"></i>
                                                </button>
                                                
                                                <!-- Status Toggle Button -->
                                                <button onclick="updateStatus('<?php echo $row['id']; ?>', '<?php echo $row['stat']; ?>')" 
                                                    class="btn btn-sm <?php echo $row['stat'] == 'online' ? 'btn-primary' : 'btn-secondary'; ?>">
                                                    <?php echo $row['stat'] == 'online' ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-x"></i>'; ?>
                                                </button>
                                            </div>
                                        </td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['dept']; ?></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $row['log_at']; ?></td>
                                        <td><?php echo $row['stat']; ?></td>
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
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <!-- Previous Button -->
                            <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php for ($page = 1; $page <= $total_pages; $page++) { ?>
                                <li class="page-item <?php if ($current_page == $page) echo 'active'; ?>">
                                    <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                </li>
                            <?php } ?>

                            <!-- Next Button -->
                            <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
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
