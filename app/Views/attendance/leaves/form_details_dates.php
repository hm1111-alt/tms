
<div class="col-xs-12" style="margin-top:30px;">

        <label><b>INCLUSIVE DATES:</b></label>
        <div class="container">
            <div class="row" style="">
                    <label class="col col-xs-3" style="text-align: right;">From</label>
                    <div class="col col-xs-9">
                        <label id="from_lbl_0">
                            <input name="date_from" id="date_from2" class="date_from datepicker form-control" type="text" style="margin-top:5px;" value="" readonly>
                        </label>
                        <?php /*
                        <select class="leave_coverage from_hd" id="from_hd2" name="from_hd">
                            <option value="0" <?php // echo @$dt->date_from_ishalf==0 ? 'selected' : ''; ?>>Whole Day</option>
                            <option value="1" <?php // echo @$dt->date_from_ishalf==1 ? 'selected' : ''; ?>>AM only</option>
                            <option value="2" <?php // echo @$dt->date_from_ishalf==2 ? 'selected' : ''; ?>>PM only</option>
                        </select> 
                         */ ?>
                        <input type="hidden" name="from_hd" class="leave_coverage from_hd" id="from_hd2" value="0">
                    </div>
            </div>
        </div>

        <div class="container">
                <div class="row" style="">
                        <label class="col col-xs-3" style="text-align: right;">To</label>
                        <div class="col col-xs-9">
                            <label id="to_lbl_0">
                                <input name="date_to" id="date_to2" class="date_to datepicker form-control" type="text" style="margin-top:5px;" value="" disabled readonly >
                            </label>
                            <?php /*<select class="leave_coverage to_hd" id="to_hd2" name="to_hd" disabled>
                                <option value="0" <?php // echo @$dt->date_from_ishalf==0 ? 'selected' : ''; ?>>Whole Day</option>
                                <option value="1" <?php // echo @$dt->date_from_ishalf==1 ? 'selected' : ''; ?>>AM only</option>
                                <!--<option value="2" <?php // echo @$dt->date_from_ishalf==2 ? 'selected' : ''; ?>>PM only</option>-->
                            </select>*/ ?>
                            <input type="hidden" name="from_hd" class="leave_coverage to_hd" id="to_hd2" value="0">
                        </div>
                </div>
        </div>
                                                                                            
        <div class="col-sm-12" style="padding-bottom:30px; display: inline-block; float: right; text-align: right;">
            <input type="hidden" name="leave_detail_id" id="leave_detail_id" value="">
            <input type="hidden" name="leave_type_id" id="leave_type_id" value="">
            <input type="hidden" name="date_id" id="date_id" value="">
            <input type="hidden" name="employee_id" value="<?php echo @$leave[0]->employee_id; ?>">
            <input type="hidden" name="leave_id" value="<?php echo @$leave[0]->id_leave; ?>">
            <button type="button" class="btn btn-primary save_button22" id="submit_date_btn" style="display: inline-block;">Add Date</button>
            <button type="button" id="cancel_date" class="btn-default btn cancel_date" style="margin-left:15px;">Cancel</button>
        </div>

</div>
                                 