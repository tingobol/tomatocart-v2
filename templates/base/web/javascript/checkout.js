/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package        TomatoCart
 * @author        TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl.html
 * @link        http://tomatocart.com
 * @since        Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Checkout Class
 *
 * @package     TomatoCart
 * @subpackage  tomatocart
 * @category    template-module-controller
 * @author      TomatoCart Dev Team
 * @link        http://tomatocart.com/wiki/
 */
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
   * 
   * @param data
   */
  sendRequest : function(data) {
    data = data || {};

    $.extend(data, {
      type : 'post',
      dataType : 'json'
    });

    $.ajax(data);
  },

  /**
   * Set options to properties
   * 
   * @param options
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
        url : base_url + 'account/login/ajax_process',
        data : $('#email_address, #password'),
        success : function(response) {
          if (response.success === true) {
            scope.loadBillingInformationForm();
          } else {
            alert(response.errors);
          }
        },
        beforeSend : function() {
          $("#checkoutMethodForm .formBody").mask();
        },
        complete : function() {
          $("#checkoutMethodForm .formBody").unmask();
        }
      });
    });
    
    //create_billing_address
    $('#create_billing_address').live('click', function(e) {
        var $this = $(this);
        
        if ($this.attr('checked') == true) {
            $('#billingAddressDetails').show();
        } else {
            $('#billingAddressDetails').hide();
        }
    });
    
    //create_shipping_address
    $('#create_shipping_address').live('click', function(e) {
        var $this = $(this);
        
        if ($this.attr('checked') == true) {
            $('#shippingAddressDetails').show();
        } else {
            $('#shippingAddressDetails').hide();
        }
    });

    // billing country change
    $('#billing_country').live('change', function() {
      scope.sendRequest({
        url : base_url + 'checkout/checkout/get_country_states',
        data : {
          countries_id : $('#billing_country option:selected').val()
        },
        success : function(response) {
          if (response.success == true) {
            $('#li-billing-state select').html(response.options);            
          }
        },
        beforeSend : function() {
          $("#billingInformationForm .formBody").mask();
        },
        complete : function() {
          $("#billingInformationForm .formBody").unmask();
        }
      });
    });

    // shipping country change
    $('#shipping_country').live('change', function() {
      scope.sendRequest({
        url : base_url + 'checkout/checkout/get_country_states',
        data : {
          countries_id : $('#shipping_country option:selected').val()
        },
        success : function(response) {
          if (response.success == true) {
            $('#li-shipping-state select').html(response.options);                
          }
        },
        beforeSend : function() {
          $("#shippingInformationForm .formBody").mask();
        },
        complete : function() {
          $("#shippingInformationForm .formBody").unmask();
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
          url : base_url + 'checkout/checkout/save_shipping_method',
          data : {
            shipping_mod_sel: shipping_method,
            shipping_comments: $('#shipping_comments').val()
          },
          success : function(response) {
              if (response.success == true) 
              {
                  // close checkout method form
                  $('#shippingMethodForm .formBody').slideUp();
                  // open billing information form
                  scope.loadPaymentInformationForm();
              } 
          },
          beforeSend : function() {
            $("#shippingMethodForm .formBody").mask();
          },
          complete : function() {
            $("#shippingMethodForm .formBody").unmask();
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
          url : base_url + 'checkout/checkout/save_payment_method',
          data : data,
          success : function(response) {
            // close checkout method form
            $('#paymentMethodForm .formBody').slideUp();
            // open billing information form
            scope.loadOrderConfirmationForm();
          },
          beforeSend : function() {
            $("#paymentMethodForm .formBody").mask();
          },
          complete : function() {
            $("#paymentMethodForm .formBody").unmask();
          }
        });        
    });
  },
  
  loadPaymentInformationForm : function() {
    this.sendRequest({
      url : base_url + 'checkout/checkout/load_payment_information_form',
      success : function(response) {
        $('#paymentInformationForm .formBody').html(response.form).slideDown('slow');
      }
    });
  },

  /**
   * Load Checkout Method Form
   */
  loadCheckoutMethodForm : function() {
    this.sendRequest({
      url : base_url + 'checkout/checkout/load_checkout_method_form',
      success : function(response) {
        $('#checkoutMethodForm .formBody').html(response.form).slideDown('slow');
      }
    });
  },
    
    /**
     * Load Billing Information Form
     */
    loadBillingInformationForm : function() {
        var scope = this;
    
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_billing_form',
            data: $('input[name=checkout_method]:checked'),
            success : function(response) {
                if (response.success == true) {
                    // close checkout method form
                    $('#checkoutMethodForm .formBody').slideUp();
                    // open billing information form
                    $('#billingInformationForm .formBody').html(response.form).slideDown();
                }
            },
            beforeSend : function() {
                $("#checkoutMethodForm .formBody").mask();
            },
            complete : function() {
                $("#checkoutMethodForm .formBody").unmask();
            }
        });
    },
  
  /**
   * Save Billing Information Form
   */
  saveBillingInformationForm: function() {
    var scope = this;

    this.sendRequest({
      url : base_url + 'checkout/checkout/save_billing_form',
      data: $('input[name=checkout_method]:checked, input[name=billing_email_address], input[name=billing_password], input[name=confirmation], input[name=billing_gender]:checked, input[name=billing_firstname], input[name=billing_company], input[name=billing_lastname], input[name=billing_street_address], input[name=billing_suburb], input[name=billing_postcode], input[name=billing_city], select[name=billing_country], select[name=billing_state], input[name=billing_telephone], input[name=billing_fax], input[name=create_billing_address]:checked, input[name=ship_to_this_address]:checked, #sel_billing_address'),
      success : function(response) {
        if (response.success == true) {
          scope.loadShippingInformationForm();  
        } else {
          alert(response.errors.join("\n"));
        }
      },
      beforeSend : function() {
        $("#billingInformationForm .formBody").mask();
      },
      complete : function() {
        $("#billingInformationForm .formBody").unmask();
      }
    });
  },

  /**
   * Load Shipping Information Form
   */
  loadShippingInformationForm : function() {
    var scope = this;

    this.sendRequest({
      url : base_url + 'checkout/checkout/load_shipping_form',
      success : function(response) {
        // close checkout method form
        $('#billingInformationForm .formBody').slideUp();
        // open billing information form
        $('#shippingInformationForm .formBody').html(response.form).slideDown();
      },
      beforeSend : function() {
        $("#billingInformationForm .formBody").mask();
      },
      complete : function() {
        $("#billingInformationForm .formBody").unmask();
      }
    });
  },
  
  /**
   * Save Billing Information Form
   */
  saveShippingInformationForm: function() {
    var scope = this;

    this.sendRequest({
      url : base_url + 'checkout/checkout/save_shipping_form',
      data: $('input[name=shipping_gender]:checked, input[name=shipping_firstname], input[name=shipping_lastname], input[name=shipping_company], input[name=shipping_street_address], input[name=shipping_suburb], input[name=shipping_postcode], input[name=shipping_city], select[name=shipping_country], select[name=shipping_state], input[name=shipping_telephone], input[name=shipping_fax], input[name=create_shipping_address]:checked, #sel_shipping_address'),
      success : function(response) {
        if (response.success == true) {
          scope.loadShippingMethodForm();  
        } else {
          alert(response.errors.join("\n"));
        }
      },
      beforeSend : function() {
        $("#billingInformationForm .formBody").mask();
      },
      complete : function() {
        $("#billingInformationForm .formBody").unmask();
      }
    });
  },
  
  loadShippingMethodForm: function() {
    var scope = this;

    this.sendRequest({
      url : base_url + 'checkout/checkout/load_shipping_method_form',
      success : function(response) {
        // close checkout method form
        //$('#checkoutMethodForm .formBody').slideUp();
        // open billing information form
        //$('#billingInformationForm .formBody').html(response.form).slideDown();
        if (response.success == true) {
          $('#shippingInformationForm .formBody').slideUp();
          $('#shippingMethodForm .formBody').html(response.form).slideDown();
        } else {
          alert(response.errors.join("\n"));
        }
      },
      beforeSend : function() {
        $("#billingInformationForm .formBody").mask();
      },
      complete : function() {
        $("#billingInformationForm .formBody").unmask();
      }
    });
  },
  
    loadOrderConfirmationForm: function() {
        var scope = this;
        
        this.sendRequest({
            url : base_url + 'checkout/checkout/load_order_confirmation_form',
            success : function(response) {
            // close checkout method form
            //$('#checkoutMethodForm .formBody').slideUp();
            // open billing information form
            //$('#billingInformationForm .formBody').html(response.form).slideDown();
                if (response.success == true) {
                    $('#paymentInformationForm .formBody').slideUp();
                    $('#orderConfirmationForm .formBody').html(response.form).slideDown();
                } else {
                    alert(response.errors.join("\n"));
                }
            },
            beforeSend : function() {
                $("#orderConfirmationForm .formBody").mask();
            },
            complete : function() {
                $("#orderConfirmationForm .formBody").unmask();
            }
        });
    }
});