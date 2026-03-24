
<h4 class="h2-class mt-2 mb-4"><?= $page[0]->page_name2; ?></h4>


<div class="px-4">
    
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Last Name</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->emp_lname; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">First Name</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->emp_fname; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Middle Initial</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->emp_mi!='' ? @$personal[0]->emp_mi : '<na>---</na>'; ?>
                </div>
        </div>


        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Name Extension</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->emp_extname!='' ? @$personal[0]->emp_extname : '<na>n/a</na>'; ?>
                </div>
        </div>



        <div class="form-group mt-3 mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Program</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->office_name;
                    echo @$personal[0]->office_abbr!='' ? ' ('.@$personal[0]->office_abbr.')' : '';
                    ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">College / Division / Office</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->division_name; 
                    echo @$personal[0]->division_abbr!='' ? ' ('.@$personal[0]->division_abbr.')' : '';
                    ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Department / Unit / Section</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->unit_name; 
                    echo @$personal[0]->unit_abbr!='' ? ' ('.@$personal[0]->unit_abbr.')' : '';
                    ?>
                </div>
        </div>



        <div class="form-group mt-3 mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Official Email</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->emp_email_official!='' ? @$personal[0]->emp_email_official : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Personal Email</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->emp_email_personal!='' ? @$personal[0]->emp_email_personal : '<na>---</na>'; ?>
                </div>
        </div>


        <div class="form-group mt-3 mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Birthday</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->BirthDate!='' ? date('F j, Y',strtotime($personal[0]->BirthDate)) : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Place of Birth</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->BirthPlace!='' ? @$personal[0]->BirthPlace : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Sex</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->emp_sex!='' ? @$personal[0]->emp_sex : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Civil Status</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->CivilStatus!='' ? @$personal[0]->CivilStatus : '<na>---</na>'; ?>
                </div>
        </div>


        <div class="form-group mt-3 mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Height</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->Height!='' ? $personal[0]->Height : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Weight</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->Weight!='' ? @$personal[0]->Weight : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Blood type</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->BloodType!='' ? @$personal[0]->BloodType : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Citizenship</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->Citizenship!='' ? @$personal[0]->Citizenship : '<na>---</na>'; ?>
                </div>
        </div>



        <div class="form-group mt-3  mb-1 px-1 row"  style="">
                <label class="col col-lg-3">GSIS ID No.</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->GSISIDNo!='' ? @$personal[0]->GSISIDNo : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Pag-ibig ID No.</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->PagIbigIDNo!='' ? @$personal[0]->PagIbigIDNo : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Philhealth ID No.</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->PhilHealthIDNo!='' ? @$personal[0]->PhilHealthIDNo : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">SSS No.</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->SSSIDNo!='' ? @$personal[0]->SSSIDNo : '<na>---</na>'; ?>
                </div>
        </div>
        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">TIN No.</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php echo @$personal[0]->TaxIDNo!='' ? @$personal[0]->TaxIDNo : '<na>---</na>'; ?>
                </div>
        </div>



        <div class="form-group mt-3  mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Residential Address</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php if(@$personal[0]->ResHouseZip!=''){
                        echo @$personal[0]->ResHouseNo!='' ? @$personal[0]->ResHouseNo.' ' : '';
                        echo @$personal[0]->ResHouseStreet!='' ? @$personal[0]->ResHouseStreet.', ' : '';
                        echo @$personal[0]->ResHouseSubd!='' ? @$personal[0]->ResHouseSubd.', ' : '';
                        echo @$personal[0]->ResHouseBrgy!='' ? @$personal[0]->ResHouseBrgy.', ' : '';
                        echo @$personal[0]->ResHouseCity!='' ? @$personal[0]->ResHouseCity.', ' : '';
                        echo @$personal[0]->ResHouseProvince!='' ? @$personal[0]->ResHouseProvince.' ' : '';
                        echo @$personal[0]->ResHouseZip;
                    } else {
                      echo '<na>---</na>';  
                    }  ?>
                </div>
        </div>

        <div class="form-group mb-1 px-1 row"  style="">
                <label class="col col-lg-3">Permanent Address</label>
                <div class="col col-lg-9 px-3 fw-bold">
                    <?php if(@$personal[0]->PerHouseZip!=''){
                        echo @$personal[0]->PerHouseNo!='' ? @$personal[0]->PerHouseNo.' ' : '';
                        echo @$personal[0]->PerHouseStreet!='' ? @$personal[0]->PerHouseStreet.', ' : '';
                        echo @$personal[0]->PerHouseSubd!='' ? @$personal[0]->PerHouseSubd.', ' : '';
                        echo @$personal[0]->PerHouseBrgy!='' ? @$personal[0]->PerHouseBrgy.', ' : '';
                        echo @$personal[0]->PerHouseCity!='' ? @$personal[0]->PerHouseCity.', ' : '';
                        echo @$personal[0]->PerHouseProvince!='' ? @$personal[0]->PerHouseProvince.' ' : '';
                        echo @$personal[0]->PerHouseZip;

                    } else {
                      echo '<na>---</na>';  
                    }  ?>
                </div>
        </div>

    
</div>