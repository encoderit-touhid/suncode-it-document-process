<?php
class encoderit_admin_functionalities
{
    public static function get_service_list()
    {
        require_once( dirname( __FILE__ ).'/admin_view/service/index.php' );
        
    }
    public static function add_new_service()
    {
        require_once( dirname( __FILE__ ).'/admin_view/service/add.php' );
    }
    public static function enoderit_custom_form_admin_submit()
    {
     
        $erros=self::check_validation_data_request();
        
        if (!wp_verify_nonce($_POST['nonce'], 'admin_ajax_nonce_encoderit_custom_form')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }elseif(!empty($erros))
        {
            echo $erros;
        }
        else
        {
            
            global $wpdb;
            $table_name = $wpdb->prefix . 'encoderit_custom_form';
        
            $data = array(
                'files_by_admin' => self::save_files_by_admin(),
                'updated_by' => wp_get_current_user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $where_condition=array(
                'id' => $_POST['form_id']
            );
             
            $inserted=$wpdb->update($table_name, $data, $where_condition);

                if($inserted)
                {
                    self::send_mail_to_user();

                    echo  json_encode([
                        'success' => 'success',
                        'message'=>'Form Submmited Successfully'
                    ]);
                }else
                {
                    echo  json_encode([
                        'success' => 'error',
                        'message'=>'Something worng.;'
                    ]);
                }
        }
        wp_die(); 
    }
    public static function update_service()
    {
        require_once( dirname( __FILE__ ).'/admin_view/service/update.php' );
    }
    public static function save_files_by_admin()
    {
        global $wpdb;
        $id=$_POST['form_id'];
        $table_name = $wpdb->prefix . 'encoderit_custom_form';
        $file_paths=[];
        $result = $wpdb->get_row("SELECT * FROM " . $table_name . " where id =$id");
        if(!empty($result->files_by_admin))
        {

            $file_paths=json_decode($result->files_by_admin,true);
        }
        $wordpress_upload_dir = wp_upload_dir();
       foreach($_FILES['file_array']['name'] as $key=>$value)
       {

         $tail='';
         $file_name_with_addition='';
         $new_file_path = $wordpress_upload_dir['path'] . '/' . $value;
         $i=1;
         while (file_exists($new_file_path)) {
            $i++;
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' .$value;
        }
        if (move_uploaded_file($_FILES['file_array']['tmp_name'][$key], $new_file_path)) {
            if($i>1)
            {
                //$string_json_path_Single=$wordpress_upload_dir['url'].'/' . $i . '_' .$value;
                $tail=$wordpress_upload_dir['subdir'].'/' . $i . '_' .$value;
                $file_name_with_addition= $i . '_' .$value;
            }else
            {
                //$string_json_path_Single=$wordpress_upload_dir['url'].'/'.$value; 
                $tail=$wordpress_upload_dir['subdir'].'/'.$value;
                $file_name_with_addition= $value; 
            }
            
           $single_file=[
            'name'=>$file_name_with_addition,
            'paths'=>$tail
           ];
           array_push($file_paths,$single_file);
        }   
       }
       return json_encode($file_paths);
    }
    public static function send_mail_to_user()
    {
        $search_data=$_POST['form_id'];

        global $wpdb;

        $table_name=$wpdb->prefix . 'encoderit_custom_form';
        $sql="SELECT * FROM " . $table_name . " WHERE id = '$search_data'";
        $result=$wpdb->get_row($sql);
        $subscriber=get_user_by('ID',$result->user_id);
        $to = $subscriber->user_email;

		$subject = 'Admin Upload Files to Your Case ' . ' (' . $subscriber->display_name . ')';

		$message = '<p>Admin Upload Files to Your Case Please Collect them </p>';

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		wp_mail($to, $subject, $message, $headers);
    }
    public static function check_validation_data_request()
    {
        $message='';
         if(empty($_FILES))
         {
            $message .='Please Input files.;';
         }
         if(!empty($message))
         {
            return json_encode([
                'success' => 'error',
                'message'=>$message
            ]);
         }else
         {
            return;
         }
    }

    public static function enoderit_get_country_code()
    {
        global $wpdb;
        $table_name=$wpdb->prefix . 'encoderit_country_with_code';
        $result = $wpdb->get_results("SELECT * FROM " . $table_name . "");
        $html='';
        foreach ($result as $singledata)
        {
            $html .='<option value='.$singledata->id.'>'.$singledata->country_name.'</option>';
        }
        echo $html;
        wp_die();
    }
    public static function enoderit_get_service_by_country()
    {
        global $wpdb;
        $is_payment_show=true;
        $country_id=$_POST['country_id'];
        $encoderit_service_with_country = $wpdb->prefix . 'encoderit_service_with_country';
        $encoderit_custom_form_services = $wpdb->prefix . 'encoderit_custom_form_services';
        
        $encoderit_fixed_service_with_country = $wpdb->prefix . 'encoderit_fixed_service_with_country';
        $encoderit_custom_form_services = $wpdb->prefix . 'encoderit_custom_form_services';

        $sql="SELECT * FROM $encoderit_service_with_country JOIN $encoderit_custom_form_services ON $encoderit_service_with_country.service_id=$encoderit_custom_form_services.id WHERE $encoderit_service_with_country.is_active=1 and $encoderit_service_with_country.country_id=$country_id and $encoderit_custom_form_services.is_active=1";
        $result = $wpdb->get_results($sql);

        $sql_fixed_service="SELECT * FROM $encoderit_fixed_service_with_country where $encoderit_fixed_service_with_country.country_id=$country_id and $encoderit_fixed_service_with_country.is_active=1";
        $sql_fixed_service_result = $wpdb->get_results($sql_fixed_service);
        
        $nhtml=self::both_fixed_variable_service($result,$sql_fixed_service_result);
        // if(!empty($result) && !empty($sql_fixed_service_result))
        // {
            
           
    
        // }elseif(empty($result) && !empty($sql_fixed_service_result))
        // {
        //     self::only_fixed_service($sql_fixed_service_result);
        // }elseif(!empty($result) && empty($sql_fixed_service_result))
        // {
        //     self::only_variable_service($result);
        // }

    //     $html ='<button id="get_customized_selection_undone" style="display:none">Get Previous</button>';
    //     $html .='<button id="get_customized_selection" style="margin:10px">Customize Your Selections</button>';
    //     $html .='<h4>You can select and change the service here</h4>';
    //     $html .='<div id="fixed_section_service">';
    //     foreach(self::fiexed_service() as $key=>$value)
    //     {
    //         if($value['is_input'])
    //         {
    //             $html .='<div class="product__item d-flex-center">
    //             <input
    //               type="checkbox"
    //               class="encoder_it_custom_services"
    //               data-price="'.$value['service_price'].'"
    //               data-name="'.$value['service_name'].'"
    //               onclick="add_total_price(this.id)"
    //               id="encoder_it_custom_services'.$value['service_id'].'"
    //               name="encoder_it_custom_services[]"
    //               value="'.$value['service_id'].'"
    //             />
    //             <label class="d-flex-center">
    //               <span>'.$value['service_name'].'</span>
    //               <span>$'.$value['service_price'].'</span>
    //               <span> X </span>
    //             <input type="number" min="1" id="input_main_applicat_increment" value="1">
    //             </label>
    //           </div>';
    //         }else
    //         {
    //             $html .='<div class="product__item d-flex-center">
    //             <input
    //               type="checkbox"
    //               class="encoder_it_custom_services"
    //               data-price="'.$value['service_price'].'"
    //               data-name="'.$value['service_name'].'"
    //               onclick="add_total_price(this.id)"
    //               id="encoder_it_custom_services'.$value['service_id'].'"
    //               name="encoder_it_custom_services[]"
    //               value="'.$value['service_id'].'"
    //             />
    //             <label class="d-flex-center">
    //               <span>'.$value['service_name'].'</span>
    //               <span>$'.$value['service_price'].'</span>
    //             </label>
    //           </div>';
    //         }
            
    //     }
  
    //     $html .='</div>';
    //     $html .='<div style="display:flex;justify-content: flex-end"><button  id="add_new_fixed_service">Add More</button></div>';


    //    $html .='<div id="customized_section_service"  style="display:none">';
    //     if(!empty($result))
    //     {
            
    //         foreach($result as $key=>$value)
    //         {
    //              $html .='<div class="product__item d-flex-center">
    //              <input
    //                type="checkbox"
    //                class="encoder_it_custom_services"
    //                data-price="'.$value->price.'"
    //                onclick="add_total_price(this.id)"
    //                id="encoder_it_custom_services'.$value->service_id.'"
    //                name="encoder_it_custom_services[]"
    //                value="'.$value->id.'"
    //              />
    //              <label class="d-flex-center">
    //                <span>'.$value->service_name.'</span>
    //                <span>$'.$value->price.'</span>
    //              </label>
    //            </div>';
    
    //         }
    //         //$is_payment_show=true;
    //     }else
    //     {
    //         $html .='<p>No service found for this country</p>';
    //     }
    //     $html .='</div>';
       // $html .='<button id="get_customized_selection_undone" style="display:none">Get Previous</button>';
        echo json_encode(
            [
                'html'=>$nhtml['html'],
                'is_payment_show'=>$nhtml['is_payment_show'],
                'fixed_service_others_price_value'=>$nhtml['fixed_service_others_price_value'],
                'fixed_service_others_price_flag'=>$nhtml['fixed_service_others_price_flag'],
                'is_payment_show'=>$nhtml['is_payment_show'],
                'main_document_present'=>$nhtml['main_document_present']
          ]);
        wp_die();
    }
    public static function render_custom_settings_page()
    {
        require_once( dirname( __FILE__ ).'/admin_view/payment_gateway/index.php' );
    }

    public static function fiexed_service()
    {
        return [
            [
                'service_name'=>'Main Applicant',
                'service_price'=>'1000',
                'service_id'=>'fixed_id_1',
                'is_input'=>true,
            ],
            [
                'service_name'=>'Spouse Dependent',
                'service_price'=>'500',
                'service_id'=>'fixed_id_2',
                'is_input'=>false,
            ],
            [
                'service_name'=>'Adult Dependent',
                'service_price'=>'400',
                'service_id'=>'fixed_id_3',
                'is_input'=>false,
            ],
            [
                'service_name'=>'Second Adult Dependent ',
                'service_price'=>'300',
                'service_id'=>'fixed_id_4',
                'is_input'=>false,
            ],
            [
                'service_name'=>'Third Adult Dependent',
                'service_price'=>'200',
                'service_id'=>'fixed_id_5',
                'is_input'=>false,
            ],
            [
                'service_name'=>'Fourth Adult Dependent',
                'service_price'=>'200',
                'service_id'=>'fixed_id_6',
                'is_input'=>false,
            ],
            [
                'service_name'=>'Fifth Adult Dependent ',
                'service_price'=>'200',
                'service_id'=>'fixed_id_7',
                'is_input'=>false,
            ],
            [
                'service_name'=>'Others',
                'service_price'=>'200',
                'service_id'=>'fixed_id_8',
                'is_input'=>false,
            ],
        ];
    }
    public static function fixed_service_create()
    {
        require_once( dirname( __FILE__ ).'/admin_view/service/add_fixed_services.php' );
    }
    public static function fixed_service_save()
    {
        
        global $wpdb;
       
        $checked_servie_data_ids=explode(',',$_POST['checked_servie_data_ids']);
        $checked_servie_data_name=explode(',',$_POST['checked_servie_data_name']);
        $checked_servie_data_is_input=explode(',',$_POST['checked_servie_data_is_input']);
        $checked_servie_price=explode(',',$_POST['checked_servie_price']);
        
        foreach($checked_servie_data_ids as $key=>$value)
        {
            $single_array=[
                'service_name'=>$checked_servie_data_name[$key],
                'service_price'=>$checked_servie_price[$key],
                'service_id'=>$checked_servie_data_ids[$key],
                'is_input'=>$checked_servie_data_is_input[$key]=="true" ? true : false,
            ];
            $final_array[]=$single_array;
        }
       
        $info = array(
            'country_id' => $_POST['country_id'],
            'service_json'=>json_encode($final_array),
            "created_at" => date('Y-m-d H:i:s')
        );
        
        $encoderit_fixed_service_with_country=$wpdb->prefix.'encoderit_fixed_service_with_country';
        if(isset($_POST['fixed_service_id']))
        {
            $info = array(
                'country_id' => $_POST['country_id'],
                'service_json'=>json_encode($final_array),
                "updated_at" => date('Y-m-d H:i:s')
            );
            $where_condition=array(
                'id' => $_POST['fixed_service_id'],
            );
            $wpdb->update($encoderit_fixed_service_with_country, $info, $where_condition);
        }else
        {
            $wpdb->insert($encoderit_fixed_service_with_country, $info);
        }
        
        echo  json_encode([
            'status' => 'success',
        ]);
        wp_die();
    }
    public static function fixed_service_list()
    {
        require_once( dirname( __FILE__ ).'/admin_view/service/index_fixed_service.php' );
    }
    public static function fixed_service_update()
    {
        require_once( dirname( __FILE__ ).'/admin_view/service/fixed_service_update.php' );
    }
    public static function both_fixed_variable_service($result,$sql_fixed_service_result)
    {
        $is_payment_show=true;
        $main_document_present="not_present";
        
        
        $html .='<h4>You can select and change the service here</h4>';
        $html .='<div id="fixed_section_service">';
        foreach(json_decode($sql_fixed_service_result[0]->service_json,true) as $key=>$value)
        {
            $fixed_service_others_price_flag=false;
            $fixed_service_others_price_value=0;
            if($value['service_name']=="Others")
            {
                $fixed_service_others_price_flag=true;
                $fixed_service_others_price_value=$value['service_price'];
                continue;
            }
            if($value['is_input'])
            {
                $main_document_present="present";

                $html .='<div class="product__item d-flex-center">
                <input
                  type="checkbox"
                  class="encoder_it_custom_services"
                  data-price="'.$value['service_price'].'"
                  data-name="'.$value['service_name'].'"
                  onclick="add_total_price(this.id)"
                  id="encoder_it_custom_services'.$value['service_id'].'"
                  name="encoder_it_custom_services[]"
                  value="'.$value['service_id'].'"
                />
                <label class="d-flex-center">
                  <span>'.$value['service_name'].'</span>
                  <input type="number" min="1" id="input_main_applicat_increment" value="1" style="margin-left:300px">
                  <span> X </span>
                <span>$'.$value['service_price'].'</span>
                </label>
              </div>';
            }else
            {
                $html .='<div class="product__item d-flex-center">
                <input
                  type="checkbox"
                  class="encoder_it_custom_services"
                  data-price="'.$value['service_price'].'"
                  data-name="'.$value['service_name'].'"
                  onclick="add_total_price(this.id)"
                  id="encoder_it_custom_services'.$value['service_id'].'"
                  name="encoder_it_custom_services[]"
                  value="'.$value['service_id'].'"
                />
                <label class="d-flex-center">
                  <span>'.$value['service_name'].'</span>
                  <span>$'.$value['service_price'].'</span>
                </label>
              </div>';
            }
            
        }
  
        $html .='</div>';
        if($fixed_service_others_price_flag)
        {

            $html .='<div style="display:flex;justify-content: flex-end"><button  id="add_new_fixed_service">Add More</button></div>';
        }
        $html .='<button id="get_customized_selection">Customize Your Selections</button>';
        
       $html .='<div id="customized_section_service"  style="display:none">';
        if(!empty($result))
        {
            
            foreach($result as $key=>$value)
            {
                 $html .='<div class="product__item d-flex-center">
                 <input
                   type="checkbox"
                   class="encoder_it_custom_services"
                   data-price="'.$value->price.'"
                   onclick="add_total_price(this.id)"
                   id="encoder_it_custom_services'.$value->service_id.'"
                   name="encoder_it_custom_services[]"
                   value="'.$value->id.'"
                 />
                 <label class="d-flex-center">
                   <span>'.$value->service_name.'</span>
                   <span>$'.$value->price.'</span>
                 </label>
               </div>';
    
            }
            $is_payment_show=true;
        }else
        {
            $html .='<p>No service found for this country</p>';
        }
        $html .='<button id="get_customized_selection_undone" style="display:none;margin-top:30px">Get Previous</button>';
        $html .='</div>';

        return [
        'html'=>$html,
        'fixed_service_others_price_value'=>$fixed_service_others_price_value,
        'fixed_service_others_price_flag'=>$fixed_service_others_price_flag,
        'is_payment_show'=>$is_payment_show,
        'main_document_present'=>$main_document_present
    ];
    }
    public static function only_fixed_service()
    {

    }
    public static function only_variable_service()
    {

    } 
}
