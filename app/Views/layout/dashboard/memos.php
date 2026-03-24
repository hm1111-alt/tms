

    <div class="card-header bg-success text-white">
        <i class="fas fa-bullhorn"></i> Memos / Announcements
    </div>

    <div class="card-body" style="padding: 5px;">

            <div class="form-group  mb-2">

                    <?php 
                    $filedir = 'public/assets/files/memos';
                    if(@$memos){
                        foreach($memos as $memo){ ?>
                            <div class="memo_div <?= $memo->memo_sort==1 ? 'active' : ''; ?>" id="<?= $memo->id_memo; ?>">
                                <b><?= $memo->memo_label; ?></b>
                                <br>
                                <p style="font-size: smaller;"><?= $memo->memo_file_description; ?></p>
                            </div>
                    <?php }
                    } ?>



            </div>

            <div class="form-group  mb-4" style="border-top: 1px solid #D9D9D9; padding:10px; height:500px;">
                <embed id="memo_preview_div" src="" style="height: 100%; width: 100%;">
            </div>

    </div>