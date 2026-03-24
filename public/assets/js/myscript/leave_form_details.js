

        var get_mindate_url;
                
        
        $('#leave_type').on("change", function () {
            
                //$('#leave_form').mask('Loading form... Please wait.');
                //$('#leave_form').html('<p class="loading-text"><span class="spinner"></span><br>Loading form... Please wait.</p>');
                
                var leave_type = $(this).val();
                
                $('#monet_lbl span.button-checkbox button').removeClass('btn-success');
                $('#monet_lbl span.button-checkbox button').addClass('btn-base');
                $('#monet_lbl span.button-checkbox button span.icon-check').css('display','none');
                $('#monet_lbl span.button-checkbox button span.icon-check-empty').css('display','inline');
                //$('#is_monetized').prop('checked',false);
                
                $('.monet_days_div').slideUp();
                $(".date_from").val('');
                $(".date_to").val('');
                $('.date_to').prop('disabled', true);
                $('.date_to').datepicker('setDaysOfWeekDisabled', [0, 6]);
                
                if(leave_type=='' || leave_type==15){ // terminal leave
                    $('#leave_type_others').slideUp();
                    $('#leave_type_others').val('');
                    $('#leave_details_div').slideUp();
                    //$('#leave_form').unmask();
                    //$('#monet_div').slideUp();
                } else if(leave_type=='others'){
                    $('#leave_type_others').slideDown();
                    $('#leave_details_div').slideUp();
                    //$('#monet_div').slideUp();
                } else {
                    $('#leave_type_others').slideUp();
                    $('#leave_type_others').val('');
                    $('#leave_details_div').slideDown();
                    $("#leave_details_div").css("overflow", "visible");
                    //$('#monet_div').slideUp();
                    
                    /*if(leave_type==1 || leave_type==2){ // ------for Monet
                        $('#monet_div').slideDown();
                    }*/
                    $('#vl_div').slideUp();
                    $('#sl_div').slideUp();
                    $('#women_div').slideUp();
                    $('#study_div').slideUp();
                    $('#maternity_div').slideUp();
                    //$('.from_hd').prop('readonly',false);
                    
                    if(leave_type==2){ // ---------------- for SICK leave
                        $('#sl_div').slideDown();
                        var datenow = new Date();
						//datenow.setDate(datenow.getDate() - 1); //same day filing
						
                        if(datenow===0 || datenow===6){
                            datenow.setDate(datenow.getDate() - 1);
                        }
                        if(datenow===0 || datenow===6){
                            datenow.setDate(datenow.getDate() - 1);
                        }
                        
                        //$(".date_from").datepicker( "option", "maxDate", datenow);
                        $('.date_from').datepicker('setEndDate', datenow);
                        //$(".date_to").datepicker( "option", "maxDate", datenow);
                        $('.date_to').datepicker('setEndDate', datenow);
                        
                        //if($('#access_level').val()==1 || $('#user_type').val()==22 || $('#user_type').val()==23 || $('#user_type').val()==4){
                        if($('#access_level').val()==1){
                            var datenow2 = new Date();
                            datenow2.setDate(datenow2.getDate() +150);
                            //$(".date_from").datepicker( "option", "maxDate", datenow2);
                            $('.date_from').datepicker('setEndDate', datenow2);
                            //$(".date_to").datepicker( "option", "maxDate", datenow2);
                            $('.date_to').datepicker('setEndDate', datenow2);
                        }
                    
                    } else if(leave_type==1 || leave_type==6){ // ------ for VL vacation leave or SLP
                        $('#vl_div').slideDown();
                        //$('.date_from').datepicker('option', 'maxDate', null);
                        $('.date_from').datepicker('setEndDate', null);
                        //$(".date_to").datepicker( "option", "maxDate", datenow);
                        $('.date_to').datepicker('setEndDate', null);
                        
                        if(leave_type==6){
                            $('#is_emergency_div').slideDown();
                        } else {
                            $('#is_emergency_div').slideUp();
                        }
                        
                    } else if(leave_type==13){ // ---------------- for special leave benefits for WOMEN
                        $('#women_div').slideDown();
                        
                    } else if(leave_type==4){ // ---------------- for PATERNITY LEAVE
                        var datenow2 = new Date();
                        datenow2.setDate(datenow2.getDate() +60);
                        alert('a')
                        $('.date_to').datepicker('setEndDate', datenow2);
                        
                    } else if(leave_type==3){ // ---------------- for MATERTNITY leave
                        $('#maternity_div').slideDown();
                        $('.date_to').datepicker('setDaysOfWeekDisabled', []);
                        //$('.from_hd').prop('readonly',true);
                        //$('.from_hd').val('0');
                        
                    } else if(leave_type==11){ // ---------------- for STUDY leave
                        $('#study_div').slideDown();
                        
                    }
                    
                    var post_data={};
                    post_data['leave_type']=$(this).val();
                    $.ajax({
                            url: get_mindate_url,
                            type: 'POST',
                            data: post_data,
                            success:function(result){
                                var now = new Date(result * 1000);
                                //$(".date_from").datepicker( "option", "minDate", now);
                                $('.date_from').datepicker('setStartDate', now);
                                //$('#leave_form').unmask();
                                
                                if(leave_type==3){
                                    $('#maternity_mindays').val(now);
                                }

                            }
                    });
                    
                }
        });



            $( ".date_to" ).datepicker({
                dateFormat: 'mm/dd/yyyy',
                /*beforeShowDay: function(date) {
                    var day = date.getDay();
                    return [(day != 0 && day != 6), ''];
                },*/
                daysOfWeekDisabled: [0, 6], // 0 for Sunday, 6 for Saturday
                
                }).on('changeDate', function (e) {
                    $('.date_to').datepicker('hide');
            });


            $( ".date_from" ).datepicker({
                    dateFormat: 'mm/dd/yyyy',
                    /*beforeShowDay: function(date) {
                        var day = date.getDay();
                        return [(day != 0 && day != 6), ''];
                    },*/
                    daysOfWeekDisabled: [0, 6], // 0 for Sunday, 6 for Saturday
                    //minDate: minDate,
                    
                }).on('changeDate', function (e) {
                
                        var date2 = e.date;
                        
                        var leave_type = $('#leave_type').val();
                        
                        if(leave_type==3){ // maternity leave
                            var fromDate = $(this).datepicker('getDate');
                            if($('#is_miscarriage').is(':checked')){
                                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +59);  // 60 days -1
                                
                            } else if($('#is_soloparent').is(':checked')){
                                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +119); // 105 + 15 days -1
                                
                            } else if($('#is_extended').is(':checked')){
                                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +29);  // 30 days -1
                                
                            } else if($('#is_allocate').is(':checked')){
                                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +97);  // 105 days - 7days -1
                                
                                
                            } else {
                                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +104);  // 105 days  -1

                            }
                            
                            var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                            $(".date_to" ).val(newdate);
                            $('.date_to').prop('readonly',true);
                            $('.date_to').prop('disabled',false);
                            //$(".date_to").datepicker( "option", "minDate", toDate);
                            $('.date_to').datepicker('setStartDate', toDate);
                            
                            if($('#is_allocate').is(':checked')){
                                var toDate2 = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +103);  // 105 days - 1 day  -1
                                //$(".date_to").datepicker( "option", "maxDate", toDate2);
                                $('.date_to').datepicker('setEndDate', toDate2);
                            } else {
                                //$(".date_to").datepicker( "option", "maxDate", toDate);
                                $('.date_to').datepicker('setEndDate', toDate);
                            }
                            $('.from_hd').val('0');
                            
                        }/* else if(leave_type==2){ // sick leave
                            
                            if($(".date_to" ).val()=='' && $('.from_hd').val()!=1){
                                $('.date_to').prop('disabled',false);
                                $('.to_hd').prop('disabled',false);
                            }
                            $(".date_to").datepicker( "option", "minDate", date2);
                            
                            
                        }*/ 
                        else {
                            if($(".date_to" ).val()=='' && $('.from_hd').val()!=1){
                                //$("#date_to" ).val($("#date_from").val());
                                //alert(date2)
                                $('.date_to').prop('disabled',false);
                                //$('.to_hd').prop('disabled',false);
                            }
                            //var fromDate = $(this).datepicker('getDate');
                            var minto = new Date(date2);
                            //$(".date_to").datepicker( "option", "minDate", date2);
                            
                            $('.date_to').datepicker('setStartDate', minto);
                            
                            if($('#leave_type').val()==6){
                                var slp_balance = parseInt($('#slp_balance').html());
                                var minto2 = new Date(date2.getFullYear(), date2.getMonth(), date2.getDate() +2);
                                $('.date_to').datepicker('setEndDate', minto2);
                                
                            } else if($('#leave_type').val()==4){ // paternity
                                var slp_balance = parseInt($('#slp_balance').html());
                                var minto2 = new Date(date2.getFullYear(), date2.getMonth(), date2.getDate() +60);
                                $('.date_to').datepicker('setEndDate', minto2);
                            }
    //                        var minDate = $(this).datepicker('getDate');
    //                        $( "#date_to" ).datepicker({
    //                            minDate: $("#date_from").val()
    //                        });
                        }
                        $('.date_from').datepicker('hide');
            });
                






