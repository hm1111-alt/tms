<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
<div class="mt-4">
    <ul class="page_title_button" style="list-style: none; float:right;">
        <li>
            <a href="<?= site_url('services/requests') ?>" class="btn btn-light" role="button">
                <i class="fas fa-arrow-circle-left"></i>
                <div style="color:#000">Back to Requests</div>
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="min-height:50vh">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">HELP US SERVE YOU BETTER!</h5>

                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('feedback/save_feedback/'.$request_id.'/'.$document_id) ?>">
                        <div class="mb-4">
                            <h5>INSTRUCTIONS:</h5>
                            <p>For SQD 0-8, please put a check mark on the column that best corresponds to your answer.</p>
                        </div>

                        <?php if (!empty($questions)): ?>
                        <div class="mb-4">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Code</th>
                                            <th>Question</th>
                                            <th>Strongly Disagree</th>
                                            <th>Disagree</th>
                                            <th>Neither Agree nor Disagree</th>
                                            <th>Agree</th>
                                            <th>Strongly Agree</th>
                                            <th>N/A</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($questions as $question): ?>
                                        <tr>
                                            <td class="fw-bold"><?= esc($question['code'] ?? '') ?></td>
                                            <td class="fw-bold">
                                                <?= esc($question['question_text'] ?? 'Question not available') ?>
                                                <?php 
                                                $question_type = $question['question_type'] ?? 'rating';
                                                $question_id = $question['id'] ?? $question['question_id'];
                                                ?>
                                            </td>
                                            <?php if ($question_type === 'rating'): ?>
                                                <td class="text-center"><input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" id="q<?= $question_id ?>_1" value="1" required></td>
                                                <td class="text-center"><input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" id="q<?= $question_id ?>_2" value="2" required></td>
                                                <td class="text-center"><input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" id="q<?= $question_id ?>_3" value="3" required></td>
                                                <td class="text-center"><input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" id="q<?= $question_id ?>_4" value="4" required></td>
                                                <td class="text-center"><input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" id="q<?= $question_id ?>_5" value="5" required></td>
                                                <td class="text-center"><input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" id="q<?= $question_id ?>_6" value="6" required></td>
                                            <?php elseif ($question_type === 'text'): ?>
                                                <td colspan="7"><textarea name="question_<?= $question_id ?>" class="form-control" placeholder="Your answer"></textarea></td>
                                            <?php elseif ($question_type === 'yes_no'): ?>
                                                <td colspan="4">
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" value="1" required>
                                                            <label class="form-check-label">Yes</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td colspan="4">
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input sqd-question" type="radio" name="question_<?= $question_id ?>" value="0" required>
                                                            <label class="form-check-label">No</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Question</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>N/A</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Overall, how would you rate your entire educational experience at CLSU? (1 as the lowest and 5 as the highest)</td>
                                            <td class="text-center"><input class="form-check-input" type="radio" name="overall_rating" id="overall_rating_1" value="1" required></td>
                                            <td class="text-center"><input class="form-check-input" type="radio" name="overall_rating" id="overall_rating_2" value="2" required></td>
                                            <td class="text-center"><input class="form-check-input" type="radio" name="overall_rating" id="overall_rating_3" value="3" required></td>
                                            <td class="text-center"><input class="form-check-input" type="radio" name="overall_rating" id="overall_rating_4" value="4" required></td>
                                            <td class="text-center"><input class="form-check-input" type="radio" name="overall_rating" id="overall_rating_5" value="5" required></td>
                                            <td class="text-center"><input class="form-check-input" type="radio" name="overall_rating" id="overall_rating_6" value="6" required></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Have you experienced any form of harassment during the transaction in this office?</h5>
                            <div class="d-flex gap-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="experienced_harassment" id="experienced_harassment_yes" value="1" required>
                                    <label class="form-check-label" for="experienced_harassment_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="experienced_harassment" id="experienced_harassment_no" value="0" required>
                                    <label class="form-check-label" for="experienced_harassment_no">No</label>
                                </div>
                            </div>
                            <textarea name="harassment_details" id="harassment_details" class="form-control" placeholder="Please provide details (required if you answered YES)" style="display:none;"></textarea>
                        </div>

                        <div class="mb-4">
                            <h5>Would you recommend CLSU to others?</h5>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="recommend_clsu" id="recommend_clsu_yes" value="1">
                                    <label class="form-check-label" for="recommend_clsu_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="recommend_clsu" id="recommend_clsu_no" value="0">
                                    <label class="form-check-label" for="recommend_clsu_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Suggestions on how we can further improve our services (optional):</h5>
                            <textarea name="suggestions" class="form-control" placeholder="Your suggestions"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success" id="submitFeedbackBtn">
                                <i class="fas fa-paper-plane"></i> Submit Feedback
                            </button>
                            <button type="button" class="btn btn-light" onclick="window.history.back()">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#submitFeedbackBtn').click(function(e) {
        var questions = {};
        $('.sqd-question').each(function() {
            var name = $(this).attr('name');
            if (!questions[name]) {
                var row = $(this).closest('tr');
                var code = row.find('td:first').text().trim();
                questions[name] = code || name.replace('question_', 'Question ');
            }
        });

        var unanswered = [];
        for (var name in questions) {
            if ($('input[name="' + name + '"]:checked').length === 0) {
                unanswered.push(questions[name]);
            }
        }
        
        if (unanswered.length > 0) {
            e.preventDefault();
            alert('Please answer ALL SQD questions before submitting.\n\nMissing: ' + unanswered.join(', '));
            return false;
        }

        if ($('input[name="overall_rating"]:checked').length === 0) {
            e.preventDefault();
            alert('Please provide an OVERALL RATING before submitting.');
            return false;
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const yesRadio = document.getElementById('experienced_harassment_yes');
    const noRadio = document.getElementById('experienced_harassment_no');
    const textarea = document.getElementById('harassment_details');

    function toggleTextarea() {
        textarea.style.display = yesRadio.checked ? 'block' : 'none';
        if (yesRadio.checked) {
            textarea.setAttribute('required', 'required');
        } else {
            textarea.removeAttribute('required');
        }
    }

    yesRadio.addEventListener('change', toggleTextarea);
    noRadio.addEventListener('change', toggleTextarea);
    toggleTextarea(); 
});
</script>

