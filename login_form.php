
<div id="loginForm">
	<h1>ADMIN</h1>
	<div id="form_error">
		<?php if(isset($_GET["error"])){
			echo '
				<div class="ui-widget">
					<div class="ui-state-error ui-corner-all" style="margin:1%;">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
						<strong>Alert:</strong> '.$_GET["error"].'</p>
					</div>
				</div>
			'; 
			}
		?>
	</div>
	<form id="login_form" method="post" action="zap.login.php">
	<input name="user" type="text" id="user" placeholder="username" />
	<br />
	<input name="pass" type="password" id="pass" placeholder="***********" />
	<br>
	<input type="hidden" name="location" value="<?php echo $_SERVER['PHP_SELF'];?>">
	<button id="login_submit" name="login_submit" type="submit">LOGIN</button>
	</form>
</div>


<script>
//1 Line of jQuery - Wayne Roddy - Input Form Validation by type and FORM process
//form variables
blank_msg = "You have blank fields that are required.";
char_msg  = "Please check for Special Characters";
at_msg    = "Where is the @ symbol?";
tel_msg	  = "Digits Only, please.";
success_msg = "Thank You for Logging In!";
var specialChars = "<>!#$%^&*()_+[]{}?:;|\"\\/~`=";
var telChars = "0123456789";
var sc_check = function(string){for(i = 0; i < specialChars.length;i++){if(string.indexOf(specialChars[i]) > -1){return true;}}return false;}
var tel_check = function(string){for(i = 0; i < telChars.length;i++){if(string.indexOf(telChars[i]) > -1){return true;}}return false;}

//case form return
function validateLoginForm(fields_1lj){var allValid = [];for(var i=0; i<fields_1lj.length; i++){var this_field = "#"+fields_1lj[i];if($(this_field).val()<1){invalidClass(this_field, blank_msg)}else{if($(this_field).attr("type")=="text"){ if(sc_check($(this_field).val()) == false){validClass(this_field)}else{invalidClass(this_field, char_msg)}}if($(this_field).attr("type")=="email"){if($(this_field).val().indexOf("@") > -1){validClass(this_field)} else {invalidClass(this_field, at_msg)}}if($(this_field).attr("type")=="tel"){if(tel_check($(this_field).val()) == true){validClass(this_field)}else{invalidClass(this_field, tel_msg)}}}}
function validClass(vfield){ $(vfield).removeClass("errorField"); $(vfield).addClass("validField"); allValid.push("yes");} 
function invalidClass(invfield, msg){ $(invfield).addClass("errorField"); $("#form_error").html(msg); allValid.push("no");} 
 
	if(!($.inArray("no", allValid) > -1)){  
		$("#login_form").submit(); 	
	}
	
}		

$("#login_submit").click(function(e) {
  e.preventDefault();
  //validation these fields
  var fields_1lj = new Array("user", "pass"); validateLoginForm(fields_1lj);
});

</script>		
