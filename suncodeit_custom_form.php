<?php
/*
 * Plugin Name:       Suncode IT Custom Form
 * Plugin URI:        https://encoderit.net/
 * Description:       Handle customized form with the plugin.
 * Version:           1.0.75
 */

 define('ENCODER_IT_CUSTOM_FORM_SUBMIT', time());
//  define('ENCODER_IT_STRIPE_PK',"pk_test_51OD1o3HXs2mM51TXR04wpLYzxxWNpOQWZr8Y84oV0Bp5aP1sB0gVic7JqBdrOgQmqYAwT7a9TOfq4UBG5ioifu9F00VwcHhkCb");
//  define('ENCODER_IT_STRIPE_SK',"sk_test_51OD1o3HXs2mM51TXAPMu48pbSpxilR2QjxiXEipq60TE8y96wg51zs9qPSDZomhDtYGcmwIFPboEgFaHi1SINsNZ00FZ8b7i8R");
//  define('ENCODER_IT_PAYPAL_CLIENT','AVT1TGV_xT-FR1XRXZdKgsyoXIhHf_N4-j26F0W6bYXgLcv4r2jJLu7Bsa1aabiU-0pVGrDFUIdOpvrQ');

 $ENCODER_IT_STRIPE_PK=get_option('ENCODER_IT_STRIPE_PK') ? get_option('ENCODER_IT_STRIPE_PK'): "pk_test_51OD1o3HXs2mM51TXR04wpLYzxxWNpOQWZr8Y84oV0Bp5aP1sB0gVic7JqBdrOgQmqYAwT7a9TOfq4UBG5ioifu9F00VwcHhkCb";
 //  define('ENCODER_IT_STRIPE_SK";
 $ENCODER_IT_STRIPE_SK=get_option('ENCODER_IT_STRIPE_SK') ? get_option('ENCODER_IT_STRIPE_SK') : "sk_test_51OD1o3HXs2mM51TXAPMu48pbSpxilR2QjxiXEipq60TE8y96wg51zs9qPSDZomhDtYGcmwIFPboEgFaHi1SINsNZ00FZ8b7i8R";
 $ENCODER_IT_PAYPAL_CLIENT=get_option('ENCODER_IT_PAYPAL_CLIENT') ? get_option('ENCODER_IT_PAYPAL_CLIENT') : "AVT1TGV_xT-FR1XRXZdKgsyoXIhHf_N4-j26F0W6bYXgLcv4r2jJLu7Bsa1aabiU-0pVGrDFUIdOpvrQ";


 define('ENCODER_IT_STRIPE_PK',$ENCODER_IT_STRIPE_PK);
 define('ENCODER_IT_STRIPE_SK',$ENCODER_IT_STRIPE_SK);
 define('ENCODER_IT_PAYPAL_CLIENT',$ENCODER_IT_PAYPAL_CLIENT);

 require_once( dirname( __FILE__ ).'/includes/create_custom_tables.php' );
 require_once( dirname( __FILE__ ).'/includes/admin_functionalities.php' );
 require_once( dirname( __FILE__ ).'/includes/user_functionalities.php' );
 require_once( dirname( __FILE__ ).'/includes/ajax_endpoint.php' );
 require_once( dirname( __FILE__ ).'/stripe-php-library/init.php' );
 
 


 register_activation_hook(__FILE__, array( 'encoderit_create_custom_table', 'create_custom_tables' ));
 register_deactivation_hook(__FILE__, array( 'encoderit_create_custom_table', 'drop_custom_tables' ));

 add_action( 'admin_menu', 'admin_menu' );

 function admin_menu()
 {
    add_menu_page('Custom Services', 'Custom Services', 'manage_options', 'scf-custom-services',array( 'encoderit_admin_functionalities', 'get_service_list' ), 'dashicons-admin-generic', 4);

    add_submenu_page('options.php', 'Service Update', 'Service Update', 'manage_options', 'scf-encoderit-custom-service-update', array( 'encoderit_admin_functionalities', 'update_service' ));

    add_submenu_page('scf-custom-services', 'Add new Service', 'Add new Service', 'manage_options', 'scf-custom-service-create', array( 'encoderit_admin_functionalities', 'add_new_service' ));

    

    add_menu_page('Cases', 'Cases', 'read', 'scf-custom-cases-user',array( 'encoderit_user_functionalities', 'get_all_case_by_user' ), 'dashicons-admin-generic', 4);

    //add_submenu_page('', 'Service Update', 'Service Update', 'manage_options', 'scf-encoderit-custom-service-update', 'encoderit_details_subscriber');
    add_submenu_page('scf-custom-cases-user', 'Add New Case', 'Add New Case', 'read', 'scf-custom-cases-user-create', array( 'encoderit_user_functionalities', 'add_new_case_by_user' ));

    add_submenu_page('options.php', 'Case View', 'Case View', 'read', 'scf-custom-cases-user-view', array( 'encoderit_user_functionalities', 'view_single_case' ));
   

    add_menu_page('Fixed Services', 'Fixed Services', 'manage_options', 'scf-fixed-service-list', array( 'encoderit_admin_functionalities', 'fixed_service_list'),'dashicons-admin-generic',4);
    add_submenu_page('scf-fixed-service-list', 'Add new Country', 'Add new Country', 'manage_options', 'scf-fixed-service-create', array( 'encoderit_admin_functionalities', 'fixed_service_create' ));
    add_submenu_page('options.php', 'Fixed Services', 'Fixed Services', 'manage_options', 'scf-encoderit-fixed-service-update', array( 'encoderit_admin_functionalities', 'fixed_service_update' ));


    add_options_page(
      'SuncodeIT Settings',        // Page title
      'SuncodeIT Settings',        // Menu title
      'manage_options',         // Capability required to access the page
      'sf-custom-settings-page',   // Menu slug (unique identifier)
      array( 'encoderit_admin_functionalities', 'render_custom_settings_page' )// Callback function to render the page

  );

 }


 
