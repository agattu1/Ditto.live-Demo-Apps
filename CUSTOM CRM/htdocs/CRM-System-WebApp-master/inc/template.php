<?php
$currentPage = 'tasks';
include_once('../inc/head.php');
?>
<h1>My Custom CRM</h1>
<?php include_once('../inc/menu.php'); ?>
<h3>Tasks</h3>
<?php
use phpGrid\C_DataGrid;
require_once("../phpGrid/conf.php");

$dg = new C_DataGrid("SELECT * FROM notes", "ID", "notes");
$dg->display();
?>
<?php include_once('../inc/footer.php'); ?>