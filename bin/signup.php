<form id='signup'>
	<h3>Registration</h3>
	<p>Email: <input type='text' id='newemail' class="required error" /></p>
	<p>Password <input type='password' id='p1' class="required error"/></p>
	<p>Re-enter password: <input type='password' id='p2' class="required error"/></p>
	<p>First name: <input type='text' id='fname' class="required error"/></p>
	<p>Second name: <input type='text' id='sname' class="required error"/></p>
	<p>Phone number: <input type='text' id='phone' class="required error"/></p>
	<p>Designation: <input type='text' id='designation' class="required error"/></p>
	<p>Enter Your Security Question.This can be used to reset your password.</p>
	<p>  <input type='text' id='question' size='40' class="required error"/></p>
	<p>Your Answer:  <input type='text' id='answer' class="required error"/></p>
	<p><input type='button' value='Register' onclick="registeruser();$('#loginbox').css('display','block');" />
	   <input type='reset' value='Reset' /></p>
	<p><a href='index.php' class='custombutton'>Cancel</a></p>
	<span id='confirm'></span>
</form>