</div>
</div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer_jscript') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.draftRestoreTimeout) {
        clearTimeout(window.draftRestoreTimeout);
    }
    window.restoreDraft = function() {
        console.log('Draft restoration disabled for feedback form');
    }; 
    const draftKey = 'request_draft_' + <?= json_encode(session()->get('userid') ?? 0) ?>;
    localStorage.removeItem(draftKey);
    
    console.log('Feedback form draft protection initialized');
});
$(document).ready(function() {
    $('#submitFeedbackBtn').click(function(e) {
        var questions = {};
        $('.sqd-question').each(function() {
            var name = $(this).attr('name');
            if (!questions[name]) {
                var row = $(this).closest('tr');
                var code = row.find('td:first').text().trim();
                questions[name] = code || name.replace('question_', 'Question ');
            }
        });

        var unanswered = [];
        for (var name in questions) {
            if ($('input[name="' + name + '"]:checked').length === 0) {
                unanswered.push(questions[name]);
            }
        }
        
        if (unanswered.length > 0) {
            e.preventDefault();
            alert('Please answer ALL SQD questions before submitting.\n\nMissing: ' + unanswered.join(', '));
            return false;
        }

        if ($('input[name="overall_rating"]:checked').length === 0) {
            e.preventDefault();
            alert('Please provide an OVERALL RATING before submitting.');
            return false;
        }
        
        const draftKey = 'request_draft_' + <?= json_encode(session()->get('userid') ?? 0) ?>;
        localStorage.removeItem(draftKey);
        console.log('Draft cleared on feedback submission');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const yesRadio = document.getElementById('experienced_harassment_yes');
    const noRadio = document.getElementById('experienced_harassment_no');
    const textarea = document.getElementById('harassment_details');

    function toggleTextarea() {
        textarea.style.display = yesRadio.checked ? 'block' : 'none';
        if (yesRadio.checked) {
            textarea.setAttribute('required', 'required');
        } else {
            textarea.removeAttribute('required');
        }
    }

    yesRadio.addEventListener('change', toggleTextarea);
    noRadio.addEventListener('change', toggleTextarea);
    toggleTextarea(); 
});
</script>
<?= $this->endSection() ?>
