<?php
 require_once( WP_PLUGIN_DIR . '/SuncodeIT-Custom-Form'.'/assets/css/main.php' );
 global $wpdb;
 $encoderit_country_with_code=$wpdb->prefix . 'encoderit_country_with_code';
 $encoderit_service_with_country=$wpdb->prefix . 'encoderit_service_with_country';
 $encoderit_custom_form_services=$wpdb->prefix . 'encoderit_custom_form_services';
 $encoderit_fixed_service_with_country=$wpdb->prefix . 'encoderit_fixed_service_with_country';

$sql="SELECT * FROM   $encoderit_country_with_code where $encoderit_country_with_code.id in (SELECT $encoderit_service_with_country.country_id from $encoderit_service_with_country join $encoderit_custom_form_services on $encoderit_service_with_country.service_id =$encoderit_custom_form_services.id where $encoderit_service_with_country.is_active=1 and $encoderit_custom_form_services.is_active=1) 
or $encoderit_country_with_code.id in  (SELECT $encoderit_fixed_service_with_country.country_id from $encoderit_fixed_service_with_country where $encoderit_fixed_service_with_country.is_active=1)" ;

 $result = $wpdb->get_results($sql);
 
 $html='<option value="0">Please select country</option>';
 foreach ($result as $singledata)
 {
     $html .='<option value='.$singledata->id.'>'.$singledata->country_name.'</option>';
 }
 
?>
<div class="wrap">
<h1 class="wp-heading-inline">Add new case</h1> 

<div style="padding: 0px">
   <form
    action=""
    method="POST"
    enctype="multipart/form-data"
    id="fileUploadForm"
  >
  <input type="hidden" value="xxxx" id="fixed_service_others_price">
  <input type="hidden" value="" id="main_document_present">


  <div class="row_d">
      <div class="titel_col">
        <label for="">Subject Country:</label>
      </div>
      <div class="right_col product__container">
        <select name="select_country" id="select_country"><?=$html?></select>
      </div>
    </div>
    <!-- <div class="row_d">
    <div class="right_col product__container">
    <button id="get_customized_selection" style="margin:10px">Customize Your Selections</button>
    <button id="get_customized_selection_undone" style="display:none">Get Previous</button>
      </div>
    </div> -->
    

    <div class="row_d services_row" id="encoder_client_service_group" style="display: none;">
      <div class="titel_col">
        <label for="">Services:</label>
      </div>
      <div class="right_col product__container" id="service_container">
        
      </div>
    </div>
    
    <div id="person_number_div" style="display: none;">
      <div class="row_d">
        <div class="titel_col">
          <label for="">Subject number:</label>
        </div>
        <div class="right_col person_number_col">
          <input type="number" name="person_number" min="1" id="person_number"  value="1" required />
        </div>
      </div>
    </div>
    
   

    <div class="row_d">
      <div class="titel_col">
        <label for="">Vendor request:</label>
      </div>
      <div class="right_col">
        <textarea
          name="description"
          id="description"
          row="10"
          style="width: 100%"
          required
        ></textarea>
      </div>
    </div>

    <div class="row_d">
      <div class="titel_col">
        <label for="">Add File:</label>
      </div>
      <div class="right_col add__file__container">
        <button id="addFile">Add File</button>
        <div id="files"></div>
      </div>
    </div>
    

    

    <div class="row_d" id="encoder_client_payment_group" style="display: none;">
      <div class="titel_col">
        <label for="">Selected Payment Method:</label>
      </div>
      <div class="right_col right_total_price">
        <div class="payment_method_container">
          <div class="item d-flex-center" id="is_paypal_div">
            <input type="radio" name="payment_method" id="encoderit_paypal"  value="Paypal" onclick="check_radio_payment_method(this.id)" />
            <span>Paypal</span>
          </div>
          <div class="item d-flex-center">
            <input type="radio" name="payment_method" id="encoderit_stripe"  value="Credit Card" onclick="check_radio_payment_method(this.id)"/>
            <span>Credit Card</span>
          </div>
          <div class="item d-flex-center">
            <input type="radio" name="payment_method" id="encoderit_bank_transfer" value="Bank Transfer" onclick="check_radio_payment_method(this.id)"/>
            <span>Pay Later</span>
          </div>
        </div>
         <div class="paymet-area">
         
         <div id="stripe_payment_div" style="display:none;margin-top:20px">
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
          </div>
        </div>
        <div id="paypal-button-container" style="display:none;margin-top:20px"></div>

        <div class="total__price">
          <span>Total Price</span><span id="price"></span>
        </div>
        <div class="submit_btn"  >
          <input class="buttons" type="submit" name="btn" id="encoder_it_submit_btn_user_form" />
        </div>
      </div>
    </div>
  </form>
</div>
</div>
<?php require_once( dirname( __FILE__ ).'/js/add_case_js_file.php' ); ?>