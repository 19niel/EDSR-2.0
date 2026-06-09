<?php
include('../php/autoRedirect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/e-dsr/css/sidebar.css">
    <link rel="stylesheet" href="/e-dsr/css/counters.css">
    <title>Quick Access</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <?php include ('header.php'); ?>

    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 p-0 overflow-auto" style="height: 88vh">
                <div class="sticky-top d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-5 pt-3 pb-3 border-bottom bg-white"
                    style="height: 8vh">
                    <h1 class="h3">Quick Access</h1>
                </div>
            </main>
        </div>
    </div>
    <script src="../js/barLineGraph.js"></script>
    <script>
        var category = "<?php echo $category; ?>";
    </script>
    <script src="../js/hideElement.js"></script>

</body>

</html>