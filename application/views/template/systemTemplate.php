<!DOCTYPE html>
<html lang="zh-tw">
	<head>
		<?php $this->load->view('basic/headLoad'); ?>
		<!-- 頁面所需的額外Script載入放在這裡 -->
		<?php echo $headComponents ?? "" ?>
		<!-- 頁面所需的額外Script載入放在這裡 -->
		<style>
		.chart_section{
			/* max-width: 1320px; */
			max-width: 1600px;
		}
		</style>
	</head>

	<body>
		<div class="wrapper">
			<!-- header -->
			<?php $this->load->view('basic/header');?>
			<!-- header_End -->

			<!-- Content_right -->
			<div class="container_full">
				<div class="content_wrapper">
					<div class="container-fluid">
						<!-- Section -->
						<section class="chart_section mx-auto">
							<div class="row">

								<div class="col-lg-12 mb-12">
									<div class="card card-shadow">
										<div class="card-header">
											<div class="card-title">
												<?php echo $bodyTitle ?? "" ?>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12">
												<div class="card-body">
													<?php echo $components ?? "" ?>
													<!-- 頁面內容 -->
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
						</section>
						<!-- Section_End -->
					</div>
				</div>
			</div>
			<!-- Content_right_End -->
			<!-- Footer -->
			<?php $this->load->view('basic/footer'); ?>
			<!-- Footer_End -->
		</div>

	</body>

</html>
