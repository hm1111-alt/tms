
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>e-Portal v1.0</title>
	<meta content="Employee Portal v1.0" name="description">
	<meta content="Tristan Peneyra" name="keywords">

        <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
	<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
	<link href="https://fonts.gstatic.com" rel="preconnect">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script> 
	<link href="<?= base_url('public/assets/css/styles.css') ?>" rel="stylesheet" />
</head>

<body class="min-vh-100">
	<main>
		<section class="section register d-flex flex-column align-items-center justify-content-center">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-xl-10 col-lg-12 col-md-12 mx-auto">
						<div class="justify-content-center py-2" style="text-align: center;">
							<a href="<?= site_url() ?>" class="logo d-flex align-items-center w-auto" style="text-decoration: none;">
								<img src="<?= base_url('public/assets/images/seal-monogram-green.png') ?>" height="150" alt="" style="border-right: 1px solid green;">
								<h2 class="d-lg-block mb-0 text-success" style="padding-left: 20px;">Employee Portal v1.0</h2>
							</a>
						</div>

						<div class="card border">
							<div class="card-header bg-success">
								<h4 class="card-title fs-4 text-white text-center pt-1 pb-0 m-0">Data Privacy Policy Statement</h4>
							</div>							
							<div class="card-body px-3 py-4">
								<div class="mb-4">
									<h5 class="fw-semibold">Data Privacy Policy </h5>
									<p align="justify">The Central Luzon State University's Human Resource Management Office (CLSU-HRMO) is committed to protecting the privacy and security of personal data of employees collected through the Employee Portal system and be stored as reference data in our Human Resources Management Information System. This policy outlines how we collect, use, and protect personal data in our system.</p>
								</div>
								<div class="mb-4">
									<h5 class="fw-semibold">Collection of Personal Data</h5>
									<p align="justify">We collect personal data from employees for the purpose of human resource management, including but not limited to recruitment, employment, training, development, performance management, and separation. We collect personal data through our Employee Portal, including the following categories of personal data such as:</p>
									<ul>
										<li>Identification information, such as name, address, contact information, and identification documents.</li>
										<li>Employment information, such as job title, employment history, education, and training.</li>
										<li>Compensation information, such as basic salary and salary grade.</li>
										<li>Performance information, such as performance evaluations, disciplinary actions, and commendations.</li>
										<li>Health and wellness information, such as medical records and insurance.</li>
									</ul>
								</div>
								<div class="mb-4">
									<h5 class="fw-semibold">Use of Personal Data</h5>
									<p align="justify">We use personal data for the following purposes:</p>
									<ul>
										<li>To fulfill our obligations as Human Resource office and to provide benefits and services to our employees.</li>
										<li>To comply with legal and regulatory requirements, including employment laws.</li>
										<li>To communicate with employees regarding HR-related matters.</li>
										<li>To evaluate employee performance and provide feedback.</li>
										<li>To make employment-related decisions, such as promotions and terminations.</li>
									</ul>
								</div>
								<div class="mb-4">
									<h5 class="fw-semibold">Protection of Personal Data</h5>
									<p align="justify">We take appropriate measures to protect personal data from unauthorized access, use, and disclosure. These measures include:</p>
									<ul>
										<li>Restricting access to personal data to employees who need to know this information.</li>
										<li>Implementing technical and organizational measures to secure personal data.</li>
										<li>Ensuring that third-party vendors who have access to personal data comply with applicable data protection laws.</li>
									</ul>
								</div>
								<div class="mb-4">
									<h5 class="fw-semibold">Retention of Personal Data</h5>
									<p align="justify">We retain personal data for as long as necessary to fulfill the purposes for which we collected the information or as required by law. When personal data is no longer necessary, we will securely dispose of it.</p>
								</div>
								<div class="mb-4">
									<h5 class="fw-semibold">Rights of Employees</h5>
									<p align="justify">Our employees have the right to access, rectify, and erase their personal data. Employees can also object to the processing of their personal data or restrict the processing of their personal data. To exercise these rights, employees should contact the CLSU-HRMO.</p>
								</div>
								<div class="mb-4">
									<h5 class="fw-semibold">Changes to this Policy</h5>
									<p align="justify">We may update this policy from time to time. Any changes will be posted on our website and will be effective immediately upon posting.</p>
								</div>
								<div class="mb-4">
									<h5 class="fw-semibold">Contact Information</h5>
									<p align="justify">If you have any questions about this policy or our Online Personal Data Sheet system, please contact the CLSU-HRMO at <a href="mailto:hrmo@clsu.edu.ph">hrmo@clsu.edu.ph</a>.</p>
								</div>
							</div>
							<!-- <div class="card-footer bg-light text-center mt-3">
								<a href="registration.php"><button class="btn btn-secondary mx-2 text-white" type="button" style="width: 120px;"><i class="bi bi-x-square-fill"></i> Close</button></a>
							</div> -->
						</div>
					</div>
				</div>
			</div>			
		</section>		
	</main>
	
            <div id="layoutAuthentication_footer" style="padding-top: 20px;">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">&copy; Copyright 2025. All rights reserved.
                                <br><span class="text-success">Management Information System Office (MISO)</span>.
                                <br><?php echo strtoupper('Central Luzon State University'); ?>
                            </div>
                            <!--<div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>-->
                        </div>
                    </div>
                </footer>
            </div>

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/main.js"></script>
</body>

</html>