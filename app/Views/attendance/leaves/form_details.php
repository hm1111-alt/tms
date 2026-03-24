
        <div class="form-group" style="">

            
                <?php $is_monetized = set_value('is_monetized') ? set_value('is_monetized') : '';
                $is_terminal = set_value('is_terminal') ? set_value('is_terminal') : ''; ?>

                <div class="row" id="leave_type_div" style="<?php echo $is_monetized ? 'display:none;' : ''; ?>">
                        <label class="col col-lg-3" for="" style="margin-top: 10px;">*<b>Type of Leave</b></label>
                        <div class="col col-lg-9">
                            <?php if(@$validation && $validation->hasError('leave_type')){ ?>
                                <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('leave_type'); ?></label></span>
                            <?php } ?>
                            <select name="leave_type" id="leave_type" class="form-control m-bot15"  style="width: 90%; font-size: 20px; height: 50px; padding: 10px; margin-top: 10px; cursor: pointer; background-color: #00c0ef; border-color: #00acd6; color: #FFFFFF; box-shadow: inset 0 1px 1px rgba(0,0,0,.075); ">
                                <option value="">Select type of leave...</option>
                                <?php $set_leave_type = set_value('leave_type') ? set_value('leave_type') : ''; ?>
                                <?php foreach($leave_types as $typ){
                                    if($typ->id_leave_type==9 && (@$manda_balance==0 || @$manda_balance<0)){ // vl
                                    } else if($typ->id_leave_type==6 && (@$slp_balance==0 || @$slp_balance<0)){ // slp
                                    } else if(($typ->id_leave_type==3 || $typ->id_leave_type==13) && @$leave[0]->emp_sex!='Female'){ // 3 = Maternity  , 13 = Benefits for Women
                                    } else if($typ->id_leave_type==4 && @$leave[0]->emp_sex!='Male'){ // paternity
                                    } else if($typ->id_leave_type==10 && @$leave[0]->emp_solo!=1 && (@$spl_balance==0 || @$spl_balance<0)){ // Solo Parent Leave
                                    } else { ?>
                                        <option value="<?php echo $typ->id_leave_type; ?>" <?php echo $set_leave_type==$typ->id_leave_type ? 'selected' : ''; ?>>
                                            <?php echo strtoupper($typ->leave_type_name);
                                            echo @$typ->leave_type_details!='' ? ' - '.$typ->leave_type_details : ''; ?> 
                                        </option>
                                <?php }
                                } ?>
                                <!--<option value="others">Others</option>-->
                                
                            </select>
                            <input type="text" name="leave_type_others" id="leave_type_others" class="form-control m-bot15" maxlength="50" value="<?php echo set_value('leave_type_others'); ?>" placeholder="Others" style="display: inline-block; width: 90%; display: none;" />
                        </div>
                </div>


            <!--OTHER-PURPOSE------------------------------------------------>

                <?php
                //if((@$details && @$leave[0]->is_monetization==1) || !@$details){
                if(@$allow_monet==1 && !@$details){ ?>

                        <div class="row" id="monet_div" style="margin-top: 20px; <?php //echo ($set_leave_type==1 || $set_leave_type==2) ? '' : 'display:none;'; ?>">
                                <label class="col col-lg-3" for="" style="margin-top: 5px;"><b>Other Purpose:</b></label>
                                <div class="col col-lg-9  " style="">
                                    <?php if(@$allow_monet==1){ ?>
                                        <div class="checkbox " style="padding-bottom: 10px; padding-left:10px; display: block;">
                                                <label id="monet_lbl">

                                                    <input type="checkbox" name="is_monetized" id="is_monetized" value="1" class="checkbox scaled-checkbox" <?php echo $is_monetized ? 'checked' : ''; ?>>
                                                    <span class="addtoservice-description">Monetization of Leave Credits</span>
                                                </label>
                                        </div>
                                        <?php if(session()->get('access_level')==1 || session()->get('isregular')==1){ ?>
                                            <div class="checkbox " style="padding-bottom: 10px; padding-left:10px; display: block;">
                                                    <label id="terminal_lbl">

                                                        <input type="checkbox" name="is_terminal" id="is_terminal" value="1" class="checkbox scaled-checkbox" <?php echo $is_terminal ? 'checked' : ''; ?>>
                                                        <span class="addtoservice-description">Terminal Leave</span>
                                                    </label>
                                            </div>
                                    <?php }
                                    } ?>
                                    <br>
                                    <div id="monet_days_div" class="row monet_days_div" style="<?php echo $is_monetized ? '' : 'display: none;'; ?>">
                                            <!--warning-->
                                                <label class="control-label" id="monetdays_error_lbl" style="margin-left:30px;<?php echo @$validation && $validation->hasError('days_monetize_total') ? '' : 'display: none;'; ?>"><?php echo $validation->getError('days_monetize_total'); ?></label> 
                                                <span class="glyphicon glyphicon-warning-sign" id="monetdays_warning_sign" style="color: #b94a48; <?php echo @$validation && $validation->hasError('days_monetize_total') ? '' : 'display: none;'; ?>"></span>

                                                <label class="control-label" id="monetdays_error_lbl" style="margin-left:30px;<?php echo @$monet_error!='' ? '' : 'display: none;'; ?>"><?php echo @$monet_error; ?></label> 
                                                <span class="glyphicon glyphicon-warning-sign" id="monetdays_warning_sign" style="color: #b94a48; <?php echo @$monet_error!='' ? '' : 'display: none;'; ?>"></span>
                                            <!--end-of-warning-->

                                            <div class="col col-lg-12">
                                                <label style="display: inline-block;">No. of VL Days</label>
                                                <input name="days_monetize_vl" id="days_monetize_vl" class="form-control amount days_monetize" type="text" style="width: 180px; margin-left:10px; display: inline-block;" value="<?php echo set_value('days_monetize_vl') ? set_value('days_monetize_vl') : '0.000'; ?>" placeholder="VL Days to monetize" onkeypress="validateamount(event)" maxlength="8">
                                            </div>
                                            <div class="col col-lg-12">
                                                <label style="display: inline-block;">No. of SL Days</label>
                                                <input name="days_monetize_sl" id="days_monetize_sl" class="form-control amount days_monetize" type="text" style="width: 180px; margin-left:10px; display: inline-block;" value="<?php echo set_value('days_monetize_sl') ? set_value('days_monetize_sl') : '0.000'; ?>" placeholder="SL Days to monetize" onkeypress="validateamount(event)" maxlength="8">
                                            </div>
                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:5px;">
                                                <label style="display: inline-block; margin-left:30px;">Total Days</label>
                                                <input name="days_monetize_total" id="days_monetize_total" class="form-control" type="text" style="width: 200px; margin-left:20px; display: inline-block; background-color: #DDD !important;" value="<?php echo set_value('days_monetize_total'); ?>" readonly="" placeholder="0.00" onkeypress="validateamount(event)">
                                            </div>
                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:5px;">
                                                <label style="display: inline-block; margin-left:30px;">Constant Factor</label>
                                                <div class="form-control" style="width: 150px; margin-left:20px; display: inline-block; background-color: #DDD !important;"><?php echo $factor; ?></div>
                                            </div>
                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:10px;">
                                                <label style="display: inline-block; margin-left:30px;">Amount</label>
                                                <input name="monetize_amount" id="monetize_amount" class="form-control" type="text" style="width: 200px; margin-left:20px; display: inline-block; background-color: #FFF !important; border: none; font-size:larger; font-weight: bold; padding:20px; text-align: right;" value="<?php echo set_value('days_monetize_total'); ?>" readonly="" placeholder="0.00" onkeypress="validateamount(event)">
                                            </div>
                                    </div>
                                    <div id="monet_purpose_div" class="row monet_days_div" style="<?php echo $is_monetized ? '' : 'display: none;'; ?> margin-top: 20px;">
                                            <div class="col col-lg-12">
                                                <label style="display: inline-block;">Purpose of Monetization:</label>
                                            </div>
                                            <!--warning-->
                                                <label class="control-label" id="purpose_error_lbl" style="margin-left:30px;<?php echo @$validation && $validation->hasError('monet_details') ? 'display: inline-block;' : 'display: none;'; ?>"><?php echo 'This field is required.'; //@$validation && $validation->hasError('monet_details'); ?></label>
                                                <span class="glyphicon glyphicon-warning-sign "  id="purpose_warning_sign" style="color: #b94a48;<?php echo @$validation && $validation->hasError('monet_details') ? '' : 'display: none;'; ?>"></span>
                                            <!--end-of-warning-->

                                            <div class="col col-lg-12">
                                                <?php $set_monet_details = set_value('monet_details') ? set_value('monet_details') : ''; ?>
                                                <input type="text" name="monet_details" id="monet_details" class="form-control m-bot15" maxlength="60" style="display: inline-block; width: 90%;" value="<?php echo $set_monet_details; ?>">
                                            </div>
                                    </div>
                                    
                                    
                                    <div id="terminal_details_div" class="row separation_div" style="<?php echo $is_monetized ? '' : 'display: none;'; ?> margin-top: 20px;">
                                            <!--warning-->
                                                <label class="control-label" id="separation_error_lbl" style="margin-left:30px;<?php echo @$validation && $validation->hasError('separation_date') ? 'display: inline-block;' : 'display: none;'; ?>"><?php echo 'Separation date is required.'; //@$validation && $validation->hasError('monet_details'); ?></label>
                                                <span class="glyphicon glyphicon-warning-sign "  id="separation_warning_sign" style="color: #b94a48;<?php echo @$validation && $validation->hasError('separation_date') ? '' : 'display: none;'; ?>"></span>
                                            <!--end-of-warning-->

                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:10px;">
                                                <label class="col col-lg-5" style="display: inline-block; margin-left:30px;">Date of Separation</label>
                                                <input name="separation_date" id="separation_date" class="form-control datepicker col-sm-7" type="text" style="width: 200px; margin-left:20px; display: inline-block; " value="<?php echo set_value('separation_date'); ?>" placeholder="YYYY-mm-dd">
                                            </div>
                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:10px;">
                                                <label class="col col-lg-5" style="display: inline-block; margin-left:30px;">Highest Monthly Salary</label>
                                                <input name="highest_salary" id="highest_salary" class="form-control col-sm-7" type="text" style="width: 200px; margin-left:20px; display: inline-block; background-color: #FFF !important; font-size:larger; font-weight: bold; padding:20px; text-align: right; border: none;" value="<?php echo set_value('highest_salary') ? set_value('highest_salary') : number_format(@$highest_salary, 2, '.', ',') ; ?>" readonly="" placeholder="0.00" onkeypress="validateamount(event)">
                                            </div>
                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:10px;">
                                                <label class="col col-lg-5" style="display: inline-block; margin-left:30px;">Total Leave Credits Balance</label>
                                                <input name="total_credits_balance" id="total_credits_balance" class="form-control col-sm-7" type="text" style="width: 200px; margin-left:20px; display: inline-block; background-color: #FFF !important; font-size:larger; font-weight: bold; padding:20px; text-align: right; border: none;" value="<?php echo set_value('total_credits_balance') ? set_value('total_credits_balance') : @$total_credits_balance; ?>" readonly="" placeholder="0.00" onkeypress="validateamount(event)">
                                            </div>
                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:10px;">
                                                <label class="col col-lg-5" style="display: inline-block; margin-left:30px;">Constant factor</label>
                                                <input name="highest_salary" id="highest_salary" class="form-control col-sm-7" type="text" style="width: 200px; margin-left:20px; display: inline-block; background-color: #FFF !important; font-size:larger; font-weight: bold; padding:20px; text-align: right; border: none;" value="<?php echo set_value('highest_salary') ? set_value('highest_salary') : $factor ; ?>" readonly="" placeholder="0.00" onkeypress="validateamount(event)">
                                            </div>
                                            <div class="col col-lg-12" style="display:block; clear:both; margin-top:10px;">
                                                <label class="col col-lg-5" style="display: inline-block; margin-left:30px;">Amount</label>
                                                <input name="tlb_amount" id="tlb_amount" class="form-control col-sm-7" type="text" style="width: 200px; margin-left:20px; display: inline-block; background-color: #FFF !important; font-size:larger; font-weight: bold; padding:20px; text-align: right; border: none;" value="<?php echo set_value('tlb_amount') ? set_value('tlb_amount') : number_format(@$tlb_amount, 2, '.', ',') ; ?>" readonly="" placeholder="0.00" onkeypress="validateamount(event)">
                                            </div>
                                    </div>
                                </div>
                        </div>

                <?php } ?>
            <!------------------------------------------------>

            <hr>
        </div>


        <div class="form-group" id="leave_details_div" style="<?php echo $set_leave_type!='' && $set_leave_type!='others' && $set_leave_type!=15 && $is_monetized!=true ? '' : 'display:none;'; ?>">

                <h4 class="boldtext" style="padding-left:20px;">Details of Leave:</h4>


            <!--VACATION-LEAVE-SLP---------------------------------------------->
                <div class="form-group" id="vl_div" style="<?php echo (($set_leave_type==1 || $set_leave_type==6)) ? '' : 'display:none;'; ?> padding-left: 30px;">

                        <div class="row">
                                <label class="col col-lg-3" for="" style="margin-top: 5px;"><b>In case of Vacation / Special Privilege Leave:</b></label>
                                <div class="col col-lg-9 checkbox" style="padding-left: 0px !important;">
                                        
                                        <div class="checkbox mb-4" id="is_emergency_div" style="padding-bottom: 10px; <?php echo ($set_leave_type==6) ? 'display: block;' : 'display:none;'; ?>">
                                                <label id="emergency_lbl">
                                                    <?php $is_emergency = set_value('is_emergency') ? set_value('is_emergency') : ''; ?>
                                                    <input type="checkbox" name="is_emergency" id="is_emergency" value="1" class="checkbox scaled-checkbox" <?php echo $is_emergency ? 'checked' : ''; ?>>
                                                    <span class="addtoservice-description">Emergency case</span>
                                                </label>
                                        </div>
                                        <div class="row" style="padding-left: 0px !important;">
                                            <?php if(@$validation && $validation->hasError('vl_leave_location')){ ?>
                                                <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('vl_leave_location'); ?></label></span>
                                            <?php } ?>
                                            <div class="col col-lg-6">
                                                <label style="display: block; clear: both;">
                                                        <input name="vl_leave_location" value="Philippines" type="radio" <?php echo set_value('vl_leave_location')=='Philippines' ? 'checked' : ''; ?> class="custom-control-input">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description" style="cursor: pointer;">
                                                            Within the Philippines
                                                        </span>
                                                </label>
                                            </div>
                                            <div class="col col-lg-6">
                                                <label style="display: block; clear: both;">
                                                        <input name="vl_leave_location" value="Abroad" type="radio" <?php echo set_value('vl_leave_location')=='Abroad' ? 'checked' : ''; ?> class="custom-control-input">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description" style="cursor: pointer;">
                                                            Abroad (Specify)
                                                        </span>
                                                </label>
                                            </div>
                                            <div class="col col-lg-12 mt-2" style="display: inline-block;">
                                                <?php if(@$validation && $validation->hasError('vl_leave_details')){ ?><span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('vl_leave_details'); ?></label></span><?php } ?>
                                                <?php $set_vl_details = set_value('vl_leave_details') ? set_value('vl_leave_details') : ''; ?>
                                                <input type="text" name="vl_leave_details" id="vl_leave_details" class="form-control m-bot15" maxlength="80" value="<?php echo $set_vl_details; ?>" placeholder="Details" style="display: inline-block; width: 90%; margin-top: 10px;" />
                                            </div>
                                        </div>
                                </div>
                        </div>



                </div>
            <!------------------------------------------------>

            <!--SICK-LEAVE----------------------------------------------->
                <div class="form-group" id="sl_div" style="<?php echo ($set_leave_type==2) ? '' : 'display:none;'; ?> padding-left: 30px;">
                        <div class="row">
                                <label class="col col-lg-3" for="" style="margin-top: 5px;"><b>In case of Sick Leave:</b></label>
                                <div class="col col-lg-9 checkbox">
                                        <div class="row">
                                                <?php if(@$validation && $validation->hasError('sl_leave_location')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('sl_leave_location'); ?></label></span>
                                                <?php } ?>
                                                <div class="col col-lg-6">
                                                    <label style="display: block; clear: both;">
                                                            <input name="sl_leave_location" value="1" type="radio" <?php echo set_value('sl_leave_location')==1 ? 'checked' : ''; ?> class="custom-control-input">
                                                            <span class="custom-control-indicator"></span>
                                                            <span class="custom-control-description" style="cursor: pointer;">
                                                                In Hospital (Specify Illness)
                                                            </span>
                                                    </label>
                                                </div>
                                                <div class="col col-lg-6">
                                                    <label style="display: block; clear: both;">
                                                            <input name="sl_leave_location" value="12" type="radio" <?php echo set_value('sl_leave_location')==12 ? 'checked' : ''; ?> class="custom-control-input">
                                                            <span class="custom-control-indicator"></span>
                                                            <span class="custom-control-description" style="cursor: pointer;">
                                                                Out Patient (Specify Illness)
                                                            </span>
                                                    </label>
                                                </div>
                                        </div>
                                        <div class="col-lg-12 mt-2" style="display: inline-block;">
                                                <?php if(@$validation && $validation->hasError('sl_leave_details')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('sl_leave_details'); ?></label></span>
                                                <?php } ?>
                                                <?php $set_sl_details = set_value('sl_leave_details') ? set_value('sl_leave_details') : ''; ?>
                                                <input type="text" name="sl_leave_details" id="sl_leave_details" class="form-control m-bot15" maxlength="80" value="<?php echo $set_sl_details; ?>" placeholder="Details" style="display: inline-block; width: 90%; margin-top: 10px;" />
                                        </div>
                                </div>
                        </div>
                </div>
            <!------------------------------------------------>

            <!--SPECIAL-LEAVE-BENEFITS-FOR-WOMEN----------------------------------------------->
                <div class="form-group" id="women_div" style="<?php echo ($set_leave_type==13) ? '' : 'display:none;'; ?> padding-left: 30px;">
                        <div class="row">
                                <label class="col col-lg-3" for="" style="margin-top: 5px;"><b>In case of Special Leave Benefits for Women:</b></label>
                                <div class="col col-lg-9 checkbox">
                                        <div class="row">
                                                <div class="col col-lg-12">
                                                    <label>Specify Illness</label>
                                                    <?php if(@$validation && $validation->hasError('women_details')){ ?>
                                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('women_details'); ?></label></span>
                                                    <?php } ?>
                                                    <?php $set_remarks = set_value('women_details') ? set_value('women_details') : ''; ?>
                                                    <input type="text" name="women_details" id="women_details" class="form-control m-bot15" maxlength="150" value="<?php echo $set_remarks; ?>" placeholder="Details" style="display: inline-block; width: 90%; margin-top: 10px;" />
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
            <!------------------------------------------------>

            <!--MATERNITY--LEAVE----------------------------------------------->
                <div class="form-group" id="maternity_div" style="<?php echo ($set_leave_type==3) ? '' : 'display:none;'; ?> padding-left: 30px;">
                        <input type="hidden" name="maternity_mindays" id="maternity_mindays" value="<?php echo set_value('maternity_mindays'); ?>">
                        <div class="row">
                                <div class="col col-lg-3">
                                    <!--<label class="control-label" style="margin-top: 5px;">105-Day Expanded Maternity Leave Law</label>-->
                                </div>
                                <div class="col col-lg-9  " style="">
                                        <div class="checkbox " style="padding-bottom: 10px; padding-left:10px; display: block;">
                                                <label id="maternity_lbl">
                                                    <?php $is_soloparent = set_value('is_soloparent') ? set_value('is_soloparent') : ''; ?>
                                                    <input type="checkbox" name="is_soloparent" id="is_soloparent" value="1" <?php echo @$leave[0]->emp_solo!=1 ? 'disabled' : ''; ?> class="checkbox " <?php echo $is_soloparent ? 'checked' : ''; ?>>
                                                    <span <?php echo @$leave[0]->emp_solo!=1 ? 'style="color:#CCC;" title="For solo parents only"' : ''; ?>> With additional 15 days, for solo parents</span>
                                                </label>
                                        </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col col-lg-3">
                                    <!--<label class="control-label" style="margin-top: 5px;">105-Day Expanded Maternity Leave Law</label>-->
                                </div>
                                <div class="col col-lg-9  " style="">
                                        <div class="checkbox " style="padding-bottom: 10px; padding-left:10px; display: block;">
                                                <label id="maternity_lbl">
                                                    <?php $is_miscarriage = set_value('is_miscarriage') ? set_value('is_miscarriage') : ''; ?>
                                                    <input type="checkbox" name="is_miscarriage" id="is_miscarriage" value="1" class="checkbox " <?php echo $is_miscarriage ? 'checked' : ''; ?>>
                                                    <span>Miscarriage / emergency termination of pregnancy / delivered stillbirth (60 days)</span>
                                                </label>
                                        </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col col-lg-3">
                                    <!--<label class="control-label" style="margin-top: 5px;">105-Day Expanded Maternity Leave Law</label>-->
                                </div>
                                <div class="col col-lg-9  " style="">
                                        <div class="checkbox " style="padding-bottom: 10px; padding-left:10px; display: block;">
                                                <label id="maternity_lbl">
                                                    <?php $is_extended = set_value('is_extended') ? set_value('is_extended') : ''; ?>
                                                    <input type="checkbox" name="is_extended" id="is_extended" value="1" class="checkbox " <?php echo $is_extended ? 'checked' : ''; ?>>
                                                    <span> Extended for 30 days</span>
                                                </label>
                                        </div>
                                </div>
                        </div>
                        <div class="row">
                                <div class="col col-lg-3">
                                    <!--<label class="control-label" style="margin-top: 5px;">105-Day Expanded Maternity Leave Law</label>-->
                                </div>
                                <div class="col col-lg-9  " style="">
                                        <div class="checkbox " style="padding-bottom: 10px; padding-left:10px; display: block;">
                                                <label id="maternity_lbl">
                                                    <?php $is_allocate = set_value('is_allocate') ? set_value('is_allocate') : ''; ?>
                                                    <input type="checkbox" name="is_allocate" id="is_allocate" value="1" class="checkbox " <?php echo $is_allocate ? 'checked' : ''; ?>>
                                                    <span> Allocate up to seven (7) days to the child’s father or the alternate caregiver</span>
                                                </label>
                                        </div>
                                </div>
                        </div>
                        <div class="row">
                                <label class="col col-lg-3" for="" style="margin-top: 5px;"></label>
                                <div class="col col-lg-9 checkbox">
                                        <div class="col col-lg-12">
                                            <span>Remarks</span>
                                            <?php $set_maternity_details = set_value('maternity_details') ? set_value('maternity_details') : ''; ?>
                                            <input type="text" name="maternity_details" id="maternity_details" class="form-control m-bot15" maxlength="150" value="<?php echo $set_maternity_details; ?>" placeholder="Details" style="display: inline-block; width: 90%; margin-top: 10px;" />
                                        </div>
                                </div>
                        </div>
                    
                </div>
            <!------------------------------------------------>

            <!--STUDY-LEAVE----------------------------------------------->
                <div class="form-group" id="study_div" style="<?php echo ($set_leave_type==11) ? '' : 'display:none;'; ?> padding-left: 30px;">
                        <div class="row">
                                <label class="col col-lg-3" for="" style="margin-top: 5px;"><b>In case of Study Leave:</b></label>
                                <div class="col col-lg-9 checkbox">
                                    <?php if(@$validation && $validation->hasError('leave_purpose')){ ?>
                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('leave_purpose'); ?></label></span>
                                    <?php } ?>
                                    <div class="col col-lg-12">
                                        <label style="">
                                                <input name="leave_purpose" value="1" type="radio" <?php echo set_value('leave_purpose')==1 ? 'checked' : ''; ?> class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description" style="cursor: pointer;">
                                                    Completion of Master's Degree
                                                </span>
                                        </label>
                                    </div>
                                    <div class="col col-lg-12" style="padding-top:10px;">
                                        <label >
                                                <input name="leave_purpose" value="2" type="radio" <?php echo set_value('leave_purpose')==2 ? 'checked' : ''; ?> class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description" style="cursor: pointer;">
                                                    BAR/Board Examination Review
                                                </span>
                                        </label>
                                    </div>
                                </div>
                        </div>
                </div>
            <!------------------------------------------------>

            <!--------INCLUSIVE-DATES---------------------------------------->
                <div class="form-group">
                        <div class="row mt-4">

                                <div class="col-sm-3"></div>
                                <div class="col-sm-9" style="display: inline-block; padding-left: 20px;">
                                        <div class="row">
                                            <label class="control-label">
                                                <b>INCLUSIVE DATES:</b>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label" style="text-align: right;">From</label>
                                                <?php if(@$validation && $validation->hasError('date_from') || @$date_error!=''){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('date_from').' '.@$date_error; ?></label></span>
                                                <?php } ?>
                                                <input name="date_from" id="date_from" class="date_from datepicker form-control" type="text" style="margin-top:5px;" value="<?php echo set_value('date_from'); ?>" readonly>
                                                    <!--<select name="from_hd" class="leave_coverage form-control from_hd" id="from_hd" style="width:100px; padding:3px; display: inline-block;">
                                                        <option value="0" <?php // echo set_value('from_hd')==0 ? 'selected' : ''; ?>>Whole Day</option>
                                                        <option value="1" <?php // echo set_value('from_hd')==1 ? 'selected' : ''; ?>>AM only</option>
                                                        <option value="2" <?php // echo set_value('from_hd')==2 ? 'selected' : ''; ?>>PM only</option>
                                                    </select> -->
                                                <input type="hidden" name="from_hd" class="leave_coverage form-control from_hd" id="from_hd" value="0">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label" style="text-align: right;">To</label>
                                                <?php if(@$validation && $validation->hasError('date_to')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('date_from'); ?></label></span>
                                                <?php } ?>
                                                <input name="date_to" id="date_to" class="date_to datepicker form-control" type="text" style="margin-top:5px;" value="<?php echo set_value('date_to'); ?>" readonly <?php echo (set_value('date_from')=='' || (set_value('date_to')=='' && set_value('from_hd')==1)) ? 'disabled' : '';   ?>>
                                                    <?php /*
                                                    <select name="to_hd" class="leave_coverage form-control to_hd" id="to_hd" <?php echo (set_value('date_from')=='' || (set_value('date_to')=='' && set_value('from_hd')==1)) ? 'disabled' : ''; ?> style="width:100px; padding:3px; display: inline-block;">
                                                        <option value="0" <?php echo set_value('to_hd')==0 ? 'selected' : ''; ?>>Whole Day</option>
                                                        <option value="1" <?php echo set_value('to_hd')==1 ? 'selected' : ''; ?>>AM only</option>
                                                        <!--<option value="2" <?php // echo @$dt->date_from_ishalf==2 ? 'selected' : ''; ?>>PM only</option>-->
                                                    </select>*/ ?>
                                                <input type="hidden" name="to_hd" class="leave_coverage form-control to_hd" id="to_hd" value="0">
                                            </div>
                                            <em style="color: #f0ad4e;">* You can add more inclusive dates later.</em>
                                        </div>
                                </div>
                        </div>
                </div>
            <!-------end-of-INCLUSIVE-DATES---------------------------------------->
        </div>

        <?php  ?>


        <div class="col col-lg-12" id="" style="padding-top:30px; padding-bottom:30px;display: inline-block; text-align: right;">
            <?php /*<a href="<?php echo site_url('attendance/leave/cancel_draft_leave'); ?>" onclick="return confirm('Are you sure you want to cancel this leave?')">
                <button type="button" id="cancel" class="btn-default btn cancel_btn" style="margin-left:15px;float: right;">Cancel</button>
            </a>
             * 
             */ ?>
            <?php //if(@$details){ ?>
                <button type="submit" class="btn btn-primary save_button1" id="submit_btn" style="display: inline-block;" role="button">
                    <!--<i class="glyphicon glyphicon-save"></i>--> Add Leave
                </button>
            <?php //} ?>
            
            <?php if(@$details){ ?>
                <button type="button" id="cancel_leave" class="btn-default btn " style="margin-left:15px;" role="button">Cancel</button>
            <?php } ?>
        </div>
