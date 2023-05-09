<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Favicons -->
    <link rel="icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet" >
    
    <link href="css/styleLogin.css" rel="stylesheet">
    <script type="text/javascript" src="js/login.js"></script>
	<script>

		function checkpassword(){
			let pass = $("#password_register").val();
			let confirm_pass = $("#confirm-password_register").val();

			if(pass != confirm_pass){
				$("#checkpass").removeClass();
				$("#checkpass").addClass("alert alert-danger");
				$("#checkpass").html("mot de pass incorrect");
			}else{
				$("#checkpass").removeClass();
				$("#checkpass").addClass("alert alert-success");
				$("#checkpass").html("mot de pass correct");
			}
		}

		$(document).ready(function(){
			$("#register-form").submit(function(e){
				e.preventDefault();
				$.ajax({
					type:"POST",
					url:"ajaxData.php",
					data:$("#register-form").serialize(),
					dataType:"json",
					success:function(response){
						if(response.user){
							$("#chechusername").addClass("alert alert-danger");
							$("#chechusername").html(response.user);
						}else {
							if(response.email){
								$("#checkemail").addClass("alert alert-danger");
								$("#checkemail").html(response.email);
							}else{
								if(response.value){
									toastr.success(response.msg);
									$("#register-form")[0].reset();
									$("#login-form-link").addClass("active");
									$("#register-form-link").removeClass();
									$("#register-form").css("display","none");
									$("#login-form").css("display","block");
								}else {
									toastr.error(response.msg);
								}
								
							}
						}
						
					}
				})
			});

			$("#confirm-password_register").keyup(checkpassword);

			$(document).ready(function(){
			$("#login-form").submit(function(e){
				e.preventDefault();
				$.ajax({
					type:"POST",
					url:"ajaxData.php",
					data:$("#login-form").serialize(),
					dataType:"json",
					success:function(response){
						if(response.user){
							toastr.succes(response.user);
						}else{
							if(response.user){
								toastr.succes(response.user);
								window.location("index.php");
							}else{
								$("#checkpsw").addClass("alert alert-danger");
								$("#checkpsw").html(response.msg);
								toastr.error(response.msg);
							}
						}
					}
				})
			});
		})
		})


	</script>
</head>

<body>

    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" class="active" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="" method="post" role="form" style="display: none;">
									<div class="form-group">
										<input type="text" name="username_login" id="username_login" tabindex="1" class="form-control" placeholder="Username" required>
									</div>
									<div id="checkuser">

									</div>
									<div class="form-group">
										<input type="password" name="password_login" id="password_login" tabindex="2" class="form-control" placeholder="Password" required>
									</div>
									<div id="checkpsw">

									</div>
									<div class="form-group text-center">
										<input type="checkbox" tabindex="3" class="" name="remember" id="remember">
										<label for="remember"> Remember Me</label>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
												<div class="text-center">
													<a href="https://phpoll.com/recover" tabindex="5" class="forgot-password">Forgot Password?</a>
												</div>
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" action="https://phpoll.com/register/process" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="username_register" id="username_register" tabindex="1" class="form-control" placeholder="Username">
									</div>
									<div id="chechusername">

									</div>
									<div class="form-group">
										<input type="email" name="email_register" id="email_register" tabindex="1" class="form-control" placeholder="Email Address">
									</div>
									<div id="checkemail">

									</div>
									<div class="form-group">
										<input type="password" name="password_register" id="password_register" tabindex="2" class="form-control" placeholder="Password">
									</div>
									<div class="form-group">
										<input type="password" name="confirm-password_register" id="confirm-password_register" tabindex="2" class="form-control" placeholder="Confirm Password">
									</div>
									<div id="checkpass">

									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>