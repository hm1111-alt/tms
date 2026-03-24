
<style type="text/css">

        table tbody tr td  {
            padding-top:5px !important;
            padding-bottom:5px !important;
        }

</style>



                    <div class="col-sm-12" style="clear:both;">

                            <table class="table-hover" border="1" style="width:100%;">
                                <tr>
                                    <td>Remarks</td>
                                    <td></td>
                                    <td align="center" colspan="4">VACATION</td>
                                    <td align="center" colspan="4">SICK</td>
                                    <td align="center">SPL</td>
                                    <td align="center">Service</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Leave records for <?php echo $year; ?></td>
                                    <td align="center">Earned</td>
                                    <td align="center">Absence<br>
                                        Undertime<br>
                                        w/ pay
                                    </td>
                                    <td align="center">Balance</td>
                                    <td align="center">Absence<br>
                                        Undertime<br>
                                        w/o pay
                                    </td>
                                    
                                    <td align="center">Earned</td>
                                    <td align="center">Absence<br>
                                        Undertime<br>
                                        w/ pay
                                    </td>
                                    <td align="center">Balance</td>
                                    <td align="center">Absence<br>
                                        Undertime<br>
                                        w/o pay
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Balance forwarded as of <?php echo $lastday; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td align="center"><?php echo @$credits[0]->vl;
                                        $balance_vl = floatval(@$credits[0]->vl);

                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="center"><?php echo @$credits[0]->sl;
                                        $balance_sl = floatval(@$credits[0]->sl);
                                        ?>
                                    </td>
                                    <td></td>
                                    <td align="center"><?php echo @$credits[0]->slp;
                                        $balance_slp = floatval(@$credits[0]->slp);
                                        $total_slp = 0 ;
                                        $total_service = 0 ;
                                        $total_vl = 0;
                                        $total_sl = 0;
                                        $total_vl_nopay = 0;
                                        $total_sl_nopay = 0;
                                        ?>
                                    </td>
                                    <td><?php echo @$credits[0]->service;
                                        $balance_service = floatval(@$credits[0]->service);
                                        ?>
                                    </td>
                                </tr>
                                <?php foreach($card as $c){ ?>
                                    <tr>
                                        <td><?php echo $c->month_str; ?></td>
                                        <td><?php 
                                            //echo @$c->vl_days_no>0 ? @$c->vl_days_no.@$c->vl_days.' ' : '' ;
                                            if(@$c->vl_days_no>0 || @$c->vl_days_nopay>0){
                                                //echo @$c->vl_days_no>0 ? '<b>'.@$c->vl_days_no.'</b>' : '';
                                                //echo @$c->vl_days_no>0 && @$c->vl_days_nopay>0 ? ',' : '';
                                                //echo @$c->vl_days_nopay>0 ? '<b>'.@$c->vl_days_nopay.'</b>(nopay)' : '';
                                                $totalvl = floatval(@$c->vl_days_no)+floatval(@$c->vl_days_nopay);
                                                echo '<b>'.$totalvl.'</b>';
                                                echo @$c->vl_days;
                                            }
                                            //echo @$c->sl_days_no>0 ? @$c->sl_days_no.@$c->sl_days.' ' : '' ;
                                            if(@$c->sl_days_no>0 || @$c->sl_days_nopay>0){
                                                //echo @$c->sl_days_no>0 ? '<b>'.@$c->sl_days_no.'</b>' : '';
                                                //echo @$c->sl_days_no>0 && @$c->sl_days_nopay>0 ? ',' : '';
                                                //echo @$c->sl_days_nopay>0 ? @$c->sl_days_nopay.'</b>(nopay)' : '';
                                                $totalsl = floatval(@$c->sl_days_no)+floatval(@$c->sl_days_nopay);
                                                echo '<b>'.$totalsl.'</b>';
                                                echo @$c->sl_days;
                                            }
                                            if(@$c->fl_days_no>0 || @$c->fl_days_nopay>0){ // manda
                                                $totalfl = floatval(@$c->fl_days_no)+floatval(@$c->fl_days_nopay);
                                                echo '<b>'.$totalfl.'</b>';
                                                echo @$c->fl_days;
                                            }
                                            
                                            echo @$c->slp_days_no>0 ? '<b>'.@$c->slp_days_no.'</b>'.@$c->slp_days.' ' : '' ;
                                            echo @$c->ml_days_no>0 ? '<b>'.@$c->ml_days_no.'</b>'.@$c->ml_days.' ' : '' ;
                                            echo @$c->pl_days_no>0 ? '<b>'.@$c->pl_days_no.'</b>'.@$c->pl_days.' ' : '' ;
                                            echo @$c->women_days_no>0 ? '<b>'.@$c->women_days_no.'</b>'.@$c->women_days.' ' : '' ;
                                            echo @$c->vawc_days_no>0 ? '<b>'.@$c->vawc_days_no.'</b>'.@$c->vawc_days.' ' : '' ;
                                            echo @$c->month_days_no>0 ? '<b>'.@$c->month_days_no.'</b>'.@$c->month_days.' ' : '' ;
                                            echo @$c->monet_total>0 ? '<b>'.@$c->monet_total.'</b> Monet[VL='.@$c->monet_vl.',SL='.@$c->monet_sl.'] ' : '' ;
                                            
                                            if(@$c->disapproved_manda){
                                                $total_manda = 0;
                                                foreach($c->disapproved_manda as $manda){
                                                    $total_manda += $manda->nodays;
                                                }
                                                echo '<b>'.$total_manda.'</b>FL(Disapproved)=';
                                                foreach($c->disapproved_manda as $manda){
                                                    echo date('j',strtotime($manda->date_from));
                                                    echo @$manda->date_to!='' && $manda->date_from!=@$manda->date_to ? '-'.date('j',strtotime($manda->date_to)).',' : ',';
                                                }
                                                
                                            }
                                            if(@$c->added_remarks!=''){
                                                echo '<br>'.$c->added_remarks;
                                            }
                                            ?>
                                        </td>

                                        <td align="center"><?php 
                                            /*if(@$last_forward_month==$c->month_str){ echo 'a';
                                                $balance_vl += floatval(@$last_forwarded[0]->vl);
                                                echo @$last_forwarded[0]->vl;
                                            } else {  echo 'b';*/
                                                echo @$c->earned_vl;
                                                $balance_vl += floatval(@$c->earned_vl);
                                            //} ?>
                                        </td><!---vl-earned-->
                                        
                                        <td align="center"><?php $vl_days_no = floatval(@$c->vl_days_no)+floatval(@$c->fl_days_no)+floatval(@$c->monet_vl);
                                            //echo @$c->vl_days_no>0 ? @$c->vl_days_no : '';
                                            echo @$vl_days_no>0 ? @$vl_days_no : '';
                                            $balance_vl = $balance_vl-$vl_days_no;
                                            $total_vl += floatval(@$vl_days_no);
                                            ?>
                                        </td><!---vl---wpay--->
                                        
                                        <td align="center" style="<?php echo @$c->month_num==12 ? 'font-weight:bold;' : ''; ?>">
                                            <?php echo $balance_vl; ?>
                                        </td><!---vl---balance--->
                                        
                                        <td align="center"><?php $vl_days_nopay = floatval(@$c->vl_days_nopay);
                                            echo @$vl_days_nopay>0 ? @$vl_days_nopay : '';
                                            $total_vl_nopay += floatval(@$vl_days_nopay);
                                            ?>
                                        </td><!---vl---wo--pay--->

                                        

                                        <td align="center"><?php 
                                            /*if(@$last_forward_month==$c->month_str){
                                                $balance_sl += floatval(@$last_forwarded[0]->sl);
                                                echo @$last_forwarded[0]->sl;
                                            } else {*/
                                                echo @$c->earned_sl;
                                                $balance_sl += floatval(@$c->earned_sl);
                                            //} ?>
                                        </td><!---sl-earned-->
                                        
                                        <td align="center"><?php $sl_days_no = floatval(@$c->sl_days_no)+floatval(@$c->monet_sl);
                                            //echo @$c->sl_days_no>0 ? @$c->sl_days_no : '';
                                            echo @$sl_days_no>0 ? @$sl_days_no : '';
                                            $balance_sl = $balance_sl-@$sl_days_no;
                                            $total_sl += floatval(@$sl_days_no);
                                            ?>
                                        </td><!---sl---wpay--->
                                        
                                        <td align="center" style="<?php echo @$c->month_num==12 ? 'font-weight:bold;' : ''; ?>">
                                            <?php echo $balance_sl; ?>
                                        </td><!---sl---balance--->
                                        
                                        <td align="center"><?php $sl_days_nopay = floatval(@$c->sl_days_nopay);
                                            echo @$sl_days_nopay>0 ? @$sl_days_nopay : '';
                                            $total_sl_nopay += floatval(@$sl_days_nopay);
                                            ?>
                                        </td><!---sl---wo--pay--->


                                        <td align="center"><?php $balance_slp = $balance_slp - floatval(@$c->slp_days_no);
                                            echo @$c->slp_days_no>0 ? @$c->slp_days_no : '';
                                            $total_slp += floatval(@$c->slp_days_no); ?>
                                        </td><!---SLP--->



                                        <td align="center"><?php $balance_service = $balance_service - floatval(@$c->service_days_no);
                                            echo @$c->service_days_no>0 ? @$c->service_days_no : '';
                                            $total_service += floatval(@$c->service_days_no); ?>
                                        </td><!---Service--credits--->
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <td colspan="2">Summary of Leave (<?php echo $year; ?>)</td>

                                    <td colspan="3" style="padding-left:5px;">VACATION</td>
                                    <td></td>
                                    <td colspan="3" style="padding-left:5px;">SICK</td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="2">Consumed Leave (<?php echo $year; ?>)</td>

                                    <td></td>
                                    <td align="center"><?php echo $total_vl; ?> </td>
                                    <td></td>
                                    <td align="center"><?php echo $total_vl_nopay; ?></td>

                                    <td></td>
                                    <td align="center"><?php echo $total_sl; ?> </td>
                                    <td></td>
                                    <td align="center"><?php echo $total_sl_nopay; ?></td>

                                    <td align="center"><?php echo $total_slp; ?> </td>
                                    <td align="center"><?php echo $total_service; ?> </td>
                                </tr>


                                <tr>
                                    <td colspan="11" style="height: 10px;"></td>
                                </tr>

                                <?php if(session()->get('access_level')==1){ ?>
                                    <tr>
                                        <td align="right" colspan="2" style="padding-right:5px;">Running Leave credits in HRMIS
                                            <br>as of <?php echo date('F j, Y',strtotime(@$current[0]->last_updated)); ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td align="center" style="<?php echo number_format($balance_vl,3,'.','')!= number_format(@$current[0]->vl,3,'.','') ? 'color: red;' : ''; ?>"> 
                                            <?php echo $current[0]->vl; ?>
                                            <?php echo number_format($balance_vl,3,'.','')!= number_format(@$current[0]->vl,3,'.','') ? '<br><em style="font-size:smaller;">(Not matched)</em>' : ''; ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td align="center" style="<?php echo number_format($balance_sl,3,'.','')!= number_format(@$current[0]->sl,3,'.','') ? 'color: red;' : ''; ?>" >
                                            <?php echo @$current[0]->sl; ?>
                                            <?php echo number_format($balance_sl,3,'.','')!= number_format(@$current[0]->sl,3,'.','') ? '<br><em style="font-size:smaller;">(Not matched)</em>' : ''; ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $current[0]->service; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                    </div>


            <?php /*
            <span style="font-style: italic; float:right;">Updating this will reset the '<b>Leave Credits</b>' of employee and will add a new record in 'Leave Credits Logs'.</span>

            <br>
            <br>
            <button type="submit" class="btn btn-primary btn-lg" name="importsubmit" id="" style="display: inline-block; margin-top: -10px; float: right;">Submit</button>
             * 
             */ ?>


