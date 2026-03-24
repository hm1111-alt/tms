
var search_employee;
                
                $('#employee_autocomplete').on("focus", function (event) {
                        $(this).autocomplete({
                                source: function( request, response ) {
                                        vals=request.term;
                                        var post_data={};
                                        post_data['search']=request.term;
                                        $.ajax({
                                                url: search_employee,
                                                type: 'POST',
                                                data: post_data,
                                                success:function(result){
                                                        var results=$.parseJSON(result);
                                                        response( $.map( results, function( training ) {
                                                                return training
                                                        }));
                                                }
                                        });
                                },
                                select: function(event, ui) {
                                        $('#employee_div').mask('Loading details... Please wait.');
                                        var selectedObj = ui.item;
                                        $('#employee_id').val(selectedObj.value);
                                        $('#emp_idno').val(selectedObj.emp_idno);
                                        $('#employee_autocomplete').val(selectedObj.label);
                                        $('#employee_autocomplete').attr('readonly',true);
                                        //$('#employee_clear').show();
                                        //remove_errors()
                                        load_employee(selectedObj.emp_idno);
                                        return false;
                                }
                        });
                });
                
        $('#employee_dropdown').on("change", function () {
                if($(this).val()!=''){
                    //$('#employee_div').mask('Loading details... Please wait.');
                    load_employee($(this).val());
                }
        });
        
        var employee_details_url;

        function load_employee(emp_idno)
        {
                var post_data={};
                post_data['emp_idno'] = emp_idno;
                $.ajax({
                        url: employee_details_url,
                        type: 'POST',
                        data: post_data,
                        success:function(result){
                            var json = $.parseJSON(result);
                            $(json).each(function(i,val){
                                $('#employee_id').val(val.id_employee);
                                $('#emp_idno').val(val.emp_idno);
                                $('#emp_lname').val(val.emp_lname);
                                $('#emp_fname').val(val.emp_fname);
                                $('#emp_mname').val(val.emp_mname);
                                $('#emp_mi').val(val.emp_mi);
                                $('#emp_extname').val(val.emp_extname);
                                
                                $('#emp_program').val(val.emp_program);
                                $('#emp_office').val(val.emp_office);
                                $('#emp_division').val(val.emp_division);
                                $('#emp_unit').val(val.emp_unit);
                                $('#emp_subunit').val(val.emp_subunit);
                                $('#emp_class_name').val(val.emp_class_name);
                                
                                $('#emp_sex').val(val.emp_gender);
                                $('#emp_solo').val(val.other_solo);
                                
                                $('#monthly_salary').val(val.salary);
                                $('#position_name').val(val.position_name);
//                                $('#monthly_salary').val('90078.00'); // for demo testing
//                                $('#monthly_salary').val('12345.00'); // for demo testing
//                                $('#position_name').val('Development Management Officer V');// for demo testing
                                $('#position_id').val(val.id_position);
                                
                                $('#leave_credits_div').slideDown();
                                
                                $('.credit_id').val(val.credit_id);
                                $('.asofvl').val(val.vl);
                                $('.asofsl').val(val.sl);
                                $('.asofslp').val(val.slp);
                                $('.asofdate').val(val.credits_asof);
                                
                                $('#asofvl').html(val.vl);
                                $('#asofsl').html(val.sl);
                                $('#asofslp').html(val.slp);
                                $('#asofservice').html(val.service);
                                $('#asofdate').html(val.credits_asof);
                                
                                $('#vl_earmarked').html(val.vl_earmarked);
                                $('#sl_earmarked').html(val.sl_earmarked);
                                $('#slp_earmarked').html(val.slp_earmarked);
                                //$('#service_earmarked').html(val.service_earmarked);
                                $('#balance_vl').html(val.balance_vl);
                                $('#balance_sl').html(val.balance_sl);
                                $('#balance_slp').html(val.balance_slp);
                                $('#balance_service').html(val.service);
                                
                                $('.vl_earmarked').val(val.vl_earmarked);
                                $('.sl_earmarked').val(val.sl_earmarked);
                                $('.slp_earmarked').val(val.slp_earmarked);
                                //$('.service_earmarked').val(val.service_earmarked);
                                $('.balance_vl').val(val.balance_vl);
                                $('.balance_sl').val(val.balance_sl);
                                $('.balance_slp').val(val.balance_slp);
                                $('.balance_service').val(val.service);
                                //$('#asofdate').html(val.asofdate);
                                //$('#asofdate').html(val.last_updated);
                                
//                                $('#employee_training_id').val(val.id_employee_training);
//                                $('#employee_autocomplete').val(val.emp_fullname);
//                                $('#emp_refno').val(val.emp_refno);
//                                $('#employee_autocomplete').val(val.emp_fullname);
//                                $('#employee_autocomplete').prop('readonly',true);
//                                $('#employee_clear').show();
//                                $('#training_return_rendered').val(val.training_return_rendered);
                                $('#employee_div').unmask();
                            });
                        }
                });
        }
        