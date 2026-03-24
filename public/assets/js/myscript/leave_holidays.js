/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var load_url;
var edit_url;
var save_url;
var delete_url;



$('#year_filter').on('change',function(){
        $('#holiday_year').html($(this).val());
        load_listevent();
});

function load_listevent(){
        $('#table_div').mask('Loading... Please wait.');
        var post_data={};
        post_data['year']=$('#year_filter').val();
        post_data['month']=$('#month_filter').val();
        post_data['search']=$('#search_txtbx').val();
        $.ajax({
                url: load_url,
                type: 'POST',
                data: post_data,
                success:function(result){
                        $("#table_div").html(result);
                        $('#table_div').unmask();
                }
        });
}


                var holidays_leave;
            
                function load_leave(holiday_id){
                        $('#holidays_leave').mask('Loading leave... Please wait.');
                        
                        $('html, body').animate({
                            scrollTop: $("#holidays_leave").offset().top
                        }, 1000);
                        
                        var post_data={};
                        post_data['holiday_id'] = holiday_id;
                        $.ajax({
                                url: holidays_leave,
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                        $("#holidays_leave").html(result);
                                        $('#holidays_leave').unmask();
                                }
                        });
                }
                
                
                

$("#save").on('click',function(){
        remove_errors();
        
        var error = 0;

        if($('#holiday_name').val()==''){
                $('#holiday_name_form_div').attr('class','col-lg-9 col-md-12 col-sm-12 has-error has-feedback');
                $('#holiday_name_error_label').show();
                error = 1;
        } if($('#holiday_date').val()==''){
                $('#holiday_date_form_div').attr('class','col-lg-9 col-md-12 col-sm-12 has-error has-feedback');
                $('#holiday_date_error_label').show();
                error = 1;
        } 

        if(error==0){
                $('#table_div').mask('Loading... Please wait.');
                var post_data={};
                post_data['holiday_id']=$('#holiday_id').val();
                post_data['holiday_name']=$('#holiday_name').val();
                post_data['holiday_date']=$('#holiday_date').val();
                post_data['holiday_remarks']=$('#holiday_remarks').val();
                post_data['holiday_category']=$('#holiday_category').val();
                post_data['holiday_span']=$('#holiday_span').val();
                $.ajax({
                        url: save_url,
                        type: 'POST',
                        data: post_data,
                        success:function(result){

                                if(result==1){
                                    $('#success').html('Successfully saved.');
                                    $('#success').show();
                                } else {
                                    $('#error').html('Error! Something went wrong while saving.');
                                    $('#error').show();
                                }
                                clear();
                                load_listevent();
                        }
                });
        }
});

$("#cancel").on('click',function(){
        clear();
        remove_errors();
});




