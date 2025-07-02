<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_role'])) {
    header('Location: login.php');
    exit();
}

$currentPage = 'home';
include_once('inc/head.php');
?>
<h1>💎Ditto Custom CRM💎</h1>
<?php include_once('inc/menu.php'); ?>

<h3>👋 Welcome to Ditto Custom CRM</h3>

<div class="card mt-4">
    <div class="card-header">
        🔐User Information 
    </div>
    <div class="card-body">
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! 🗝️</p>
        <p>You are logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong></p>
        
        <?php if ($_SESSION['user_role'] === 'manager'): ?>
            <p>As a manager, you have full access to all sections of the CRM system, including the Won Customers section.</p>
        <?php else: ?>
            <p>As a sales department user, you have access to all sections except the Won Customers section.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <strong>🔗 Quick Links 🔗<br></strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="sales/tasks.php" class="btn btn-primary btn-lg d-block">
                    <i class="fas fa-tasks me-2"></i>Tasks
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="sales/leads.php" class="btn btn-success btn-lg d-block">
                    <i class="fas fa-user-plus me-2"></i>Leads
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="sales/opportunities.php" class="btn btn-info btn-lg d-block">
                    <i class="fas fa-chart-line me-2"></i>Opportunities
                </a>
            </div>
            <?php if ($_SESSION['user_role'] === 'manager'): ?>
            <div class="col-md-4 mb-3">
                <a href="sales/customerwon.php" class="btn btn-warning btn-lg d-block">
                    <i class="fas fa-trophy me-2"></i>Won Customers
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>





<?php include_once('inc/footer.php'); ?>