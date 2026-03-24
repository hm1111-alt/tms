
        </div>
        <!----end--of--layoutSidenav---->
        
        <script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
        <script src="<?= js('scripts.js'); ?>"></script>
        <!--<script src="<?php // echo base_url('public/assets/js/jquery-3.6.0.min.js'); ?>"></script>-->
        <?php /*
        <script src="<?= base_url('public/assets/js/Chart.min.js'); ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('public/assets/demo/chart-area-demo.js'); ?>"></script>
        <script src="<?= base_url('public/assets/demo/chart-bar-demo.js'); ?>"></script>
        <script src="<?= base_url('public/assets/simple-datatables/simple-datatables.min.js'); ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('public/assets/js/datatables-simple-demo.js'); ?>"></script>
         * 
         */ ?>
        
        <!-- Back to Top Button -->
        <button id="backToTop" class="btn btn-success rounded-circle shadow"  title="Back to top">
          <i class="fas fa-arrow-up"></i>
        </button>
  
    </body>
</html>

<?= $this->include('layout/modal_change_password'); ?>

<script>

        const btn = document.getElementById("backToTop");

        window.onscroll = function () {
          btn.style.display = window.scrollY > 200 ? "block" : "none";
        };

        btn.onclick = function () {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        };

</script>