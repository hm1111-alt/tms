
                <table class="table table-hover " id="">
                        <thead>
                                <tr>
                                        <th style='width:5%; text-align: right;'><label>#</label></th>
                                        <th style="width:10%;"><label>Date</label></th>
                                        <th style="width:20%;"><label>Title</label></th>
                                        <th style="width:10%;"><label>Coverage</label></th>
                                        <th style="width:5%;"><label>Day</label></th>
                                        <th style="width:10%;"><label>Category</label></th>
                                        <th style="width:20%;"><label>Remarks</label></th>
                                        <th style="width:20%;"><label>Link</label></th>
                                        <!--<th style="width:10%;"><label>Actions</label></th>-->
                                </tr>
                        </thead>
                        <tbody>
                                <?php
                                $num = $details['aa'];
                                $count0 = 0;
                                if(@$records){
                                foreach($records as $row){
                                        $count0++; 
                                        $num++; ?>
                                        <tr class="odd gradeX" style="">
                                                <td align='right' style=""><?php echo $num; ?></td>
                                                <td><?php echo date('F j, Y',strtotime(@$row->holiday_date)); ?></td>
                                                <td><?php echo @$row->holiday_name; ?></td>
                                                <td><?php echo @$row->holiday_coverage_name; ?></td>
                                                <td style="<?php echo date('N',strtotime(@$row->holiday_date))==6 ||date('N',strtotime(@$row->holiday_date))==7 ? 'color: #BCBCBC;' : ''; ?>">
                                                    <?php echo date('l',strtotime(@$row->holiday_date)); ?>
                                                </td>
                                                <td><?php echo @$row->holiday_category_name; ?></td>
                                                <td><?php echo @$row->holiday_remarks; ?></td>

                                                <td><?php if(@$row->link_memo!=''){ ?>
                                                    <a href="<?php echo @$row->link_memo; ?>" role="button" target="_blank" title="Go to link">
                                                        <?php echo strlen(@$row->link_memo)>40 ? substr(@$row->link_memo, 0, 40).'...' : @$row->link_memo; ?>
                                                    </a>
                                                <?php } else echo '---'; ?>
                                                </td>

                                                <!--<td class="td-actions">
                                                    <a class="btn btn-success btn-xs" href="<?php echo site_url('holidays/edit/'.@$row->id_holiday); ?>" role="button">
                                                        <i class="fas fa-pencil"></i> Edit
                                                    </a>
                                                    <a class="btn btn-danger btn-xs" href="<?php echo site_url('holidays/delete/'.@$row->id_holiday); ?>" role="button" onclick="return confirm('Are you sure you want to delete? This cannot be undone.')">
                                                        <i class="fas fa-trash-can"></i> Delete
                                                    </a>

                                                </td>-->
                                        </tr>
                                <?php }
                                } else { ?>
                                        <tr class="odd gradeX">
                                            <td colspan="9">No record.</td>
                                        </tr>
                                <?php } ?>
                        </tbody>
                </table>


                                 

<?php $details['num'] = $count0; ?>
<?= view('layout/mytable/my_table_pagination',$details); ?>