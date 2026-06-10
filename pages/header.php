<nav class="sticky-top navbar navbar-expand-lg navbar-dark bg px-lg-5">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <a class="navbar-brand" href="welcome_page.php">
                <h4 class="m-0">E-DSR</h4>
            </a>
            <span class="ms-3 text-white"><strong><?php echo $name; ?></strong></span>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="welcome_page.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="encode.php">Encode</a></li>
                <li id="performanceTab" class="nav-item"><a class="nav-link text-white" href="performance.php">Performance</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="bo_search.php">Search</a></li>
                <li id="users" class="nav-item admin"><a class="nav-link text-white" href="user.php">User</a></li>
                <li id="leave" class="nav-item admin"><a class="nav-link text-white" href="leaveData.php">Leave Data</a></li>
                <li id="customize" class="nav-item admin"><a class="nav-link text-white" href="customize.php">Customize</a></li>
                <li id="bo_dashboard" class="nav-item admin"><a class="nav-link text-white" href="bo_dashboard.php">BO Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../php/logout.php?logoutid=<?php echo $name; ?>" onclick="return confirm('Logout Account?')">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
