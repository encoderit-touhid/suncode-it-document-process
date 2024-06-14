
<div class="row_d">
            <div class="titel_col">
                <label for="">Payment Method:</label>
            </div>
            <div class="right_col right_total_price">
                <div class="payment_method_container">
                    <div class="item d-flex-center">
                        <input type="radio" checked disabled/>
                        <span><?=$result->payment_method?></span>
                    </div>
                </div>
            </div>
</div>

<div class="row_d" id="encoder_client_payment_group">
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
        </div>
         <div class="paymet-area">
         
         <div id="stripe_payment_div" style="display:none;margin-top:20px">
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
          </div>
        </div>
        <div id="paypal-button-container" style="display:none;margin-top:20px"></div>

        <div class="total__price">
          <span>Total Price</span><span id="price">$ <?=$result->total_price?></span>
        </div>
        <div class="submit_btn"  >
          <input class="buttons" type="submit" name="btn" id="encoder_it_submit_btn_user_form" />
        </div>
      </div>
    </div>


    <style>
  #card-element {
	padding: 13px 5px 13px 20px;
	border-radius: 6px;
	font-size: 16px;
	font-weight: 600;
	color: #0f668a;
	background: #fff !important;
	border: 2px solid var(--primary-color);
}
</style>



    <script src="https://www.paypal.com/sdk/js?client-id=<?=ENCODER_IT_PAYPAL_CLIENT?>&currency=USD&disable-funding=paylater"></script>
       

<script>
 function check_radio_payment_method(id)
  {
    document.getElementById('stripe_payment_div').style.display='none';
    document.getElementById('paypal-button-container').style.display='none'; 
    

    payment_method=document.getElementById(id).value;
    if(id == "encoderit_stripe")
    {
       document.getElementById('stripe_payment_div').style.display='block';
       document.getElementById('paypal-button-container').style.display='none';

       //document.getElementById('encoderit_bank_transfer').style.display='none';
       /*** Show the Submit Button */
       document.getElementById('encoder_it_submit_btn_user_form').removeAttribute("disabled");
    }else if(id=="encoderit_paypal")
    {
      document.getElementById('stripe_payment_div').style.display='none';
      document.getElementById('paypal-button-container').style.display='block';

     // document.getElementById('encoderit_bank_transfer').style.display='none';
      /*** hide the Submit Button */
      document.getElementById('encoder_it_submit_btn_user_form').setAttribute("disabled",true);
    }
    else
    {
      document.getElementById('stripe_payment_div').style.display='none';
      document.getElementById('paypal-button-container').style.display='none';
     // document.getElementById('encoderit_bank_transfer').style.display='none';
       /*** hide the Submit Button */
       document.getElementById('encoder_it_submit_btn_user_form').setAttribute("disabled");
    }
  }

