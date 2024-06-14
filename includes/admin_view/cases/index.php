<?php
ob_start();
// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


/**
 * Create a new table class that will extend the WP_List_Table
 */
class EncoderITCustomForm extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->process_bulk_action();
       

        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
    // public function get_bulk_actions() {
	// 	return array(
	// 		'trash' => __( 'Move to Trash', 'admin-table-tut' ),
	// 	);
	// }

	/**
	 * Get bulk actions.
	 *
	 * @return void
	 */
	// public function process_bulk_action() {
	// 	if ( 'trash' === $this->current_action() ) {
	// 		$post_ids = filter_input( INPUT_GET, 'draft_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	// 		var_dump($post_ids );
	// 		global $wpdb;
	// 		$table_name = $wpdb->prefix . 'contacts';
	// 		$wpdb->query("DELETE from $table_name  WHERE id > 0");

	// 	}
	// }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            "Case No." => "Case No.",
            "Amount" => "Amount",
            "Country" => "Country",
            "Submitted By" => "Submitted By",
            "Payment Method" => "Payment Method",
            "Transaction Method" => "Transaction Method",
             "Date"        =>      "Date",
            "Admin File Upload"  =>"Admin File Upload",
            'Action'  => 'Action',
            'Cancel'  => 'Cancel'
        );

        return $columns;
    }


  

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array(
                    'Case No.'=>array('Case No.',true),
                    );
    }

    /**
     * Get the table data
     *
     * @return Array
     */
   
    private function table_data()
    {
        // echo '<script>
        // if(jQuery(".wp-list-table .case_no_cancel_check").hasClass("encoder_it_cancled_row"))
        //     {
        //     console.log("aa");
        //     }                    
        // </script>';
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'encoderit_custom_form';
        if (isset($_POST['s']) && !empty($_POST['s'])) {
            
            $search_data = $_POST['s'];
            $result = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE id = '%" . $search_data . "%' OR total_price LIKE '%" . $search_data . "%' OR  user_id LIKE '%" . $search_data . "%' OR  person_number LIKE '%" . $search_data . "%' OR  transaction_number LIKE '%" . $search_data . "%' OR  payment_method LIKE '%" . $search_data . "%' OR  created_at LIKE '%" . $search_data . "%' ");
        } else {
            
            $result = $wpdb->get_results("SELECT * FROM " . $table_name . " ORDER BY id DESC");
        }
        if (count($result) != 0) {
            $sl = 1;
            foreach ($result as $singledata) {
                
                $upload_by_admin_flag=false;
                $upload_by_admin_massage='Not Uploaded';
                if(!empty($singledata->files_by_admin) && !empty($singledata->updated_by) )
                {
                    $upload_by_admin_massage = '<a  href="' .admin_url() .'user-edit.php'. '?user_id='.$singledata->updated_by.'" class="" target="_blank">'.get_user_by('ID',$singledata->updated_by)->display_name.'</a>';
                    $upload_by_admin_flag=true;
                }
                $cancle_class='';
                $cancle_button='<a  href="javascript:void(0)" class="button" onclick="cancle_the_form(this.id)" id="admin_cancle_form_id_'.$singledata->id.'" data-case="'.$singledata->id.'" style="background-color: #c82333;color: black">Cancel</a>';

                if($singledata->is_cancelled == 1)
                {
                    $cancle_class='encoder_it_cancled_row';
                    $cancle_button='<a  href="javascript:void(0)" class="button" onclick="restore_the_form(this.id)" id="admin_cancle_form_id_'.$singledata->id.'" data-case="'.$singledata->id.'" style="background-color: #009B00;color: black">Restore</a>';
                }
                $date=explode(' ',$singledata->created_at)[0];
                $data[] = array(
                    'Case No.'                    => '#'.$singledata->id,
                    'Amount'              => '<p class='.$cancle_class.' $ >'. $singledata->total_price.'</p>',
                    'Country'             =>enc_get_country_name_by_id($singledata->country_id),
                    'Submitted By'             =>'<a  href="' .admin_url() .'user-edit.php'. '?user_id='.$singledata->user_id.'" class="'.$cancle_class.' case_no_cancel_check" target="_blank">'.get_user_by('ID',$singledata->user_id)->display_name.'</a>',
                    'Payment Method'          => $singledata->payment_method,
                    'Transaction Method'       => $singledata->transaction_number,
                    'Date'                =>  implode('/',array_reverse(explode('-',$date))),
                    'Admin File Upload'        =>$upload_by_admin_massage,
                    'Action'                    => '<a  href="' .admin_url() .'admin.php'. '?page=scf-custom-cases-user-view&id=' . $singledata->id . '" class="button" target="_blank" style="background-color: #009B00;color: black">Details</a>
                    ',
                    'Cancel'                    => $cancle_button,
                );
                $sl++;
            }
        } else {
            $data = [];
        }

        return $data;
    }
    
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case "Case No.":
            case "Amount":
            case "Country":
            case "Submitted By":
            case "Date":
            case "Payment Method":
            case "Transaction Method":
            case "Admin File Upload":
            case 'Action':
            case 'Cancel': 
                return $item[$column_name];

            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'Case No.';
        $order = 'desc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }


        $result = strnatcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }
 
    // public function single_row( $item ) {
    //     $cssClass = ($item['is_cancelled'] == 1) ? 'encoder_it_cancled_row' : '';
    //     echo '<tr class="'.$cssClass.'">';
    //     $this->single_row_columns( $item );
    //     echo '</tr>';
    // }

}
$pbwp_products = new EncoderITCustomForm();
$pbwp_products->prepare_items();

?>
<div class="wrap pbwp">
    <div>
        <h1 class="pbwp-headingtag pbwp-mb-4 pbwp-p-1">All Cases</h1>
    </div>
    <div class="pbwp-mt-3">
        <form method="post" class="pbwp-d-inline" style="display: none;">
            <input type="hidden" name="page" value="pbwp_product_table" />
            <?php $pbwp_products->search_box('search', 'search_id'); ?>
        </form>
    </div>
    <?php $pbwp_products->display(); ?>
    <script>
        if(jQuery('.wp-list-table .case_no_cancel_check').hasClass('encoder_it_cancled_row'))
            {
                jQuery('.encoder_it_cancled_row').closest('tr').css('background-color', 'lightcoral');
            }
    </script>
</div>