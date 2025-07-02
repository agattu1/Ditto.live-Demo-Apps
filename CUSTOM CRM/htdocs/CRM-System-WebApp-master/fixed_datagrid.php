<?php
// filename: fixed_datagrid.php (place in your project)
require_once '/Applications/MAMP/htdocs/CRM-System-WebApp-master/phpGrid/phpGrid_Lite/server/classes/cls_datagrid.php';

class Fixed_DataGrid extends C_DataGrid {
    public $col_formats;
    public $jq_shrinkToFit;
    public $jq_is_group_summary;
    
    // Constructor that passes all arguments to parent
    public function __construct() {
        $args = func_get_args();
        call_user_func_array(array('parent', '__construct'), $args);
    }
}
?>