$('#submit_btn').on('click',function(){
    
    if($('#is_monetized').is(':checked')){
        //var total_credits = parseFloat($('#asof_total_credits').val());
        //var tomonet = parseFloat($('#days_monetize_total').val());
        
        var asofvl = parseFloat($('#asofvl').html());
        var asofsl = parseFloat($('#asofsl').html());
        var monet_vl = parseFloat($('#days_monetize_vl').val());
        var monet_sl = parseFloat($('#days_monetize_sl').val());
        var vl_balance = parseFloat($('#vl_balance').html());
        var sl_balance = parseFloat($('#sl_balance').html());
        
        
        $('#monet_purpose_div').removeClass('has-error has-feedback');
        $('#purpose_error_lbl').hide();
        $('#purpose_warning_sign').hide();
        
        $('#monet_days_div').removeClass('has-error has-feedback');
        $('#monetdays_error_lbl').hide();
        $('#monetdays_warning_sign').hide();
        
        //if(monet_vl>asofvl || monet_sl>asofsl){
        if(monet_vl>vl_balance || monet_sl>sl_balance){
            
            $('#monet_days_div').addClass('has-error has-feedback');
            $('#monetdays_warning_sign').show();
            $('#monetdays_error_lbl').show();
            $('#monetdays_error_lbl').html('VL or SL days must not be greater than VL or SL credits balance.');
            
        } else if($('#monet_details').val()==''){
            $('#monet_purpose_div').addClass('has-error has-feedback');
            $('#purpose_warning_sign').show();
            $('#purpose_error_lbl').show();
            $('#purpose_error_lbl').html('Purpose of Monetization is required.');
            
        } else {
            //$('#leave_form').mask('Submitting... Please wait.');
            
            $('form[name=leave_form]').submit();
        } // testing
        //$('form[name=leave_form]').submit();
        
    } else if($('#is_terminal').is(':checked')){
        
        $('#terminal_details_div').removeClass('has-error has-feedback');
        $('#separation_warning_sign').hide();
        $('#separation_error_lbl').hide();
        
        if($('#separation_date').val()==''){
            $('#terminal_details_div').addClass('has-error has-feedback');
            $('#separation_warning_sign').show();
            $('#separation_error_lbl').show();
            
        } else {
            //$('#leave_form').mask('Submitting... Please wait.');
            $('form[name=leave_form]').submit();
        }
    } else {
        //$('#leave_form').mask('Submitting... Please wait.');
        $('form[name=leave_form]').submit();
    }

});


        


        
$('#is_emergency').on('click',function(){
    
        if($(this).is(':checked') && $('#leave_type').val()==6){
                var now = new Date();
                //$('#leave_form').unmask();
                //$(".date_from").datepicker( "option", "minDate", now);
                $('.date_from').val('');
                $('.date_from').datepicker('setStartDate', now);
        } else {
            
                var date = new Date();
                var now = new Date(date.getFullYear(), date.getMonth(), date.getDate() +7); // min days allowed
                //$('#leave_form').unmask();
                //$(".date_from").datepicker( "option", "minDate", now);
                $('.date_from').val('');
                $('.date_from').datepicker('setStartDate', now);
        }
});


        


        
$('#is_monetized').on('click',function(){
        if($(this).is(':checked')){
            $('#is_terminal').prop("checked", false).trigger("change");
            $('#leave_type').val('');
            $('#leave_type_div').slideUp();
            $('#leave_details_div').slideUp();
            $('#terminal_details_div').slideUp();
            $('.monet_days_div').slideDown();
            $('#submit_btn').html('Submit');
        } else {
            //$('#leave_details_div').slideDown();
            //$("#leave_details_div").css("display", "block");
            //$("#leave_details_div").css("overflow", "visible");
            $('.monet_days_div').slideUp();
            $('#leave_type_div').slideDown();
            $('#submit_btn').html('Add Leave');
        }
});
        
