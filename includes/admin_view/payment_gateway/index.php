<?php
$message='<span style="color: tomato">Not Registered</span>';
$ENCODER_IT_STRIPE_PK=get_option('ENCODER_IT_STRIPE_PK');
$ENCODER_IT_STRIPE_SK=get_option('ENCODER_IT_STRIPE_SK');
$ENCODER_IT_PAYPAL_CLIENT=get_option('ENCODER_IT_PAYPAL_CLIENT');

if(isset($_POST['login'])&&!empty($_POST))
{

    update_option('ENCODER_IT_STRIPE_PK',str_replace(' ', '', $_POST['ENCODER_IT_STRIPE_PK']));
    update_option('ENCODER_IT_STRIPE_SK',str_replace(' ', '', $_POST['ENCODER_IT_STRIPE_SK']));
    update_option('ENCODER_IT_PAYPAL_CLIENT',str_replace(' ', '', $_POST['ENCODER_IT_PAYPAL_CLIENT']));
}
else
{
   $ENCODER_IT_STRIPE_PK=get_option('ENCODER_IT_STRIPE_PK');
   $ENCODER_IT_STRIPE_SK=get_option('ENCODER_IT_STRIPE_SK');
   $ENCODER_IT_PAYPAL_CLIENT=get_option('ENCODER_IT_PAYPAL_CLIENT');
   
}
if($ENCODER_IT_STRIPE_PK == "pk_test_51OD1o3HXs2mM51TXR04wpLYzxxWNpOQWZr8Y84oV0Bp5aP1sB0gVic7JqBdrOgQmqYAwT7a9TOfq4UBG5ioifu9F00VwcHhkCb" && $ENCODER_IT_STRIPE_SK=="sk_test_51OD1o3HXs2mM51TXAPMu48pbSpxilR2QjxiXEipq60TE8y96wg51zs9qPSDZomhDtYGcmwIFPboEgFaHi1SINsNZ00FZ8b7i8R" && $ENCODER_IT_PAYPAL_CLIENT=="AVT1TGV_xT-FR1XRXZdKgsyoXIhHf_N4-j26F0W6bYXgLcv4r2jJLu7Bsa1aabiU-0pVGrDFUIdOpvrQ")
   {

       $message='<span style="color: tomato">Registered with Test Keys</span>';
   }elseif(isset($ENCODER_IT_STRIPE_PK) && !empty($ENCODER_IT_STRIPE_PK) && isset($ENCODER_IT_STRIPE_SK) && !empty($ENCODER_IT_STRIPE_SK) && isset($ENCODER_IT_PAYPAL_CLIENT) && !empty($ENCODER_IT_PAYPAL_CLIENT))
   {
    $message='<span style="color: green">Registered</span>';
   }
?>
        <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:300);

            .login-page {
              width: 820px;
              padding: 8% 0 0;
              margin: auto;
            }
            .form {
              position: relative;
              z-index: 1;
              background: #FFFFFF;
              max-width: 1000px;
              margin: 0 auto 100px;
              padding: 45px;
              text-align: center;
              box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }
            .form input {
              font-family: "Roboto", sans-serif;
              outline: 0;
              background: #f2f2f2;
              width: 100%;
              border: 0;
              margin: 0 0 15px;
              padding: 15px;
              box-sizing: border-box;
              font-size: 14px;
            }
            .form button {
              font-family: "Roboto", sans-serif;
              text-transform: uppercase;
              outline: 0;
              background: #4CAF50;
              width: 100%;
              border: 0;
              padding: 15px;
              color: #FFFFFF;
              font-size: 14px;
              -webkit-transition: all 0.3 ease;
              transition: all 0.3 ease;
              cursor: pointer;
            }
            .form button:hover,.form button:active,.form button:focus {
              background: #43A047;
            }
            .form .message {
              margin: 15px 0 0;
              color: #b3b3b3;
              font-size: 12px;
            }
            .form .message a {
              color: #4CAF50;
              text-decoration: none;
            }
            .form .register-form {
              display: none;
            }
            .container {
              position: relative;
              z-index: 1;
              max-width: 300px;
              margin: 0 auto;
            }
            .container:before, .container:after {
              content: "";
              display: block;
              clear: both;
            }
            .container .info {
              margin: 50px auto;
              text-align: center;
            }
            .container .info h1 {
              margin: 0 0 15px;
              padding: 0;
              font-size: 36px;
              font-weight: 300;
              color: #1a1a1a;
            }
            .container .info span {
              color: #4d4d4d;
              font-size: 12px;
            }
            .container .info span a {
              color: #000000;
              text-decoration: none;
            }
            .container .info span .fa {
              color: #EF3B3A;
            }
            label {
                display: block;
                font-weight: bold;
                margin: 15px 5px;
                float: left;
            }
            
           
</style>
           
        <div class="login-page">
        
              <div class="form">
              <h1 id="register_msg"><?=$message?> </h1>
                <form class="login-form" method="post" action="" id="encoder_option_page_Setings">
                  <label>Stripe Published key</label>
                  <input type="text" id="ENCODER_IT_STRIPE_PK" name="ENCODER_IT_STRIPE_PK" value="<?=$ENCODER_IT_STRIPE_PK?>" placeholder="Stripe PK (published key)"/>
                  <label>Stripe Secret key</label>
                  <input type="text" id="ENCODER_IT_STRIPE_SK" name="ENCODER_IT_STRIPE_SK" value="<?=$ENCODER_IT_STRIPE_SK?>" placeholder="Stripe SK (secrect key)"/>
                  <label>Paypal Client ID</label>
                  <input type="text" id="ENCODER_IT_PAYPAL_CLIENT" name="ENCODER_IT_PAYPAL_CLIENT" value="<?=$ENCODER_IT_PAYPAL_CLIENT?>" placeholder="Paypal Client ID"/>
                  <button type="submit" id="encoder_option_page_Setings_button">Register</button>
                </form>
              </div>
            </div>

            <script>
                jQuery('#encoder_option_page_Setings_button').on('click',function(e){
                e.preventDefault();
                Swal.fire({
                title: 'Are you sure?',
                // text: 'This action Change Make it Set Keys.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                   
                    var formdata = new FormData();
                        formdata.append('action','encoder_it_set_payment_keys');
                        formdata.append('nonce',action_url_ajax.nonce)
                        formdata.append('ENCODER_IT_STRIPE_PK',jQuery('#ENCODER_IT_STRIPE_PK').val())
                        formdata.append('ENCODER_IT_STRIPE_SK',jQuery('#ENCODER_IT_STRIPE_SK').val())
                        formdata.append('ENCODER_IT_PAYPAL_CLIENT',jQuery('#ENCODER_IT_PAYPAL_CLIENT').val())
                        jQuery.ajax({
                        url: action_url_ajax.ajax_url,
                        type: 'post',
                        processData: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        success: function(data) {
                         jQuery('#register_msg').html(data);
                        }
                        });
                } else {
                    // User clicked "Cancel" or closed the dialog
                    Swal.fire('Cancelled', 'The action was cancelled.', 'info');
                    // Add your logic here for cancellation
                }
        });
                 })
            </script>
    <?php
  
    