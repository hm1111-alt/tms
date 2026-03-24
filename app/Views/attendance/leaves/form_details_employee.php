<?php
// Get the request service
$request = \Config\Services::request();
?>

                    <div class="container mb-2">
                            <div class="row">

                                    <label class="col col-lg-3" style="padding-top:0px;"><b>Employee</b></label>
                                    <div class="col col-lg-9">
                                        <?php //$fullname = @$leave[0]->emp_fullname;
                                        $fullname = strtoupper(@$leave[0]->emp_lname).', ';
                                        $fullname .= ucfirst(@$leave[0]->emp_fname).' ';
                                        $fullname .= @$leave[0]->emp_extname!='' ? @$leave[0]->emp_extname.' ' : '';
                                        $fullname .= @$leave[0]->emp_mname!='' ? @$leave[0]->emp_mname.' ' : '';
                                        echo $fullname;
//                                        echo 'Juan dela Cruz '; // for live demo testing
                                        ?>
                                    </div>
                            </div>
                    </div>
                    <div class="container mb-2">

                            <div class="row">
                                    <label class="col col-lg-3" style="padding-top:0px;"><b>ID No.</b></label>
                                    <div class="col col-lg-9">
                                        <?php echo @$leave[0]->emp_idno;
//                                        echo '00-1201'; // for live demo testing
                                        ?>
                                    </div>
                            </div>
                    </div>
                    <div class="container mb-2">

                            <div class="row">
                                <label class="col col-lg-3" style="padding-top:0px;">Position</label>
                                <div class="col col-lg-9">
                                    <?php echo @$leave[0]->position_name;
//                                        echo 'Administrative Officer '; // for live demo testing
                                    ?>
                                </div>
                            </div>
                    </div>
                    <div class="container mb-2">

                            <div class="row">
                                <label class="col col-lg-3" style="padding-top:0px;">Monthly Salary</label>
                                <div class="col col-lg-9">
                                    <input type="hidden" id="monthly_salary_val" value="<?php echo @$leave[0]->monthly_salary; ?>">
                                    <?php echo number_format(@$leave[0]->monthly_salary, 2, '.', ',');
//                                        echo '12,345.00 '; // for live demo testing
                                        ?>
                                </div>
                            </div>
                    </div>
                    <div class="container mb-2">

                            <div class="row">
                                <label class="col col-lg-3" style="padding-top:0px;">Reference no.</label>
                                <div class="col col-lg-9" id="leave_refno_lbl">
                                    <?php echo @$leave[0]->leave_refno; ?>
                                </div>
                            </div>
                    </div>

                    <?php if($request->uri->getSegment(2)!='view'){ ?>
                        <div class="container mb-2">

                                <div class="row">
                                    <label class="col col-lg-3" style="padding-top:0px;">Status</label>
                                    <div class="col col-lg-9">
                                        <?php echo @$leave[0]->is_draft!=1 ? @$leave[0]->leave_status_name : '<em>(Draft)</em>'; ?>
                                    </div>
                                </div>
                        </div>
                    <?php } ?>


