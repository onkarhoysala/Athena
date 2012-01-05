<div id='loginbox' style='margin-right:10px'>
	
	<div id='login'>
		<form action='bin/login.php' method='post' id='loginform' style='color:white'>
		Email <input type='text' name='email' id="email" style='font-size:8pt' class="required error"/>
		Password <input type='password' name='password' style='font-size:8pt' id='password' class="required error"/>
		<input type='submit' id='submitbutton' value="Login"/><br/><br/>
		
		<span style='float:left;padding-left:15px'>New users, sign up <span style='color:#eee;cursor:pointer;text-decoration:underline' onclick='signup();'>here</span></span>
		<span style='float:right'><a href='forgot.php' style='color:#eee;text-decoration:underline'>Forgot password?</a></span>
		
		<br/><span style='color:black;font-weight:bold'>
			<?php
			
				if(isset($_GET['error']))
				{
					echo "<script>$('#loginbox').show();</script>";
					switch($_GET['error'])
					{
						case '1': echo "Invalid email or password"; break;
						case '2': echo "Please enter the email and password"; break;
					}
				}
			?>
		</span>
		</form>
	</div>
</div>

