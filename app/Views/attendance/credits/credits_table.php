



<table class="table table-hover" id="sample_1">
         <thead>
                <tr>
                        <th style="text-align: right; width: 3%;"><label>#</label></th>
                        <th style="width: 12%;"><label class="sort" id="emp_lname" title="Sort by Last Name"><i class="icon-user"></i> Employee Name</label></th>
                        <th style="width: 10%;"><label class="sort" id="leave_credits.emp_idno" title="Sort by ID No."><i class="icon-info"></i> ID #</label></th>
                        <th style="width: 13%;">Position</th>
                        <th style="width: 5%;">Program</th>
                        <th style="width: 5%;">Office</th>
                        <th style="width: 5%;">Division</th>
                        <th style="width: 5%;">Unit</th>
                        <th style="width: 5%;"><label class="sort" id="vl" title="Sort by Vacation Leave">VL</label></th>
                        <th style="width: 5%;"><label class="sort" id="sl" title="Sort by Sick Leave">SL</label></th>
                        <th style="width: 5%;"><label class="sort" id="slp" title="Sort by Special Leave Privilege">SLP</label></th>
                        <th style="width: 10%;"><label class="sort" id="slp" title="Sort by Service Credits">Service Credit</label></th>
                        <th style="width: 10%;"><label class="sort" id="last_updated" title="Sort by Updated Date">Date Updated</label></th>
                        <th style="width: 7%;"><label>Actions</label></th>
                </tr>
        </thead>
        <tbody>
                <?php
                $num = $details['aa'];
                $count0 = 0;
                if(@$records){
                foreach($records as $row){
                        $count0++; 
                        $num++;
                        ?>
                        <tr class="odd gradeX">
                                <td align='right' style=""><?php echo $num; ?></td>
                                <td><?php $employee = $row->employee;  ?>
                                    <a href="<?php echo site_url('credits/view/'.$row->employee_id.'/'.date('Y')); ?>" class="employee_name" title="View Leave Report">
                                        <?php echo strtoupper(@$row->employee[0]->emp_lname).', '.@$row->employee[0]->emp_fname; 
                                        echo @$row->employee[0]->emp_extname!='' ? ' '.@$row->employee[0]->emp_extname : ''; 
                                        echo ' '. @$row->employee[0]->emp_mname; ?>
                                    </a>
                                </td>
                                <td><?php echo $row->emp_idno; ?></td>
                                <td><?php echo @$row->employee[0]->position_name ? $row->employee[0]->position_name : '<na>---</na>'; ?></td>
                                
                                <td><?php echo @$row->employee[0]->program_abbr ? @$row->employee[0]->program_abbr : @$row->employee[0]->program_name; ?></td>
                                <td><?php echo @$row->employee[0]->office_abbr ? @$row->employee[0]->office_abbr : @$row->employee[0]->office_name; ?></td>
                                <td><?php $emp_division = @$row->employee[0]->division_abbr ? @$row->employee[0]->division_abbr : @$row->employee[0]->division_name;
                                        echo @$emp_division!='' ? $emp_division : '<na>---</na>'; ?>
                                </td>
                                <td><?php echo @$row->employee[0]->unit_name ? @$row->employee[0]->unit_name : '<na>---</na>'; ?></td>
                                
                                <td><?php echo $row->vl; ?></td>
                                <td><?php echo $row->sl; ?></td>
                                <td><?php echo $row->slp; ?></td>
                                <td><?php echo $row->service>0 ? $row->service : 0; ?></td>
                                <td><?php echo date('F j, Y', strtotime($row->last_updated));
                                    echo '<br>';
                                    echo date('h:i A', strtotime($row->last_updated)); ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('credits/view/'.$row->employee_id.'/'.date('Y')); ?>" title="View">
                                        <button class="btn btn-primary btn-xs" style=""><i class="icon-list-alt"></i> View</button>
                                    </a>
                                    <?php /*if($access->edit){ ?>
                                            <a href="<?php echo site_url('attendance/leave/credits_edit/'.$row->employee_id.'/'.$row->emp_idno); ?>" title="Update">
                                                <button class="btn btn-success btn-xs action_btn" style=""><i class="icon-pencil"></i> Edit</button>
                                            </a>
                                    <?php }*/ ?>
                                </td>
                        </tr>
                <?php }
                } else { ?>
                        <tr>
                            <td colspan="14" style="font-style: italic;">
                                <?php if(session()->get('credits_search')){ ?>
                                    Your search did not match any record.
                                    <br>Try different keywords or remove search filters.
                                <?php } else { ?>
                                    No record.
                                <?php } ?>
                            </td>
                        </tr>
                <?php } ?>
        </tbody>
</table>
                

                                 

<?php $details['num'] = $count0; ?>
<?= view('layout/mytable/my_table_pagination',$details); ?>