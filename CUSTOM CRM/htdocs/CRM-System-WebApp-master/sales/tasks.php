<?php
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    if ($errno === E_DEPRECATED && strpos($errstr, 'C_DataGrid') !== false) {
        return true;
    }
    return false;
}

set_error_handler('custom_error_handler', E_DEPRECATED);
ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);
$currentPage = 'task';
include_once('../inc/head.php');
?>
<h1>💎Ditto Custom CRM💎</h1>
<?php include_once('../inc/menu.php'); ?>

<h3>Tasks</h3>

<?php if (!isset($_GET['edit'])): ?>
<!-- Welcome message for users -->
<div class="welcome-box" style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; border: 1px solid #ddd;">
    <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
    <p>You are logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong></p>
    
    <?php if ($_SESSION['user_role'] === 'manager'): ?>
        <p>As a manager, you have full access to all sections of the CRM system, including the Won Customers section.</p>
    <?php else: ?>
        <p>As a sales department user, you have access to all sections except the Won Customers section.</p>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php
require_once("../phpGrid/phpGrid_Lite/conf.php");

$dg = new C_DataGrid("SELECT * FROM notes", "id", "notes");
$dg->display();
?>
<?php include_once('../inc/footer.php'); ?>