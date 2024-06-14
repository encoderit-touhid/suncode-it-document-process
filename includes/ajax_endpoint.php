<?php
require_once( WP_PLUGIN_DIR . '/SuncodeIT-Custom-Form'.'/stripe-php-library/init.php' );
 \Stripe\Stripe::setApiKey(ENCODER_IT_STRIPE_SK);

class encoderit_ajax_endpoints
{
    public static function enoderit_custom_form_submit()
    {
         
        //var_dump($_POST);
        //exit;
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
            if($_POST['payment_method'] == "Paypal")
            {
                $is_payment_success=self::encode_it_paypal_payment();
                if(!empty($is_payment_success))
                {
                    echo $is_payment_success;
                } 
            }
            elseif($_POST['payment_method'] == "Pay Later")
            {
                $json_files_by_user=self::save_files_by_user();
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'encoderit_custom_form';
                    if(isset($_POST['is_fixed_service']))
                    {
                        $services=self::enc_get_json_service_price_fixed_service();
                    }else
                    {
                      $services=enc_get_json_service_price($_POST['sumbit_service'],$_POST['select_country']);
                    }
                    $data = array(
                        'user_id' => wp_get_current_user()->id,
                        'country_id'=>$_POST['select_country'],
                        'person_number' => $_POST['person_number'],
                        'description' => $_POST['description'],
                        'services' =>  $services,
                        'files_by_user' => $json_files_by_user,
                        'payment_method' => $_POST['payment_method'],
                        'transaction_number' => $_POST['paymentMethodId'],
                        'total_price' => $_POST['total_price'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    
                    $inserted=$wpdb->insert($table_name, $data);
                    if($inserted)
                    {
                        self::send_mail();
                        self::send_mail_client();
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
            else
            {
                $encode_it_stripe_payment_return=self::encode_it_stripe_payment();
                $decoded_stripe=json_decode($encode_it_stripe_payment_return,true);
                if($decoded_stripe['status'] == "success")
                {
                    $json_files_by_user=self::save_files_by_user();
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'encoderit_custom_form';
                    if(isset($_POST['is_fixed_service']))
                    {
                        $services=self::enc_get_json_service_price_fixed_service();
                    }else
                    {
                      $services=enc_get_json_service_price($_POST['sumbit_service'],$_POST['select_country']);
                    }
                    $data = array(
                        'user_id' => wp_get_current_user()->id,
                        'country_id'=>$_POST['select_country'],
                        'person_number' => $_POST['person_number'],
                        'description' => $_POST['description'],
                        'services' =>  $services,
                        'files_by_user' => $json_files_by_user,
                        'payment_method' => $_POST['payment_method'],
                        'transaction_number' => $_POST['paymentMethodId'],
                        'total_price' => $_POST['total_price'],
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    
                    $inserted=$wpdb->insert($table_name, $data);
                    if($inserted)
                    {
                        self::send_mail();
                        self::send_mail_client();
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
                }else
                {
                    echo  json_encode([
                        'success' => 'error',
                        'message'=>$decoded_stripe['message'].';'
                    ]);
                }
            }

        } 
        wp_die();
    }
    public static function check_validation_data_request()
    {
        $message='';
         if(empty($_FILES))
         {
            $message .='Please Input files.;';
         }
         if(empty($_POST['total_price']))
         {
            $message .='No Service Selected.;';
         }
         if(empty($_POST['payment_method']))
         {
            $message .='Please Select Payment Method.;';
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
    public static function save_files_by_user()
    {
        
        $file_paths=[];
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
    public static function encode_it_stripe_payment()
    {
        try {
            // Create a PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $_POST['total_price']* 100, // Replace with your actual amount
                'currency' => 'usd',
                'payment_method' => $_POST['paymentMethodId'],
                //'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'email' => wp_get_current_user()->user_email,
                    'name'=>wp_get_current_user()->display_name,
                ],
                'automatic_payment_methods'=>[
                    'enabled'=>true,
                    'allow_redirects'=>'never'
                ]
            ]);
           
            // Confirm the PaymentIntent
            // if ($paymentIntent->status === 'requires_action' ||
            //     $paymentIntent->status === 'requires_source_action') {
            //     // Card action required
            //     $confirmation = \Stripe\PaymentIntent::confirm($paymentIntent->id);
            // }
    
            // Handle the success or failure of the payment
            if ($paymentIntent->status === 'succeeded') {
                // Payment succeeded
                return json_encode(['status' => 'success', 'message' => 'Payment succeeded!']);
            } else {
                // Payment failed
                return json_encode(['status' => 'failure', 'message' => 'Payment failed.']);
            }
        } catch (\Exception $e) {
            // Handle errors
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

          
    }
    public static function encode_it_paypal_payment()
    {
        $json_files_by_user=self::save_files_by_user();
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_custom_form';
        if(isset($_POST['is_fixed_service']))
        {
            $services=self::enc_get_json_service_price_fixed_service();
        }else
        {
          $services=enc_get_json_service_price($_POST['sumbit_service'],$_POST['select_country']);
        }
        $data = array(
            'user_id' => wp_get_current_user()->id,
            'country_id'=>$_POST['select_country'],
            'person_number' => $_POST['person_number'],
            'description' => $_POST['description'],
            'services' =>  $services,
            'files_by_user' => $json_files_by_user,
            'payment_method' => $_POST['payment_method'],
            'transaction_number' => $_POST['paymentMethodId'],
            'total_price' => $_POST['total_price'],
            'created_at' => date('Y-m-d H:i:s'),
        );
        
        $inserted=$wpdb->insert($table_name, $data);
        if($inserted)
        {
            self::send_mail();
            self::send_mail_client();
            return  json_encode([
                'success' => 'success',
                'message'=>'Form Submmited Successfully'
            ]);
        }else
        {
            return  json_encode([
                'success' => 'error',
                'message'=>'Something worng.;'
            ]);
        }
       
    }
    public static function send_mail()
    {
        $to = get_option('admin_email');

		$subject = 'New Case Form Submission ' . ' (' . wp_get_current_user()->display_name . ')';

		$message = '<p> Contact Name: ' . wp_get_current_user()->display_name . '</p>';
        $message = '<p> Customer country: '.$_POST['select_country'].'</p>';
		$message .= '<p> Contact Email: ' . wp_get_current_user()->user_email . '</p>';
		$message .= '<p> Payment_method: ' . $_POST['payment_method'] . '</p>';
		$message .= '<p> Transaction Number: ' . $_POST['paymentMethodId'] . '</p>';
        $message .= '<p> Total Price: ' . $_POST['total_price'] . '</p>';

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		wp_mail($to, $subject, $message, $headers);
    }
    public static function send_mail_client()
    {
        $to = wp_get_current_user()->user_email;

		$subject = 'New Case Form Submission ' . ' (' . wp_get_current_user()->display_name . ')';

		$message = '<p> Contact Name: ' . wp_get_current_user()->display_name . '</p>';
        $message = '<p> Customer country: '.$_POST['select_country'].'</p>';
		$message .= '<p> Contact Email: ' . wp_get_current_user()->user_email . '</p>';
		$message .= '<p> Payment_method: ' . $_POST['payment_method'] . '</p>';
		$message .= '<p> Transaction Number: ' . $_POST['paymentMethodId'] . '</p>';
        $message .= '<p> Total Price: ' . $_POST['total_price'] . '</p>';

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		wp_mail($to, $subject, $message, $headers);
    }
    public static function enoderit_custom_form_cancle_form()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_custom_form';
        if (!wp_verify_nonce($_POST['nonce'], 'user_zip_download_suncode')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }else
        {
            $data = array(
                'is_cancelled' => 1,
            );
            $where_condition=array(
                'id' => $_POST['case_id']
            );
            $wpdb->update($table_name, $data, $where_condition);
            echo  json_encode([
                'success' => 'success',
            ]);
        }
       
        wp_die();
    }

    public static function enoderit_custom_form_restore_form()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_custom_form';
        if (!wp_verify_nonce($_POST['nonce'], 'user_zip_download_suncode')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }else
        {
            $data = array(
                'is_cancelled' => 0,
            );
            $where_condition=array(
                'id' => $_POST['case_id']
            );
           $wpdb->update($table_name, $data, $where_condition);
            echo  json_encode([
                'success' => 'success',
            ]);
        }
        
        wp_die();
    }

    public static function enoderit_custom_form_cancle_service()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_custom_form_services';
        if (!wp_verify_nonce($_POST['nonce'], 'user_zip_download_suncode')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }else
        {
            $data = array(
                'is_active' => 0,
            );
            $where_condition=array(
                'id' => $_POST['service']
            );
           $wpdb->update($table_name, $data, $where_condition);
            echo  json_encode([
                'success' => 'success',
            ]);
        }
        
        wp_die();
    }

    public static function enoderit_custom_form_restore_service()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_custom_form_services';
        if (!wp_verify_nonce($_POST['nonce'], 'user_zip_download_suncode')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }else
        {
            $data = array(
                'is_active' => 1,
            );
            $where_condition=array(
                'id' => $_POST['service']
            );
            $wpdb->update($table_name, $data, $where_condition);
            echo  json_encode([
                'success' => 'success',
            ]);
        }
        
        wp_die();
    }
    public static function enoderit_custom_form_remove_service_country()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_service_with_country';
        if (!wp_verify_nonce($_POST['nonce'], 'user_zip_download_suncode')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }else
        {
            $data = array(
                'is_active' => 3,
            );
            $where_condition=array(
                'service_id' => $_POST['service'],
                'country_id' => $_POST['country'],
            );
            $wpdb->update($table_name, $data, $where_condition);

            echo  json_encode([
                'success' => 'success',
            ]);
        }
       
        wp_die();
    }
    public static function encoder_it_set_payment_keys()
    {
        update_option('ENCODER_IT_STRIPE_PK',str_replace(' ', '', $_POST['ENCODER_IT_STRIPE_PK']));
        update_option('ENCODER_IT_STRIPE_SK',str_replace(' ', '', $_POST['ENCODER_IT_STRIPE_SK']));
        update_option('ENCODER_IT_PAYPAL_CLIENT',str_replace(' ', '', $_POST['ENCODER_IT_PAYPAL_CLIENT']));
         
        $ENCODER_IT_STRIPE_PK=get_option('ENCODER_IT_STRIPE_PK');
        $ENCODER_IT_STRIPE_SK=get_option('ENCODER_IT_STRIPE_SK');
        $ENCODER_IT_PAYPAL_CLIENT=get_option('ENCODER_IT_PAYPAL_CLIENT');

        if($ENCODER_IT_STRIPE_PK == "pk_test_51OD1o3HXs2mM51TXR04wpLYzxxWNpOQWZr8Y84oV0Bp5aP1sB0gVic7JqBdrOgQmqYAwT7a9TOfq4UBG5ioifu9F00VwcHhkCb" && $ENCODER_IT_STRIPE_SK=="sk_test_51OD1o3HXs2mM51TXAPMu48pbSpxilR2QjxiXEipq60TE8y96wg51zs9qPSDZomhDtYGcmwIFPboEgFaHi1SINsNZ00FZ8b7i8R" && $ENCODER_IT_PAYPAL_CLIENT=="AVT1TGV_xT-FR1XRXZdKgsyoXIhHf_N4-j26F0W6bYXgLcv4r2jJLu7Bsa1aabiU-0pVGrDFUIdOpvrQ")
        {

            $message='<span style="color: tomato">Registered with Test Keys</span>';
        }elseif(isset($ENCODER_IT_STRIPE_PK) && !empty($ENCODER_IT_STRIPE_PK) && isset($ENCODER_IT_STRIPE_SK) && !empty($ENCODER_IT_STRIPE_SK) && isset($ENCODER_IT_PAYPAL_CLIENT) && !empty($ENCODER_IT_PAYPAL_CLIENT))
        {
            $message='<span style="color: green">Registered</span>';
        }
        echo $message;
        wp_die();
    } 
    public static function enc_get_json_service_price_fixed_service()
    {
        $service=[];
        $fixed_service_name_arr=explode(',',$_POST['fixed_service_name']);
        $fixed_service_price_arr=explode(',',$_POST['fixed_service_price']);
        foreach($fixed_service_name_arr as $key=>$value)
        {
            
            $single['name']=$value;
            $single['price']=$fixed_service_price_arr[$key];
            if($value == "Main Applicant" && isset($_POST['input_main_applicat_increment']))
            {
                $single['is_count']=true;
                $single['input_main_applicat_increment']=$_POST['input_main_applicat_increment'];
            }else
            {
                $single['is_count']=false;
                $single['input_main_applicat_increment']=null;
            }
            //var_dump($single);
            //exit;
            array_push($service,$single);
        }
        
        $services['is_fixed_service']=true;
        $services['service']=$service;
        return json_encode($services);
    }
    public static function enoderit_custom_form_cancle_fixed_service()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_fixed_service_with_country';
        if (!wp_verify_nonce($_POST['nonce'], 'user_zip_download_suncode')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }else
        {
            $data = array(
                'is_active' => 0,
            );
            $where_condition=array(
                'id' => $_POST['service']
            );
           $wpdb->update($table_name, $data, $where_condition);
            echo  json_encode([
                'success' => 'success',
            ]);
        }
        
        wp_die();
    }

