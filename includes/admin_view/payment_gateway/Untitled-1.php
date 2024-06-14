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
$login_form='
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
           
</style>
           
        <div class="login-page">
        
              <div class="form">
              <h1>'.$message.' </h1>
                <form class="login-form" method="post" action="">
                  <input type="text" name="ENCODER_IT_STRIPE_PK" value="'.$ENCODER_IT_STRIPE_PK.'" placeholder="Stripe PK (published key)"/>
                  <input type="text" name="ENCODER_IT_STRIPE_SK" value="'.$ENCODER_IT_STRIPE_SK.'" placeholder="Stripe SK (secrect key)"/>
                  <input type="text" name="ENCODER_IT_PAYPAL_CLIENT" value="'.$ENCODER_IT_PAYPAL_CLIENT.'" placeholder="Paypal Client ID"/>
                  <button type="submit" name="login">Register</button>
                </form>
              </div>
            </div>
    ';

    echo $login_form;