<script src="https://www.paypal.com/sdk/js?client-id=<?=ENCODER_IT_PAYPAL_CLIENT?>&currency=USD&disable-funding=paylater"></script>
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
<script>
  let total_price = 0;
  let person_number=0;
  let temp_price_on_service_check=0;
  let payment_method='';
  let paypal_tansaction_id='';
  let paypal_transaction_status='';
  let paypal_transaction_name='';
  let paypal_transaction_details='';
  let is_palypal=true;

  jQuery(document).ready(function () {
    
    jQuery("#select_country").select2();

    jQuery("#addFile").on("click", function (e) {
      e.preventDefault();
      var newInput =
        '<div class="file_item"><input type="file" class="file_add" name="files[]" multiple><button class="removefile">X</button><div>';
      jQuery("#files").append(newInput);
    });
  });
  jQuery(document).on("click", ".removefile", function (e) {
    e.preventDefault();

    jQuery(this).closest("div").remove(); // to get clicked element
     
    var check_payment_option_radio=false;
     if(document.getElementById('encoderit_paypal').checked || document.getElementById('encoderit_stripe').checked)
     {
         check_payment_option_radio=true;
     }
    if(document.getElementsByClassName("file_add").length == 0 && check_payment_option_radio)
    {
        location.reload();
    }
  });

  function add_total_price(id) {
     person_number=document.getElementById('person_number').value;
     var checked_servie=[];
     temp_price_on_service_check=0;
    //console.log(person_number);
    if(!person_number)
    {
      alert();
      document.getElementById(id).checked = false;   
      swal.fire({text: 'Please select the number of persons.', });
      return ;
    }
    // if (document.getElementById(id).checked) {
    //   temp_price_on_service_check =
    //   temp_price_on_service_check +
    //     parseFloat(document.getElementById(id).getAttribute("data-price"));
    // } else {
    //   temp_price_on_service_check =
    //   temp_price_on_service_check -
    //     parseFloat(document.getElementById(id).getAttribute("data-price"));
    // }
     
    var service=document.getElementsByClassName("encoder_it_custom_services");
    //console.log(service);
    for(var j=0;j<service.length;j++)
    {
      if(service[j].checked)
      {
        checked_servie.push(service[j].id);
      }
    }
    //console.log(checked_servie);
    for(var k=0;k<checked_servie.length;k++)
    {
      if(checked_servie[k]=="encoder_it_custom_servicesfixed_id_1")
      {
        temp_price_on_service_check = temp_price_on_service_check + parseFloat(document.getElementById(checked_servie[k]).getAttribute("data-price") *document.getElementById("input_main_applicat_increment").value );
        
      }else
      {
        temp_price_on_service_check = temp_price_on_service_check + parseFloat(document.getElementById(checked_servie[k]).getAttribute("data-price") * 1);

      }
      
    }
    var custom_service=document.getElementsByClassName("fixed_custom_services_add_more");
    var custom_service_label=document.getElementsByClassName("add_service_label");
        
    for(var i=0;i<custom_service.length;i++)
    {
        if(custom_service[i].checked && custom_service_label[i].value !="")
        {
          temp_price_on_service_check=temp_price_on_service_check+parseFloat(document.getElementById("fixed_service_others_price").value);
        }
    }
    total_price=person_number*temp_price_on_service_check;
    if(total_price <= 0)
    {
      total_price=0;
      temp_price_on_service_check=0;
    }

    document.getElementById("price").innerText = total_price;
  }

  document.getElementById('person_number').addEventListener('change', function(event) {
             var person_number_on_event = event.target.value;
             if(person_number_on_event <= 0)
             {
                  return ;
             }
            add_total_price_by_persons(person_number_on_event)
        });

  document.getElementById('person_number').addEventListener('input', function(event) {
         var person_number_on_event = event.target.value;
         if(person_number_on_event <= 0)
             {
                  return ;
             }
            add_total_price_by_persons(person_number_on_event)
        });

        


  function add_total_price_by_persons(number_of_persons)
  {
   
    // var temp_price=0;
    // var custom_services_checked_or_not=document.getElementsByClassName("encoder_it_custom_services");
    //   for(var i=0;i<custom_services_checked_or_not.length;i++)
    // {
    //     if(custom_services_checked_or_not[i].checked)
    //     {
    //       temp_price =
    //       temp_price +
    //          parseFloat(custom_services_checked_or_not[i].getAttribute("data-price"));
    //     }
              
    // }
    total_price=number_of_persons*temp_price_on_service_check;
    if(total_price <= 0)
    {
      total_price=0;
      temp_price_on_service_check=0;
      
    }

    document.getElementById("price").innerText = total_price;
  }


  function check_radio_payment_method(id)
  {
    document.getElementById('stripe_payment_div').style.display='none';
    document.getElementById('paypal-button-container').style.display='none'; 
    document.getElementById('encoderit_bank_transfer').style.display='none'; 


    var description=document.getElementById('description').value;
    var person_number=document.getElementById('person_number').value;
    var service=document.getElementsByClassName("encoder_it_custom_services");
    var sumbit_service=[];
    for(var i=0;i<service.length;i++)
    {
            if(service[i].checked)
            {
                sumbit_service.push(service[i].value)
            }
    }
    var custom_file=document.getElementsByClassName("file_add");
    var file_bug=false;
      for(var i=0;i<custom_file.length;i++)
    {
           if(!custom_file[i].files[0])
           {
             file_bug=true;
             break;
           }
              
    }
    
    if(total_price == 0 || document.getElementsByClassName("file_add").length == 0 || sumbit_service.length == 0 || person_number == 0 || !description || file_bug)
    {
      if(id !=="NULL")
      {
        swal.fire({
                text: "Please provide all information",
              });
              
      }
              document.getElementById("encoderit_stripe").checked = false;
              document.getElementById("encoderit_paypal").checked = false; 
              document.getElementById("encoderit_bank_transfer").checked = false;        
        return false;
         
    }
    // if(document.getElementsByClassName("file_add").length < sumbit_service.length )
    // {
    //   swal.fire({
    //             text: "You provide lower number of file than service ",
    //           });
    //           document.getElementById(id).checked = false;      
    //     return false;
    // }
    payment_method=document.getElementById(id).value;
    if(id == "encoderit_stripe")
    {
       document.getElementById('stripe_payment_div').style.display='block';
       document.getElementById('paypal-button-container').style.display='none';

       document.getElementById('encoderit_bank_transfer').style.display='none';
       /*** Show the Submit Button */
       document.getElementById('encoder_it_submit_btn_user_form').removeAttribute("disabled");
    }else if(id=="encoderit_paypal")
    {
      document.getElementById('stripe_payment_div').style.display='none';
      document.getElementById('paypal-button-container').style.display='block';

      document.getElementById('encoderit_bank_transfer').style.display='none';
      /*** hide the Submit Button */
      document.getElementById('encoder_it_submit_btn_user_form').setAttribute("disabled",true);
    }
    else if(id=="encoderit_bank_transfer")
    {
      document.getElementById('stripe_payment_div').style.display='none';
      document.getElementById('paypal-button-container').style.display='none';

      document.getElementById('encoderit_bank_transfer').style.display='block';
      /*** hide the Submit Button */
      document.getElementById('encoder_it_submit_btn_user_form').removeAttribute("disabled");
    }else
    {
      document.getElementById('stripe_payment_div').style.display='none';
      document.getElementById('paypal-button-container').style.display='none';
      document.getElementById('encoderit_bank_transfer').style.display='none';
       /*** hide the Submit Button */
       document.getElementById('encoder_it_submit_btn_user_form').setAttribute("disabled");
    }
  }

