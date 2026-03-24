

            <div class="card mb-4 col-lg-12">
                    <div class="card-header bg-success text-white" style="background-color: #8D5116;">
                        <i class="fas fa-calendar-days"></i> Leave Credits <span id="asof"></span>
                        <a href="<?= site_url('leaves/filing') ?>">
                            <button class="btn btn-xs btn-light " type="button" style="float:right;">
                                <i class="fas fa-file"></i> File Leave
                            </button>
                        </a>
                    </div>

                    <div class="card-body " style="overflow: hidden;">

                            <div class="row  mb-2 mt-1 px-2 text-center leave_credits" >
                                <div class="col col-lg-3 leave_credits_div" id="credit_vl_div">
                                    <div id="credit_vl">-</div>
                                    Vacation Leave
                                </div>
                                <div class="col col-lg-3 leave_credits_div" id="credit_sl_div">
                                    <div id="credit_sl">-</div>
                                        Sick Leave
                                </div>
                                <div class="col col-lg-3 leave_credits_div" id="credit_spl_div">
                                    <div id="credit_spl">-</div>
                                    SPL
                                </div>
                                <div class="col col-lg-3 leave_credits_div" id="credit_service_div">
                                    <div id="credit_service">-</div>
                                    Service credits
                                </div>
                            </div>
                        
                            <!--<div class="row " style="">
                                <button class="btn btn-success w-50 " type="button" style="text-align: center; display: inline-block">
                                    <i class="fas fa-plus"></i> File Leave
                                </button>
                            </div>-->

                    </div>
            </div>
<?php /*
            <div class="card mb-4 col-lg-12">
                    <div class="card-header text-white" style="background-color: #e06b0d;">
                        <i class="fas fa-paste"></i> Leave History
                    </div>

                    <div class="card-body " style="overflow: hidden;">

                            <div class="row  mb-2 mt-1 px-2 text-center leave_credits" >
                                <div class="col col-lg-3">
                                    <div>1.0</div>
                                    Vacation Leave
                                </div>
                                <div class="col col-lg-3">
                                    <div>2.075</div>
                                        Sick Leave
                                </div>
                                <div class="col col-lg-3">
                                    <div>1.5</div>
                                    SPL
                                </div>
                                <div class="col col-lg-3">
                                    <div>0</div>
                                    Service credits
                                </div>
                            </div>

                    </div>
            </div>*/