    public static function enoderit_custom_form_restore_fixed_service()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_fixed_service_with_country';
        if (!wp_verify_nonce($_POST['nonce'], 'user_zip_download_suncode')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }else
        {
            $data = array(
                'is_active' => 1,
            );
            $where_condition=array(
                'id' => $_POST['service']
            );
            $wpdb->update($table_name, $data, $where_condition);
            echo  json_encode([
                'success' => 'success',
            ]);
        }
        
        wp_die();
    }

    public static function custom_form_pay_later()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'admin_ajax_nonce_encoderit_custom_form')) {
            echo json_encode([
                'success' => 'error',
                'message'=>'Invalid Nonce field'
            ]);
        }
        if($_POST['payment_method'] == "Paypal")
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'encoderit_custom_form';
        
            $data = array(
                'payment_method' => $_POST['payment_method'],
                'transaction_number' => $_POST['paymentMethodId'],
                'updated_by' => wp_get_current_user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $where_condition=array(
                'id' => $_POST['form_id']
            );
             
            $updated=$wpdb->update($table_name, $data, $where_condition);
            if($updated)
            {
                self::send_mail_update_pay_later();
                self::send_mail_client_update_pay_later();
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

        }else
        {
            $encode_it_stripe_payment_return=self::encode_it_stripe_payment();
            $decoded_stripe=json_decode($encode_it_stripe_payment_return,true);
            if($decoded_stripe['status'] == "success")
            {
                global $wpdb;
                $table_name = $wpdb->prefix . 'encoderit_custom_form';
            
                $data = array(
                    'payment_method' => $_POST['payment_method'],
                    'transaction_number' => $_POST['paymentMethodId'],
                    'updated_by' => wp_get_current_user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $where_condition=array(
                    'id' => $_POST['form_id']
                );
                 
                $updated=$wpdb->update($table_name, $data, $where_condition);
                if($updated)
              {
                self::send_mail_update_pay_later();
                self::send_mail_client_update_pay_later();
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
        }
      wp_die();
    }

    public static function send_mail_update_pay_later()
    {
        $to = get_option('admin_email');

		$subject = 'Update Form Submission  ID' . ' (' . '# '. $_POST['form_id'] . ')';

		$message = '<p> Contact Name: ' . wp_get_current_user()->display_name . '</p>';
      
		$message .= '<p> Contact Email: ' . wp_get_current_user()->user_email . '</p>';
		$message .= '<p> Payment_method: ' . $_POST['payment_method'] . '</p>';
		$message .= '<p> Transaction Number: ' . $_POST['paymentMethodId'] . '</p>';
        $message .= '<p> Total Price: ' . $_POST['total_price'] . '</p>';

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		wp_mail($to, $subject, $message, $headers);
    }
    public static function send_mail_client_update_pay_later()
    {
        $to = wp_get_current_user()->user_email;

		$subject = 'Update Form Submission  ID' . ' (' . '# '. $_POST['form_id'] . ')';

		$message = '<p> Contact Name: ' . wp_get_current_user()->display_name . '</p>';
       
		$message .= '<p> Contact Email: ' . wp_get_current_user()->user_email . '</p>';
		$message .= '<p> Payment_method: ' . $_POST['payment_method'] . '</p>';
		$message .= '<p> Transaction Number: ' . $_POST['paymentMethodId'] . '</p>';
        $message .= '<p> Total Price: ' . $_POST['total_price'] . '</p>';

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		wp_mail($to, $subject, $message, $headers);
    }


}