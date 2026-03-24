
            <table style="width: 90%;" border="1" style="">
                <tr>
                    <td style="width:22%;"></td>
                    <td style="width:26%; text-align: center; padding: 5px; font-weight: bold;">Vacation Leave</td>
                    <td style="width:26%; text-align: center; padding: 5px; font-weight: bold;">Sick Leave</td>
                    <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">SPL</td>
                    <td style="width:16%; text-align: center; padding: 5px; font-weight: bold;">Service Credits</td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Total Earned</td>
                    <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofvl">
                        <?php $earned_vl = @$credits[0]->vl;
                        echo $earned_vl;
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofsl">
                        <?php $earned_sl = @$credits[0]->sl;
                        echo $earned_sl;
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofslp">
                        <?php $earned_slp = @$credits[0]->slp;
                        echo $earned_slp;
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofservice">
                        <?php $earned_service = @$credits[0]->service;
                        echo $earned_service;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px; text-align: center; font-style: italic;">Earmarked (Pending)</td>
                    <td style="text-align: center; padding: 5px;" id="vl_earmarked">
                        <?php $vl_earmarked = @$leave[0]->vl_earmarked; 
                        echo $vl_earmarked;
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px;" id="sl_earmarked">
                        <?php $sl_earmarked = @$leave[0]->sl_earmarked;
                        echo $sl_earmarked;
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px;" id="slp_earmarked">
                        <?php $slp_earmarked = @$leave[0]->slp_earmarked; 
                        echo $slp_earmarked;
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px;" id="service_earmarked">
                        <?php $service_earmarked = @$leave[0]->service_earmarked;
                        echo $service_earmarked;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Less this application</td>
                    <?php
                    $balance_sl = $earned_sl - $sl_earmarked;
                    $sl_applied = @$leave[0]->totalsl_days_applied;
                    
                    if($balance_sl>=$sl_applied){
                        $totalbalance_sl = $balance_sl - $sl_applied;
                        $over_sl = 0;
                    } else {
                        $totalbalance_sl = 0;
                        $over_sl = $sl_applied - $earned_sl;
                        $sl_applied = $balance_sl;
                    }
                    
                    
                    $balance_vl = $earned_vl - $vl_earmarked;
                    $vl_applied = @$leave[0]->totalvl_days_applied;
                    
                    if($balance_vl>=$vl_applied){ 
                        $totalbalance_vl = $balance_vl - $vl_applied;
                        $over_vl = 0;
                    } else {
                        $totalbalance_vl = 0;
                        $over_vl = $vl_applied - $earned_vl;
                        $vl_applied = $balance_vl;
                    }
                    
                    
                    $balance_slp = $earned_slp - $slp_earmarked;
                    $slp_applied = @$leave[0]->totalslp_days_applied;
                    
                    if($balance_slp>=$slp_applied){ 
                        $totalbalance_slp = $balance_slp - $slp_applied;
                        $over_slp = 0;
                    } else {
                        $totalbalance_slp = 0;
                        $over_slp = $slp_applied - $earned_slp;
                        $slp_applied = $balance_slp;
                    }
                    
                    
                    
                    if($over_vl<=0){ 
                        
                        // -- not finished----
                            if($totalbalance_vl>=$over_slp){
                                $vl_applied = $vl_applied + $over_slp;
                                $over_slp = 0;
                                $totalbalance_vl = $totalbalance_vl - $over_slp;
                            } else { 
                                $vl_applied = $vl_applied + $totalbalance_vl;
                                $over_slp = $over_slp - $totalbalance_vl;
                                $totalbalance_vl = 0;
                            }
                        // -- not finished-----------
                        
                        if($totalbalance_vl>=$over_sl){
                            $vl_applied = $vl_applied + $over_sl;
                            $over_sl2 = 0;
                            $totalbalance_vl = $totalbalance_vl - $over_sl;
                        } else { 
                            $vl_applied = $vl_applied + $totalbalance_vl;
                            $over_sl2 = $over_sl - $totalbalance_vl;
                            $totalbalance_vl = 0;
                        }
                    }
                    
                    
                    $sl_applied = $sl_applied + @$over_sl2;
                    $totalbalance_sl = $totalbalance_sl-@$over_sl2;
                    
                    
                    
                    ?>
                    
                    
                    <td style="text-align: center; padding: 5px;"><?php 
                        echo @$vl_applied ? @$vl_applied : ''; 
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px;"><?php 
                        echo @$sl_applied ? @$sl_applied : ''; 
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px;"><?php 
                        echo @$leave[0]->totalslp_days_applied ? @$leave[0]->totalslp_days_applied : ''; 
                        ?>
                    </td>
                    <td style="text-align: center; padding: 5px;"><?php 
                        echo @$leave[0]->totalservice_days_applied ? @$leave[0]->totalservice_days_applied : ''; 
                        ?>
                    </td>
                </tr>

                <?php 
                //$vl_balance = @$credits[0]->vl - @$leave[0]->vl_earmarked - @$leave[0]->totalvl_days_applied;
                //$sl_balance = @$credits[0]->sl - @$leave[0]->sl_earmarked - @$leave[0]->totalsl_days_applied;
                $slp_balance = @$credits[0]->slp - @$leave[0]->slp_earmarked - @$leave[0]->totalslp_days_applied;
                $service_balance = @$credits[0]->service - @$leave[0]->totalservice_days_applied;
                ?>

                <tr style="<?php if($totalbalance_vl>0 && $totalbalance_sl>0 && $slp_balance>0){ echo 'background-color:#8de0ce;'; }else{ echo 'background-color: #ffb4ae;'; } ?>">

                    <td style="padding: 5px; text-align: right; font-weight:bold; font-size: larger;">Balance</td>
                    <td id="vl_balance" style="text-align: center; padding: 5px; font-weight:bold; font-size: larger;<?php echo $totalbalance_vl<=0 ? 'color:red;' : ''; ?> ">
                        <?php echo $totalbalance_vl; ?>
                    </td>
                    <td id="sl_balance" style="text-align: center; padding: 5px; font-weight:bold; font-size: larger;<?php echo $totalbalance_sl<=0 ? 'color:red;' : ''; ?>">
                        <?php echo $totalbalance_sl; ?>
                    </td>
                    <td id="slp_balance" style="text-align: center; padding: 5px; font-weight:bold; font-size: larger;<?php echo $slp_balance<=0 ? 'color:red;' : ''; ?>">
                        <?php echo $slp_balance; ?>
                    </td>
                    <td id="slp_balance" style="text-align: center; padding: 5px; font-weight:bold; font-size: larger;<?php echo $slp_balance<=0 ? 'color:red;' : ''; ?>">
                        <?php echo $service_balance; ?>
                    </td>
                </tr>
            </table>