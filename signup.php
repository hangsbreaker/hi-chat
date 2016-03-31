<div class="Hi">Hi</div>
<form method="post" class="form">
	<div id="notify"></div>
	<input type="text" id="usr" placeholder="Username" onkeyup="cekusername()"/>
	<input type="password" id="pwd1" placeholder="Password" onkeyup="cekpass1()"/>
	<input type="password" id="pwd2" placeholder="Retype password" onkeyup="cekpass2()"/>
	<input type="text" id="email" placeholder="Email" onkeyup="cekemail()"/>
	<button type="button" id="register" class="btn btn-hi" onclick="registration()">Sign Up</button>
</form>
<br><br><br>
<div class="footer">
	<p>
		<small>Registered?</small> <a href="#" id="tologin" class="linkfoot">Login</a>
	<p>
</div>
<script>
var notify = document.getElementById("notify");
var usr = document.getElementById('usr');
var pass1 = document.getElementById('pwd1');
var pass2 = document.getElementById('pwd2');
var email = document.getElementById('email');
var validity = [0,0,0];
function cekusername(){
	var re =  /^[a-zA-Z0-9]+$/;
	if(usr.value.length < 6 || usr.value.length > 30){
		notify.innerHTML="Username should between 6 and 30 characters.";
		usr.style.borderBottomColor = "red";
		usr.focus();
		validity[0]=0;
		valid=false;
	}else{
		notify.innerHTML=""; 
		if (re.test(usr.value)){
			$.post(main, {cuser: true, user:usr.value},function(data){
				if(data){
					notify.innerHTML="Someone already has that username.";
					usr.style.borderBottomColor = "red";
					usr.focus();
					validity[0]=0;
					valid=false;
				}else{
					usr.style.borderBottomColor = "#2ecc71";
					validity[0]=1;
					if(validity.indexOf(0)>-1){
						valid=false;
					}else{
						valid=true;
					}
				}
			});
		}else{
			notify.innerHTML="Username should letter and number.";
			usr.style.borderBottomColor = "red";
			usr.focus();
			valid=false;
		}
	}
}
function cekpass1(){
	var re = /^[a-zA-Z0-9]+$/;
	if(pass1.value.length < 6){
		notify.innerHTML="Password should contain at least 6 characters.";
		pass1.style.borderBottomColor = "red";
		pass2.value="";
		pass1.focus();
		validity[1]=0;
		valid=false;
	}else{
		if(re.test(pass1.value)){
			pass2.value="";
			notify.innerHTML = "";
			pass1.style.borderBottomColor = "#2ecc71";
			validity[1]=1;
		}else{
			notify.innerHTML="Password should letter and number.";
			pass1.style.borderBottomColor = "red";
			pass1.focus();
			validity[1]=0;
			valid=false;
		}
	}
}
function cekpass2(){
	if(pass1.value != pass2.value || pass2.value == ""){
		notify.innerHTML="The passwords don't match.";
		pass2.style.borderBottomColor = "red";
		pass2.focus();
		validity[2]=0;
		valid=false;
	}else{
		notify.innerHTML = "";
		pass2.style.borderBottomColor = "#2ecc71";
		validity[2]=1;
		if(validity.indexOf(0)>-1){
			valid=false;
		}else{
			valid=true;
		}
	}
}
function cekemail(){
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;
	if (email.value == '' || !re.test(email.value)){
		notify.innerHTML="Please enter a valid email address.";
		email.style.borderBottomColor = "red";
		email.focus();
		validity[3]=0;
		valid=false;
	}else{
		notify.innerHTML="";
		$.post(main, {cemail:true, email:email.value},function(data){
			if(data){
				notify.innerHTML="Email is already registered by someone.";
				email.style.borderBottomColor = "red";
				email.focus();
				validity[3]=0;
				valid=false;
			}else{
				email.style.borderBottomColor = "#2ecc71";
				validity[3]=1;
				if(validity.indexOf(0)>-1){
					valid=false;
				}else{
					valid=true;
				}
			}
		});
	}
}
function registration(){
	if(!valid){
		cekemail();
		cekpass2();
		cekpass1();
		cekusername();
		notify.innerHTML="Please fill all the blank.";
	}
}
</script>