var stripe = Stripe("<?=ENCODER_IT_STRIPE_PK?>");
  var elements = stripe.elements();
  var cardElement = elements.create('card', {
  style: {
    base: {
      iconColor: '#000',
      color: '#3c434a',
      fontWeight: '500',
      fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
      fontSize: '16px',
      fontSmoothing: 'antialiased',
      ':-webkit-autofill': {
        color: '#868686',
      },
      '::placeholder': {
        color: '#3c434a',
      },
    },
    invalid: {
      iconColor: '#ff000c',
      color: '#ff000c',
    },
      g: {
    fill: '#000',
  },
  },
});
  
  // Mount the Card Element to the DOM
  cardElement.mount('#card-element');

  // Paypal Start here //

  if (typeof paypal !== 'undefined') {
  paypal.Buttons({
          createOrder: function(data, actions) {
              return actions.order.create({
                  purchase_units: [{
                      amount: {
                          value: <?=$result->total_price?>,
                          currency_code: 'USD',
                      }
                  }]
              });
          },
          onApprove: function(data, actions) {
              return actions.order.capture().then(function(details) {
                  //const result=JSON.stringify(details,null,2);
                 // console.log(details.purchase_units[0].payments.captures[0].id , details.purchase_units[0].payments.captures[0].status);
                  let paypal_tansaction_id=details.purchase_units[0].payments.captures[0].id;
                  let paypal_transaction_status=details.purchase_units[0].payments.captures[0].status;
                  let paypal_transaction_name=details.payer.name.given_name;
                  if(paypal_transaction_status == "COMPLETED")
                  {
                    swal.showLoading();
            var formdata = new FormData();
            formdata.append('paymentMethodId',paypal_tansaction_id);
           
          
            formdata.append('total_price',<?=$result->total_price?>);
          
            formdata.append('payment_method',payment_method);
            formdata.append('paymentMethodId',paypal_tansaction_id);
            formdata.append('paypal_transaction_name',paypal_transaction_name);
            formdata.append('form_id',<?=$_GET['id']?>);
            formdata.append('action','custom_form_pay_later');
            formdata.append('nonce','<?php echo wp_create_nonce('admin_ajax_nonce_encoderit_custom_form') ?>')
            jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'post',
                    processData: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    success: function(data) {
                      swal.hideLoading()
                      const obj = JSON.parse(data);
                      console.log(obj);

                        if (obj.success == "success") {
                          Swal.fire({
                            text: 'Your payment is successfully completed.',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            
                          }).then((result) => {
                            // Check if the user clicked 'Yes'
                            if (result.isConfirmed) {
                              // Perform your action here
                              // For example, redirect to a URL
                              window.location.href='<?=admin_url() .'/admin.php.?page=scf-custom-cases-user'?>'
                            }else
                            {
                              window.location.href='<?=admin_url() .'/admin.php.?page=scf-custom-cases-user'?>'
                            }
                          });
                        }
                        if(obj.success == "error")
                        {
                          let message_arr=obj.message.split(';')
                          let html='';
                          for(let index=0;index<message_arr.length;index++)
                          {
                               var temp=message_arr[index]+"\n";
                               html = html+temp;
                          }
                          swal.fire({
                            

                            html: html,
                        
                           });
                        }
                    }
                      });
                  }
              });
          },
          onError: function(err) {
              console.error('Error:', err);
              alert('Can Not Pay Zero')
          }
      }).render('#paypal-button-container');
}else
{
  is_palypal=false;
  jQuery('.payment_method_container #is_paypal_div').hide();
}





/********** Pay Pal END Here ******* */












        var form = document.getElementById('fileUploadForm');
   form.addEventListener('submit', function(event) {
    event.preventDefault();
   
    var service=document.getElementsByClassName("encoder_it_custom_services");
   

     if(payment_method == "Credit Card"){
      stripe.createPaymentMethod({
      type: 'card',
      card: cardElement,
      billing_details: {
           name: '<?=wp_get_current_user()->display_name?>',
           email:'<?=wp_get_current_user()->user_email?>',
          },
        }).then(function(result) {
          if (result.error) {
            // Display error to your user
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
          } else {
            
            swal.showLoading();
            var formdata = new FormData();
            formdata.append('paymentMethodId',result.paymentMethod.id);
            formdata.append('total_price',<?=$result->total_price?>);
            formdata.append('payment_method',payment_method);
            formdata.append('form_id',<?=$_GET['id']?>);
            formdata.append('action','custom_form_pay_later');
            formdata.append('nonce','<?php echo wp_create_nonce('admin_ajax_nonce_encoderit_custom_form') ?>')
            jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'post',
                    processData: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    success: function(data) {
                      const obj = JSON.parse(data);
                      console.log(obj);

                        if (obj.success == "success") {
                            Swal.fire({
                            text: 'Your payment is successfully completed.',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            
                          }).then((result) => {
                            // Check if the user clicked 'Yes'
                            if (result.isConfirmed) {
                              // Perform your action here
                              // For example, redirect to a URL
                              window.location.href='<?=admin_url() .'/admin.php.?page=scf-custom-cases-user'?>'
                            }else
                            {
                              window.location.href='<?=admin_url() .'/admin.php.?page=scf-custom-cases-user'?>'
                            }
                          });
                        }
                        if(obj.success == "error")
                        {
                          let message_arr=obj.message.split(';')
                          let html='';
                          for(let index=0;index<message_arr.length;index++)
                          {
                               var temp=message_arr[index]+"\n";
                               html = html+temp;
                          }
                          swal.fire({
                            

                            text: html,
                        
                           });
                        }
                    }
            });
          }
        });
     }
     else
     {
      swal.fire({text: 'Please select a transaction method.', });
     }
     
     
});
    </script>