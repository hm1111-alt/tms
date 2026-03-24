/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




$('.days').on('keyup',function(){
        
        var detail_id = $(this).attr('id');
        
        var sum = 0;
        $('.days_'+detail_id+'').each(function(){
            if(this.value!=''){
                sum += parseFloat(this.value);
            }
        });
        
        var sum_final = sum.toFixed(3);
        
        $('#detail_total_'+detail_id+'').val(sum_final);
        $('.detail_total_'+detail_id+'').html(sum_final);
        
        var day_type = $(this).attr('day_type');
        
        var sum2 = 0;
        $('.'+day_type+'').each(function(){
            if(this.value!=''){
                sum2 += parseFloat(this.value);
            }
        });
        var sum2_final = sum2.toFixed(3);
        
        $('.'+day_type+'_total').val(sum2_final);
        
        
        compute_total_days();
        
});



function compute_total_days()
{
        var super_total = 0;
        $('.days_subtotal').each(function(){
            if(this.value!=''){
                super_total += parseFloat(this.value);
            }
        });
        
        var super_total_final = super_total.toFixed(3);
        $('#total_approved').val(super_total_final);
        $('.total_approved').html(super_total_final);
        
        var total_applied =  parseFloat($('#total_days').val()).toFixed(3);
    
        if(parseFloat(super_total_final)>total_applied){
            $("#total_approved_div").addClass("days_error");
            $("#total_approved_div").removeClass("days_warning");
        } else if(parseFloat(super_total_final)<total_applied){
            $("#total_approved_div").addClass("days_warning");
            $("#total_approved_div").removeClass("days_error");
        } else {
            $("#total_approved_div").removeClass("days_warning");
            $("#total_approved_div").removeClass("days_error");
        }
}

$('.sl_days').on('keyup',function(){
        var totalsl_applied =  parseFloat($('#totalsl_days_applied').html());
        var total_sl;// =  parseFloat($('#total_sl').html());
        if($('#sl_withpay').val()!=''){
            var sl_withpay =  parseFloat($('#sl_withpay').val());
        } else {
            var sl_withpay = 0;
        }
        if($('#sl_wopay').val()!=''){
            var sl_wopay =  parseFloat($('#sl_wopay').val());
        } else {
            var sl_wopay =  0;
        }

        total_sl = sl_withpay + sl_wopay;
        $('#total_sl').html(total_sl);
        if(parseFloat(total_sl)>totalsl_applied){
            $("#total_sl_div").addClass("days_warning");
        } else {
            $("#total_sl_div").removeClass("days_warning");
        }
        compute_total_days();
});

$('.slp_days').on('keyup',function(){
        var totalslp_applied =  parseFloat($('#totalslp_days_applied').html());
        var total_slp;// =  parseFloat($('#total_slp').html());
        if($('#slp_withpay').val()!=''){
            var slp_withpay =  parseFloat($('#slp_withpay').val());
        } else {
            var slp_withpay = 0;
        }

        total_slp = slp_withpay;
        $('#total_slp').html(total_slp);
        if(parseFloat(total_slp)>totalslp_applied){
            $("#total_slp_div").addClass("days_warning");
        } else {
            $("#total_slp_div").removeClass("days_warning");
        }
        compute_total_days();
});


$('#btn_receive').on('click',function(event){
        event.preventDefault();
        var total_approved =  parseFloat($('#total_approved').val());
        var total_applied =  parseFloat($('#total_days').val());
        
        if(total_approved>total_applied){
            alert('Approved days do not match with the days applied.')
        } else if(parseFloat(total_approved)<total_applied){
            if(confirm('Approved days is less than the total days applied. Do you want to continue?')){
                //$('#main-content').mask('Submitting... Please wait.');
                $('form[name=receive_form]').submit();
            }
        } else {
            if(confirm('Are you sure you want to continue?')){
                //$('#main-content').mask('Submitting... Please wait.');
                $('form[name=receive_form]').submit();
            }
        }
}); 



//function compute_total_days()
//{
//    var totalvl =  parseFloat($('#total_vl').html());
//    var totalsl =  parseFloat($('#total_sl').html());
//    var totalslp =  parseFloat($('#total_slp').html());
//    var total_applied =  parseFloat($('#total_days').val());
//
//
//    var total_approved = totalvl + totalsl + totalslp;
//    $('#total_approved').val(total_approved);
//    $('.total_approved').html(total_approved);
//    if(parseFloat(total_approved)>total_applied){
//        $("#total_approved_div").addClass("days_warning");
//    } else {
//        $("#total_approved_div").removeClass("days_warning");
//    }
//}
//                
                
                
                
                
                
function validateamount(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode( key );
        var regex = /[0-9]|[\b]|[\.]|[\t]/;
        if( !regex.test(key) ) {
              theEvent.returnValue = false;
              if(theEvent.preventDefault) theEvent.preventDefault();
        }
} 

 
                $('.days').on('focus',function(){
                    if ($(this).val() == '0.00' || $(this).val() == 0) {
                            $(this).val('');
                    }
                });

                $('.days').on('blur',function(){
                    if ($(this).val() == '' || $(this).val() == 0) {
                            $(this).val('0');
                    }
                });
                