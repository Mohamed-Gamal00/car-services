<!doctype html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
	<meta content="Themesbrand" name="author">
	<!-- App favicon -->

	<!-- Bootstrap Css -->
	<link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css">
	<!-- Icons Css -->
	<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
	<!-- App Css-->
	<link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css">
	<title>404</title>

</head>

<body>
<!-- Begin page -->
<div class="authentication-bg d-flex align-items-center pb-0 vh-100">
	<div class="content-center w-100">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-10">
					<div class="card">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-lg-4 ms-auto">
									<div class="ex-page-content">
										<h1 class="text-dark display-1 mt-4">404</h1>
										<h4 style="font-size: 24px" class="mb-4 error-page">صفحة غير موجوده</h4>
											<a class="btn btn-primary mb-5 waves-effect waves-light" href="{{ url('/') }}"><i class="mdi mdi-home"></i>العودة للمتجر</a>
									</div>

								</div>
								<div class="col-lg-5 mx-auto">
									<img src="{{asset('assets/images/error.png')}}" alt="" class="img-fluid mx-auto d-block">
								</div>
							</div>
						</div><!-- end cardbody -->
					</div><!-- end card -->
				</div><!-- end col -->
			</div><!-- end row -->
		</div>
		<!-- end container -->
	</div>

</div>
<!-- end error page -->

<!-- JAVASCRIPT -->


</body>
</html>