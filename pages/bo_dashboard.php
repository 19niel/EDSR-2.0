<?php
include('../php/autoRedirect.php');
include('../php/accountList.php');
include('../php/db_conn.php'); 

// Fetch dynamic configuration target limits directly from database
$kpiSalesTarget = 5000000.00; // System fallback default
$settingsQuery = "SELECT setting_value FROM dashboard_settings WHERE setting_key = 'kpi_sales_target' LIMIT 1";
$settingsResult = mysqli_query($conn, $settingsQuery);

if ($settingsResult && mysqli_num_rows($settingsResult) > 0) {
    $settingsRow = mysqli_fetch_assoc($settingsResult);
    $kpiSalesTarget = floatval($settingsRow['setting_value']);
}
mysqli_close($conn); 

$userRole = isset($_SESSION['category']) ? strtoupper(trim($_SESSION['category'])) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-DSR BO Dashboard — Real-time sales KPIs, pipeline funnel, leaderboard, and project tracking.">

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

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Theme & App CSS -->
    <link rel="stylesheet" href="../css/theme.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/dashboard_2.css">

    <title>E-DSR — BO Dashboard</title>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <main class="col-12 col-xl-11 mx-auto">
                
                <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom flex-wrap gap-3">
                    <div>
                        <h5 class="m-0 fw-bold tracking-tight" style="color:var(--text-primary);">BO Dashboard</h5>
                        <p class="small m-0 mt-1" style="color:var(--text-secondary);">Data Generation Log for Sales Department</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="../php/exportAllData.php" class="btn btn-white border border-secondary-subtle btn-light px-3 fw-medium d-flex align-items-center gap-2 shadow-sm rounded-3">
                            <i class="fa fa-download text-secondary"></i> Export Dataset
                        </a>
                    <?php if ($userRole === 'ADMIN' || $userRole === 'VP'): ?>
                        <a href="bo_dashboardSettings.php" class="btn btn-outline-secondary px-3 fw-medium d-flex align-items-center gap-2 shadow-sm rounded-3">
                            <i class="fa-solid fa-gear text-secondary"></i> Settings
                        </a>
                    <?php endif; ?>
                    </div>
                </div>

                <!-- KPI Summary Cards -->
                <?php include('dashboard/0_kpiSummaryCards.php'); ?>
                                
                <!-- ==========================================================================
                FIXED 3x2 MASTER GRID ENGINE (NO-WRAP VIEWPORT ARCHITECTURE)
                ========================================================================== -->
                <!-- row-cols-md-2 handles tablet collapse, while row-cols-lg-3 forces a 3-wide split on desktop monitors -->
                <div class="row g-2 row-cols-1 row-cols-md-2 row-cols-lg-3 align-items-stretch">
                    
                    <!-- GRID ROW 1, SLOT 1: Sales Meter -->
                    <div class="col d-flex">
                        <?php include('dashboard/1_kpiMeter.php'); ?>
                    </div>

                    <!-- GRID ROW 1, SLOT 2: Sales Meter -->
                    <div class="col d-flex">
                        <?php include('dashboard/2_pipeline.php'); ?>
                    </div>

                    <div class="col d-flex">
                        <?php include('dashboard/3_newAndExisting.php'); ?>
                    </div>

                    <div class="col d-flex">
                        <?php include('dashboard/4_leaderboard.php'); ?>
                    </div>

                    
                    <div class="col d-flex">
                        <?php include('dashboard/5_wonProjects.php'); ?>
                    </div>

                    <div class="col d-flex">
                        <?php include('dashboard/6_agingProjects.php'); ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        window.dashboardConfig = {
            salesTarget: <?php echo $kpiSalesTarget; ?>
        };
    </script>

    <script src="../js/db_1kpiRealtime.js"></script>
    <script src="../js/db_2pipelineRealtime.js"></script> 
    <script src="../js/db_3newAndExistingRealtime.js"></script>
    <script src="../js/db_4leaderboardRealtime.js"></script>
    <script src="../js/db_5wonProjectsRealtime.js"></script>
    <script src="../js/db_6agingProjectsRealTime.js"></script> </body>

    <script>
        var category = "<?php echo $_SESSION['category'] ?? $category ?? ''; ?>";
    </script>
    <script src="../js/hideElement.js"></script>
</body>
</html>