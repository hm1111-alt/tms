<?php $this->load->view('dashboard/header'); ?>
<?php $this->load->view('dashboard/sidebar'); ?>

                
<!--    <link href="<?php echo css('jquery-ui-datepicker.css'); ?>" rel="stylesheet">
    <link href="<?php echo css('jquery-ui-datepicker.min.css'); ?>" rel="stylesheet">-->

<link rel="stylesheet" href="<?php echo jquery('leave/fullcalendar/fullcalendar.min.css'); ?>">
<link rel="stylesheet" href="<?php echo jquery('leave/fullcalendar/fullcalendar.print.css'); ?>" media="print">
<script src="<?php echo jquery('leave/fullcalendar/fullcalendar.min.js'); ?>"></script>
<script src="<?php echo jquery('bootstrap.min.js'); ?>"></script>

    <link href="<?php echo css('leave/AdminLTE.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo css('leave/skins/_all-skins.min.css'); ?>" rel="stylesheet">

    <link href="<?php echo css('leave/leave.css'); ?>" rel="stylesheet">
    
    
    <script src="<?php echo jquery('leave/jQuery-2.1.4.min.js'); ?>"></script>
    <script src="<?php echo jquery('leave/moment/moment.min.js'); ?>"></script>
<script src="<?php echo jquery('leave/fullcalendar/fullcalendar.min.js'); ?>"></script>
    <script src="<?php echo jquery('bootstrap.min.js'); ?>"></script>
<script src="<?php echo jquery('jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js'); ?>"></script>
    
    <!--Leave scripts-->
    <script src="<?php echo jquery('leave/jquery-ui-datepicker.js'); ?>"></script>
    <script src="<?php echo jquery('leave/underscore.js'); ?>"></script> 
    <script src="<?php echo jquery('leave/jquery-debounce.js'); ?>"></script>
    
    
<link rel="stylesheet" href="<?php echo jquery('jquery-ui-1.10.3.custom/development-bundle/themes/ui-lightness/jquery.ui.all.css'); ?>">
<script type="text/javascript">
        $(document).ready(function(){

                $( ".datepicker" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });

        });
</script>

