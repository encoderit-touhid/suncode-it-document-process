<?php
if(isset($_POST['btn'])){
  
    if(empty($_POST['country_names']))
    {
      echo "<script>alert('Please Insert Country Name and Price')</script>";
    }
    else
    {
      global $wpdb;
      $table_name = $wpdb->prefix . 'encoderit_custom_form_services';
      $search_data=$_POST["service_name"];
      $sql="SELECT * FROM " . $table_name . " WHERE service_name = '$search_data'";
      $result=$wpdb->get_results($sql);
      if (count($result) != 0)
      {
        echo "<script>alert('Already inserted $search_data Data')</script>";
      }
      else
      {
        $data = array(
          "service_name" => $_POST["service_name"],
          "created_at" => date('Y-m-d H:i:s')
        );
        $inserted = $wpdb->insert($table_name, $data);
        if($inserted)
        {
          //$sql="SELECT * FROM " . $table_name . " WHERE service_name = '$search_data'";
          //$service_data=$wpdb->get_results($sql);
          $service_id=$wpdb->insert_id;
          $table_updated_encoderit_service_with_country=$wpdb->prefix . 'encoderit_service_with_country';
          foreach($_POST['country_names'] as $key=>$value)
          {
            if(!empty($_POST['service_prices'][$key]))
            {
              $info = array(
                'service_id' => $service_id,
                'country_id' => $_POST['country_names'][$key],
                'price'=>$_POST['service_prices'][$key],
                'is_active'=>$_POST['is_active'][$key],
            );
            $where_condition=array(
                'country_id' => $value,
                'service_id'=>$service_id
            );
            $updated_encoderit_service_with_country=$wpdb->update($table_updated_encoderit_service_with_country, $info, $where_condition);
            if ($updated_encoderit_service_with_country === FALSE || $updated_encoderit_service_with_country < 1) {
              $wpdb->insert($table_updated_encoderit_service_with_country, $info);
              
          }
            }
          }
        }
      } 
    }
    
}
  
  
  

?>


<style type='text/css'>
    :root {
        --white-color: #ffffff;
        --primary-color: #91d3ee;
        --border-color: #8c8f94;
        --text-color: #3c434a;
        --bg-secondary-color: #c3e1ff;
    }

    label {
        display: block;
        font-weight: bold;
        margin: 15px 0;
    }

    input {
        padding: 2px;
        border: 1px solid #eee;
        font: normal 1em Verdana, sans-serif;
        color: #777;
    }

    select {
        padding: 2px;
        border: 1px solid #eee;
        font: normal 1em Verdana, sans-serif;
        color: #777;
        width: 100%;
    }

    input.buttons {
        font: bold 12px Arial, Sans-serif;
        height: 50px;
        width: 150px;
        margin-top: 20px;
        margin-left: 200px;
        margin-bottom: 50px;
        cursor: pointer;
        color: #333;
        background: #e7e6e6 url(MarketPlace-images/button.jpg) repeat-x;
        border: 1px solid #dadada;
    }

    .flex {
        display: flex;
    }

    .removefile {
        margin-left: 5px;
    }

    form {
        font-size: 15px;
        margin-top: 25px;
        padding-top: 30px;
        border-top: 1px solid var(--border-color);
        max-width: 900px;
    }
</style>


<div class="wrap pbwp">
<h1 class="wp-heading-inline">Add New Service</h1>




<a href="<?=admin_url() .'admin.php.?page=scf-custom-services'?>" class="button" style="padding:5px 25px;background-color: #2271b1;color: white">Back</a>

  <form action="" method='POST' enctype="multipart/form-data">
    <label for="">Service Name:</label>
    <input type="text" name="service_name" value="" style="width:100%;" required>
    <br><br>
    <label for="">Service Price:</label>
    <div id="services_div"></div>
    <button id="addFile" class="button" style="display: none;margin-top:20px">Add Country</button>

    <br>
    <br>

    <input class="buttons button-primary" type="submit" value="Save" name="btn">    

  </form>
</div>
<script>
  jQuery(document).ready(function () {
          
           let country_options=null;
           var formdata = new FormData();
            formdata.append('action','enoderit_get_country_code');
            formdata.append('nonce','<?php echo wp_create_nonce('admin_ajax_nonce_encoderit_custom_form') ?>')
            jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'post',
                    processData: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    success: function(data) {
                      country_options=data;
                      console.log(country_options)
                      jQuery("#addFile").show();
                    }
              });
              var service_options='<option value="1">Active</option><option value="0">Inactive</option>';

    jQuery("#addFile").on("click", function (e) {
      e.preventDefault();
      var newInput =
      '<div class="file_item flex">';
      newInput +='<div><label for="">Country:</label><select class="country_names" name="country_names[]">'+country_options+'</select></div>';
      newInput +='<div><label for="">Service Price:</label><input type="number" min="1"  name="service_prices[]"></div>';
      newInput +='<div><label for="">Is Active:</label><select class="country_names" name="is_active[]">'+service_options+'</select></div>';
      newInput +='<div style="margin-left: 14px;   margin-top: 49px;"> <a class="removefile" style="cursor:pointer"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" style="    width: 25px;    color: #e20000;"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>       </a></div>';

      newInput +='</div>';         
      jQuery("#services_div").append(newInput);
      jQuery('.country_names').select2();
    });

    jQuery(document).on("click", ".removefile", function (e) {
     e.preventDefault();
    jQuery(this).closest(".file_item").remove(); // to get clicked element
     
  });

  });
</script>