$('#is_terminal').on('click',function(){
        if($(this).is(':checked')){
            $('#is_monetized').prop("checked", false).trigger("change");
            $('#terminal_details_div').slideDown();
            
            $('#leave_type').val('');
            $('#leave_details_div').slideUp();
            $('#leave_type_div').slideUp();
            $('.monet_days_div').slideUp();
            $('#submit_btn').html('Submit');
            $('.monet_days_div').slideUp();
            $('#leave_type_div').slideUp();
        } else {
            $('#terminal_details_div').slideUp();
            
            $('.monet_days_div').slideUp();
            $('#leave_type_div').slideDown();
            //$('#leave_details_div').slideDown();
            //$("#leave_details_div").css("display", "block");
            //$("#leave_details_div").css("overflow", "visible");
            $('#submit_btn').html('Add Leave');
        }
});
        
$('.days_monetize').on('keyup',function(event){
    //var monet_vl = parseFloat($('#days_monetize_vl').val());
    //var monet_sl = parseFloat($('#days_monetize_sl').val());
        if($('#days_monetize_vl').val()!=''){
            var monet_vl = parseFloat($('#days_monetize_vl').val());
        } else {
            var monet_vl = 0;
        }
        if($('#days_monetize_sl').val()!=''){
            var monet_sl = parseFloat($('#days_monetize_sl').val());
        } else {
            var monet_sl = 0;
        }
    var total_monet = parseFloat(monet_vl + monet_sl).toFixed(3);
    
    if($('#factor').val()!=''){
        var factor = parseFloat($('#factor').val());
        var monthly_salary = parseFloat($('#monthly_salary_val').val());
        var monetize_amounta = parseFloat(factor * monthly_salary * total_monet);
        var monetize_amount = Math.round(monetize_amounta * 100) / 100;;
        $('#monetize_amount').val(monetize_amount);
    } 
        
    $('#days_monetize_total').val(total_monet);
});
        
