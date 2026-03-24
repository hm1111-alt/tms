
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Application for Leave</title>
    
        <style type="text/css">

            @font-face {
                    font-family: "Arial Narrow";
                    src: url('<?php echo WRITEPATH.'fonts\ARIALN.TTF'; ?>') format("truetype");
                    font-weight: normal;
                    font-style: normal;
            }
            @font-face {
                    font-family: "Arial Narrow";
                    src: url('<?php echo WRITEPATH.'fonts\ARIALNB.TTF'; ?>') format("truetype");
                    font-weight: bold;
                    font-style: normal;
            }
            @font-face {
                    font-family: "Arial Narrow";
                    src: url('<?php echo WRITEPATH.'fonts\ARIALNBI.TTF'; ?>') format("truetype");
                    font-weight: bold;
                    font-style: italic;
            }
            @font-face {
                    font-family: "Arial Narrow";
                    src: url('<?php echo WRITEPATH.'fonts\ARIALNI.TTF'; ?>') format("truetype");
                    font-weight: normal;
                    font-style: italic;
            }
                body {
                        font-family: "Arial Narrow",Helvetica;
                        font-size: 13px;
                        margin: -40px 20px -80px 20px;
                }
                tr td.title{
                        font-size: 11px;
                }
                
                div.leave_details_div table tr td {
                    padding-top:2px;
                }
                
                table#leave_credits_table tr td {
                    padding-top:0px; 
                    padding-left:3px; 
                    padding-right:3px; 
                    padding-bottom:0px; 
                }
                td {
                    padding-top:10px;
                }
                
                td.top,th.top {
                        border-top: 0.2px solid #000;
                }
                
                td.bottom,th.bottom {
                        border-bottom: 0.2px solid #000;
                }
                
                td.right,th.right {
                        border-right: 0.2px solid #000;
                }
                
                td.left,th.left {
                        border-left: 0.2px solid #000;
                }
                
                h3.section_title,td.section_title{
                    text-align: center; 
                    font-size: 14px; 
                    padding-top:5px; 
                    padding-bottom:5px; 
                    margin-bottom: 0px;
                    border-top: 2px double #000;
                    border-bottom: 2px double #000;
                    border-left: 0.2px solid #000;
                        border-right: 0.2px solid #000;
                }
                
                .watermark {
                    position: fixed;
                    top: 20%;
                    left: 5%;
                    width: 100%;
                    font-size: 200px;
                    color: rgba(0, 0, 0, 0.15);
                    transform: rotate(-45deg);
                    z-index: -1;
                }

        </style>
