<?php
include('../php/autoRedirect.php');
include('../php/accountList.php');
include('../php/db_conn.php');

$statusMessageHtml = "";

// 🎯 Handle Form Submissions in place before HTML layout compilation renders
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Processing Panel Action 1: KPI Sales Target Limit Meter
    if (isset($_POST['target_goal'])) {
        $targetGoal = floatval($_POST['target_goal']);
        if ($targetGoal > 0) {
            $updateQuery = "INSERT INTO dashboard_settings (setting_key, setting_value) 
                            VALUES ('kpi_sales_target', '$targetGoal')
                            ON DUPLICATE KEY UPDATE setting_value = '$targetGoal'";
            if (mysqli_query($conn, $updateQuery)) {
                $statusMessageHtml = '
                    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>Sales target configuration metric saved successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            } else {
                $statusMessageHtml = '<div class="alert alert-danger">Error writing target value: ' . mysqli_error($conn) . '</div>';
            }
        } else {
            $statusMessageHtml = '<div class="alert alert-warning">Sales target metrics must be greater than zero.</div>';
        }
    }
    
    // Processing Panel Action 2: Critical Aging Stagnation Target Threshold Limit
    if (isset($_POST['aging_days_threshold'])) {
        $agingDays = intval($_POST['aging_days_threshold']);
        if ($agingDays >= 1) {
            $updateQuery = "INSERT INTO dashboard_settings (setting_key, setting_value) 
                            VALUES ('aging_days_threshold', '$agingDays')
                            ON DUPLICATE KEY UPDATE setting_value = '$agingDays'";
            if (mysqli_query($conn, $updateQuery)) {
                $statusMessageHtml = '
                    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>Stagnation day aging rules saved successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            } else {
                $statusMessageHtml = '<div class="alert alert-danger">Error writing aging values: ' . mysqli_error($conn) . '</div>';
            }
        } else {
            $statusMessageHtml = '<div class="alert alert-warning">Stagnation limit rules require at least 1 day.</div>';
        }
    }
}

// 🎯 Fetch current live configurations from the database to pre-populate input cells
$currentSalesTarget = 5000000.00; // System baseline fallback limit
$agingDaysThreshold = 60;         // Stagnation baseline fallback rule index

$settingsQuery = "SELECT setting_key, setting_value FROM dashboard_settings WHERE setting_key IN ('kpi_sales_target', 'aging_days_threshold')";
$settingsResult = mysqli_query($conn, $settingsQuery);

