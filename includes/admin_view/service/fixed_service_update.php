<?php

require_once( WP_PLUGIN_DIR . '/SuncodeIT-Custom-Form'.'/assets/css/main.php' );  
require_once( WP_PLUGIN_DIR . '/SuncodeIT-Custom-Form'.'/includes/user_functionalities.php' );
if(!isset($_GET['id']))
{
    exit;
}else
{
    $service_details=encoder_get_countries_fixed_service_id($_GET['id']);
    $previous_services=explode(',',$service_details['service_name']);
    $previous_prices=explode(',',$service_details['prices']);  
    
}


?>
<style type='text/css'>
/* form elements */
form {
}
label {
  display:block;
  font-weight:bold;
  margin:15px 0;
}
input {
  padding:2px;
  border:1px solid #eee;
  font: normal 1em Verdana, sans-serif;
  color:#777;
}
select
{
 padding:2px;
  border:1px solid #eee;
  font: normal 1em Verdana, sans-serif;
  color:#777;
  width:100%;
}
input.buttons { 
  font: bold 12px Arial, Sans-serif; 
  height: 50px;
  width: 150px;
  margin-top:20px;
  margin-left:200px;
  margin-bottom: 50px;
  cursor: pointer;  
  color: #333;
  background: #e7e6e6 url(MarketPlace-images/button.jpg) repeat-x;
  border: 1px solid #dadada;
}
.flex{
  display: flex;
}
.removefile
{
  margin-left:5px ;
}
</style>


<div class="wrap pbwp">
<h1 class="wp-heading-inline">Set Country Wise Fixed Services Update</h1>
<?php
global $wpdb;
 $encoderit_country_with_code=$wpdb->prefix . 'encoderit_country_with_code';
$fixed_services=encoderit_admin_functionalities::fiexed_service();
$checkboxes='';
foreach($fixed_services as $key=>$value)
        {
          $check='';
          $price=1;
          $key_found=array_search($value['service_name'],$previous_services);
          
          if(in_array($value['service_name'],$previous_services))
          {
            $check='checked';
            $price= $previous_prices[$key_found];
          }
            if($value['is_input'])
            {
                $checkboxes .='<div class="product__item d-flex-center">
                <input
                  type="checkbox"
                  class="encoder_it_fixed_services_create"
                  data-price="'.$value['service_price'].'"
                  data-name="'.$value['service_name'].'"
                  id="encoder_it_custom_services'.$value['service_id'].'"
                  data-service_id="'.$value['service_id'].'"
                  data-is_input="true"
                  name="encoder_it_custom_services[]"
                  value="'.$value['service_id'].'"
                  '. $check.'
                />
                <label class="d-flex-center">
                  <span>'.$value['service_name'].'</span>
                <input type="number" min="1" class="fixed_service_prices" value="'. $price.'" id="x_'.$value['service_id'].'">
                </label>
              </div>';
            }else
            {
                $checkboxes .='<div class="product__item d-flex-center">
                <input
                  type="checkbox"
                  class="encoder_it_fixed_services_create"
                  data-price="'.$value['service_price'].'"
                  data-name="'.$value['service_name'].'"
               
                  id="encoder_it_custom_services'.$value['service_id'].'"
                  data-service_id="'.$value['service_id'].'"
                  data-is_input="false"
                  name="encoder_it_custom_services[]"
                  value="'.$value['service_id'].'"
                  '. $check.'
                />
                <label class="d-flex-center">
                  <span>'.$value['service_name'].'</span>
                  <input type="number" min="1" class="fixed_service_prices" value="'.$price.'" id="x_'.$value['service_id'].'">
                </label>
              </div>';
            }
            
        }
$sql="SELECT * FROM   $encoderit_country_with_code" ;
$result = $wpdb->get_results($sql);
$html='<option disabled value="0">Please select country</option>';
foreach ($result as $singledata)
{
    $checked='disabled';
    if($service_details['country'] == $singledata->country_name)
    {
        $checked='selected';
    }
    $html .='<option '.$checked.' value='.$singledata->id.'>'.$singledata->country_name.'</option>';
}
?>