</head>
<body>
<?php /*
        <script type="text/php">
            if ( isset($pdf) ) {
                $first_name = "<?php //echo $this->session->userdata('username'); ?>";
                $font = Font_Metrics::get_font("arial narrow", "normal");
                $today = date('m/d/Y h:i A');
                $text2 = html_entity_decode("PhilRice &copy; Human Resource Information System (HRIS). All rights reserved.", ENT_QUOTES, 'UTF-8');
                //$pdf->page_text(20, 810, "$text2 Date Printed: $today by $first_name Ref. no.: <?php echo $details[0]->leave_refno; ?>", $font, 7, array(0,0,0));
                $pdf->page_text(20, 810, "$text2 Ref. no.: <?php echo $details[0]->leave_refno; ?>", $font, 7, array(0,0,0));
            }
        </script> */ ?>
    
    <?php if(@$details[0]->is_draft==1){ ?>
        <div class="watermark">DRAFT</div>
    <?php } ?>
        
        <div style="" height="100%" >
            <div style="width: 80px; padding: 5px; font-size: 9px; margin-top: 20px;">
                CSC Form No.6<br/>
                Revised 2020
            </div>
            
            <table width="100%" border="0" style=" margin-top: -40px;">
                <tr>
                    <td width="23%" valign="top"><img src="<?php echo images('clsu_logo250.png'); ?>" style="padding-left: 20px; padding-top: 20px; float:left;" width="70"/></td>
                    <td width="54%" valign="top">
                            <div style="text-align: center; font-size: 11px; font-weight: bold;">
                                Republic of the Philippines
                                <br>
                                <?php echo $agency_name; ?>
                                <br>
                                <?php echo $agency_address; ?>
                            </div>
                    </td>
                    <td width="23%" valign="top" align="right">
                        <div style="float:right; padding: 5px; font-size: 10px;">
                            Date Received: <?php echo $details[0]->leave_received!='' ? '<span style="text-decoration: underline;">'.date('F j, Y',strtotime($details[0]->leave_received)).'</span>' : '________________' ; ?>
                        </div>
                        
                    </td>
                </tr>
            </table>
            
            
            
            
            <div style="text-align: center; font-size: 20px; margin-top: 30px; margin-bottom: 10px; font-weight: bold;">
                APPLICATION FOR LEAVE
            </div>
            
            <table border="0" cellspacing="0" cellpadding="0" width="100%" style="">
                <tr>
                    <td colspan="2" align="left" class="title left top" style="padding-left: 5px;">1. OFFICE/DEPARTMENT</td>
                    <td colspan="2" align="left" class="title top">2. NAME <em style="padding-left: 10px;">(Last)</em></td>
                    <td align="center" class="title top"><em>(First)</em></td>
                    <td align="center" class="title top right"><em>(Middle)</em></td>
                </tr>
                <tr style="">
                    <?php //echo @$details[0]->office_abbr;
                        $office_name = strtoupper(@$details[0]->office_program);
                        if(@$details[0]->unit_name){
                            $office_name =  strtoupper($details[0]->unit_name); //$details[0]->unit_abbr ? ' / '.$details[0]->unit_abbr : ' / '.$details[0]->unit_name;
                        } else if(@$details[0]->division_name){
                            $office_name =  strtoupper($details[0]->division_name); //$details[0]->division_abbr ? ' / '.$details[0]->division_abbr : ' / '.$details[0]->division_name;
                        } ?>
                    <td colspan="2" align="center" width="140px" class="bottom left" style="font-size: <?php echo strlen($office_name)>30 ? '11px' : '13px'; ?>">
                        <b><?= $office_name ?></b>
                    </td>
                    <td colspan="2" align="center" width="150px" class="bottom " style="padding-bottom: 5px;">
                        <b><?php echo strtoupper($details[0]->emp_lname); //'DELA CRUZ';// testing // ?></b>
                        <!--for testing part-->
                    </td>
                    <td align="center" width="170px" class="bottom ">
                        <b><?php echo strtoupper($details[0]->emp_fname.' '.$details[0]->emp_extname); //'JUAN';// ?></b>
                        <!--for testing part-->
                    </td>
                    <td align="center" width="120px" class="bottom right">
                        <b><?php echo strtoupper($details[0]->emp_mi); //'C';// ?></b>
                        <!--for testing part-->
                    </td>
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" style="">
                <tr>
                    <td width="13%" align="left" class="title left" style="padding-left: 5px; padding-top:10px;">3. DATE OF FILING</td>
                    <td width="1%"></td>
                    <td width="18%" align="left" class="bottom" style="padding-left:10px; padding-top:10px;">
                        <?php echo date('M-j-Y', strtotime($details[0]->filing_date)); ?>
                    </td>
                    <td width="1%"></td>
                    <td width="10%" align="left" class="title" style=" padding-top:10px;">4. POSITION</td>
                    <td width="32%" align="left" class="bottom" style="padding-left:10px;padding-top:10px;">
                        <?php echo @$details[0]->position_abbr!='' ? $details[0]->position_abbr : $details[0]->position_name; ?>
                    </td>
                    <td width="1%"></td>
                    <td width="9%" align="left" class="title" style="padding-top:10px;">5. SALARY </td>
                    <td width="14%" align="right" class="bottom" style="padding-right:10px;padding-top:10px;">
                        <?php //$grade = "sg_sin".$details[0]->step_increment;
                                echo @$details[0]->monthly_salary!='' ? number_format($details[0]->monthly_salary,2,'.',',') : ''; //'12,345.00';//
                                //echo '(SG-'.$details[0]->salary_grade.')';
                                ?>
                        <!--for testing part-->
                    </td>
                    <td width="1%" class="right"></td>
                </tr>
                <tr>
                    <td class="left right"  colspan="10" style="font-size:5px; color:white;">_</td>
                </tr>
            </table>
            
            
            <!--<div style="border-top-style: double;" ></div>-->
            
                

            <table border="" cellspacing="0" cellpadding="0" width="100%" style=" padding-top:0px; margin-top:0px;">
                <tr>
                    <td colspan="2" align="center" class="section_title">
                        <b style="padding-top:0px; margin-top:0px;">6. DETAILS OF APPLICATION</b>
                    </td>
                </tr>
                <tr>
                        <td align="left" valign="top" class="title left top" style="padding-left:5px; padding-top:2px; margin-top:2px; width: 60%;">
                                6.A <span style="padding-left: 10px;">TYPE OF LEAVE TO BE AVAILED</span>

                                <div style="margin-left: 12px; padding-top:2px; font-size: 10px;">
                                    <?php foreach($leave_types as $typ){
                                        $check=0;
                                        foreach($details as $det){
                                            if($det->leave_type_id==$typ->id_leave_type) {
                                                $check=1; ?>
                                                <div  style="padding-top:2px;">
                                                    <img src="<?php echo images('checkbox_checked.png'); ?>" style="padding-top:2px;" /> 
                                                        <?php echo $typ->leave_type_name; ?>
                                                        <span style="font-size:7px; font-weight: normal;">(<?php echo $typ->leave_type_details; ?>)</span>
                                                </div>
                                            <?php break;
                                            }
                                        }
                                        if($check==0){ ?>
                                                <div style="padding-top:2px;">
                                                    <img src="<?php echo images('checkbox.png'); ?>" style="padding-top:2px;" /> 
                                                        <?php echo $typ->leave_type_name; ?>
                                                        <span style="font-size:7px; font-weight: normal;">(<?php echo $typ->leave_type_details; ?>)</span>
                                                </div>
                                        <?php }
                                    } ?>
                                </div>

                                <div style="margin-left:20px; padding-bottom:10px; padding-top:20px;">
                                    <table border="0" cellspacing="0" cellpadding="0" width="90%" style="">
                                        <tr>
                                            <td colspan="2" ><em>Others:</em></td>
                                        </tr>
                                        <tr style="padding-top:0px; min-height:30px;">
                                            <td style="width: 90%; padding-top:0px; font-size:11px; min-height:30px;" class="bottom">
                                                <?php echo ($details[0]->leave_type_id==3) ? $details[0]->leave_details : ''; ?>
                                            </td>
                                            <td style="width:5%; padding-top:0px; color:white;">.</td>
                                        </tr>
                                    </table>
                                </div>
                        </td>
                        <td align="left" valign="top" class="title left right" style="padding-left:5px; padding-top:2px; margin-top:2px; width: 40%;">
                                6.B <span style="padding-left: 10px;">DETAILS OF LEAVE</span>

                                <div style="margin-left:20px; margin-right:10px; font-size: 10px;">

                                        <div style="width: 100%; padding-top: 5px;" class="leave_details_div">

                                                <span style="font-style: italic; font-size: 11px;">In case of Vacation / Special Privilege Leave:</span>

                                                <?php $vl_ph = 0;
                                                $vl_ph_loc = '';
                                                $vl_abroad = 0;
                                                $vl_abroad_loc = '';
                                                foreach($vl_details as $vld){
                                                    if($vld->leave_location=='Philippines'){
                                                        $vl_ph++;
                                                        $vl_ph_loc .= $vld->leave_details;
                                                        $vl_ph_loc .= $vld->is_slp_emergency==1 ? ' (emergency)' : '';
                                                        $vl_ph_loc .= '; ';
                                                    }else if($vld->leave_location=='Abroad'){
                                                        $vl_abroad++;
                                                        $vl_abroad_loc .= $vld->leave_details.'; ';
                                                    }
                                                } ?>
                                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:3px;">
                                                    <tr>
                                                        <td valign="top" style="width:45%;">
                                                            <img src="<?php echo $vl_ph>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  /> 
                                                            Within the Philippines 
                                                        </td>

                                                        <td class="bottom" valign="top" style="width:55%; padding-left:5px; padding-right: 5px;<?php echo strlen($vl_ph_loc)>60 ? 'font-size:9px' : 'font-size:10px'; ?>">
                                                            <?php echo $vl_ph_loc; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:2px;">
                                                    <tr>
                                                        <td width="70" valign="top">
                                                            <img src="<?php echo $vl_abroad>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>" /> 
                                                            Abroad (Specify)
                                                        </td>

                                                        <td class="bottom" valign="top" style="padding-left:5px; padding-right: 5px;">
                                                            <?php echo $vl_abroad_loc; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                        </div>


                                        <div style="width: 100%; padding-top:5px;" class="leave_details_div">

                                                <span style="font-style: italic; font-size: 11px;">In case of Sick Leave:</span>

                                                <?php $sl_out = 0;
                                                $sl_out_loc = '';
                                                $sl_hospital = 0;
                                                $sl_hospital_loc = '';
                                                foreach($sl_details as $sld){
                                                    if($sld->leave_location=='Out Patient'){
                                                        $sl_out++;
                                                        $sl_out_loc .= $sld->leave_details.'; ';
                                                    }else if($sld->leave_location=='In Hospital'){
                                                        $sl_hospital++;
                                                        $sl_hospital_loc .= $sld->leave_details.'; ';
                                                    }
                                                } ?>

                                                <?php if($sl_hospital_loc!=''){ ?>

                                                                                                            <?php /*<img src="<?php echo $sl_hospital>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  /> In Hospital (Specify illness)
                                                    <span style="text-decoration: underline;"> <?php echo $sl_hospital_loc; ?></span>
                                                                                                            */ ?>

                                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:3px;">
                                                        <tr>
                                                            <td valign="top" style="width:52%;">
                                                                <img src="<?php echo $sl_hospital>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  />  In Hospital (Specify illness)
                                                            </td>

                                                            <td class="bottom" valign="top" style="padding-left:5px; padding-right: 5px;<?php echo strlen($sl_hospital_loc)>60 ? 'font-size:9px' : 'font-size:10px'; ?>">
                                                                <?php echo $sl_hospital_loc; ?>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                <?php } else { ?>

                                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:3px;">
                                                        <tr>
                                                            <td valign="top" style="width:52%;">
                                                                <img src="<?php echo $sl_hospital>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  />  In Hospital (Specify illness)
                                                            </td>

                                                            <td class="bottom" valign="top" style="padding-left:5px; padding-right: 5px;<?php echo strlen($sl_hospital_loc)>60 ? 'font-size:9px' : 'font-size:10px'; ?>">
                                                                <?php echo $sl_hospital_loc; ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>

                                                <?php if($sl_out_loc!=''){ ?>

                                                    <!--<img src="<?php // echo $sl_out>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  /> Out Patient (Specify illness)-->
                                                    <!--<span style="text-decoration: underline; padding-left:2px;"> <?php //echo $sl_out_loc; ?></span>-->

                                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:2px;">
                                                        <tr>
                                                            <td valign="top" style="width:54%;">
                                                                <img src="<?php echo $sl_out>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  />  Out Patient (Specify illness)
                                                            </td>

                                                            <td class="bottom" valign="top" style="padding-left:5px; padding-right: 5px;<?php echo strlen($sl_out_loc)>60 ? 'font-size:9px' : 'font-size:10px'; ?>">
                                                                <?php echo $sl_out_loc; ?>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                <?php } else { ?>

                                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:2px;">
                                                        <tr>
                                                            <td valign="top" style="width:54%;">
                                                                <img src="<?php echo $sl_out>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  />  Out Patient (Specify illness)
                                                            </td>

                                                            <td class="bottom" valign="top" style="padding-left:5px; padding-right: 5px;">
                                                                <?php echo $sl_out_loc; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" valign="top" class="bottom" style="padding-left:5px; padding-right: 5px; color: white;">.
                                                            </td>
                                                        </tr>
                                                    </table>

                                                <?php } ?>
                                        </div>


                                        <div style="width: 100%; padding-top:5px;" class="leave_details_div">

                                                <span style="font-style: italic; font-size: 11px;">In case of Special Leave Benefits for Women:</span>

                                                <?php if(@$women_details[0]->leave_details!=''){ ?>
                                                    <br>
                                                    (Specify illness) 
                                                    <span style="text-decoration: underline;"> <?php echo $women_details[0]->leave_details; ?></span>
                                                <?php } else { ?>

                                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:3px;">
                                                        <tr>
                                                            <td valign="top" style="width:30%;">
                                                                (Specify illness)
                                                            </td>

                                                            <td class="bottom" valign="top" style="padding-left:5px; padding-right: 5px;">
                                                                <?php echo @$women_details[0]->leave_details; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"  valign="top"class="bottom" style="padding-left:5px; padding-right: 5px; color: white;">.
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>
                                        </div>


                                        <div style="width: 100%; padding-top:7px;" class="leave_details_div">

                                                <span style="font-style: italic; font-size: 11px;">In case of Study Leave:</span>

                                                <?php $study_ms = 0;
                                                $study_bar = 0;
                                                foreach($study_details as $study){
                                                    if($study->leave_purpose=="Completion of Master's Degree"){
                                                        $study_ms++;
                                                    }else if($study->leave_purpose=='BAR/Board Examination Review'){
                                                        $study_bar++;
                                                    }
                                                } ?>
                                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:3px;">
                                                    <tr>
                                                        <td>
                                                            <img src="<?php echo $study_ms>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  /> 
                                                            Completion of Master's Degree
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <img src="<?php echo $study_bar>0 ? images('checkbox_checked.png') : images('checkbox.png'); ?>" /> 
                                                            BAR/Board Examination Review
                                                        </td>
                                                    </tr>
                                                </table>
                                        </div>


                                        <div style="width: 100%; padding-top:7px;" class="leave_details_div">

                                                <span style="font-style: italic; font-size: 11px;">Other purpose:</span>

                                                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top:3px;">
                                                    <tr>
                                                        <td>
                                                            <img src="<?php echo $details[0]->is_monetization==1 ? images('checkbox_checked.png') : images('checkbox.png'); ?>"  /> 
                                                            Monetization of Leave Credits

                                                        </td>
                                                    </tr>

                                                    <?php if($details[0]->is_monetization==1){ ?>
                                                        <tr>
                                                            <td style="padding-left:20px; text-decoration: underline;"><?php echo $details[0]->leave_details; ?></td>
                                                        </tr>
                                                    <?php } ?>

                                                    <tr>
                                                        <td>
                                                            <img src="<?php echo $details[0]->is_terminal==1 ? images('checkbox_checked.png') : images('checkbox.png'); ?>" /> 
                                                            Terminal Leave
                                                        </td>
                                                    </tr>
                                                </table>
                                        </div>


                                        <br/>

                                </div>

                        </td>
                </tr>
                <tr>
                        <td align="left" width="50%" valign="top" class="title left top" style="padding-left:5px; padding-top:0px; margin-top:0px;">
                                6.C <span style="padding-left: 10px;">NUMBER OF WORKING DAYS APPLIED FOR</span>

                                <div style="width: 80%; padding-top:5px; margin-left:20px;" class="leave_details_div">
                                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tr>
                                                <td class="bottom" style="padding-left: 20px; font-size: 12px;">
                                                    <b><?php if($details[0]->is_terminal!=1){
                                                            echo $details[0]->total_days_applied!=1 ? $details[0]->total_days_applied.' day(s)' : '1 day';
                                                        } else {
                                                            echo '__';
                                                        } ?>
                                                    </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="" style="padding-top:5px;">
                                                    INCLUSIVE DATES
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="bottom" style="padding-left: 20px; min-height: 30px; font-size: 12px;">


                                        <?php 
                                        $total_days = $details[0]->total_days_applied;
                                        $prev_month = '';
                                        $year = '';
                                        $leave_str = '';
                                        $ctr = 0;
                                        $count = count($leave_dates);
                                        $leave_one_day = '';
                                        $prev_type = '';
                                        foreach($leave_dates as $dates){
                                            $date_from = $dates->date_from;
                                            $date_to = $dates->date_to;

                                            if($prev_type!=$dates->type_abbr && $prev_type!=''){
                                                $prev_type=$dates->type_abbr;
                                                $leave_str .= '('.$dates->type_abbr.') ';
                                            }

                                            //if($total_days<60){
                                                $new_month =  substr($date_from,5,2);
                                                $new_year = substr($date_from,0,4);
                                                if($prev_month==""){
                                                    $prev_month = $new_month;
                                                    $leave_str .= date("M",strtotime($date_from)).' ';
                                                }

                                                if($prev_month!=$new_month){
                                                    $leave_str .= date("M",strtotime($date_from)).' ';
                                                }

                                                if($date_from==$date_to || $date_to==''){
                                                     //$leave_str .= substr($date_from,8,2);
                                                     $leave_str .= date('j',strtotime($date_from));
                                                     if($dates->date_from_ishalf==1){
                                                         $leave_str .= 'am';
                                                     } else if($dates->date_from_ishalf==2){
                                                         $leave_str .= 'pm';
                                                     }
                                                     $leave_str .= ', ';
                                                     $leave_one_day = $date_from;
                                                     $ctr++;
                                                 } else {
                                                    //$leave_str .= substr($date_from,8,2);
                                                    $leave_str .= date('j',strtotime($date_from));
                                                    if($dates->date_from_ishalf==1){
                                                        $leave_str .= 'am';
                                                    } else if($dates->date_from_ishalf==2){
                                                        $leave_str .= 'pm';
                                                    }
                                                    /*if($dates->date_from_ishalf!=0){
                                                        $leave_str .= 'hd';
                                                    } */
                                                    if($date_from==$date_to){
                                                        $leave_str .= ', ';
                                                    //} else if($dates->nodays>1){
                                                    } else  {
                                                        //$leave_str .= '-'.substr($date_to,8,2);
                                                        if(date('Y',strtotime($date_from))!=date('Y',strtotime($date_to))){
                                                            $leave_str .= ' '.date('Y',strtotime($date_from)).'-'.date('M j',strtotime($date_to));
                                                        } else if(date('m',strtotime($date_to))==date('m',strtotime($date_from))){
                                                            $leave_str .= '-'.date('j',strtotime($date_to));
                                                        } else {
                                                            $leave_str .= '-'.date('M j',strtotime($date_to));
                                                        }
                                                        if($dates->date_to_ishalf==1){
                                                            $leave_str .= 'am';
                                                        } else if($dates->date_to_ishalf==2){
                                                            $leave_str .= 'pm';
                                                        }
                                                        /*if($dates->date_to_ishalf!=0){
                                                            $leave_str .= 'hd';
                                                        }*/
                                                        $leave_str .= ', ';
                                                     }
                                                     $ctr++;
                                                 }

                                                if($year==""){
                                                    $year = $new_year;
                                                    //$leave_str .= date("Y",strtotime($date_from)).' ';
                                                }
                                                if($ctr==$count){
                                                    //$leave_str .= date("Y",strtotime($year)).' ';
                                                    $leave_str .= $date_to!='' ? substr($date_to,0,4).' ' : substr($date_from,0,4).' ';//$year.' ';
                                                }
                                            /*}else if($total_days==60){
                                                $leave_str = date("M j, Y",strtotime($date_from)).' - '.date("M j, Y",strtotime($date_to)).' ';
                                            } else {
                                                $leave_str = date("M j",strtotime($date_from)).' - '.date("M j, Y",strtotime($date_to)).' ';
                                            }*/

                                        } echo $leave_str!='' ? $leave_str : '<br>'; ?>

                                                </td>
                                            </tr>
                                        </table>

                                </div>

                        </td>
                        <td align="left" width="50%" valign="top" class="title left top right" style="padding-left:5px; padding-top:0px; margin-top:0px;">
                                6.D <span style="padding-left: 10px;">COMMUTATION</span>

                                <div style="padding-top: 5px; padding-left: 20px;">
                                    <img src="<?php echo ($details[0]->is_monetization==1 || $details[0]->is_terminal==1) ? images('checkbox_checked.png') : images('checkbox.png'); ?>" /> Requested
                                    <br>
                                    <img src="<?php echo ($details[0]->is_monetization==1 || $details[0]->is_terminal==1) ? images('checkbox.png') : images('checkbox_checked.png'); ?>" style="padding-top:3px;"/> Not Requested
                                </div>

                                <div style="width:100%; text-align: center; margin-top:10px; margin-bottom:5px;">
                                    <?php if(@$employee_esign!='' && session()->get('access_level')==1){
                                            if(@$employee_esign[0]->esign_location=='hrmis'){
                                                $empsign_addr = uploads('esigns/');
                                            } else if(@$employee_esign[0]->esign_location=='eportal' && ($_SERVER['SERVER_ADDR']=='::1' || $_SERVER['SERVER_ADDR']=='127.0.0.1')){
                                                $empsign_addr = 'http://localhost/employeeportal/public/assets/uploads/esigns/';
                                            } else {
                                                $empsign_addr = 'https://e-portal2.clsu.edu.ph/public/assets/uploads/esigns/';
                                            } ?>
                                            <img src="<?php echo $empsign_addr.@$employee_esign[0]->esign_file_name; ?>" style="width:100px; margin-top: -30px; margin-left: 70px; position:absolute;">
                                    <?php } ?>
                                    __________________________________
                                    <br/>
                                    <span>(Signature of Applicant)</span>
                                </div>
                        </td>
                </tr>

            </table>


            <table border="" cellspacing="0" cellpadding="0" width="100%" style="">
                <tr>
                    <td colspan="2" align="center" class="section_title">
                        <b style="padding-top:0px; margin-top:0px;">7. DETAILS OF ACTION ON APPLICATION</b>
                    </td>
                </tr>
                <tr>
                        <td align="left" valign="top" class="title left" style="padding-left:5px; padding-top:2px; margin-top:2px; width: 48%;">

                                7.A <span style="padding-left: 10px;">CERTIFICATION OF LEAVE CREDITS </span>

                                <?php $print_credits = $details[0]->is_terminal==1 ? 0 : $print_credits; ?>

                                <div style="padding-left: 50px; padding-top: 5px;">
                                    <div style="display: inline-block; width:30px; text-align: left; padding-left:20px;">As of</div>
                                    <div style="display: inline-block; width:120px; text-align: left; padding-left:20px; border-bottom: 1px solid black; height: 15px;">
                                        <?php echo $print_credits==1 ? date('F j, Y',strtotime($details[0]->leave_credit_asofdate)) : ''; //.' '.$details[0]->log_remarks;
                                        ?>
                                    </div>
                                </div>

                                <div style="padding-left:30px; padding-right:30px; padding-top:5px;" class="leave_details_div">
                                    <table border="1" cellspacing="0" cellpadding="5" width="100%" id="leave_credits_table">
                                        <tr>
                                            <td align="center"></td>
                                            <td align="center">Vacation Leave</td>
                                            <td align="center">Sick Leave</td>
                                        </tr>
                                        <tr>
                                            <td align="center">Total Earned</td>
                                            <td align="center"><?php 
                                                echo $print_credits==1 ? $details[0]->balance_vl : ''; // $details[0]->leave_credit_asofvl
                                                //__ Days ?>
                                            </td>
                                            <td align="center"><?php 
                                                echo $print_credits==1 ? $details[0]->balance_sl : ''; // $details[0]->leave_credit_asofsl
                                                //__ Days ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">Less this application</td>
                                            <td align="center"><?php 
                                                $sl_bal = floatval($details[0]->balance_sl) - floatval($details[0]->totalsl_days_applied);
                                                if($sl_bal<0){
                                                    $sl_rem = floatval($details[0]->totalsl_days_applied) - floatval($details[0]->balance_sl);
                                                } else {
                                                    $sl_rem = 0;
                                                }
                                                echo $details[0]->is_terminal!=1 ? $details[0]->totalvl_days_applied : ''; //__ Days 
                                                echo $sl_rem>0 ? ' (SL = '.$sl_rem.')' : '';
                                                ?>
                                            </td>
                                            <td align="center"><?php 
                                                if($details[0]->is_terminal!=1){
                                                    echo $sl_rem>0 ? $details[0]->balance_sl : $details[0]->totalsl_days_applied;
                                                }  
                                                //__ Days ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">Balance</td>
                                            <td align="center"><?php 

                                                //$vl_bal = floatval($details[0]->leave_credit_asofvl) - floatval($details[0]->totalvl_days_applied);
                                                $vl_bal = floatval($details[0]->balance_vl) - floatval($details[0]->totalvl_days_applied) - floatval($sl_rem);
                                                echo $print_credits==1 ? $vl_bal : ''; ?>
                                            </td>
                                            <td align="center"><?php 
                                                //$sl_bal = floatval($details[0]->leave_credit_asofsl) - floatval($details[0]->totalsl_days_applied);
                                                //$sl_bal = floatval($details[0]->balance_sl) - floatval($details[0]->totalsl_days_applied);
                                                if($print_credits==1){
                                                    echo $sl_bal<0 ? 0 : $sl_bal;
                                                } ?>
                                            </td>
                                        </tr>
                                    </table>
                                            <div style="text-align: right; width:100%; padding-right:5px;">
                                                <?php //echo $print_credits==1 ? '<em style="font-size:smaller; ">* SLP = '.intval($details[0]->leave_credit_asofslp).'</em>' : ''; ?>
                                                <?php echo $print_credits==1 ? '<em style="font-size:smaller; ">* SLP = '.floatval($details[0]->balance_slp).'</em>' : ''; ?>
                                            </div>

                                </div>

                                <div style="width:100%; text-align: center; margin-top:10px; margin-bottom:10px;">

                                    <div style="display: inline-block; margin-left: 50px; margin-right: 50px; padding-top: 15px;">
                                        <?php if(@$signatories[0]->cert_name){
                                            //if($certification){
                                                //$certi_name = $certification[0]->emp_fname.' ';
                                                //$certi_name .= $certification[0]->emp_mname ? $certification[0]->emp_mname[0].'. ' : '';
                                                //$certi_name .= $certification[0]->emp_lname.' '.$certification[0]->emp_extname;
                                                echo '<b style="text-decoration: underline;">'.strtoupper($signatories[0]->cert_name).'</b>';
                                                ?>
                                                <br>
                                                <i><?php if($signatories[0]->cert_designation_abbr!=''){
                                                        echo $signatories[0]->cert_designation_abbr;
                                                    } else if($$signatories[0]->cert_designation_name!=''){
                                                        echo $signatories[0]->cert_designation_name;
                                                    } else if($signatories[0]->cert_position_name!=''){
                                                        echo $signatories[0]->cert_position_name;
                                                    } else {
                                                        echo 'Authorized Officer';
                                                    }
                                                    /*if($certification[0]->designation_abbr!=''){
                                                        echo $certification[0]->designation_abbr;
                                                    } else if($certification[0]->designation_name!=''){
                                                        echo $certification[0]->designation_name;
                                                    } else if($certification[0]->position_name!=''){
                                                        echo $certification[0]->position_name;
                                                    }*/   ?>
                                                </i>
                                        <?php } else {
                                            //echo '__________________________________<br>(Authorized Officer)';
                                            echo '<b style="text-decoration: underline;">JONATHAN T. GURION</b>';
                                            echo '<br><em>HRMO</em>';
                                        } ?>
                                    </div>
                                </div>

                        </td>



                        <td align="left" valign="top" class="title left right" style="padding-left:5px; width:40%; margin-top: 2px; padding-top: 2px;">
                                7.B <span style="padding-left: 10px;">RECOMMENDATION </span>

                                <div style="padding-top: 5px; padding-left: 20px;">

                                    <img src="<?php echo ($signatories[0]->recommending_status==3) ? images('checkbox_checked.png') : images('checkbox.png'); ?>" /> For Approval


                                    <?php if($signatories[0]->recommending_message!='' && $signatories[0]->recommending_status==2){ ?>

                                            <img src="<?php echo ($signatories[0]->recommending_status==2) ? images('checkbox_checked.png') : images('checkbox.png'); ?>" style="padding-top:2px;"/> For Disapproval due to
                                            <span style="text-decoration: underline;"> <?php echo $signatories[0]->recommending_message; ?>
                                                    <?php if($signatories[0]->recommending_status==2 && $signatories[0]->recommending_via!=''){ ?>
                                                        <em style="font-size:8px;">(<?php echo $signatories[0]->recommending_via.' - ref# '.$signatories[0]->recommending_code; ?>)</em>
                                                    <?php } ?>
                                            </span>

                                    <?php } else { ?>

                                            <table border="0" cellpadding="0" cellspacing="0" style="width: 95%;">
                                                <tr>
                                                    <td valign="top" style="width:50%; padding-top: 0px;">
                                                        <img src="<?php echo ($signatories[0]->recommending_status==2) ? images('checkbox_checked.png') : images('checkbox.png'); ?>" style="padding-top:2px;"/> For Disapproval due to
                                                    </td>

                                                    <td class="bottom" style="padding-left:5px; padding-right: 5px;">
                                                        <?php //echo $signatories[0]->recommending_message; ?>
                                                        <?php if($signatories[0]->recommending_status==2 && $signatories[0]->recommending_via!=''){ ?>
                                                            <em style="font-size:9px;">(Disapproved <?php echo $signatories[0]->recommending_via.' - ref# '.$signatories[0]->recommending_code; ?>)</em>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="bottom" style="padding-left:5px; padding-right: 5px; color: white; padding-top: 2px;">.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="bottom" style="padding-left:5px; padding-right: 5px; color: white; padding-top: 3px;">.
                                                    </td>
                                                </tr>
                                                <!--<tr>
                                                    <td colspan="2" class="bottom" style="padding-left:5px; padding-right: 5px; color: white;">.
                                                    </td>
                                                </tr>-->
                                            </table>
                                    <?php } ?>

                                </div>

                                <div style="width:100%; text-align: center; margin-top:50px;">

                                    <div style="display: inline-block; margin-left: 10px; <?php echo ($signatories[0]->recommending_status==3 && $signatories[0]->recommending_via!='') ? 'width:65%;' : ''; ?>">

                                        <?php if($signatories[0]->recommending_name!=''){
                                                echo '<b style="text-decoration: underline;">'.strtoupper($signatories[0]->recommending_name).'</b>';
                                                ?>
                                                <br>
                                                <i><?php if($signatories[0]->recommending_designation!=''){
                                                        echo $signatories[0]->recommending_designation;
                                                    } else {
                                                        echo '(Authorized Officer)';
                                                    }  ?>
                                                </i>
                                        <?php } else {
                                            echo '__________________________________<br>(Authorized Officer)';
                                        } ?>
                                    </div>

                                    <?php if($signatories[0]->recommending_status==3 && $signatories[0]->recommending_via!=''){ ?>
                                        <div style="display: inline-block; width:30%">

                                                <?php if(@$signatories[0]->reco1_esign!='' && session()->get('access_level')==1){
                                                    if(@$signatories[0]->reco1_esign_location=='hrmis'){
                                                        $reco1_addr = uploads('esigns/');
                                                    } else if(@$signatories[0]->reco1_esign_location=='eportal' && ($_SERVER['SERVER_ADDR']=='::1' || $_SERVER['SERVER_ADDR']=='127.0.0.1')){
                                                        $reco1_addr = 'http://localhost/employeeportal/public/assets/uploads/esigns/';
                                                    } else {
                                                        $reco1_addr = 'https://e-portal2.clsu.edu.ph/public/assets/uploads/esigns/';
                                                    } ?>
                                                    <span style="position: absolute; margin-top:-40px; font-size:10px;">E-signature:</span>
                                                    <img src="<?php echo $reco1_addr.@$signatories[0]->reco1_esign; ?>" style="width:100px; margin-top:-30px; margin-left:-20px; position: absolute;">
                                                <?php } ?>

                                                <em style="font-size:8px;">(Approved <?php echo $signatories[0]->recommending_via.' ';
                                                    echo $signatories[0]->recommending_dateupdated!='' ? '<br>'.date('M.j,Y',strtotime($signatories[0]->recommending_dateupdated)):'';
                                                    echo ' <br>ref# '.$signatories[0]->recommending_code; ?>)
                                                </em>
                                        </div>
                                    <?php } ?>
                                </div>

                        </td>
                </tr>
                <tr>
                        <td align="left" valign="top" class="title left top" style="padding-left:5px; padding-top:2px; margin-top:2px; border-bottom: 0px; border-right: 0px;">
                                7.C <span style="padding-left: 10px;">APPROVED FOR: </span>

                                <div style="margin-left:40px; padding-top:5px;">
                                    <!--<div style="display: inline-block; width:80px; text-align: left; padding-left:10px; border-bottom: 1px solid black;"></div>-->
                                    <table border="0">
                                        <tr>
                                            <td style="border-bottom: 1px solid black; width: 50px; padding-top:0px;">
                                                <?php echo @$approved[0]->total_days_approved; ?>
                                            </td>
                                            <td style="padding-top:0px;">day/s with pay</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid black; min-width: 120px; padding-top:0px;">
                                                <?php echo @$approved[0]->total_nopay_approved; ?>
                                            </td>
                                            <td style="padding-top:0px;">day/s without pay</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid black; min-width: 120px; padding-top:0px;"></td>
                                            <td style="padding-top:0px;">others <i>(Specify)</i></td>
                                        </tr>
                                    </table>
                                </div>
                        </td>
                        <td align="left" valign="top" class="title top right" style="padding-left:5px; padding-top:2px; margin-top:2px; border-bottom: 0px;  border-left: 0px;">
                                7.D <span style="padding-left: 10px;">DISAPPROVED DUE TO: </span>

                                <div style="padding-left:20px;">

                                    <?php if($signatories[0]->approving_message!='' && $signatories[0]->approving_status==2){ ?>
                                        <span style="text-decoration: underline;"> <?php echo $signatories[0]->approving_message; ?>
                                                <?php if($signatories[0]->approving_status==2 && $signatories[0]->approving_via!=''){ ?>
                                                    <em style="font-size:8px;">(Disapproved <?php echo $signatories[0]->approving_via.' - ref# '.$signatories[0]->approving_code; ?>)</em>
                                                <?php } ?>
                                        </span>
                                    <?php } else { ?>

                                            <table border="0" cellpadding="0" cellspacing="0" style="width: 95%;">
                                                <tr>
                                                    <td colspan="2" class="bottom" style="padding-left:5px; padding-right: 5px;">
                                                        <?php if($signatories[0]->approving_status==2 && $signatories[0]->approving_via!=''){ ?>
                                                            <em style="font-size:9px;">(<?php echo $signatories[0]->approving_via.' - ref# '.$signatories[0]->approving_code; ?>)</em>
                                                        <?php } else echo '<span style="color:white;">.</span>'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="bottom" style="padding-left:5px; padding-right: 5px; color: white;">.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="bottom" style="padding-left:5px; padding-right: 5px; color: white;">.
                                                    </td>
                                                </tr>
                                            </table>
                                    <?php } ?>



                                </div>

                        </td>
                </tr>
                <tr>
                    <td colspan="2" align="center" class="left bottom right" style="padding-top: 10px;  border-top: 0px;">

                            <div style="width:100%; text-align: center; margin-top:10px;">

                                <div style="display: inline-block; margin-left: 10px; <?php echo ($signatories[0]->approving_status==3 && $signatories[0]->approving_via!='') ? 'width:55%; text-align:right;' : ''; ?>">

                                    <?php if($signatories[0]->approving_name!=''){
                                            echo '<b style="text-decoration: underline;">'.strtoupper($signatories[0]->approving_name).'</b>';
                                            ?>
                                            <br>
                                            <i><?php if($signatories[0]->approving_designation!=''){
                                                    echo $signatories[0]->approving_designation;
                                                } else {
                                                    echo 'Authorized Official';
                                                }  ?>
                                            </i>
                                    <?php } else {
                                        echo '__________________________________<br>(Authorized Official)';
                                    } ?>
                                </div>

                                <?php if($signatories[0]->approving_status==3 && $signatories[0]->approving_via!=''){ ?>
                                    <div style="display: inline-block; width:20%; padding-left:15px;  padding-top:25px; text-align: left; vertical-align: middle; margin-top:10px;">

                                            <?php if(@$signatories[0]->approv_esign!='' && session()->get('access_level')==1){ 
                                                    if($signatories[0]->approv_esign_location=='hrmis'){
                                                        $approv_addr = uploads('esigns/');
                                                    } else if($signatories[0]->approv_esign_location=='eportal' && ($_SERVER['SERVER_ADDR']=='::1' || $_SERVER['SERVER_ADDR']=='127.0.0.1')){
                                                        $approv_addr = 'http://localhost/employeeportal/public/assets/uploads/esigns/';
                                                    } else {
                                                        $approv_addr = 'https://e-portal2.clsu.edu.ph/public/assets/uploads/esigns/';
                                                    } ?>

                                                    <span style="display: block; margin-top:-40px; font-size:10px; margin-left:-10px; ">E-signature:</span>
                                                    <img src="<?php echo $approv_addr.@$signatories[0]->approv_esign; ?>" style="height:30px; display: block; margin-top:-30px; margin-left:10px;  position: absolute;">
                                            <?php } ?>

                                            <em style="font-size:8px;">(Approved <?php echo $signatories[0]->approving_via.' ';
                                                echo $signatories[0]->approving_dateupdated!='' ? '<br>'.date('M.j,Y',strtotime($signatories[0]->approving_dateupdated)):'';
                                                echo ' <br>ref# '.$signatories[0]->approving_code; ?>)
                                            </em>
                                    </div>
                                    <div style="display: inline-block; width:20%; text-align: left;"></div>
                                <?php } ?>
                                    <br>
                                    <br>
                            </div>
                    </td>
                </tr>
            </table>


            <br/>
            <br/>
            <br/>
            
            
            
        </div>
</body>
</html>