function admin_enqueue_scripts_load()
{


	//enqueue js
   

   wp_register_script('encoderit_custom_form_sweet_alert_admin', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array(), ENCODER_IT_CUSTOM_FORM_SUBMIT, true);
   
   wp_register_script('encoderit_custom_form_stripe_admin', 'https://js.stripe.com/v3/', array(), ENCODER_IT_CUSTOM_FORM_SUBMIT);

   //$paymal_url="https://www.paypal.com/sdk/js?client-id=".ENCODER_IT_PAYPAL_CLIENT;

   wp_register_script('encoderit_custom_form_js_admin', plugins_url('assets/js/main.js',__FILE__ ), array(), ENCODER_IT_CUSTOM_FORM_SUBMIT);

   wp_register_script('encoderit_custom_form_js_zs_zip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js',array(),ENCODER_IT_CUSTOM_FORM_SUBMIT);

   wp_register_script('encoderit_select_2_js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',array(),ENCODER_IT_CUSTOM_FORM_SUBMIT);

   wp_enqueue_style('encoderit_select_2_css','https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), ENCODER_IT_CUSTOM_FORM_SUBMIT);

  wp_localize_script('encoderit_custom_form_js_admin', 'action_url_ajax', array(
   'ajax_url' => admin_url('admin-ajax.php'),
   'nonce' => wp_create_nonce('user_zip_download_suncode')
  ));

   wp_enqueue_script('encoderit_custom_form_stripe_admin');
   wp_enqueue_script('encoderit_custom_form_sweet_alert_admin');
   wp_enqueue_script('encoderit_custom_form_js_zs_zip');
   wp_enqueue_script('encoderit_custom_form_js_admin');
   wp_enqueue_script('encoderit_select_2_js');

	wp_enqueue_media();

	
}
add_action('admin_enqueue_scripts', 'admin_enqueue_scripts_load');


/***********Ajax Functionalities ************/
add_action('wp_ajax_enoderit_custom_form_submit', array('encoderit_ajax_endpoints','enoderit_custom_form_submit'));
add_action('wp_ajax_enoderit_custom_form_cancle_form', array('encoderit_ajax_endpoints','enoderit_custom_form_cancle_form'));
add_action('wp_ajax_enoderit_custom_form_restore_form', array('encoderit_ajax_endpoints','enoderit_custom_form_restore_form'));
add_action('wp_ajax_enoderit_custom_form_admin_submit', array('encoderit_admin_functionalities','enoderit_custom_form_admin_submit'));

add_action('wp_ajax_enoderit_get_country_code', array('encoderit_admin_functionalities','enoderit_get_country_code'));
add_action('wp_ajax_enoderit_get_service_by_country', array('encoderit_admin_functionalities','enoderit_get_service_by_country'));



add_action('wp_ajax_enoderit_custom_form_cancle_service', array('encoderit_ajax_endpoints','enoderit_custom_form_cancle_service'));
add_action('wp_ajax_enoderit_custom_form_restore_service', array('encoderit_ajax_endpoints','enoderit_custom_form_restore_service'));

add_action('wp_ajax_enoderit_custom_form_restore_fixed_service', array('encoderit_ajax_endpoints','enoderit_custom_form_restore_fixed_service'));
add_action('wp_ajax_enoderit_custom_form_cancle_fixed_service', array('encoderit_ajax_endpoints','enoderit_custom_form_cancle_fixed_service'));

add_action('wp_ajax_enoderit_custom_form_remove_service_country', array('encoderit_ajax_endpoints','enoderit_custom_form_remove_service_country'));

add_action('wp_ajax_encoder_it_set_payment_keys', array('encoderit_ajax_endpoints','encoder_it_set_payment_keys'));

add_action('wp_ajax_fixed_service_save', array('encoderit_admin_functionalities','fixed_service_save'));

add_action('wp_ajax_custom_form_pay_later', array('encoderit_ajax_endpoints','custom_form_pay_later'));

if (!function_exists('encoderit_download_button_avaialbe')) {
   function encoderit_download_button_avaialbe($updated_at)
   {
      if(empty($updated_at))
      {
         return false;
      }
      
      $currentTimestamp = strtotime(date('Y-m-d H:i:s'));
      $otherTimestamp = strtotime($updated_at);
      $timeDifferenceInSeconds = $otherTimestamp - $currentTimestamp;
      $interval = abs($timeDifferenceInSeconds);

      if($interval/3600 <=24)
      {
         return true;
      }else
      {
         return false;
      }
   }
}
if (!function_exists('encoderit_download_button_avaialbe_time_expire')) {
   function encoderit_download_button_avaialbe_time_expire($updated_at)
   {
      if(empty($updated_at))
      {
         return false;
      }
      
      $currentTimestamp = strtotime(date('Y-m-d H:i:s'));
      $otherTimestamp = strtotime($updated_at);
      $timeDifferenceInSeconds = $otherTimestamp - $currentTimestamp;
      $interval = abs($timeDifferenceInSeconds);
      if($interval/3600 <=24)
      {
         return true;
      }else
      {
         return false;
      }
   }
}
if (!function_exists('encoder_get_countries_service_id')) {
   function encoder_get_countries_service_id($service_id)
   {
      global $wpdb;
      $encoderit_service_with_country = $wpdb->prefix . 'encoderit_service_with_country';
      $encoderit_custom_form_services = $wpdb->prefix . 'encoderit_custom_form_services';
      $encoderit_country_with_code =$wpdb->prefix . 'encoderit_country_with_code';

      $sql="SELECT $encoderit_country_with_code.country_name FROM $encoderit_country_with_code WHERE $encoderit_country_with_code.id IN (SELECT $encoderit_service_with_country.country_id FROM $encoderit_service_with_country WHERE $encoderit_service_with_country.service_id =$service_id and $encoderit_service_with_country.is_active <> 3)";
      $result = $wpdb->get_results($sql);
      $country_name=[];
      foreach($result as $value)
      {
         array_push($country_name,$value->country_name);
      }
      return implode(',',$country_name);
   }
}


// Add custom stylesheet to change row color for the custom table
if (!function_exists('encoder_get_cancel_button')) {
   function encoder_get_cancel_button($service_id)
   {
      global $wpdb;
      $encoderit_service_with_country = $wpdb->prefix . 'encoderit_service_with_country';
      $encoderit_custom_form_services = $wpdb->prefix . 'encoderit_custom_form_services';
      $encoderit_country_with_code =$wpdb->prefix . 'encoderit_country_with_code';

      $sql="Select *from $encoderit_service_with_country where service_id=$service_id and is_active=1";
      $result = $wpdb->get_results($sql);
      if(count($result)==0)
      {
         return true;
      }else
      {
         return false;
      }
   }
}

if (!function_exists('enc_get_json_service_price')) {
   function enc_get_json_service_price($service_ids,$country_id)
   {
      global $wpdb;
      $encoderit_service_with_country = $wpdb->prefix . 'encoderit_service_with_country';
      $encoderit_custom_form_services = $wpdb->prefix . 'encoderit_custom_form_services';
      

      $sql="SELECT $encoderit_custom_form_services.service_name,$encoderit_service_with_country.price FROM $encoderit_custom_form_services JOIN $encoderit_service_with_country ON $encoderit_custom_form_services.id=$encoderit_service_with_country.service_id WHERE $encoderit_custom_form_services.id IN ($service_ids) and $encoderit_service_with_country.country_id=$country_id AND $encoderit_custom_form_services.is_active=1 AND $encoderit_service_with_country.is_active=1";

      $result = $wpdb->get_results($sql);
      //var_dump($result);
      $service_jsone=[];
      foreach($result as $key=>$value)
      {
         $single['name']=$value->service_name;
         $single['price']=$value->price;
         array_push($service_jsone,$single);
      }
      return json_encode($service_jsone);
   }
}

if (!function_exists('enc_get_country_name_by_id')) {
   function enc_get_country_name_by_id($country_id)
   {
      global $wpdb;
      $encoderit_country_with_code = $wpdb->prefix . 'encoderit_country_with_code';
     
      $sql="SELECT *from $encoderit_country_with_code where id=$country_id";

      $result = $wpdb->get_row($sql);
      return $result->country_name;
   }
}

if (!function_exists('encoder_get_countries_fixed_service_id')) {
   function encoder_get_countries_fixed_service_id($service_id)
   {
      global $wpdb;
      $encoderit_fixed_service_with_country = $wpdb->prefix . 'encoderit_fixed_service_with_country';
      
      $encoderit_country_with_code =$wpdb->prefix . 'encoderit_country_with_code';

      $sql="SELECT $encoderit_country_with_code.country_name,$encoderit_fixed_service_with_country.service_json FROM $encoderit_fixed_service_with_country INNER JOIN $encoderit_country_with_code ON $encoderit_fixed_service_with_country.country_id=$encoderit_country_with_code.id and $encoderit_fixed_service_with_country.is_active <> 3 and $encoderit_fixed_service_with_country.id=$service_id";
      $result = $wpdb->get_row($sql);
     
      $country_name=[];
   
      foreach(json_decode($result->service_json,true) as $key=>$value)
      {
         $services[]=$value['service_name'];
         $prices[]=$value['service_price'];
      }
      
      return[
         'country'=>$result->country_name,
         'service_name'=> implode(',',$services),
         'prices'=> implode(',',$prices)
      ];

   }
}
if(!function_exists('enc_user_can_view_case'))
{
   function enc_user_can_view_case($id)
   {
      global $wpdb;
      $table_name = $wpdb->prefix . 'encoderit_custom_form';
      $result = $wpdb->get_row("SELECT * FROM " . $table_name . " where user_id = ".get_current_user_id()."  and id=$id");
      return !empty($result);
   }
}
