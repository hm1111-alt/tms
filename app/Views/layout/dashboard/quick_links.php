

        <div class="card mb-4 col-lg-12">
                <div class="card-header bg-success text-white" style="">
                    <i class="fas fa-link"></i> Quick Links
                        <!--<button class="btn btn-xs btn-light " type="button" style="float: right;">
                            <i class="fas fa-right-to-bracket"></i> View all systems
                        </button>-->
                </div>

                <div class="card-body " style="overflow: hidden;">

                    <table class="table w-100" style="font-size: 1.2rem; font-weight: 500;">
                        <?php if(session()->get('hrmis_access')==1){ ?>
                            <tr>
                                <td>Human Resource Management IS</td>
                                <td>
                                    <?php $hrmis_url = ($_SERVER['REMOTE_ADDR']=='::1') ? 'http://localhost/hrmisv2/login_portal' : 'https://hrmis2.clsu.edu.ph/login_portal'; ?>
                                    <!--<a href="https://hrmis2.clsu.edu.ph/" target="_blank">-->
                                    <form action="<?php echo $hrmis_url; ?>" method="post" target="_blank">
                                        <input type="hidden" name="refkey" value="<?= session_id() ?>">
                                        <input type="hidden" name="empid" value="<?= session()->get('emp_idno') ?>">
                                        <button class="btn btn-warning-gold w-100 text-white" type="submit" style="text-align: center; display: inline-block">
                                            <i class="fas fa-circle-right"></i> Go to HRMIS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        
                        <tr>
                            <td style="width:65%;">Comprehensive Academic IS</td>
                            <td>
                                <a href="https://oad.clsu2.edu.ph/star/user-login/1" target="_blank">
                                    <button class="btn btn-warning-gold w-100 text-white" type="button" style="text-align: center; display: inline-block">
                                        <i class="fas fa-circle-right"></i> Go to CAIS
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Research and Development Integrated IS</td>
                            <td>
                                <a href="https://radiis.clsu.edu.ph/" target="_blank">
                                    <button class="btn btn-warning-gold w-100 text-white" type="button" style="text-align: center; display: inline-block">
                                        <i class="fas fa-circle-right"></i> Go to RADIIS
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Document Record and Tracking</td>
                            <td>
                                <a href="https://tracker.clsu.edu.ph/" target="_blank">
                                    <button class="btn btn-warning-gold w-100 text-white" type="button" style="text-align: center; display: inline-block">
                                        <i class="fas fa-circle-right"></i> Go to DRTS
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Executive IS</td>
                            <td>
                                <a href="https://eiscms.clsu.edu.ph/" target="_blank">
                                    <button class="btn btn-warning-gold w-100 text-white" type="button" style="text-align: center; display: inline-block">
                                        <i class="fas fa-circle-right"></i> Go to EIS
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Accreditation Web Portal</td>
                            <td>
                                <a href="https://ia.clsu.edu.ph/" target="_blank">
                                    <button class="btn btn-warning-gold w-100 text-white" type="button" style="text-align: center; display: inline-block">
                                        <i class="fas fa-circle-right"></i> Go to IAP
                                    </button>
                                </a>
                            </td>
                        </tr>
                    </table>

                </div>
        </div>