$('.cancel_date').on('click',function(event){
    //    $('#next_div').slideDown();
        $('#inclusive_dates_div').slideUp();
        $('#leave_detail_id').val('');
        $('#leave_type_id').val('');
        $("#submit_btns_div").slideDown();
        $('.date_from').val('');
        $('.date_to').val('');
        $('.date_to').prop('disabled',true);
        //$('.to_hd').prop('disabled',true);
});
        
$('.add_date_btn').on('click',function(event){
//        $('#next_div').slideUp();
        var str = $(this).prop('id');
        
        //$('#inclusive_dates_div').mask('Loading form... Please wait.');
        
        $("#inclusive_dates_div").slideDown(80,function(){
            $('html, body').animate({
                scrollTop: $("#inclusive_dates_div").offset().top
            }, 1000);
            $("#details_form_div").slideUp();
            $("#submit_btns_div").slideUp();
            
            var arr = str.split("_");
            var detail_id = arr[0];
            var type_id = arr[1];
            $('#leave_detail_id').val(detail_id);
            $('#leave_type_id').val(type_id);
            
                                $('#date_from2').val('');
                                //$('#from_hd2').val('0');
                                $('#date_to2').val('');
                                //$('#to_hd2').val('0');
                                $('#date_id').val('');
                                //$('#inclusive_dates_div').unmask();
            $("#submit_date_btn").html("Add date");
            
            if(type_id==2){ // sick leave
                var datenow = new Date();
                $('.date_from').datepicker('setEndDate', datenow);
                $('.date_to').datepicker('setEndDate', datenow);
            } else {

            }
                        
            load_mindate(type_id);
                                
        });
});


var edit_date_url;
$('.edit_date_btn').on('click',function(event){
//        $('#next_div').slideUp();
        var date_id = $(this).prop('id');
        //$('#inclusive_dates_div').mask('Loading dates... Please wait.');
        
        $("#inclusive_dates_div").slideDown(80,function(){
            $('html, body').animate({
                scrollTop: $("#inclusive_dates_div").offset().top
            }, 1000);
            $("#details_form_div").slideUp();
            $("#submit_btns_div").slideUp();
            $("#submit_date_btn").html("Save date");
            
            
                var post_data={};
                post_data['date_id']=date_id;
                $.ajax({
                        url: edit_date_url,
                        type: 'POST',
                        data: post_data,
                        success:function(result){

                            var json = $.parseJSON(result);
                            $(json).each(function(i,val){
                                
                                $('#leave_detail_id').val(val.leave_detail_id);
                                $('#leave_type_id').val(val.leave_type_id);
                                $('#date_id').val(val.id_leave_date);
                                
                                $('#date_from2').val(val.date_from);
                                $('#from_hd2').val(val.date_from_ishalf);
                                $('#date_to2').val(val.date_to);
                                //$('#to_hd2').val(val.date_to_ishalf);
                                
                                if(val.date_from_ishalf==1){
                                    $('#date_to2').prop('disabled',true);
                                    //$('#to_hd2').prop('disabled',true);
                                } else {
                                    $('#date_to2').prop('disabled',false);
                                    //$('#to_hd2').prop('disabled',false);
                                }
                                
                                /*if(val.date_to==val.date_from){
                                    $('#to_hd2').val('0');
                                    $('#to_hd2').prop('disabled',true);
                                } else {
                                    $('#to_hd2').prop('disabled',false);
                                }*/
                                
                                //$('#inclusive_dates_div').unmask();
                                load_mindate(val.leave_type_id)
                                
                                
                            });
                        }
                });
                
            
            
        });
});

