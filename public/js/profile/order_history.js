$(function() {

  

    $(window).on('keydown',function(event){
        if(event.key === 'Enter') {
          event.preventDefault();
          return false;
        }
      });
      
      


      
    
    var activeLangText =  $('#activeLang').text();
    var order_cancel_title = $('.order_cancel_title');
    var order_payment_title = $('.order_payment_title');
    
    

        $('.modal_cancel').on('hidden.bs.modal', function (e) {
        
                
        })
        

      
    $('.cancelBtn').on('click',function(){
        
        var getData = $(this).data('order-id-code');
        myArray = getData.split(",");
       
         var order_code = myArray[0];
         var order_id= myArray[1];

        $('#modal_'+order_id).modal('show');
        $('#send_email_'+order_id).on('click',function(e){
            
            var order_code_sendmail = $('.order_code').val();
           
            var $this = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> sending...';
    
          if ($(this).html() !== loadingText) {
              $this.data('original-text', $(this).html());
              $this.html(loadingText);
          }
          setTimeout(function(){
              $this.html($this.data('original-text'));
          }, 9000);

          var data = {
            _token: $(".idToken").val(),
            'order_code':order_code_sendmail,
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            })
            $.ajax({
                type: "post",
                url: $('#url').val() + '/send_cancel_code/'+order_code_sendmail ,
                data: data,
                dataType: "json",
                success: function (response) {
                   console.log(response);
                }
        
            })
        });


        $('#formcancel_'+order_id).on('submit',function(e){
            e.preventDefault();
            console.log(order_id);
           var text_confirm_value =  $('#text-confirm_'+order_id).val();
           console.log(text_confirm_value);
           var data = {
            _token: $(".idToken").val(),
            'order_id':order_id,
            'input': text_confirm_value,
            };
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        })
    
        $.ajax({
            type: "post",
            url: $('#url').val() + '/user/cancel_contract' ,
            data: data,
            dataType: "json",
            success: function (response) {
                   if(response.status == 400){
                        $.each(response.errors,function(key,item){
                            $('.text-errors').html("");
                            $('.text-errors').removeClass('alert alert-warning');
                            $('.text-errors').addClass('alert alert-danger');
                            $('.text-errors').text(item);
                           
                        });
                    }else if(response.status == 404){
                        $('.text-errors').html("");
                        $('.text-errors').removeClass('alert alert-warning');
                        $('.text-errors').addClass('alert alert-danger');
                        $('.text-errors').text(response.message);
                        
                    
                    }else{
                        
                        $('#modal_'+order_id).modal('hide');
                        $('.text-errors').html("");
                        window.location.href = $('#url').val() +"/user/profile/auth/order_history";
                    
                   }

                  
                
                
            }
    
        })
        
        });
        
       
    });

    
    $('.CloseBtn').on('click',function(){
        var getData = $(this).data('order-id-code');
        myArray = getData.split(",");
       
             order_code = myArray[0];
             order_id= myArray[1];
            //  $(this).find('#formcancel_'+order_id)[0].reset();
        $('#modal_'+order_id).modal('hide');
        
        $('#modal_'+order_id).on('hidden.bs.modal', function () {
           
        
        });
        
        
        // formcancel_{{ $order_info->order_id }}
    });  

   
  

    //CHECK ORDER STATUS
    $("#OrderHistoryList tbody tr").each(function() {
        var order_status = $(this).find("td:nth-child(4)").html(); //get text to check
        var order_payment =  $(this).find("td:nth-child(7)"); //get element
        var order_cancel = $(this).find("td:nth-child(8)"); // get element
      
       

    if(activeLangText == 'EN'){
            
        if(order_status == 'ordered'){
            //show payment
            order_payment.html('Waiting for information check');  
            order_cancel.html('None');
        }else if(order_status == 'checked'){
            order_payment.html('Waiting for information check');     
        }else if(order_status == 'checkinfo'){      
        }else if(order_status == 'deposited'){
            order_payment.html('Canceled');
            order_payment.html('Deposited');
        }else if(order_status == 'canceled'){
            order_payment.html('Canceled');
            order_cancel.html('Canceled');
        }else if(order_status == 'custcanceled'){
            order_payment.html('Canceled');
            order_cancel.html('Canceled');
        }else if(order_status == 'confirm' || order_status == 'released'){
            order_payment.html('Deposited');
        }else if(order_status == 'sold'){
            order_payment.html('Paid');
            order_cancel.html('None');
        }
    }else{
        if(order_status == 'ordered'){
            //show payment
            order_status = $(this).find("td:nth-child(4)").html("???? ?????t H??ng");
            order_payment.html('Ch??? Ki???m Tra Th??ng Tin');
            order_cancel.html('Kh??ng');
            // order_cancel.html('Kh??ng');
        }else if(order_status == 'checked'){
            order_status = $(this).find("td:nth-child(4)").html("???? Nh???n ????n");
            order_payment.html('Ch??? Ki???m Tra Th??ng Tin');
           
        }else if(order_status == 'checkinfo'){
            order_status = $(this).find("td:nth-child(4)").html("???? Ki???m Tra Th??ng Tin");
            // order_cancel.html('None');
        }else if(order_status == 'deposited'){
            order_status = $(this).find("td:nth-child(4)").html("???? ?????t C???c");
            order_payment.html('???? ?????t C???c');
            
        }else if(order_status == 'canceled'){
            order_status = $(this).find("td:nth-child(4)").html("???? Hu??? ????n");
            order_payment.html('???? Hu???');
            order_cancel.html('???? Hu???');

        }else if(order_status == 'custcanceled'){
            order_status = $(this).find("td:nth-child(4)").html("???? Hu??? ????n");
            order_payment.html('???? Hu???');
            order_cancel.html('???? Hu???');

        }else if(order_status == 'confirm' ){
            order_status = $(this).find("td:nth-child(4)").html("X??c Nh???n L???y Xe ");
            order_payment.html('???? ?????t C???c');
            

        }else if(order_status == 'released'){
            order_status = $(this).find("td:nth-child(4)").html("Xe ???? R???i Kho");
            order_payment.html('???? ?????t C???c');

        }else if(order_status == 'sold'){
            order_status = $(this).find("td:nth-child(4)").html("???? Giao Xe");
            order_payment.html('???? Thanh To??n');
            order_cancel.html('Kh??ng');
        }

    }
    })

   
  

    //switchLanguage   
       if(activeLangText == 'VN'){
        $('#OrderHistoryList').DataTable({
        
            "language": {
                "lengthMenu": "Hi???n th??? _MENU_ d??ng m???i trang",
                "zeroRecords": "Kh??ng t??m th???y th??ng tin tr??ng kh???p",
                "info": "Hi???n th??? _PAGE_ of _PAGES_ b???n ghi",
                "infoEmpty": "Kh??ng c?? th??ng tin n??o",
                "infoFiltered": "(l???c t??? _MAX_ b???n ghi t???ng c???ng)",
                "search": "T??m ki???m",
                "paginate": {
                    "next": "Ti???p",
                    'previous':"Tr?????c"
                  }
              
            }
        });
           
       }else{
            $('#OrderHistoryList').DataTable({
            
            });
       }
       
       
     

});