/******* Stripe Sections */
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
  
  /******* Stripe Sections end */

/********** Pay Pal Start Here ******* */
if (typeof paypal !== 'undefined') {
  paypal.Buttons({
          createOrder: function(data, actions) {
              return actions.order.create({
                  purchase_units: [{
                      amount: {
                          value: total_price,
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
                    var service=document.getElementsByClassName("encoder_it_custom_services");
                    var sumbit_service=[];
                    var sumbit_file=[];
                    
                    
                    for(var i=0;i<service.length;i++)
                    {
                      if(service[i].checked)
                      {
                        sumbit_service.push(service[i].value)
                        }
                    }
                    var description=document.getElementById('description').value;
                    var person_number=document.getElementById('person_number').value;

                    var get_fixed_service=get_fixed_service_information();
                    var fixed_service_flag=get_fixed_service.is_fixed_service;
                    var fixed_service_name=get_fixed_service.fixed_service_name;
                    var fixed_service_price=get_fixed_service.fixed_service_price;
            
            var formdata = new FormData();
            
            if(get_fixed_service.is_fixed_service)
            {
              formdata.append('is_fixed_service',true);
              formdata.append('fixed_service_name',fixed_service_name);
              formdata.append('fixed_service_price',fixed_service_price);
              if(document.getElementById('main_document_present').value == "present")
              {
                if(document.getElementById('encoder_it_custom_servicesfixed_id_1').checked)
                {

                  formdata.append('input_main_applicat_increment',document.getElementById("input_main_applicat_increment").value);
                }
              }
             
            }

            formdata.append('paymentMethodId',paypal_tansaction_id);
            formdata.append('sumbit_service',sumbit_service);
            formdata.append('description',description);
            formdata.append('person_number',person_number);
            var custom_file=document.getElementsByClassName("file_add");
            var select_country=jQuery('#select_country').find(":selected").val();
            for(var i=0;i<custom_file.length;i++)
            {

              formdata.append('file_array[]', custom_file[i].files[0]);

            }
            formdata.append('total_price',total_price);
            formdata.append('select_country',select_country);
            formdata.append('payment_method',payment_method);
            formdata.append('paymentMethodId',paypal_tansaction_id);
            formdata.append('paypal_transaction_name',paypal_transaction_name);
            formdata.append('action','enoderit_custom_form_submit');
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
                            text: 'Your payment is successfully completed.', //'Your files and Payment are successfully uploaded',
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
    var sumbit_service=[];
    var sumbit_file=[];
    
    
    for(var i=0;i<service.length;i++)
    {
      if(service[i].checked)
       {
        sumbit_service.push(service[i].value)
        }
    }
    var description=document.getElementById('description').value;
    var person_number=document.getElementById('person_number').value;


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
            var get_fixed_service=get_fixed_service_information();

            var fixed_service_flag=get_fixed_service.is_fixed_service;
            var fixed_service_name=get_fixed_service.fixed_service_name;
            var fixed_service_price=get_fixed_service.fixed_service_price;

            var formdata = new FormData();
            formdata.append('paymentMethodId',result.paymentMethod.id);
            formdata.append('sumbit_service',sumbit_service);
            formdata.append('description',description);
            formdata.append('person_number',person_number);
            if(get_fixed_service.is_fixed_service)
            {
              formdata.append('is_fixed_service',true);
              formdata.append('fixed_service_name',fixed_service_name);
              formdata.append('fixed_service_price',fixed_service_price);
              if(document.getElementById('main_document_present').value == "present")
              {
                if(document.getElementById('encoder_it_custom_servicesfixed_id_1').checked)
                {

                  formdata.append('input_main_applicat_increment',document.getElementById("input_main_applicat_increment").value);
                }
              }
            }
            var custom_file=document.getElementsByClassName("file_add");
            var select_country=jQuery('#select_country').find(":selected").val();
            for(var i=0;i<custom_file.length;i++)
            {

              formdata.append('file_array[]', custom_file[i].files[0]);

            }
            formdata.append('total_price',total_price);
            formdata.append('select_country',select_country);
            formdata.append('payment_method',payment_method);
            formdata.append('action','enoderit_custom_form_submit');
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
     }else if(payment_method =="Bank Transfer")
     {
      
            
            swal.showLoading();
            var get_fixed_service=get_fixed_service_information();

            var fixed_service_flag=get_fixed_service.is_fixed_service;
            var fixed_service_name=get_fixed_service.fixed_service_name;
            var fixed_service_price=get_fixed_service.fixed_service_price;

            var formdata = new FormData();
            formdata.append('paymentMethodId',"Pay Later");
            formdata.append('sumbit_service',sumbit_service);
            formdata.append('description',description);
            formdata.append('person_number',person_number);
            if(get_fixed_service.is_fixed_service)
            {
              formdata.append('is_fixed_service',true);
              formdata.append('fixed_service_name',fixed_service_name);
              formdata.append('fixed_service_price',fixed_service_price);
              if(document.getElementById('main_document_present').value == "present")
              {
                if(document.getElementById('encoder_it_custom_servicesfixed_id_1').checked)
                {

                  formdata.append('input_main_applicat_increment',document.getElementById("input_main_applicat_increment").value);
                }
              }
            }
            var custom_file=document.getElementsByClassName("file_add");
            var select_country=jQuery('#select_country').find(":selected").val();
            for(var i=0;i<custom_file.length;i++)
            {

              formdata.append('file_array[]', custom_file[i].files[0]);

            }
            formdata.append('total_price',total_price);
            formdata.append('select_country',select_country);
            formdata.append('payment_method',"Pay Later");
            formdata.append('action','enoderit_custom_form_submit');
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
                            // Swal.fire({
                            //     // position: 'top-end',
                            //     icon: 'success',
                            //     text: 'Your files successfully uploaded',
                            //     showConfirmButton: true,
                            //     //timer: 2500
                            // })
                            


                            Swal.fire({
                            text: 'Your files are successfully uploaded and payment is pending. To complete the process, please pay from case list.',
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
     else
     {
      swal.fire({text: 'Please Select The Transaction Method.', });
     }
     
     
});

jQuery('#select_country').on('change',function(e){
  e.preventDefault();
  jQuery('#person_number_div').hide();
  total_price = 0;
  temp_price_on_service_check=0;
  document.getElementById("price").innerText = total_price;
  swal.showLoading();
  jQuery('#encoder_client_service_group').fadeOut('fast');
  jQuery('#encoder_client_payment_group').fadeOut('fast');
  jQuery('#service_container').fadeOut('fast');
  var formdata = new FormData();
  formdata.append('country_id',jQuery(this).val());
  formdata.append('action','enoderit_get_service_by_country');
  formdata.append('nonce','<?php echo wp_create_nonce('admin_ajax_nonce_encoderit_custom_form') ?>')
  jQuery.ajax({
    url: '<?php echo admin_url('admin-ajax.php'); ?>',
    type: 'post',
    processData: false,
    contentType: false,
    processData: false,
    data: formdata,
    success: function(data) {
      swal.close();
      const obj = JSON.parse(data);
       jQuery('#encoder_client_service_group').fadeIn('slow');
       if(obj.is_payment_show)
       {
         jQuery('#encoder_client_payment_group').fadeIn('slow');
       }
       jQuery('#service_container').fadeIn('slow');
       jQuery('#service_container').html(obj.html);
       if(!is_palypal)
       {
        jQuery('.payment_method_container #is_paypal_div').hide();
       }
         
        if(obj.fixed_service_others_price_flag)
        {
          jQuery('#fixed_service_others_price').val(obj.fixed_service_others_price_value)
        }else
        {
          jQuery('#add_new_fixed_service').hide();
        }
        jQuery('#main_document_present').val(obj.main_document_present);
        
     }
        });
})


//  New JS CODE 
  
// jQuery(document).on("click", "#get_customized_selection", function (e) {
//   e.preventDefault();
//   document.getElementById("fixed_section_service").style.display='none';
//   document.getElementById("customized_section_service").style.display='block';
//   document.getElementById("get_customized_selection").style.display='none';

//   check_radio_payment_method("NULL");

// })

jQuery(document).on("click", "#get_customized_selection", function (e) {
  e.preventDefault();

   //jQuery('#fixed_section_service').fadeOut('fast');
   //jQuery('#customized_section_service').fadeIn('fast');

  //document.getElementById("fixed_section_service").style.display='none';
  //document.getElementById("customized_section_service").style.display='block';
  document.getElementById("get_customized_selection_undone").style.display='block';
  document.getElementById("get_customized_selection").style.display='none';
  
  jQuery('#service_container #fixed_section_service').hide();
  jQuery('#service_container #customized_section_service').slideDown();//.fadeIn('slow');

  total_price = 0;
  temp_price_on_service_check=0;
  document.getElementById("price").innerText = total_price;

  var service=document.getElementsByClassName("encoder_it_custom_services");
  for(var j=0;j<service.length;j++)
  {
    service[j].checked = false;
  }
  check_radio_payment_method("NULL");
  jQuery("#add_new_fixed_service").hide();
  jQuery("#person_number_div").show();
  
  jQuery('.others_service_add_more').remove();
  


})

jQuery(document).on("click", "#get_customized_selection_undone", function (e) {
  e.preventDefault();
  //document.getElementById("fixed_section_service").style.display='block';
  //document.getElementById("customized_section_service").style.display='none';
  
  jQuery("#customized_section_service").hide();
  jQuery("#fixed_section_service").slideDown();//.show();
  //document.getElementById("get_customized_selection_undone").style.display='none';
  //document.getElementById("get_customized_selection").style.display='block';
  
  jQuery("#get_customized_selection_undone").fadeOut('slow');
  jQuery("#get_customized_selection").fadeIn('slow');

  //document.getElementById("add_new_fixed_service").style.display='block';
  //document.getElementById("person_number_div").style.display='none';

  jQuery("#add_new_fixed_service").show();
  jQuery("#person_number_div").hide();
  jQuery("#person_number").val("1");

  total_price = 0;
  temp_price_on_service_check=0;
  document.getElementById("price").innerText = total_price;

  var service=document.getElementsByClassName("encoder_it_custom_services");
  for(var j=0;j<service.length;j++)
  {
    service[j].checked = false;
  }
  check_radio_payment_method("NULL");
})

jQuery(document).on("input", "#input_main_applicat_increment", function (e) {
  
  if(document.getElementById('encoder_it_custom_servicesfixed_id_1').checked)
     {
      add_total_price("encoder_it_custom_servicesfixed_id_1");
      console.log("Ad");
     }
})
jQuery(document).on("change", "#input_main_applicat_increment", function (e) {
  
  if(document.getElementById('encoder_it_custom_servicesfixed_id_1').checked)
     {
      add_total_price("encoder_it_custom_servicesfixed_id_1");
      console.log("Ad");
     }
})

function get_fixed_service_information()
{
  var service=document.getElementsByClassName("encoder_it_custom_services");
  var is_fixed_service=false;
  var fixed_service_name=[];
  var fixed_service_price=[];
  for(var j=0;j<service.length;j++)
  {
     if(service[j].checked)
     {
      if(service[j].id.includes("encoder_it_custom_servicesfixed_id"))
      {
        is_fixed_service=true;
        fixed_service_name.push(service[j].getAttribute("data-name"));
        fixed_service_price.push(service[j].getAttribute("data-price"));
      }
     }
  }

  var fixed_service_others_price=jQuery('#fixed_service_others_price').val();
   if(fixed_service_others_price != 0)
   {
      var custom_service=document.getElementsByClassName("fixed_custom_services_add_more");
      var custom_service_label=document.getElementsByClassName("add_service_label");
        
      for(var i=0;i<custom_service.length;i++)
      {
          if(custom_service[i].checked && custom_service_label[i].value !="")
          {
            is_fixed_service=true;
            fixed_service_name.push(custom_service_label[i].value);
            fixed_service_price.push(fixed_service_others_price);
          }
      }
   }
    
  return {
    'is_fixed_service':is_fixed_service,
    'fixed_service_name':fixed_service_name,
    'fixed_service_price':fixed_service_price
  }
}

jQuery(document).on('click','#add_new_fixed_service',function(e){
  e.preventDefault();
  var fixed_service_others_price=jQuery('#fixed_service_others_price').val();
  var newInput =`<div class="product__item d-flex-center others_service_add_more">
                <input type="checkbox" class="fixed_custom_services_add_more"  id="abc_x" onclick="add_total_price(this.id)"  name="encoder_it_custom_services[]" value="fixed_id_7">
                <label class="d-flex-center">
                  <span><input type="text" class="add_service_label"></span>
                  <span class="other_service_price_string">$${fixed_service_others_price}</span>
                </label>
                <button class="removeservice">X</button>
              </div>`;
          
      jQuery("#fixed_section_service").append(newInput);
      jQuery('#fixed_section_service .product__item:last').hide().slideDown(300);
      
})

jQuery(document).on('click','.removeservice',function(e){
 e.preventDefault();
 //jQuery(this).closest("div").remove();
 jQuery(this).closest("div").slideUp(300, function(){ 
  jQuery(this).closest("div").remove();
  add_total_price("NULL");
});
//jQuery(this).closest(".add_service_label").remove();

})

// Example: Generate a random number between 10 and 100
jQuery(document).on('input','.add_service_label',function(e){
 e.preventDefault();

 add_total_price("NULL");
})
jQuery(document).on('change','.add_service_label',function(e){
 e.preventDefault();
 
 add_total_price("NULL");
})


</script>