function load_mindate(leave_type)
{
    
        //$('#inclusive_dates_div').mask('Loading dates... Please wait.');
        
            var post_data2={};
            post_data2['leave_type']=leave_type;
            $.ajax({
                    url: get_mindate_url,
                    type: 'POST',
                    data: post_data2,
                    success:function(result2){
                        var now = new Date(result2 * 1000);
                        //$('#inclusive_dates_div').unmask();

                        //$(".date_from").datepicker( "option", "minDate", now);
                        $('.date_from').datepicker('setStartDate', now);

                        if($(".date_from").val()!=''){
                            var date2 = new Date($(".date_from").val());
                            //$(".date_to").datepicker( "option", "minDate", date2);
                            $('.date_to').datepicker('setStartDate', date2);
                        }

                    }
            });
}

        
$('.add_leave_btn').on('click',function(){
    
        $("#details_form_div").slideDown(80,function(){
            $('html, body').animate({
                scrollTop: $("#details_form_div").offset().top
            }, 1000);
            $("#inclusive_dates_div").slideUp();
            $("#submit_btns_div").slideUp();
        });
});
        
$('#cancel_leave').on('click',function(event){
        $("#details_form_div").slideUp();
        $("#submit_btns_div").slideDown();
        $('.date_from').val('');
        $('.date_to').val('');
        //$('.to_hd').prop('disabled',true);
});

/*
$('.from_hd').on('change',function(){
    if($(this).val()==1){
        $('.date_to').prop('disabled',true);
        $('.date_to').val('');
        $('.to_hd').prop('disabled',true);
    } else {
        $('.date_to').prop('disabled',false);
        $('.to_hd').prop('disabled',false);
    }
});

$('#date_to2').on('change',function(){
    if($(this).val()==$('#date_from2').val()){
        $('#to_hd2').val('0');
        $('#to_hd2').prop('disabled',true);
    } else {
        $('#to_hd2').prop('disabled',false);
    }
});
*/




                $('#submit_date_btn').on('click',function(event){
                        event.preventDefault();
                        if($('#date_from2').val()==''){
                            alert('Please input valid date.');
                        } else if($('#date_from2').val()>$('#date_to').val() && $('#date_to').val()!=''){
                            alert('Please input valid date.');
                        } else {
                            $('form[name=leave_date_form]').submit();
                        }
                });
                
                

$('.amount').on('focus',function(){
    if ($(this).val() == '0.000' || $(this).val() == 0) {
            $(this).val('');
    }
});

$('.amount').on('blur',function(){
    if ($(this).val() == '' || $(this).val() == 0) {
            $(this).val('0.000');
    }
});




$('#is_soloparent').on('click',function(){
        $('.date_to').datepicker('setDaysOfWeekDisabled', []);
        
        if($(this).is(':checked')){
            
            var maternity_mindays = $('#maternity_mindays').val();
            var mindays = new Date(maternity_mindays);
            //$(".date_from").datepicker( "option", "minDate", mindays);
            $('.date_from').datepicker('setStartDate', mindays);
            
            $('#is_miscarriage').prop("checked", false).trigger("change");
            //$('#is_soloparent').prop("checked", false).trigger("change");
            $('#is_extended').prop("checked", false).trigger("change");
            $('#is_allocate').prop("checked", false).trigger("change");
            
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +119);

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                //$(".date_to").datepicker( "option", "maxDate", toDate);
                $('.date_to').datepicker('setEndDate', toDate);
            }
            
            $('#maternity_details').val('With additional 15 days, for solo parents');

        } else {
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +104);

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                //$(".date_to").datepicker( "option", "maxDate", toDate);
                $('.date_to').datepicker('setEndDate', toDate);
                
            }
            $('#maternity_details').val('');
        }
});


