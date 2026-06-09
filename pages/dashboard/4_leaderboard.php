<style>
/* Leaderboard Graph Element Structures matching image_b3dc0a.png layout rules */
.leaderboard-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    width: 100%;
}

.leaderboard-label {
    width: 130px;
    font-size: 0.72rem;
    font-weight: 600;
    color: #495057;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 8px;
    text-align: right;
}

.leaderboard-bar-container {
    flex-grow: 1;
    background-color: transparent;
    display: flex;
    align-items: center;
    position: relative;
}

.leaderboard-bar-fill {
    height: 24px;
    background-color: #0d6efd; /* Vibrant blue matches image reference */
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.leaderboard-value {
    font-size: 0.72rem;
    font-weight: 700;
    color: #212529;
    padding-left: 6px;
}
</style>

<div class="col-12 col-md-6 col-lg-4"> 
    <div class="main-content-card p-4 shadow-sm d-flex flex-column">
        <div class="w-100 mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="text-uppercase text-secondary tracking-wider fw-bold small m-0">
                    <i class="fa-solid fa-trophy me-2 text-warning"></i>Top 5 Sales Executive
                </h6>
                <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.68rem; font-weight: 600;">Rankings</span>
            </div>
            <hr class="my-2 text-black-50">
        </div>

        <!-- Render Target Stack Container for JavaScript Entry Injection -->
        <div id="leaderboard-chart-container" class="d-flex flex-column justify-content-center flex-grow-1">
            <!-- Dynamic rows will be inserted here by JavaScript -->
            <div class="text-center py-4 text-muted small">Loading metric records...</div>
        </div>
    </div>
</div>