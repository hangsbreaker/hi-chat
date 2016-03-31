<div class="flist">
	<input type="text" id="find" placeholder="Search by Username" onkeyup="seacrhf()"/>
	<br/><hr/>
	<ol id="fresut">
	</ol>
</div>
<script>
function seacrhf(){
	var find = document.getElementById('find');
	var re =  /^[a-zA-Z0-9]+$/;
	if (re.test(find.value)){
		document.getElementById('fresut').innerHTML='<li style="padding:10px;text-align:center;"><strong>Searching...</strong></li>';
		$.post(main, {fsearch: true, username:find.value,friend:0},function(data){
			document.getElementById('fresut').innerHTML=data;
		});
	}else{
		document.getElementById('fresut').innerHTML='<li style="padding:10px;text-align:center;"><strong>User not found.</strong></li>';
	}
}
</script>