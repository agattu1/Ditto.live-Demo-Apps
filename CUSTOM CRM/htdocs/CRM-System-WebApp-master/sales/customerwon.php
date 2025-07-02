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
$currentPage = 'customerwon';
include_once('../inc/head.php');

// Additional check for manager role
if ($_SESSION['user_role'] !== 'manager') {
    header('Location: tasks.php'); // Redirect to task.php in same directory
    exit();
}
?>
<h1>💎Ditto Custom CRM💎</h1>
<?php include_once('../inc/menu.php'); ?>
<h3>Customers/Won</h3>
<?php
require_once("../phpGrid/phpGrid_Lite/conf.php");

$dg = new C_DataGrid("SELECT c.*, u.Name_First, u.Name_Last 
                      FROM contact c 
                      JOIN users u ON c.Sales_Rep = u.id 
                      WHERE c.Status = 3", "id", "contact");
$dg->display();
?>
<?php include_once('../inc/footer.php'); ?>