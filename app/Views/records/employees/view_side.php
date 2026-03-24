<?php $request = \Config\Services::request(); ?>

    <?php if(@$basic){
    foreach($basic as $bas){ ?>

            <div class="user-heading round">

                    <input type="hidden" id="employee_id" value="<?php echo $bas->id_employee; ?>">
                    <input type="hidden" id="emp_idno" value="<?php echo $bas->emp_idno; ?>">


                    <div id="profilepix">
                        
                            <?php if(@file_get_contents(images('profile/'.$bas->profile_picture)) && $bas->profile_picture!=''){ ?>
                        
                                <img src="<?php echo images('profile/'.$bas->profile_picture); ?>" alt="">
                                
                            <?php } else { ?>
                                
                                <img src="<?php echo @$bas->emp_gender=='Male' ? images('icon_m2.png') : images('icon_f1.png'); ?>" alt="">
                                
                            <?php } ?>
                                
                                
                            <?php if(@$access->edit || $request->uri->getSegment(1)=='myprofile'){
                                $update_picture_url =  session()->get('access_level')!=1 ? site_url('myprofile/update_picture') : site_url('employees/update_picture/'.$bas->id_employee); ?>
                                
                                <a href="<?php echo $update_picture_url; ?>" title="Update Profile Picture">
                                    <div id="change_dp_btn">
                                        <div class="">
                                            <i class="icon icon-camera"></i>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
                    </div>
                    
                    <p style="color: #33b2e5; font-size: 14px; font-weight: normal;"><?php echo $bas->emp_idno; ?></p>
                    <p><?php echo $bas->emp_email_official; ?></p>
                    <p><?php echo $bas->emp_email_personal; ?></p>
                    <p><?php echo @$bas->emp_cpno; ?></p>
                    
                    <!--
                    <hr>
                    <p style="color: #33b2e5; font-size: 18px; font-weight: bold;">&#8369; 1,234,560.00</p>
                    <p style="color: #33b2e5; font-size: 14px; font-weight: normal;">
                        Your earned balance monetary equivalent as of this day.
                    </p>
                    -->

                    <br/>

                    <div class="row" style="text-align: left;">    


                            <div class="col-lg-12" style="text-align: center;">

                                <?php if((($request->uri->getSegment(1)!='employees' && $request->uri->getSegment(2)!='view') && $request->uri->getSegment(1)!='myprofile') || ( $request->uri->getSegment(1)=='employees' && $request->uri->getSegment(2)!='view')){ ?>

                                    <a href="<?php echo site_url('employees/view/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                        <button class="btn btn-xs btn-info">Profile</button>
                                    </a>

                                <?php }

                                if(@$access2->view && $request->uri->getSegment(2)!='servicerecords' ){ ?>

                                            <?php if($bas->id_employment!=''){ ?>
                                                <a href="<?php echo site_url('employees/servicerecords/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:5px; display:inline-block;">
                                                    <button class="btn btn-xs btn-primary">Service Record</button>
                                                </a>
                                            <?php } else if($bas->id_employment==''){ ?>
                                                <a href="<?php echo site_url('employees/service_records/add/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                                    <button class="btn btn-xs btn-primary">Update Position</button>
                                                </a>
                                            <?php } ?>

                                <?php }

                                if($request->uri->getSegment(2)!='designations' ){ ?>

                                        <a href="<?php echo site_url('employees/designations/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                            <button class="btn btn-xs btn-success">Designations</button>
                                        </a>

                                <?php }

                                if($request->uri->getSegment(2)!='dtr' ){  ?>
                                
                                        <a href="<?php echo site_url('employees/dtr/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                            <button class="btn btn-xs btn-warning" style="">DTR</button>
                                        </a>
                                
                                <?php }

                                if($request->uri->getSegment(2)!='viewfiles' ){   ?>
                                
                                        <a href="<?php echo site_url('employees/viewfiles/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                            <button class="btn btn-xs btn-info">Files</button>
                                        </a>
                                
                                <?php }

                                if($request->uri->getSegment(2)!='awards' ){ ?>
                                
                                        <a href="<?php echo site_url('employees/awards/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:5px; display:inline-block;">
                                            <button class="btn btn-xs btn-primary">Awards</button>
                                        </a>
                                
                                <?php }

                                if($request->uri->getSegment(2)!='ipcr' && ($bas->status_is_service==1)){  ?>

                                        <a href="<?php echo site_url('employees/ipcr/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                            <button class="btn btn-xs btn-success" style="">IPCR</button>
                                        </a>
                                
                                <?php }

                                if($request->uri->getSegment(2)!='trainings'){  ?>

                                        <a href="<?php echo site_url('employees/trainings/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                            <button class="btn btn-xs btn-warning" style="">Trainings</button>
                                        </a>
                                
                                <?php } ?>
                                
                                <?php /*
                                <a href="<?php echo site_url('employees/servicerecords/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                    <button class="btn btn-xs btn-info">Plantilla</button>
                                </a>

                                <a href="<?php echo site_url('employees/service_records/summary/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:3px; display:inline-block;">
                                    <button class="btn btn-xs btn-primary" style="">Payroll</button>
                                </a>



                                <a href="<?php echo site_url('employees/service_records/summary/'.$bas->id_employee); ?>" style="margin-right:3px; margin-bottom:5px; display:inline-block;">
                                    <button class="btn btn-xs btn-success">IDP</button>
                                </a>
                                 * 
                                 */ ?>


                            </div>

                            <br>
                            <br>


                            <div class="bio-row2">
                                
                                    <table border="0" style="width:100%; font-size: 12px;">
                                        <tr>
                                            <td style="width:30%; vertical-align: top;">Biometrics ID:</td>
                                            <td style="width:70%; vertical-align: top;"><?php echo @$bas->biometrics_id; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 8px; vertical-align: top;">Date Hired:</td>
                                            <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo @$bas->emp_date_hired ? date('F j, Y', strtotime($bas->emp_date_hired)) : ''; ?></td>
                                        </tr>
                                        
                                        <?php if(@$bas->emp_position_date!='' && @$bas->emp_position_date!='1900-01-01'){ ?>
                                            <tr>
                                                <td style="padding-top: 8px; vertical-align: top;">Last Salary adjustment:</td>
                                                <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo date('F j, Y', strtotime($bas->emp_position_date)); ?></td>
                                            </tr>
                                        <?php } ?>
                                            
                                        <tr>
                                            <td style="padding-top: 8px; vertical-align: top;">Status:</td>
                                            <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo $bas->status_name; ?></td>
                                        </tr>
                                        
                                        <?php if(@$designation){ ?>
                                            <tr>
                                                <td style="padding-top: 8px; vertical-align: top;">Designation:</td>
                                                <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo $designation; ?></td>
                                            </tr>
                                        <?php } ?>
                                            
                                        <tr>
                                            <td style="padding-top: 8px; vertical-align: top;">Position:</td>
                                            <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo $bas->position_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 8px; vertical-align: top;">Program:</td>
                                            <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo $bas->program_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 8px; vertical-align: top;">Office:</td>
                                            <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo $bas->office_abbr; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 8px; vertical-align: top;">Division:</td>
                                            <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo @$bas->division_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 8px; vertical-align: top;">Unit:</td>
                                            <td style="padding-top: 8px; padding-left: 8px; vertical-align: top;"><?php echo @$bas->unit_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 15px; vertical-align: top;">Last updated:</td>
                                            <td style="padding-top: 15px; padding-left: 8px; vertical-align: top;">
                                                <?php if(@$bas->emp_date_updated!=''){
                                                    echo date('F j, Y h:ia',strtotime(@$bas->emp_date_updated));
                                                    echo '<br><em>by ';
                                                    echo @$updated_by[0]->emp_fname!='' ? @$updated_by[0]->emp_fname.' '.@$updated_by[0]->emp_mi.' '.@$updated_by[0]->emp_lname : @$updated_by[0]->username;
                                                    echo '</em>';
                                                } else echo '---'; ?>
                                            </td>
                                        </tr>
                                        
                                            
                                    </table>
                            </div>



                    </div>
            </div>

    <?php }
    } else { ?>

            <div class="user-heading round">
                    <a><img src="<?php echo images('icon_m2.png'); ?>"></a>                                                        
                    <br/>
                    <br/>
                    <br/>                                                        
                    <p style="color: #33b2e5; font-size: 14px; font-weight: normal;">No employee record exists.</p>
                    <div class="row" style="text-align: left;">                                                    
                            <div class="bio-row2"></div>
                    </div>
            </div>

    <?php } ?>