<a href="<?=admin_url() .'admin.php.?page=scf-fixed-service-list'?>" class="button" style="padding:5px 25px;background-color: #2271b1;color: white">Back</a>

  <form action="" method='POST' enctype="multipart/form-data">
    <input type="hidden" value="<?=$_GET['id']?>" id="fixed_service_edit_id">
    <label for="">Select Country:</label>
    <select name="select_country" id="select_country"><?=$html?></select>
    <div class="right_col product__container" id="service_container">
    <?=$checkboxes?>
   </div>
    <br>
    <br>
    <input class="buttons button-primary" type="submit" value="Save" name="btn" id="fixed_service_check">    

  </form>
</div>
<script>
  jQuery(document).ready(function () {

    jQuery('.fixed_service_prices').on('input',function(e){
      e.preventDefault();
      if(parseInt(jQuery(this).val()) < 1)
      {
        Swal.fire({
            
            text: "Please input at Least 1",
          });
      }
      return;
    })
    
    jQuery('.fixed_service_prices').on('change',function(e){
      e.preventDefault();
      if(parseInt(jQuery(this).val()) < 1)
      {
        Swal.fire({
            
            text: "Please input at Least 1",
          });
      }
      return;
    })
     
    jQuery('#fixed_service_check').on('click',function(e){
      e.preventDefault();
      var service=document.getElementsByClassName("encoder_it_fixed_services_create");
      var checked_servie=[];
      var checked_servie_data_ids=[];
      var checked_servie_data_name=[];
      var checked_servie_data_is_input=[];
      var checked_servie_price=[];

      for(var j=0;j<service.length;j++)
      {
        if(service[j].checked)
        {
          checked_servie.push(service[j].id);
          checked_servie_data_ids.push(service[j].getAttribute('data-service_id'));
          checked_servie_data_name.push(service[j].getAttribute('data-name'));
          checked_servie_data_is_input.push(service[j].getAttribute('data-is_input'));
        }
      }
      for(var k=0;k<checked_servie_data_ids.length;k++)
      {
        if(document.getElementById("x_"+checked_servie_data_ids[k]).value < 1)
        {
          Swal.fire({
            
            text: "Please input at Least 1",
          });
          return;
        }
        
        checked_servie_price.push(document.getElementById("x_"+checked_servie_data_ids[k]).value)
      }
      console.log(checked_servie);
      console.log(checked_servie_data_ids);
      console.log(checked_servie_data_name);
      console.log(checked_servie_data_is_input);
      console.log(checked_servie_price);
      var country_id=document.getElementById("select_country").value;         
      if(country_id == 0 || service.length == 0)
      {
        alert('please Select Country')
      }else
      {
        var formdata = new FormData();
              formdata.append('action','fixed_service_save');
              formdata.append('checked_servie',checked_servie);
              formdata.append('fixed_service_id',jQuery('#fixed_service_edit_id').val());
              formdata.append('checked_servie_data_ids',checked_servie_data_ids);
              formdata.append('checked_servie_data_name',checked_servie_data_name);
              formdata.append('checked_servie_data_is_input',checked_servie_data_is_input);
              formdata.append('checked_servie_price',checked_servie_price);
              formdata.append('country_id',country_id);
              formdata.append('nonce','<?php echo wp_create_nonce('admin_ajax_nonce_encoderit_custom_form') ?>')
              jQuery.ajax({
                      url: '<?php echo admin_url('admin-ajax.php'); ?>',
                      type: 'post',
                      processData: false,
                      contentType: false,
                      processData: false,
                      dataType:"json",
                      data: formdata,
                      success: function(obj) {
                        console.log(obj.status);
                          if(obj.status == "success")
                          {
                            location.href = '<?=admin_url('admin.php?page=scf-fixed-service-list')?>';
                          }
                        
                      }
                });
      }
    })
     
          
  });
</script>