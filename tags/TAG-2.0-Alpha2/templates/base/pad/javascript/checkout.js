jQuery.Toc.Checkout = function(config) {
  config = config || {};

  config.steps = {
    checkoutMethodForm : 1,
    billingInformationForm : 2,
    shippingInformationForm : 3,
    shippingMethodForm : 4,
    paymentInformationForm : 5,
    orderConfirmationForm : 6
  };

  this.initialize(config);
};

/*
 * 
 */
jQuery.Toc.override(jQuery.Toc.Checkout, {

  /**
   * Send Ajax Request to Server
   */
  sendRequest : function(data) {
    data = data || {};

    $.extend(data, {
      type : 'post',
      dataType : 'json',
      beforeSend : function() {
        $.mobile.showPageLoadingMsg();
      },
      complete : function() {
        $.mobile.hidePageLoadingMsg();  
      }
    });

    $.ajax(data);
  },

  /**
   * Set options to properties
   */
  setOptions : function(options) {
    for ( var m in options) {
      this[m] = options[m];
    }
  },

  /**
   * Initialize the Checkout Forms.
   */
  initialize : function(options) {
    // initialize options
    this.setOptions(options);

    // initialize checkout navigation
    this.iniCheckoutForms();

    if (this.logged_on == false) {
      this.loadCheckoutMethodForm();
    } else {
      this.loadBillingInformationForm();
    }
  },

  /**
   * Initialize Checkout Forms.
   */
  iniCheckoutForms : function() {
    var scope = this;

    // process btn new customer
    $('#btn-new-customer').live('click', function() {
      scope.loadBillingInformationForm();
    });

    // process btn login
    $('#btn-login').live('click', function(e) {
      e.preventDefault();

      scope.sendRequest({
        url : 'http://www.toc2.me/account/login/process',
        data : $('#email_address, #password'),
        success : function(response) {
          if (response.success === true) {
            scope.loadBillingInformationForm();
          } else {
            alert('false');
          }
        }
      });
    });

    // billing country change
    $('#billing_country').live('change', function() {
      scope.sendRequest({
        url : 'http://www.toc2.me/checkout/checkout/get_country_states',
        data : {
          countries_id : $('#billing_country option:selected').val()
        },
        success : function(response) {
          if (response.success == true) {
            $('#billing_state').html(response.options).trigger('change');
          }
        }
      });
    });

    // shipping country change
    $('#shipping_country').live('change', function() {
      scope.sendRequest({
        url : 'http://www.toc2.me/checkout/checkout/get_country_states',
        data : {
          countries_id : $('#shipping_country option:selected').val()
        },
        success : function(response) {
          if (response.success == true) {
            $('#shipping_state').html(response.options).trigger('change');         
          }
        }
      });
    });
    
    //save billing form
    $('#btn-save-billing-form').live('click', function(e) {
      e.preventDefault();

      scope.saveBillingInformationForm();
    });
    
    //save shipping form
    $('#btn-save-shipping-form').live('click', function(e) {
      e.preventDefault();

      scope.saveShippingInformationForm();
    });
    
    //save shipping form
    $('#btn-save-shipping-method').live('click', function(e) {
      e.preventDefault();

      var shipping_methods = document.getElementsByName("shipping_mod_sel"); 
      var shipping_method = null;
      $.each(shipping_methods, function(index, method) {
        if (method.type == 'radio') {
          if (method.checked) {
            shipping_method = method.value;
          }
        } else if (method.type == 'hidden') {
          shipping_method = method.value;
        }
      });
      
      if (shipping_method != null) {
        scope.sendRequest({
          url : 'http://www.toc2.me/checkout/checkout/save_shipping_method',
          data : {
            shipping_mod_sel: shipping_method,
            shipping_comments: $('#shipping_comments').val()
          },
          success : function(response) {
            // close checkout method form
            $('#shippingMethodForm .formBody').slideUp();
            // open billing information form
            scope.loadPaymentInformationForm();
          }
        });
      } else {
        alert('Please select a shipping method!');
      }
    });
    
    //save payment form
    $('#btn-save-payment-form').live('click', function(e) {
      e.preventDefault();
      
      var data = {
          payment_comments: $('#payment_comments').val()
        };
         
        if ($('#conditions').length > 0) {
          data.conditions = (($('#conditions').checked == true) ? 1 : 0);
        } 
              
        //if (this.isTotalZero == false) {
          var payment_methods = document.getElementsByName("payment_method"); 
          var payment_method = null;
          
          $.each(payment_methods, function(index, method) {
            if (method.type == 'radio') {
              if (method.checked) {
                payment_method = method.value;
              }
            } else if (method.type == 'hidden') {
              payment_method = method.value;
            }
          });
          
          if (payment_method != null) {
            data['payment_method'] = payment_method;

            //get all the inputs
            var div_payment = $('#payment_method_' + payment_method);
            var inputs = div_payment.find('input');
            if (inputs.length > 0) {
              $.each(inputs, function(index, input){
                if (input.type == 'text') {
                  data[input.name] = input.value;
                } else if ((input.type == 'checkbox') || (input.type == 'radio')) {
                  if (input.checked == true) {
                    data[input.name] = input.value;
                  }
                }
              });
            }
            
            var selects = div_payment.find('select');
            $.each(selects, function(index, select) {
              data[select.name] = select.options[select.selectedIndex].value;
            });
          } else {
            alert('Please select a payment method!');
          }
          //}
        
        scope.sendRequest({
          url : 'http://www.toc2.me/checkout/checkout/save_payment_method',
          data : data,
          success : function(response) {
            // close checkout method form
            $('#paymentMethodForm .formBody').slideUp();
            // open billing information form
            scope.loadOrderConfirmationForm();
          }
        });        
    });
  },
  
  loadPaymentInformationForm : function() {
    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/load_payment_information_form',
      success : function(response) {
          // close checkout method form
          $('#shippingMethodForm .formBody').trigger('collapse');
          // open billing information form
          $('#paymentInformationForm .formBody').html(response.form).trigger('create').show();
          $('#paymentInformationForm').trigger('expand');
      }
    });
  },

  /**
   * Load Checkout Method Form
   */
  loadCheckoutMethodForm : function() {
    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/load_checkout_method_form',
      success : function(response) {
        $('#checkoutMethodForm .formBody').html(response.form).trigger('create').show();
        $('#checkoutMethodForm').trigger('expand');
      }
    });
  },

  /**
   * Load Billing Information Form
   */
  loadBillingInformationForm : function() {
    var scope = this;

    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/load_billing_form',
      data: $('input[name=checkout_method]:checked'),
      success : function(response) {
        // close checkout method form
        $('#checkoutMethodForm .formBody').trigger('collapse');
        // open billing information form
        $('#billingInformationForm .formBody').html(response.form).trigger('create').show();
        $('#billingInformationForm').trigger('expand');
      }
    });
  },
  
  /**
   * Save Billing Information Form
   */
  saveBillingInformationForm: function() {
    var scope = this;

    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/save_billing_form',
      data: $('input[name=checkout_method]:checked, input[name=billing_email_address], input[name=billing_password], input[name=confirmation], input[name=billing_gender]:checked, input[name=billing_firstname], input[name=billing_company], input[name=billing_lastname], input[name=billing_street_address], input[name=billing_suburb], input[name=billing_postcode], input[name=billing_city], select[name=billing_country], select[name=billing_state], input[name=billing_telephone], input[name=billing_fax], input[name=create_billing_address], input[name=ship_to_this_address]'),
      success : function(response) {
        // close checkout method form
        //$('#checkoutMethodForm .formBody').slideUp();
        // open billing information form
        //$('#billingInformationForm .formBody').html(response.form).slideDown();
        if (response.success == true) {
          scope.loadShippingInformationForm();  
        } else {
          alert(response.errors.join("\n"));
        }
      }
    });
  },

  /**
   * Load Shipping Information Form
   */
  loadShippingInformationForm : function() {
    var scope = this;

    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/load_shipping_form',
      success : function(response) {
        // close checkout method form
        $('#billingInformationForm .formBody').trigger('collapse');
        // open billing information form
        $('#shippingInformationForm .formBody').html(response.form).trigger('create').show();
        $('#shippingInformationForm').trigger('expand');
      }
    });
  },
  
  /**
   * Save Billing Information Form
   */
  saveShippingInformationForm: function() {
    var scope = this;

    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/save_shipping_form',
      data: $('input[name=shipping_gender]:checked, input[name=shipping_firstname], input[name=shipping_lastname], input[name=shipping_company], input[name=shipping_street_address], input[name=shipping_suburb], input[name=shipping_postcode], input[name=shipping_city], select[name=shipping_country], select[name=shipping_state], input[name=shipping_telephone], input[name=shipping_fax]'),
      success : function(response) {
        // close checkout method form
        //$('#checkoutMethodForm .formBody').slideUp();
        // open billing information form
        //$('#billingInformationForm .formBody').html(response.form).slideDown();
        if (response.success == true) {
          scope.loadShippingMethodForm();  
        } else {
          alert(response.errors.join("\n"));
        }
      }
    });
  },
  
  loadShippingMethodForm: function() {
    var scope = this;

    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/load_shipping_method_form',
      success : function(response) {
        if (response.success == true) {
            // close checkout method form
            $('#shippingInformationForm .formBody').trigger('collapse');
            // open billing information form
            $('#shippingMethodForm .formBody').html(response.form).trigger('create').show();
            $('#shippingMethodForm').trigger('expand');
        } else {
            alert(response.errors.join("\n"));
        }
      }
    });
  },
  
  loadOrderConfirmationForm: function() {
    var scope = this;

    this.sendRequest({
      url : 'http://www.toc2.me/checkout/checkout/load_order_confirmation_form',
      success : function(response) {
        // close checkout method form
        //$('#checkoutMethodForm .formBody').slideUp();
        // open billing information form
        //$('#billingInformationForm .formBody').html(response.form).slideDown();
        if (response.success == true) {
            // close checkout method form
            $('#paymentInformationForm .formBody').trigger('collapse');
            // open billing information form
            $('#orderConfirmationForm .formBody').html(response.form).trigger('create').show();
            $('#orderConfirmationForm').trigger('expand');
        } else {
          alert(response.errors.join("\n"));
        }
      }
    });
  }
});