



<table class="table table-hover" id="sample_1">
         <thead>
                <tr>
                        <th style="text-align: right; width: 3%;"><label>#</label></th>
                        <th style="width: 15%;"><label class="sort" id="emp_lname" title="Sort by Last Name"><i class="icon-user"></i> Employee Name</label></th>
                        <th style="width: 10%;"><label class="sort" id="leave_credits.emp_idno" title="Sort by ID No."><i class="icon-info"></i> ID #</label></th>
                        <th style="width: 10%;"><label class="sort" id="status_name" title="Sort by Status">Status</label></th>
                        <th style="width: 17%;"><label class="sort" id="position_name" title="Sort by Position">Position</label></th>
                        <th style="width: 7%;"><label class="sort" id="program_name" title="Sort by Program">Program</label></th>
                        <th style="width: 6%;"><label class="sort" id="office_name" title="Sort by Office">Office</label></th>
                        <th style="width: 6%;"><label class="sort" id="division_name" title="Sort by Division">Division</label></th>
                        <th style="width: 6%;"><label class="sort" id="unit_name" title="Sort by Unit">Unit</label></th>
                        <th style="width: 10%;"><label class="sort" id="last_updated" title="Sort by Updated Date">Date Updated</label></th>
                        <th style="width: 10%;"><label>Actions</label></th>
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
                                <td><a href="<?php echo site_url('credits/view/'.@$row->id_employee.'/'.date('Y')); ?>" class="employee_name" title="View Leave Report">
                                        <?php echo strtoupper(@$row->emp_lname).', '.@$row->emp_fname; 
                                        echo @$row->emp_extname!='' ? ' '.@$row->emp_extname : ''; 
                                        echo @$row->emp_mname!='' ?  ' '.@$row->emp_mname : ' '.@$row->emp_mi; ?>
                                    </a>
                                </td>
                                <td><?php echo @$row->emp_idno; ?></td>
                                <td><?php echo @$row->status_name ? @$row->status_name : '<na>---</na>'; ?></td>
                                <td><?php echo @$row->position_name ? @$row->position_name : '<na>---</na>'; ?></td>
                                
                                <td><?php echo @$row->program_abbr ? @$row->program_abbr : @$row->program_name; ?></td>
                                <td><?php echo @$row->office_abbr ? @$row->office_abbr : @$row->office_name; ?></td>
                                <td><?php $emp_division = @$row->division_abbr ? @$row->division_abbr : @$row->division_name;
                                        echo @$emp_division!='' ? $emp_division : '<na>---</na>'; ?>
                                </td>
                                <td><?php echo @$row->unit_name ? @$row->unit_name : '<na>---</na>'; ?></td>
                                
                                <td><?php echo date('F j, Y', strtotime(@$row->emp_date_updated.' - 20days'));
                                    echo '<br>';
                                    echo date('h:i A', strtotime(@$row->emp_date_updated)); ?>
                                </td>
                                <td class="td-actions">
                                    <a href="<?php echo site_url('employees/view/'.@$row->id_employee); ?>" title="View" class="btn btn-primary btn-xs" role="button">
                                        <i class="fas fa-search"></i> View
                                    </a>
                                    <a href="<?php echo site_url('employees/edit/'.@$row->id_employee); ?>" title="Edit" class="btn btn-success btn-xs" role="button">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                    <?php /*if($access->edit){ ?>
                                            <a href="<?php echo site_url('attendance/leave/credits_edit/'.@$row->id_employee.'/'.@$row->emp_idno); ?>" title="Update">
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