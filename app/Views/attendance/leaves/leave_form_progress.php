<style>
    
.control-label {
    padding-top: 0px !important;
}
.not_qual {
    color: #d9534f;
    font-weight: bold;
}
.qual {
    color: #26B99A;
    font-weight: bold;
}

    
.wizard_horizontal ul.wizard_steps {
    display: table;
    list-style: none;
    position: relative;
    width: 100%;
    margin: 0 0 0px;
}
.wizard_horizontal ul.wizard_steps li {
    display: table-cell;
    text-align: center;
}
.wizard_horizontal ul.wizard_steps li a {
    display: block;
    position: relative;
    -moz-opacity: 1;
    filter: alpha(opacity=100);
    opacity: 1;
    color: #666;
}.wizard_horizontal ul.wizard_steps li:hover {
    position: relative;
    -moz-opacity: 1;
    filter: alpha(opacity=100);
    opacity: 1;
    color: #666;
}
.btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus, :active, :focus, :visited, a, a:active, a:focus, a:visited {
    outline: 0;
}
.detail a, .expand, .jqstooltip, .paging_full_numbers a:hover, .site_title:focus, .site_title:hover, a, a:focus, a:hover {
    text-decoration: none;
}
.wizard_horizontal ul.wizard_steps li:first-child a:before {
    left: 50%;
}
.step_no, .wizard_horizontal ul.wizard_steps li a.selected:before {
    background: #1ABB9C; /*#34495E;*/
    color: #fff;
}
.wizard_horizontal ul.wizard_steps li a:before {
    content: "";
    position: absolute;
    height: 4px;
    background: #ccc;
    top: 20px;
    width: 100%;
    z-index: 4;
    left: 0;
}
.wizard_horizontal ul.wizard_steps li a .step_no {
    width: 40px;
    height: 40px;
    line-height: 40px;
    border-radius: 100px;
    display: block;
    margin: 0 auto 5px;
    font-size: 16px;
    text-align: center;
    position: relative;
    z-index: 5;
}
.form_wizard .stepContainer {
    display: block;
    position: relative;
    margin: 0;
    padding: 0;
    border: 0 solid #CCC;
    overflow-x: hidden;
}.wizard_horizontal ul.wizard_steps li:last-child a:before {
    right: 50%;
    width: 50%;
    left: auto;
}.wizard_horizontal ul.wizard_steps li a.disabled .step_no {
    background: #ccc;
}
a.step_no_last:before {
    background: #ccc !important;
    /*background-image: linear-gradient(to right, transparent 50%, green 50%);*/
}
a.step_no_last span.step_no {
    background-color: #eb5448 !important;
}
.switch {
  display: inline-block;
  height: 34px;
  position: relative;
  width: 60px;
}

.switch input {
  display:none;
}

.slider {
  background-color: #ccc;
  bottom: 0;
  /*cursor: pointer;*/
  left: 0;
  /*position: absolute;*/
  height:35px;
  right: 0;
  /*top: 0;*/
  padding-top: 20px;
  transition: .4s;
}

.slider:before {
  background-color: #fff;
  bottom: 4px;
  content: "";
  height: 26px;
  left: 4px;
  position: absolute;
  transition: .4s;
  width: 26px;
}

input:checked + .slider {
  background-color: #66bb6a;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;margin-bottom: -10px;
}
.slider {
    margin-top: -10px !important;
}
</style>
                        <div class="card-body">
                                                        <h5 style="margin-bottom: 0px;margin-top: 5px;">Progress</h5>
                                                        
                                                        <div class="form-group col-lg-12" style="margin-bottom: 0px !important;">

                                                                <div id="wizard" class="form_wizard wizard_horizontal">

                                                                        <ul class="wizard_steps anchor">

                                                                            <?php $snum = 0;
                                                                            foreach ($steps as $ste){ $snum++; ?>
                                                                                <li style="width:8%;">
                                                                                    <a class="<?php echo @$step>=$ste->id_step ? 'selected ' : ' disabled '; echo (@$ste->status_end==1 && @$step>=$ste->id_step) ? 'step_no_last' : ''; ?>" 
                                                                                       <?php echo @$ste->status_link!='' ? 'href="'.site_url('leaves/'.@$ste->status_link).'"' : ''; ?>
                                                                                       isdone="<?php echo @$step>=$ste->id_step ? '1' : '0'; ?>" rel="<?php echo $ste->id_step; ?>">
                                                                                        <span class="step_no"><?php echo $snum; ?></span>
                                                                                        <span class="step_descr">
                                                                                            <?php echo 'Step '.$snum.' - '.$ste->step_name; ?><br>
                                                                                            <!--<small>Step 7 description</small>-->
                                                                                        </span>
                                                                                    </a>
                                                                                </li>
                                                                            <?php } ?>

                                                                        </ul>

                                                                </div>
                                                        </div>
                        </div>