if ($settingsResult) {
    while ($row = mysqli_fetch_assoc($settingsResult)) {
        if ($row['setting_key'] === 'kpi_sales_target') {
            $currentSalesTarget = floatval($row['setting_value']);
        } elseif ($row['setting_key'] === 'aging_days_threshold') {
            $agingDaysThreshold = intval($row['setting_value']);
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/table.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    
    <title>E-DSR - BO Dashboard Settings</title>
    
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
            min-height: 250px; /* Perfectly aligned matching box dimensions across row axis */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
        }
        .border-dashed {
            border-style: dashed !important;
            background-color: #fdfdfd;
            justify-content: center !important;
            align-items: center !important;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <main class="col-12 col-xl-11 mx-auto">
                
                <div class="d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom flex-wrap gap-3">
                    <div>
                        <h3 class="m-0 fw-bold tracking-tight text-dark">BO Dashboard Configurations</h3>
                        <p class="text-muted small m-0 mt-1">Modify panel operational limits in place. Cards match the main layout placement grid.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="bo_dashboard.php" class="btn btn-outline-secondary px-3 fw-medium d-flex align-items-center gap-2 shadow-sm rounded-3">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <div id="settingsAlertPlaceholder" class="mb-3">
                    <?php echo $statusMessageHtml; ?>
                </div>

                <div class="row g-4">
                    
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="main-content-card p-4 shadow-sm text-start">
                            <div class="w-100 mb-3">
                                <h6 class="text-uppercase text-primary tracking-wider fw-bold small m-0">
                                    <i class="fa-solid fa-sliders me-2"></i>Cell 1: KPI Sales Meter
                                </h6>
                                <hr class="my-2 text-black-50">
                            </div>
                            
                            <form id="kpiSalesTargetForm" method="POST" action="" class="w-100 d-flex flex-column h-100 justify-content-between">
                                <div class="mb-3 flex-grow-1">
                                    <label for="targetAmountInput" class="form-label small fw-bold text-secondary text-uppercase" style="font-size:0.68rem;">Target Amount (₱)</label>
                                    <div class="input-group mb-2 input-group-sm">
                                        <span class="input-group-text bg-light fw-bold text-secondary">₱</span>
                                        <input type="number" step="0.01" min="1" class="form-control fw-bold fs-6 text-dark" id="targetAmountInput" name="target_goal" value="<?php echo $currentSalesTarget; ?>" required>
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.65rem;">
                                        Shorthand Label Preview: <strong id="shorthandPreview" class="text-dark">--</strong>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3 fw-semibold mt-2">
                                    <i class="fa fa-save me-2"></i>Save Panel Target
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="main-content-card p-4 shadow-sm text-center border-dashed">
                            <span class="text-muted small">Cell 2 Settings Placeholder</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="main-content-card p-4 shadow-sm text-center border-dashed">
                            <span class="text-muted small">Cell 3 Settings Placeholder</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="main-content-card p-4 shadow-sm text-center border-dashed">
                            <span class="text-muted small">Cell 4 Settings Placeholder</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="main-content-card p-4 shadow-sm text-center border-dashed">
                            <span class="text-muted small">Cell 5 Settings Placeholder</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="main-content-card p-4 shadow-sm text-start">
                            <div class="w-100 mb-3">
                                <h6 class="text-uppercase text-danger tracking-wider fw-bold small m-0">
                                    <i class="fa-solid fa-clock-history me-2"></i>Cell 6: Aging Stagnation Alert
                                </h6>
                                <hr class="my-2 text-black-50">
                            </div>
                            
                            <form id="agingStagnationConfigForm" method="POST" action="" class="w-100 d-flex flex-column h-100 justify-content-between">
                                <div class="mb-3 flex-grow-1">
                                    <label for="agingDaysInput" class="form-label small fw-bold text-secondary text-uppercase" style="font-size:0.68rem;">Stagnation Rule Threshold (Days)</label>
                                    <div class="input-group mb-2 input-group-sm">
                                        <span class="input-group-text bg-light text-secondary"><i class="fa-solid fa-calendar-day"></i></span>
                                        <input type="number" min="1" class="form-control fw-bold text-dark" id="agingDaysInput" name="aging_days_threshold" value="<?php echo $agingDaysThreshold; ?>" required>
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.65rem;">
                                        Records with no changes for this many days will flag on the live summary dashboard tracking index row view.
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm btn-danger w-100 rounded-3 fw-semibold mt-2">
                                    <i class="fa fa-save me-2"></i>Save Aging Threshold
                                </button>
                            </form>
                        </div>
                    </div>

                </div> 
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        // Formatter function to show dynamic shorthand preview inside field forms
        function formatToShorthand(value) {
            const num = parseFloat(value);
            if (isNaN(num) || num <= 0) return "--";
            if (num >= 1000000) {
                return (num / 1000000).toFixed(num % 1000000 === 0 ? 0 : 1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(num % 1000 === 0 ? 0 : 1) + 'K';
            }
            return num.toString();
        }

        $(document).ready(function() {
            const $inputField = $('#targetAmountInput');
            const $previewText = $('#shorthandPreview');

            // Update numerical shorthand preview text live on input actions
            $inputField.on('input', function() {
                $previewText.text(formatToShorthand($(this).val()));
            });
            
            // Execute once on template load initialization
            $previewText.text(formatToShorthand($inputField.val()));
        });
    </script>
</body>
</html>