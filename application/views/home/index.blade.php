@layout('layout.default')
@section('title') Welcome to LaraPlus @endsection
@section('internal_css')
<style type="text/css">
	#home_box{
		/*display: none;*/
	}
	#trigger_log, #trigger_signup, #trigger_cancel{
		margin-left: 5px;
		width: 80px;
	}
	#trigger_cancel{
		display: none;
	}
	#login_box, #signup_box{
		width: 300px;
		margin: auto;
		margin-top: 15px;
		display: none;
	}
	#log_control_box, #signup_control_box{
		padding-top: 10px;
		margin-bottom: 20px;
		text-align: right;
		display: none;
	}
	#legend_h1{
		font-size: 28pt;
		font-family: 'Ubuntu';
		margin: 0 0 10px 0;
		letter-spacing: 2px;
	}
	#login_msg{
		font-weight: bold;
		font-size: 20px;
	}
	#email_login, #password_login, #firstname, #lastname, #email, #password, #confirm_password{
		width: 286px;
		height: 30px;
	}
	#gender{
		width: 298px;
		height: 30px;
	}
	#submit_login, #submit_signup{
		width: 298px;
	}
	#login_err_box, #signup_err_box{
		width: 248px;
		text-align: center;
		font-weight: bold;
		display: none;
	}
	#logging_in_box{
		width: 200px;
		text-align: center;
		margin: 100px auto;
		display: none;
	}
</style>
@endsection
@section('content')
	<div id="home_box">
		<h2>Welcome to LaraPlus</h2>
		<p>
			Our main goal is to connect to your friends and love ones!
		</p>
		<p>
			How about you get started by <a href="/register">creating an account</a>? :)
		</p>

		<h2>But who we are?</h2>
		<p>
			<button class="btn btn-info">Learn more about us!</button>
		</p>
	</div>

	<div id="login_box" class="well">
		{{ Form::open('users/validate_login', 'POST', array('id'=>'login_form')) }}
			<fieldset>
				<legend><center><h1 id="legend_h1">LaraPlus</h1></center></legend>
				{{ Form::text('email_login', '', array('id'=>'email_login', 'placeholder'=>'Email')) }}
				{{ Form::password('password_login', array('id'=>'password_login', 'placeholder'=>'Password')) }}

				<div id="login_err_box" class="alert alert-error"></div>

				<button type="submit" id="submit_login" class="btn btn-info btn-large">Login</button>
			</fieldset>
		{{ Form::close() }}
	</div>

	<div id="signup_box" class="well">
		{{ Form::open('users/validate_registration', 'POST', array('id'=>'registration_form')) }}
			<fieldset>
				<legend><center><h1 id="legend_h1">LaraPlus</h1></center></legend>
				{{ Form::text('firstname', '', array('id'=>'firstname', 'placeholder'=>'First name', 'maxlength'=>'30')) }}
				{{ Form::text('lastname', '', array('id'=>'lastname', 'placeholder'=>'Last name', 'maxlength'=>'30')) }}
				{{ Form::text('email', '', array('id'=>'email', 'placeholder'=>'Email')) }}				
				{{ Form::password('password', array('id'=>'password', 'placeholder'=>'Password')) }}
				<select name="gender" id="gender">
					<option value="">Gender</option>
					<option value="Female">Female</option>
					<option value="Male">Male</option>
				</select>
				<div id="signup_err_box" class="alert alert-error"></div>
				<button type="submit" id="submit_signup" class="btn btn-primary btn-large">Signup</button>
			</fieldset>
		{{ Form::close() }}
	</div>

	<div id="logging_in_box">
		<h2>Logging in...</h2>
		Please wait...
	</div>
@endsection
@section('js_scripts')
<script>
	(function(){
		var email_login = $("#email_login"), password_login = $("#password_login"), login_err_box = $("#login_err_box"), firstname= $("#firstname"), lastname= $("#lastname"), email = $("#email"), password = $("#password"), confirm_password = $("#confirm_password"), gender = $("#gender"), signup_err_box = $("#signup_err_box"), registration_form = $("#registration_form"), login_form = $("#login_form"), logging_in_box = $("#logging_in_box"), l = window.location, base_url = l.protocol + "//" + l.host + "/";
		$("#submit_login").on("click", function(e){
			e.preventDefault();
			login_err_box.slideUp().text('');
			if(validate_fields()){
				login_err_box.slideDown().text('Empty fields!');
				return false;
			}else{
				$.ajax({
					type : 'POST',
					url : base_url + 'ajax/validate_login',
					dataType : 'json',
					data : login_form.serialize(),

					success : function(data){
						if(data.error){
							setTimeout(function(){ login_err_box.slideDown().text(data.msg); }, 500);
						}else{
							$("#login_box").slideUp();
							logging_in_box.show();
							setTimeout(function(){
								window.location.href = data.msg
							}, 2000);
						}
					}
				});
			}
		});

		$("#submit_signup").on("click", function(e){
			e.preventDefault();
			signup_err_box.slideUp().text('');
			if(validate_signup_fields()){
				$.ajax({
					type : 'POST',
					url : base_url + 'ajax/validate_registration',
					dataType : 'json',
					data : registration_form.serialize(),

					success : function(data){
						if(data.error){
							setTimeout(function(){ signup_err_box.slideDown().text(data.msg); }, 500);
						}else{
							$("#signup_box").slideUp();
							logging_in_box.show();
							setTimeout(function(){
								window.location.href = data.msg
							}, 2000);
						}
					}
				});
			}
		});

		function validate_signup_fields(){
			if(firstname.val().length == 0 || lastname.val().length == 0 || email.val().length == 0 || password.val().length == 0 ||gender.val().length == 0){
				setTimeout(function(){ signup_err_box.slideDown().text('Empty fields!'); }, 500);
				return false;
			}else{
				var userReg = /^[a-zA-Z0-9]{1,30}$/;
				if(!userReg.test(firstname.val()) || !userReg.test(lastname.val())){
					setTimeout(function(){ signup_err_box.slideDown().text('Names should not contain special characters.'); }, 500);
					return false;
				}
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test(email.val())){
					setTimeout(function(){ signup_err_box.slideDown().text('Email address is not valid.'); }, 500);
					return false;
				}

				if(password.val().length < 6){
					setTimeout(function(){ signup_err_box.slideDown().text('Passwords should be atleast 6 characters long'); }, 500);
					return false;
				}
			}
			return true;
		}

		function validate_fields(){
			return (email_login.val().length == 0 || password_login.val().length == 0) ? true : false;
		}

		$("#trigger_log").on("click", function(){
			$("#home_box").hide();
			$("#signup_box").hide();
			$("#trigger_log").hide();
			$("#trigger_signup").show();
			$("#trigger_cancel").show();
			$("#login_box").fadeIn(1000);
		});
		$("#trigger_signup").on("click", function(){
			$("#login_box").hide();
			$("#home_box").hide();
			$("#trigger_signup").hide();
			$("#trigger_log").show();
			$("#trigger_cancel").show();
			$("#signup_box").fadeIn(1000);
		});		
		$("#trigger_cancel").on("click", function(){
			$("#login_box").hide();
			$("#signup_box").hide();
			$("#trigger_cancel").hide();

			$("#trigger_signup").show();
			$("#trigger_log").show();
			$("#home_box").show();
		});
	})();
</script>
@endsection