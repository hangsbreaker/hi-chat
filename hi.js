var sndck=false;
var valid=false;
var main = "ui.php";
(function($) {
	$(window).load(function() {
		document.getElementById("data").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
	});
	$(document).ready(function(e) {
		$("#data").load(main);		
		
		$('#login').live("click", function(){
			var usr = document.getElementById("usr").value;
			var pwd = document.getElementById("pwd").value;
			document.getElementById("notify").innerHTML="Processing...";
			$.post(main, {usr:usr,pwd:pwd} ,function(data) {
				if(data){
					location.reload();
				}else{
					document.getElementById("notify").innerHTML="The username and password do not match.";
				}
			});
		});
		$('#tologin').live("click", function(){
			$("#data").load(main);
		});
		$('#signup').live("click", function(){
			$.post(main, {signup:true},function(data){
				$("#data").load(data);
			});
		});
		$('#register').live("click", function(){
			document.getElementById("notify").innerHTML="Processing...";
			if(valid){
				var usr = document.getElementById("usr").value;
				var pwd = document.getElementById("pwd1").value;
				var email = document.getElementById("email").value;
				$.post(main, {register:true,user:usr,pwd:pwd,email:email},function(data){
					if(data){
						$("#data").load(main, function(responseTxt, statusTxt, xhr){
							if(statusTxt == "success"){
								document.getElementById("notify").innerHTML="Sign Up complete. Now you can login.";
							}
						});
					}else{
						
					}
				});
			}
		});
		$('#send').live("click", function(){
			var msg = document.getElementById("msg").value;
			var touid = document.getElementById("touid").value;
			sndck = true;
			document.getElementById("msg").value="";
			document.getElementById("msg").focus();
			$.post(main, {touid:touid,msg:msg},function(data){
			});
		});
		
		$('.friends').live("click", function(){
			document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
			document.getElementById("rmenu").innerHTML='<a href="#" class="search">&#10061;</a>';
			$.post(main, {friends:true},function(data){
				$("#datas").load(data);
				document.getElementById('tm').checked = false;
			});
		});
		$('.friend').live("click", function(){
			document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
			document.getElementById("rmenu").innerHTML='<a href="#" id="'+this.id+'" class="frinfo">&#10065;</a>';
			$.post(main, {message:true,id:this.id},function(data){
				$("#datas").load(data);
				document.getElementById('tm').checked = false;
			});
		});
		$('.fapv').live("click", function(){
			document.getElementById('s'+this.id).remove();
			$.post(main, {fapv:true,id:this.id},function(data){});
		});
		$('.fadd').live("click", function(){
			$.post(main, {fadd:true,id:this.id},function(data){});
		});
		$('.frinfo').live("click", function(){
			document.getElementById("rmenu").innerHTML='<a href="#" id="'+this.id+'" class="friend">&#11013;</a>';
			$.post(main, {frinfo:true},function(data){
				$("#datas").load(data);
			});
		});
		$('.delfriend').live("click", function(){
			var del = confirm("Are you sure delete this friend?");
			if(del){
				document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
				document.getElementById("rmenu").innerHTML='<a href="#" class="search">&#10061;</a>';
				$.post(main, {delfriend:true,id:this.id},function(data){
					if(data){
						$("#datas").load(data);
					}else{
						alert("Delete failed, Please try again.");
					}
				});
			}
		});
		
		
		$('#newgroup').live("click", function(){
			document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
			document.getElementById("rmenu").innerHTML='<a href="#" id="creategroup"><small>Create</small></a>';
			$.post(main, {newgroup:true,id:0},function(data){
				$("#datas").load(data);
				document.getElementById('tm').checked = false;
			});
		});
		$('#creategroup').live("click", function(){
			var gname = document.getElementById("gname");
			var member = document.getElementById("memberlist").value;
			gname.focus();
			if(gname.value != ''){
				$.post(main, {creategroup:true,gname:gname.value,member:member},function(data){
					if(data){
						document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
						document.getElementById("rmenu").innerHTML='<a href="#" id="newgroup"><small>New</small></a>';
						$.post(main, {groups:true},function(data){
							$("#datas").load(data);
						});
					}
				});
			}else{
				gname.focus();
			}
		});
		$('.editgroup').live("click", function(){
			document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
			document.getElementById("rmenu").innerHTML='<a href="#" id="'+this.id+'" class="savegroup"><small>Save</small></a>';
			$.post(main, {newgroup:true,id:this.id},function(data){
				$("#datas").load(data);
				document.getElementById('tm').checked = false;
			});
		});
		$('.savegroup').live("click", function(){
			var gname = document.getElementById("gname");
			var member = document.getElementById("memberlist").value;
			var gid = this.id;
			gname.focus();
			if(gname.value != ''){
				$.post(main, {savegroup:true,gname:gname.value,member:member,id:gid},function(data){
					if(data){
						document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
						document.getElementById("rmenu").innerHTML='<a href="#" id="'+gid+'" class="group">&#11013;</a>';
						$.post(main, {grinfo:true},function(data){
							$("#datas").load(data);
						});
					}
				});
			}else{
				gname.focus();
			}
		});
		$('.groups').live("click", function(){
			document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
			document.getElementById("rmenu").innerHTML='<a href="#" id="newgroup"><small>New</small></a>';
			$.post(main, {groups:true},function(data){
				$("#datas").load(data);
				document.getElementById('tm').checked = false;
			});
		});
		$('.group').live("click", function(){
			document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
			document.getElementById("rmenu").innerHTML='<a href="#" id="'+this.id+'" class="grinfo">&#10065;</a>';
			$.post(main, {gmessage:true,id:this.id},function(data){
				$("#datas").load(data);
				document.getElementById('tm').checked = false;
			});
		});
		$('#gsend').live("click", function(){
			var msg = document.getElementById("msg").value;
			var togid = document.getElementById("togid").value;
			sndck = true;
			document.getElementById("msg").value="";
			document.getElementById("msg").focus();
			$.post(main, {togid:togid,msg:msg},function(data){
			});
		});
		$('.grinfo').live("click", function(){
			document.getElementById("rmenu").innerHTML='<a href="#" id="'+this.id+'" class="group">&#11013;</a>';
			$.post(main, {grinfo:true},function(data){
				$("#datas").load(data);
			});
		});
		$('.exitgroup').live("click", function(){
			var del = confirm("Are you sure Exit Group?");
			if(del){
				document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
				document.getElementById("rmenu").innerHTML='<a href="#" id="newgroup"><small>New</small></a>';
				$.post(main, {exitgroup:true,id:this.id},function(data){
					if(data){
						$("#datas").load(data);
					}else{
						alert("Delete failed, Please try again.");
					}
				});
			}
		});
		
		$('.search').live("click", function(){
			document.getElementById("datas").innerHTML='<div align="center"><br><br><br><span>Loading...</span></div>';
			$.post(main, {search:true},function(data){
				$("#datas").load(data);
				document.getElementById('tm').checked = false;
			});
		});
		$('#logout').live("click", function(){
			$.post(main, {logout:true},function(data){
				$("#datas").load(data);
			});
		});
		
	});
}) (jQuery);