$('#is_miscarriage').on('click',function(){
        $('.date_to').datepicker('setDaysOfWeekDisabled', []);
        if($(this).is(':checked')){
            
            var min_today = new Date();
            //$(".date_from").datepicker( "option", "minDate", min_today);
            $('.date_from').datepicker('setStartDate', min_today);
            
            //$('#is_miscarriage').prop("checked", false).trigger("change");
            $('#is_soloparent').prop("checked", false).trigger("change");
            $('#is_extended').prop("checked", false).trigger("change");
            $('#is_allocate').prop("checked", false).trigger("change");
            
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +59);

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                //$(".date_to").datepicker( "option", "maxDate", toDate);
                $('.date_to').datepicker('setEndDate', toDate);
            }
            $('#maternity_details').val('Miscarriage / emergency termination of pregnancy / delivered stillbirth (60 days)');

        } else {
            
            var maternity_mindays = $('#maternity_mindays').val();
            var mindays = new Date(maternity_mindays);
            //$(".date_from").datepicker( "option", "minDate", mindays);
            $('.date_from').datepicker('setStartDate', mindays);
            
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +104);

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                //$(".date_to").datepicker( "option", "maxDate", toDate);
                $('.date_to').datepicker('setEndDate', toDate);
            }
            $('#maternity_details').val('');
        }
});

$('#is_extended').on('click',function(){
        $('.date_to').datepicker('setDaysOfWeekDisabled', []);
        if($(this).is(':checked')){
            
            var maternity_mindays = $('#maternity_mindays').val();
            var mindays = new Date(maternity_mindays);
            //$(".date_from").datepicker( "option", "minDate", mindays);
            $('.date_from').datepicker('setStartDate', mindays);
            
            $('#is_miscarriage').prop("checked", false).trigger("change");
            $('#is_soloparent').prop("checked", false).trigger("change");
            //$('#is_extended').prop("checked", false).trigger("change");
            $('#is_allocate').prop("checked", false).trigger("change");
            
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +29);

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                //$(".date_to").datepicker( "option", "maxDate", toDate);
                $('.date_to').datepicker('setEndDate', toDate);
            }   
            $('#maternity_details').val('Extended maternity leave for 30 days');             

        } else {
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +104);

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                //$(".date_to").datepicker( "option", "maxDate", toDate);
                $('.date_to').datepicker('setEndDate', toDate);
            }
            $('#maternity_details').val('');
        }
});


$('#is_allocate').on('click',function(){
        $('.date_to').datepicker('setDaysOfWeekDisabled', []);
        if($(this).is(':checked')){
            
            var maternity_mindays = $('#maternity_mindays').val();
            var mindays = new Date(maternity_mindays);
            //$(".date_from").datepicker( "option", "minDate", mindays);
            $('.date_from').datepicker('setStartDate', mindays);
            
            $('#is_miscarriage').prop("checked", false).trigger("change");
            $('#is_soloparent').prop("checked", false).trigger("change");
            $('#is_extended').prop("checked", false).trigger("change");
            //$('#is_allocate').prop("checked", false).trigger("change");
            
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +97); // minus 7 days

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                
                
                var toDate2 = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +103);   // 105 days - 1 day  -1
                //$(".date_to").datepicker( "option", "maxDate", toDate2);
                $('.date_to').datepicker('setEndDate', toDate2);
            }
            $('#maternity_details').val('With allocation up to seven (7) days to the child\'s father or the alternate caregiver');

        } else {
            if($(".date_from" ).val()!=''){
                var fromDate = new Date($(".date_from" ).val());
                var toDate = new Date(fromDate.getFullYear(), fromDate.getMonth(), fromDate.getDate() +104);

                var newdate = toDate.getFullYear() + "-" + (toDate.getMonth() + 1).toString().padStart(2, '0') + "-" + toDate.getDate().toString().padStart(2, '0');

                $(".date_to" ).val(newdate);
                $('.date_to').prop('readonly',true);
                $('.date_to').prop('disabled',false);
                //$(".date_to").datepicker( "option", "minDate", toDate);
                $('.date_to').datepicker('setStartDate', toDate);
                //$(".date_to").datepicker( "option", "maxDate", toDate);
                $('.date_to').datepicker('setEndDate', toDate);
            }
            $('#maternity_details').val('');
        }
});