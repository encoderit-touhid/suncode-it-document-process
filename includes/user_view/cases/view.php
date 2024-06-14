<?php

require_once WP_PLUGIN_DIR . '/SuncodeIT-Custom-Form' . '/assets/css/main.php';
global $wpdb;
        
  $table_name = $wpdb->prefix . 'encoderit_custom_form';
  $id=$_GET['id'];
  if(empty($id) || !isset($_GET['id']))
  {
    echo "Sorry This is Wrong Page ID Missing";
    exit;
  }      
  $result = $wpdb->get_row("SELECT * FROM " . $table_name . " where id =$id");
  //var_dump($result);
  //var_dump(json_decode($result->files_by_user,true));
  $files_by_user=json_decode($result->files_by_user,true);
  $files_by_admin=json_decode($result->files_by_admin,true);
  //var_dump($files_by_admin);
  //$service_ids='('.$result->services.')';
  $country_name=enc_get_country_name_by_id($result->country_id);
  $get_all_services=json_decode($result->services,true);
  if(empty($result))
  {
    ?>
      <script>
        alert();
      window.location.replace("<?=admin_url().'/index.php'?>")
      
      </script>
    <?php
  }     
?>

<div style="padding: 30px">
    <h1>Case ID #<?=$id?></h1>
    <form action="" method="POST" enctype="multipart/form-data" id="fileUploadForm">
        <div class="row_d">
            <div class="titel_col">
                <label for="">Person number:</label>
            </div>
            <div class="right_col person_number_col">
                <span style="font-size: 24px; font-weight:900"><?=$result->person_number?></span>
            </div>
        </div>
        <div class="row_d">
            <div class="titel_col">
                <label for="">Country:</label>
            </div>
            <div class="right_col">
                <p style="font-size: 15px; font-weight:900"><?=$country_name?></p>
            </div>
        </div>
        <div class="row_d">
            <div class="titel_col">
                <label for="">Description:</label>
            </div>
            <div class="right_col">
                <p style="font-size: 15px; font-weight:900"><?=$result->description?></p>
            </div>
        </div>

        <div class="row_d">
            <div class="titel_col">
                <label for="">Add File:</label>
            </div>
            <div class="right_col add__file__container">
                
                <?php
                foreach($files_by_user as $key=>$value)
                {
                    ?>
                     <a href="<?=wp_upload_dir()['baseurl'].$value['paths']?>" target="_blank"><?=$value['name']?></a>
                     <br>
                     <br>
                    <?php

                }
                
                ?>
            </div>
        </div>
        <?php
        
        if(encoderit_download_button_avaialbe_time_expire($result->updated_at) && !empty($files_by_admin) && $result->is_cancelled != 1)
        {
            ?>
             <div class="row_d">
            <div class="titel_col">
            <label for="">File By Admin:</label>
            </div>
          <div class="right_col add__file__container">
          <?php
              if(!empty($files_by_admin))
              {
                foreach($files_by_admin as $key=>$value)
                {
                    ?>
                     <a href="<?=wp_upload_dir()['baseurl'].$value['paths']?>" target="_blank"><?=$value['name']?></a>
                     <br>
                     <br>
                    <?php

                }
              }
                
            ?>
        </div>
    </div>
            <?php
        }
        ?>
    
        <div class="row_d services_row">
            <div class="titel_col">
                <label for="">Services:</label>
            </div>
            <div class="right_col product__container">
                <?php
                 
                // var_dump($get_all_services);
                 if(array_key_exists("is_fixed_service",$get_all_services))
                 {
                    foreach ($get_all_services['service'] as $key => $value) 
                    {
                        ?>
                    <div class="product__item d-flex-center">
                        <input type="checkbox" class="encoder_it_custom_services" checked disabled/>
                        <label class="d-flex-center">
                            <span><?= $value['name'] ?></span>
                            
                            <?php 
                            if($value['is_count'])
                            {
                                ?>
                                <spna><?=$value['input_main_applicat_increment']?> X</spna>
                                <?php
                            }
                            ?>
                            <span>$<?= $value['price'] ?></span>
                        </label>
                    </div>
                    <?php
                    }

                 }else
                 {
                    foreach ($get_all_services as $key => $value) 
                    {
                        ?>
                    <div class="product__item d-flex-center">
                        <input type="checkbox" class="encoder_it_custom_services" checked disabled/>
                        <label class="d-flex-center">
                            <span><?= $value['name'] ?></span>
                            <span>$<?= $value['price'] ?></span>
                        </label>
                    </div>
                    <?php
                   }
                 }

       ?>
            </div>
        </div>

        <?php 
        
        if($result->payment_method == "Pay Later")
        {
           
            require_once( dirname( __FILE__ ).'/view_includes/pay_later_additional_payment.php' );
        }else
        {
            require_once( dirname( __FILE__ ).'/view_includes/already_paid.php' );
        }
        
        ?>
        
        
    </div>
    </form>
</div>