<section id="main-content">
	<section class="wrapper">
		<div class="row" id="leave_form_page">
                    
                                
			<div class="col-lg-12">
                                <!--breadcrumbs start -->
                                <ul class="breadcrumb" style="padding: 25px;">
                                    <?php $this->load->view('dashboard/breadcrumbs'); ?>    
                                    <li class="active">(Application for Leave)</li>
                                </ul>

                                <ul class="page_title_button" style="">
                                    <?php if($access->add){ ?>
                                        <li style="">
                                            <a href="<?php echo site_url('attendance/leave/add'); ?>" class="plus-sign add_button">
                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                <div style="color: #999;">Add new</div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>

                                <ul>
                                    <li>
                                        

                                    </li>
                                </ul>
                        </div>
             
			<div class="col-lg-12">
                                <?php $this->load->view('settings/messages'); ?>
                            
                                <div class="alert alert-success" id="success1" role="alert" style="display: none;">Successfully saved.</div>
                                <div class="alert alert-danger" id="error1" role="alert" style="display: none;">Error! Something went wrong while saving.</div>
                
                        </div>
                    
                        <div class="col-lg-12 col-md-12">

                                <section class="content">
                                        <div class="col-lg-12">
                                                <div class="leave-header">Application for Leave</div>
                                        </div>
                                    
                                        <div class="col-md-4">
                                                <div class="box box-solid">
                                                        <div class="box-header with-border" id="errorLeave" style="display: none; color: red;">
                                                                <h4 class="box-title">Ooops!</h4>
                                                                <div id="errorTxt"></div>
                                                        </div>
                                                        <div class="box-header with-border">
                                                                <h4 class="box-title">Employee Information</h4>
                                                        </div>
                                                        <div class="box-body">
                                                                <div class="form-group">
                                                                        <div class="input-group">
                                                                                <?php if($access_level>2){ ?>
                                                                                        <select class="search_items form-control" id="employee_dropdown" onChange="select_employee2()">
                                                                                            <option>Select Employee</option>
                                                                                            <?php if(@$employees){
                                                                                            foreach($employees as $emp){ ?>
                                                                                                        <option value="<?php echo $emp->emp_idno.'_'.$emp->emp_fullname.'_'.$emp->id_employee; ?>">
                                                                                                            <?php echo strtoupper($emp->emp_lname).', '.$emp->emp_fname.' '.$emp->emp_extname.' '.$emp->emp_mname; ?>
                                                                                                        </option>
                                                                                            <?php }
                                                                                            } ?>
                                                                                        </select>
                                                                                        <span class="input-group-addon">
                                                                                             <i class="glyphicon glyphicon-search"></i>
                                                                                        </span>
                                                                                <?php } else { ?>
                                                                                        <input id="search_employee" type="text" class="form-control" placeholder="Search Employee">
                                                                                        <span class="input-group-addon">
                                                                                             <i class="glyphicon glyphicon-search"></i>
                                                                                        </span>
                                                                                <?php } ?>
                                                                        </div><!-- /input-group -->
                                                                        <input type="hidden" value="" name="emp_idno" id="emp_idno"/>
                                                                        <input type="hidden" value="" name="employee_id" id="employee_id"/>
                                                                </div>

                                                                <div id="searchProfile" class="search_container">
                                                                    <div class="search_items">&nbsp;<img src="<?php echo images('loading.gif'); ?>"> Loading...</div>
                                                                </div>  
                                                                <div id="profile-loader"></div>
                                                                <div id="loader"></div>
                                                        </div> 
                                                    
                                                        <hr>
                                                        
                                                        <div class="box-header with-border">
                                                                <h4 class="box-title">Details of Application</h4>
                                                        </div>
                                                    
                                                        <div class="box-body" style=" padding-left: 30px;">
                                                            
                                                                <div class="form-group" style="">
                                                                        <label><?php echo strtoupper('Date of Filing:'); ?></label>
                                                                        <div class="radio">
                                                                            <input id="date_filing" class="datepicker form-control" type="date" style="margin-top:5px; width: 150px;" value="<?php echo date('Y-m-d'); ?>">
                                                                        </div>
                                                                </div>
                                                            
                                                                <div class="form-group" id="">
                                                                        <label for="vacation_reason">a. <?php echo strtoupper('Type of Leave'); ?></label>
                                                                        <div  style="padding-left:20px;">
                                                                                <div class="btn-group">
                                                                                        <button type="button" class="btn btn-info btn-flat"><span id="leave-txt">Select Leave</span></button>
                                                                                        <button type="button" class="btn btn-info dropdown-toggle btn-flat" data-toggle="dropdown">
                                                                                            <span class="caret"></span>
                                                                                        </button>
                                                                                        <ul class="dropdown-menu" id="leave_types_div">
                                                                                            <li>No employee selected.</li>
                                                                                        </ul>
                                                                                </div>  

                                                                                <!-- the events -->
                                                                                <div id="external-events">
                                                                                        <label style="margin-top: 10px; display: none;" id="leave-label">Drag this to calendar</label>
                                                                                        <div id="vacation_leave" class="leave-events">  
                                                                                            <div class="external-event bg-green" id="" style="display: none;">Vacation</div>
                                                                                        </div>
                                                                                        <div id="sick_leave" class="leave-events">
                                                                                            <div class="external-event bg-yellow" id="" style="display: none;">Sick</div>
                                                                                        </div>
                                                                                        <div id="maternity" class="leave-events">
                                                                                            <div class="external-event bg-aqua" id="" style="display: none;">Maternity</div>
                                                                                        </div>
                                                                                        <div id="paternity" class="leave-events">
                                                                                            <div class="external-event bg-light-blue" id="" style="display: none;">Paternity</div>
                                                                                        </div>
                                                                                        <div id="rehabilitation" class="leave-events">
                                                                                            <div class="external-event bg-red" id="" style="display: none;">Rehabilitation</div>
                                                                                        </div>
                                                                                        <div id="calamity" class="leave-events">
                                                                                            <div class="external-event bg-purple" id="" style="display: none;">Calamity</div>
                                                                                        </div>
                                                                                        <div id="slp" class="leave-events">
                                                                                            <div class="external-event bg-teal" id="" style="display: none;">Special Leave Privilege</div>
                                                                                        </div>

                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                    
                                                        <div class="box-body" style="padding-left: 30px;">  
                                                            
                                                                <div class="form-group" id="vacation_reason" style="padding-left: 20px; display: none;">
                                                                        <label for="vacation_reason">Vacation Reason</label>
                                                                        <div  style="padding-left:20px;">
                                                                                <div class="radio">
                                                                                    <label>
                                                                                        <input type="radio" name="vacation_reason" id="vacation_reason1" value="To seek employment" >
                                                                                        To seek employment
                                                                                    </label>
                                                                                </div>
                                                                                <div class="radio">
                                                                                    <label>
                                                                                        <input type="radio" name="vacation_reason" id="vacation_reason2" value="Others" checked>
                                                                                        Others (Specify)
                                                                                    </label>
                                                                                    <input id="vacation_others" class="form-control" type="text" style="margin-top:5px;" placeholder="Others (Specify)" maxlength="50">
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group" id="slp_reason_div" style="padding-left: 20px; display:none;">
                                                                        <label for="slp_reason">Specify SLP reason</label>
                                                                        <div  style="padding-left:20px;">
                                                                                
                                                                                <div class="radio">
                                                                                    <input id="slp_reason" class="form-control" type="text" style="margin-top:5px;" placeholder="Please Specify..." maxlength="60">
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group" id="leave_spent" style="padding-top: 20px; display:none;">
                                                                        <label for="">b. <?php echo strtoupper('Where leave will be spent'); ?></label>
                                                                        <div id="vacation_location" style="padding-left:20px; display:none;">
                                                                                <label for="spent_vacation">1. In case of Vacation Leave</label>
                                                                                <div  style="padding-left:20px;">
                                                                                        <div class="radio">
                                                                                            <label>
                                                                                                <input type="radio" name="spent_vacation" id="spent_vacation1" value="Within the Philippines" checked>
                                                                                                Within the Philippines
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="radio">
                                                                                            <label>
                                                                                                <input type="radio" name="spent_vacation" id="spent_vacation2" value="Abroad">
                                                                                                Abroad (Specify)
                                                                                            </label>
                                                                                            <input id="abroad" class="form-control" type="text" style="margin-top:5px;" placeholder="Abroad (Specify)" maxlength="50">
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                        <div id="sick_location" style="padding-left:20px; display:none;">
                                                                                <label for="sick_location">2. In case of Sick Leave</label>
                                                                                <div  style="padding-left:20px;">
                                                                                        <div class="radio">
                                                                                            <label>
                                                                                                <input type="radio" name="sick_location" id="sick_location1" value="Hospital" checked>
                                                                                                In Hospital (Specify)
                                                                                            </label>
                                                                                            <input id="hospital" class="form-control" type="text" maxlength="150" style="margin-top:5px;" placeholder="Hospital (Specify)">
                                                                                        </div>
                                                                                        <div class="radio">
                                                                                            <label>
                                                                                                <input type="radio" name="sick_location" id="sick_location2" value="Out Patient">
                                                                                                Out Patient (Specify)
                                                                                            </label>
                                                                                            <input id="patient" class="form-control" type="text" maxlength="150" style="margin-top:5px;" placeholder="Out Patient (Specify)">
                                                                                        </div>
                                                                                </div>
                                                                        </div>  
                                                                </div>
                                                            
                                                                <!--<div class="form-group" id="total_days_div" style="padding-top: 20px; display:none;">-->
                                                                <div class="form-group" id="total_days_div" style="padding-top: 20px; ">
                                                                        <label for="">c. <?php echo strtoupper('Number of Working days applied for:'); ?></label>
                                                                        <div  style="padding-left:20px;">
                                                                                <div class="radio">
                                                                                    <input id="total_days" class="form-control" onkeypress="validatedecimal(event)" type="text" style="margin-top:5px; width: 150px;" placeholder="No. of days" readonly="">
                                                                                    <input id="vl_wpay" type="hidden" value="0.0">
                                                                                    <input id="sl_wpay" type="hidden" value="0.0">
                                                                                </div>
                                                                        </div>
                                                                        <div id="inclusive_date_div" style="margin-left:20px;">
                                                                            <label><?php echo strtoupper('Inclusive Dates:'); ?></label>
                                                                            <div  style="padding-left:20px;">
                                                                                <ol id="inclusive_dates">
                                                                                    <!--<li><label id="from_lbl_0">a</label>
                                                                                        <select class="leave_coverage" id="from_hd_0">
                                                                                            <option value="0">WD</option>
                                                                                            <option value="1">AM</option>
                                                                                            <option value="2">PM</option>
                                                                                        </select> - 
                                                                                        <label id="to_lbl_0">a</label>
                                                                                        <select class="leave_coverage" id="to_hd_0">
                                                                                            <option value="0">WD</option>
                                                                                            <option value="1">AM</option>
                                                                                            <option value="2">PM</option>
                                                                                        </select>
                                                                                    </li>-->
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            
                                                                <div class="form-group" id="commutation" style="padding-top: 20px; display:none;">
                                                                        <label for="">d. <?php echo strtoupper('Commutation'); ?></label>
                                                                        <div  style="padding-left:20px;">
                                                                                <div class="radio">
                                                                                    <label style="margin-left: -20px; cursor: default;">
                                                                                        No. of days applied for 
                                                                                    </label>
                                                                                    <br>VL <input id="vl_days" class="form-control" onkeypress="validatedecimal(event)" type="text" value="0.0" style="margin-top:5px; width: 50%; display: inline-block;">
                                                                                    <br>SL <input id="sl_days" class="form-control" onkeypress="validatedecimal(event)" type="text" value="0.0" style="margin-top:5px; width: 50%; display: inline-block;">
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                            
                                                        </div><!-- /.box-body -->
                                                        
                                                        <div class="box-body" style="padding-left: 30px;">  

                                                                <!-- Hidden Inputs -->
                                                                <input type="hidden" value="1" name="leave_id" id="leave_id"/>
                                                                <input type="hidden" value="" name="log_id" id="log_id"/>
                                                                <!--End of Hidden Inputs --> 
                                                        </div>
                                                        
                                                </div><!-- /. box -->
                                        </div><!-- /.col -->
                                        
                                        
                                        <div class="col-md-8">
                                                <div class="box box-primary">
                                                        <div class="box-body">
                                                                <!-- THE CALENDAR -->
                                                                <div id="calendar"></div>
                                                        </div><!-- /.box-body -->
                                                </div><!-- /. box -->
                                                
                                                <div class="box box-warning">
                                                    
                                                        <div class="box-header with-border">
                                                                <h4 class="box-title">Details of Action on Application</h4>
                                                        </div>
                                                        
                                                    
                                                        <div class="box-body">
                                                                <div id="leave-loader" style="padding-left: 20px;">
                                                                        <div class="form-group">
                                                                                <label>a. <?php echo strtoupper('Certified Leave Credits as of').' '.date("F d, Y"); ?></label>
                                                                        </div>
                                                                        <table class="table table-bordered">
                                                                                <tr>
                                                                                    <th style="width: 33%">Vacation</th>
                                                                                    <th style="width: 33%">Sick</th>
                                                                                    <th style="width: 33%">Total</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                        </table>
                                                                </div>
                                                            
                                                                <div class="form-group" style="margin-bottom: 5px; padding-left: 20px;">
                                                                        <label for="">Certified by:</label>
                                                                        <br/>
                                                                        <div class="btn-group">
                                                                                
                                                                                <?php foreach($signatories as $sig){ 
                                                                                    $signame1_id = $sig->id_employee.' ';
                                                                                    $signame1 = $sig->emp_fname.' ';
                                                                                    $signame1 = $sig->emp_fname.' ';
                                                                                    $signame1 .= @$sig->emp_mname!='' ? $sig->emp_mname[0].'. ' : '';
                                                                                    $signame1 .= $sig->emp_lname.' '. $sig->emp_extname; 
                                                                                    $signame1_position = @$sig->designation_name ? $sig->designation_name : $sig->position_name;
                                                                                    break; 
                                                                                } ?>
                                                                                <input type="hidden" id="certified_by" value="<?php echo $signame1_id; ?>">
                                                                                
                                                                                <button type="button" class="btn btn-info btn-flat" id="cert-official"><span id="cert-txt"><?php echo $signame1; ?></span></button>
                                                                                <button type="button" class="btn btn-info dropdown-toggle btn-flat" data-toggle="dropdown">
                                                                                    <span class="caret"></span>
                                                                                </button>
                                                                                <ul class="dropdown-menu">
                                                                                    <?php foreach($signatories as $sig){ 
                                                                                        $signame_id = $sig->id_employee.' ';
                                                                                        $signame = $sig->emp_fname.' ';
                                                                                        $signame .= @$sig->emp_mname!='' ? $sig->emp_mname[0].'. ' : '';
                                                                                        $signame .= $sig->emp_lname.' '. $sig->emp_extname; ?>
                                                                                        <li><a href="#certified" onclick="setCertified('<?php echo $signame; ?>','<?php echo $signame_id; ?>')">
                                                                                                <?php echo $signame.', ';
                                                                                                echo @$sig->designation_name ? $sig->designation_name : $sig->position_name; ?>
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                        </div>
                                                                </div>
                                                                    
                                                                <div class="form-group" style="padding-top: 20px;">
                                                                        <label for="search_recommendation">b. <?php echo strtoupper('Recommending Official'); ?></label><br/>
                                                                        <div class="btn-group" style="padding-left: 20px;">
                                                                                <button type="button" class="btn btn-info btn-flat" id="rec-official"><span id="rec-txt">Search Recommending Official</span></button>
                                                                                <button type="button" class="btn btn-info dropdown-toggle btn-flat" data-toggle="dropdown">
                                                                                  <span class="caret"></span>
                                                                                </button>
                                                                                <ul class="dropdown-menu" id="recommending_officials_div">
                                                                                    <li>No employee selected.</li>
                                                                                </ul>
                                                                                <input type="hidden" id="recommending_id">
                                                                        </div>
                                                                </div>

                                                                <div class="form-group" style="padding-top: 20px;">
                                                                        <label for="">c. <?php echo strtoupper('APPROVED FOR'); ?></label><br/>
                                                                        <div class="btn-group" style="padding-left: 20px;">
                                                                            <table>
                                                                                <tr>
                                                                                    <td align="center" id="days_wpay_txt" style="border-bottom: 1px solid #999; width: 200px; font-weight: bold;">
                                                                                    </td>
                                                                                    <td>Days with pay</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="center" id="days_nopay_txt" style="border-bottom: 1px solid #999; width: 200px; font-weight: bold;">
                                                                                    </td>
                                                                                    <td>Days without pay
                                                                                        <input type="hidden" value="0" id="vl_nopay">
                                                                                        <input type="hidden" value="0" id="sl_nopay">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="center" id="days_others_txt" style="border-bottom: 1px solid #999; width: 200px; font-weight: bold;">
                                                                                        <input type="hidden" value="0" id="days_others">
                                                                                    </td>
                                                                                    <td>Others</td>
                                                                                </tr>
                                                                                <tr id="leave_wopay_warning" style="display: none;">
                                                                                        <td colspan="2" style="color: #b94a48; font-weight: bold;">Leave without pay will be deducted to the subsequent payroll.</td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group" style="padding-top: 20px;">
                                                                        <label for="search_authorized">d. <?php echo strtoupper('Authorized Official'); ?></label><br/>
                                                                        <div class="btn-group" style="padding-left: 20px;">
                                                                                <button type="button" class="btn btn-info btn-flat" id="auth-official"><span id="auth-txt">Search Authorized Official</span></button>
                                                                                <button type="button" class="btn btn-info dropdown-toggle btn-flat" data-toggle="dropdown">
                                                                                  <span class="caret"></span>
                                                                                </button>
                                                                                <ul class="dropdown-menu" id="authorized_officials_div">
                                                                                    <li>No employee selected.</li>
                                                                                </ul>
                                                                                <input type="hidden" id="authorized_id">
                                                                                
                                                                        </div>
                                                                </div>


                                                                <div class="form-group" style="text-align: right; margin-right: 20px; min-height: 150px">
                                                                        <button class="btn bg-green btn-flat" id="savebtn"><i class="glyphicon glyphicon-save"></i> Submit</button>
                                                                        <button class="btn bg-teal btn-flat" data-toggle="modal" data-target="#myModal" id="modalbtn" style="display: none;"><i class="glyphicon glyphicon-print"></i> Print</button>
                                                                        <!--<button class="btn bg-aqua btn-flat" id="refreshbtn" style="display:none"><i class="glyphicon glyphicon-refresh"></i> Reload</button>-->
                                                                		<br><br><br><br>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div><!-- /.col -->
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="myModal" role="dialog">
                                                <div class="modal-dialog">

                                                  <!-- Modal content-->
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                      <h4 class="modal-title">Leave Submitted.</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div id="disapproveLoader">
                                                            <div class="leave-text-group" style="float: none;">
                                                                <p>Your leave is submitted and waiting for approval.</p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-default" id="closeModal" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>  Close</button>
                                                      <button type="button" class="btn btn-success" onclick="printtopdf(2)"><i class="glyphicon glyphicon-save"></i>  Save to PDF</button>
                                                      <button type="button" class="btn btn-success" onclick="printtopdf(1)"><i class="glyphicon glyphicon-print"></i>  Print</button>
                                                    </div>
                                                  </div>

                                                </div>
                                        </div>  
                                                          
                                </section><!-- /.content -->
                                               
                        </div>
		</div>
	</section>
</section>

<?php $this->load->view('dashboard/footer'); ?>

<link href="<?php echo jquery('loadmask/jquery.loadmask.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo jquery('loadmask/jquery.loadmask.min.js'); ?>"></script>

<script src="<?php echo jquery('myscript/leave.js'); ?>"></script>

<script type="text/javascript">
        $(document).ready(function(){
                
                load_credits_url = '<?php echo site_url('attendance/leave/load_profile_credits'); ?>';

                
                $('#vacation_reason').hide();

                $('#leave_spent').hide();        
                $('#vacation_location').hide();        
                $('#sick_location').hide();

                $('#commutation').hide();

                $('#leave-label').hide();
                $('#total_days_div').hide();
                
                select_employee('94-0402','Luis Alejandre Ibasco Tamani ','1010002285');
                
        });

        $('#search_employee').keyup(_.debounce(search_employee , 1000));
        
        function search_employee () {
            var val = $('#search_employee').val();
            
            if(val!=""){
                $('#searchProfile').slideDown();
                $('#searchProfile').load('<?php echo site_url('attendance/leave/search_employee'); ?>',{search : val});
            }else{
                $('#searchProfile').slideUp();
            }
        }

        function select_employee(emp_idno, fullname, id_employee){
        
            $('#emp_idno').val(emp_idno);
            $('#employee_id').val(id_employee);
            $('#search_employee').val(fullname);
            $('#searchProfile').slideUp();
            $('#profile-loader').html("Loading...");
            $('#profile-loader').load('<?php echo site_url('attendance/leave/load_profile_info'); ?>',{id : emp_idno});  
            $('#leave-loader').load('<?php echo site_url('attendance/leave/leave_credits_loader'); ?>',{employee_id : id_employee});
            $('#authorized_officials_div').load('<?php echo site_url('attendance/leave/load_authorized_officials'); ?>',{employee_id : id_employee});
            $('#recommending_officials_div').load('<?php echo site_url('attendance/leave/load_recommending_officials'); ?>',{employee_id : id_employee});
            //load_leave_credits(id_employee);
            $('#leave_types_div').load('<?php echo site_url('attendance/leave/load_leave_types'); ?>');  
        }

        function select_employee2()
        {
            var str = $("#employee_dropdown").val();
            var res = str.split("_");
            select_employee(res[0], res[1], res[2]);
        }
        
    
        
        $('#savebtn').click(function() {
                $('#leave_form_page').mask('Saving... Please wait.');
//                $.ajax({
//                    url: '<?php // echo site_url('attendance/leave/set_log_id'); ?>',
//                    success:function(result){
//                        $('#log_id').val(result);
//                        save_leave();
//                    }
//                });
                save_leave();
        });
        
        function save_leave(){
        
                //Variables
                var myEvents = $('#calendar').fullCalendar('clientEvents');
                var error = 0;
                var errorTxt = '';
                
                var post_data = {};
                var leave_id = $('#leave_id').val();
                post_data['leave_id'] = leave_id;
                //post_data['log_id'] = $('#log_id').val();
                var emp_idno = $('#emp_idno').val();
                post_data['emp_idno'] = emp_idno;
                var date_filing = $('#date_filing').val();
                post_data['date_filing'] = date_filing;
                post_data['employee_id'] = $('#employee_id').val();
                post_data['certified_by'] = $('#certified_by').val();
                post_data['recommending_id'] = $('#recommending_id').val();
                var authorized_id = $('#authorized_id').val();
                post_data['authorized_id'] = authorized_id;
                
                post_data['slp_reason'] = $('#slp_reason').val();
                post_data['vl_wpay'] = $('#vl_wpay').val();
                post_data['sl_wpay'] = $('#sl_wpay').val();
                
                post_data['vl_nopay'] = $('#vl_nopay').val();
                post_data['sl_nopay'] = $('#sl_nopay').val();
                
                post_data['credits_vl'] = $('#credits_vl').html();
                post_data['credits_sl'] = $('#credits_sl').html();
                post_data['credits_total'] = $('#credits_total').html();
                post_data['leave_credits_remarks'] = $('#leave_credits_remarks').html();
                
                var days = 0;
                var x = 0;
                //End of Variables 
                
                //------------------------------------------------vacation-leave----------
                post_data['vac_reason'] = '';
                post_data['vac_others'] = $('#vacation_others').val();
                post_data['vac_spent'] = '';
                post_data['abroad'] = $('#abroad').val();
                if(leave_id==1){
                    if($('#vacation_reason1').is(':checked')){
                        post_data['vac_reason'] = $('#vacation_reason1').val();
                    }else if($('#vacation_reason2').is(':checked')){
                        post_data['vac_reason'] = $('#vacation_reason2').val();
                    }
                
                    if($('#spent_vacation1').is(':checked')){
                        post_data['vac_spent'] = $('#spent_vacation1').val();
                    }else if($('#spent_vacation2').is(':checked')){
                        post_data['vac_spent'] = $('#spent_vacation2').val();
                    }
                }
                
                //------------------------------------------------sick-leave----------
                post_data['sick_location'] = '';
                post_data['hospital'] = $('#hospital').val();
                post_data['patient'] = $('#patient').val();
                if(leave_id==2){
                    if($('#sick_location1').is(':checked')){
                        post_data['sick_location'] = $('#sick_location1').val();
                    }else if($('#sick_location2').is(':checked')){
                        post_data['sick_location'] = $('#sick_location2').val();
                    }
                }
                
                //-------------------------------------------------------------error-trapping---------   
                if(emp_idno==""){
                        error++;
                        errorTxt = errorTxt+'Employee is Required.<br>';
                } else {
                    if(date_filing==''){
                        error++;
                        errorTxt = errorTxt+'Date of filing is required.<br>';
                    } if(leave_id!=8 && myEvents.length==0){
                        error++;
                        errorTxt = errorTxt+'No leave placed in calendar.<br>';
                    } if(leave_id==6 && $('#slp_reason').val()==''){
                        error++;
                        errorTxt = errorTxt+'SLP Reason is Required.<br>';
                    } if(authorized_id==""){
                        error++;
                        errorTxt = errorTxt+'Authorized Official is Required.<br>';
                    } if(leave_id==8){
                        if($('#total_days').val()==""){
                            error++;
                            errorTxt = errorTxt+'Monetized Days is Required.<br>';
                        }
                    }
                }
                        
                if(error==0){
                            
                        $('#error1').html('');
                        $('#error1').hide();
                        
                        //Saving of events to database  
                        days =$('#total_days').val();
                        post_data['days'] = days;
                        $.ajax({
                                url: '<?php echo site_url('attendance/leave/saveleave'); ?>',
                                type: 'POST',
                                data: post_data,
                                success:function(result){

                                    if(leave_id==8){
                                        if(result!='0'){
                                            x++;
                                            $('#log_id').val(result)
                                            show_result(x)
                                        } else {
                                            x=0;
                                            show_result(x)
                                        }
                                    } else {
                                        if(result!='0'){
                                            $('#log_id').val(result)
                                            save_leave_dates(result);
                                        } else {
                                            x=0;
                                            show_result(x)
                                        }
                                    }

                                }
                        });
                } else {
                    $('#error1').html(errorTxt);
                    $('#error1').show();
                    
                    $('html, body').animate({
                        scrollTop: ($("#error1").offset().top,80),
                    }, 1000);
                    
                    $('#leave_form_page').unmask();
                }
        }
        
        function save_leave_dates(leave_id)
        {
                var myEvents = $('#calendar').fullCalendar('clientEvents');
                var days = 0;
                var y = 0;
                
                var post_data = {};
                post_data['leave_id'] = leave_id;
                for(var i in myEvents){
                        days = 0;

                        var start = myEvents[i].start.format();
                        if(jQuery.type(myEvents[i].end) === 'null'){
                             var end = myEvents[i].start.format();
                             days=1;
                        }else{
                                var start_date = new Date(myEvents[i].start.format());
                                start_date = start_date.toString().substr(0,15);
                                var end_date = new Date(myEvents[i].end.format());
                                end_date.setDate(end_date.getDate() - 1);
                                end_date = end_date.toString().substr(0,15);
                                var newdate = new Date(myEvents[i].start.format());
                                var newdate1 = new Date(myEvents[i].end.format());
                                newdate1.setDate(newdate1.getDate() - 1);
                                var end = moment(newdate1).format('YYYY-MM-DD');
                                while(start_date!=end_date){
                                        var nd = new Date(newdate);
                                        var a = newdate.toString().substr(0, 3);
                                        if(a!="Sat" && a!="Sun"){
                                              days++;
                                              start_date = new Date(newdate);
                                              start_date = start_date.toString().substr(0,15);
                                        }  
                                        newdate.setDate(newdate.getDate() + 1); 
                                }
                        }


                        var eventid = myEvents[i]._id.replace("_fc", "");

                        var checkelem = document.getElementById('from_hd_'+eventid);
                        if (checkelem === null){ }
                        else {
                            if($('#from_hd_'+eventid).val()!=0){
                                days = parseFloat(days)-0.5;
                            }
                        }

                        var checkelem2 = document.getElementById('to_hd_'+eventid);
                        if (checkelem2 === null){ }
                        else {
                            if($('#to_hd_'+eventid).val()!=0){
                                days = parseFloat(days)-0.5;
                            }
                        }

                        post_data['date_from_ishalf'] = $('#from_hd_'+ eventid).val();
                        post_data['date_to_ishalf'] = $('#to_hd_'+ eventid).val();

                        post_data['days'] = days;
                        post_data['start'] = start;
                        post_data['end'] = end;

                        if(leave_id==3){
                            days = $('#total_days').val();
                        }

                        $.ajax({
                                url: '<?php echo site_url('attendance/leave/saveleave_dates'); ?>',
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                        if(result=='1'){
                                            y++;
                                            show_result(y)
                                        } else {
                                            y=0;
                                            show_result(y)
                                        }

                                }
                        });
                }
        }
            
        
        
        function show_result(x)
        {
                $('#leave_form_page').unmask();
                if(x!='0'){
                    $('#modalbtn').trigger('click');
                    $('#savebtn').hide();
//                    $('#modalbtn').show();
//                    $('#refreshbtn').show();
                    $('#errorLeave').hide();
                } else {
                    $('#error1').html('Error! Something happened during saving.');
                    $('#error1').show();
                    
                    $('html, body').animate({
                        scrollTop: ($("#error1").offset().top,80),
                    }, 1000);
                }
        }
        
        $('#closeModal').click(function() {
                window.location.replace("<?php echo site_url('attendance/leave/'); ?>");
        });
    
        function printtopdf(num)
        {
                window.open("<?php echo site_url('attendance/leave/download'); ?>/" + $('#log_id').val() + "/" + num);
                
                window.location.replace("<?php echo site_url('attendance/leave/'); ?>");
        }
        
        $(function () {
            
                /* initialize the external events
                -----------------------------------------------------------------*/
                function ini_events(ele) {
                        ele.each(function () {

                                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                                // it doesn't need to have a start or end
                                var eventObject = {
                                  title: $.trim($(this).text()) // use the element's text as the event title
                                };

                                // store the Event Object in the DOM element so we can get to it later
                                $(this).data('eventObject', eventObject);

                                // make the event draggable using jQuery UI
                                $(this).draggable({
                                  zIndex: 1070,
                                  revert: true, // will cause the event to go back to its
                                  revertDuration: 0  //  original position after the drag
                                });
                        });
                }
                
                ini_events($('#external-events div.external-event'));

                /* initialize the calendar
                 -----------------------------------------------------------------*/
                //Date for the calendar events (dummy data)
                var date = new Date();
                var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
                
                $('#calendar').fullCalendar({
                        header: {
                            left: 'prev ,next today',
                            center: 'title',
                            right: ''
                        },
                        buttonText: {
                            today: 'today'
                        },
                        //Random default events
                        events: [],
                        editable: true,
                        weekends : false,
                        droppable: true,
                        eventDrop: function(event, delta, revertFunc, date) {
                            
                                //$('#external-events').slideUp();
                                
                                var leave_id = $('#leave_id').val();
                                if(leave_id==2){
                                        var datenow = new Date(Date.now());
                                        var day = datenow.getDay();
                                        var sick_cutoff = 10; //sick leave application date before
                                        if(day==1){
                                            var newDate = Date.now() - 4*(12 * sick_cutoff)*3600*1000; // date 4 days ago in milliseconds UTC
                                        } else if(day==2){
                                            var newDate = Date.now() - 5*(12 * sick_cutoff)*3600*1000; // date 5 days ago in milliseconds UTC
                                        } else {
                                            var newDate = Date.now() - 2*(12 * sick_cutoff)*3600*1000; // date 2 days ago in milliseconds UTC
                                        }
                                        //var newDate = Date.now() - 2*24*3600*1000; // date 2 days ago in milliseconds UTC
                                }else{
                                        var newDate = Date.now() - 1*24*3600*1000; // date 1 days ago in milliseconds UTC
                                }
                                
                                var title = event.title;
                                var start = event.start.format();
                                var end = event.end;
                                
                                if(end==null){
                                        end = Date.parse(event.start);
                                } else {
                                        end = end - 1*24*3600*1000; // date 1 days ago in milliseconds UTC
                                }
                                
                                //alert(end+' end : ' +Date.parse(end)+' 1day before now :' + newDate);

                                if(end<newDate){
                                        alert("You cannot apply leave for this day.");
                                        $('#calendar').fullCalendar( 'removeEvents', event._id );
                                }else{

                                }
                                compute_total_days();
                                //alert(start);
                        },
                        eventResize: function(event, delta,jsEvent, revertFunc) {
                                var title = event.title;
                                var end = event.end.format();
                                var start = event.start.format();
                              
                                compute_total_days();

                        },
                        eventClick: function(event) {
                                if (confirm("Delete this date?") == true) {
                                        $('#calendar').fullCalendar( 'removeEvents', event._id );
                                          //$('#external-events').slideDown();
                                          //alert(event._id);
                                        var evid = event._id.replace("_fc", "");
                                        $("#new_date" + evid).remove();
                                        compute_total_days();
                                }
                        },
                        drop: function (date, allDay) { // this function is called when something is dropped
                            
                                //$('#external-events').slideUp();

                                var leave_id = $('#leave_id').val();
                                var error = 0;
                                var myEvents = $('#calendar').fullCalendar('clientEvents');

                                if(leave_id==2){ 

                                        var datenow = new Date(Date.now());
                                        var day = datenow.getDay();
                                        var sick_cutoff = 10; //sick leave application date before
                                        if(day==1){
                                            var newDate = Date.now() - 4*(12 * sick_cutoff)*3600*1000; // date 2 days ago in milliseconds UTC
                                        } else if(day==2){
                                            var newDate = Date.now() - 5*(12 * sick_cutoff)*3600*1000; // date 2 days ago in milliseconds UTC
                                        } else {
                                            var newDate = Date.now() - 2*(12 * sick_cutoff)*3600*1000; // date 2 days ago in milliseconds UTC
                                        }

                                        if(myEvents.length>=1){
                                            alert('You only allow to apply one sick leave');
                                            error++;
                                        }
                                }else{ 
                                        var newDate = Date.now() - 1*24*3600*1000; // date 1 days ago in milliseconds UTC
                                }
                                //alert(date.format());

                                for(var i in myEvents){
                                        if(date.format()!=myEvents[i].start.format()){

                                        }else{
                                            error++;
                                            alert('You can only file one(1) leave per day.');
                                        }
                                }
                            
                                if(error==0){
                                        if(date<newDate){
                                            alert("You cannot apply leave for this day.22");
                                        }else{

                                            // retrieve the dropped element's stored Event Object
                                            var originalEventObject = $(this).data('eventObject');

                                            // we need to copy it, so that multiple events don't have a reference to the same object
                                            var copiedEventObject = $.extend({}, originalEventObject);

                                            // assign it the date that was reported
                                            var tempDate = new Date(date);  //clone date
                                            copiedEventObject.start = date;
                                            if(leave_id==3){
                                                copiedEventObject.end = new Date(tempDate.setHours(tempDate.getHours()+1440)); // <-- make sure we assigned a date object

                                            }   
                                            copiedEventObject.allDay = allDay;
                                            copiedEventObject.backgroundColor = $(this).css("background-color");
                                            copiedEventObject.borderColor = $(this).css("border-color");

                                            // render the event on the calendar
                                            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                                            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                                            // is the "remove after drop" checkbox checked?


                                            if ($('#drop-remove').is(':checked')) {
                                                // if so, remove the element from the "Draggable Events" list
                                                $(this).remove();
                                            }

                                        }
                                }
                                compute_total_days();
                        }
                });

                /* ADDING EVENTS */
                var currColor = "#3c8dbc"; //Red by default
                //Color chooser button
                var colorChooser = $("#color-chooser-btn");
                
                $("#color-chooser > li > a").click(function (e) {
                    e.preventDefault();
                    //Save color
                    currColor = $(this).css("color");
                    //Add color effect to button
                    $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
                });
                
                $("#add-new-event").click(function (e) {
                    e.preventDefault();
                    //Get value and make sure it is not null
                    var val = $("#new-event").val();
                    if (val.length == 0) {
                        return;
                    }

                    //Create events
                    var event = $("<div />");
                    event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
                    event.html(val);
                    $('#external-events').prepend(event);

                    //Add draggable funtionality
                    ini_events(event);

                    //Remove event from text input
                    $("#new-event").val("");
                });
        });
</script>	