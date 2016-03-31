<?php
require 'session.php';
require 'co.php';

$member = '';
$grbname='';
co_open();
// get data group
$groups = mysql_query("SELECT * FROM groups WHERE id=".$_SESSION['togrpid']);
if(mysql_num_rows($groups) > 0){
	// get member
	$dtgroup = mysql_fetch_array($groups);
	$grbname = $dtgroup['name'];
	$member = str_replace("[","",str_replace("]","",$dtgroup['member']));
}
$member = $member==''?$_SESSION['usrid']:$member;
co_close();
?>
<div class="flist">
	<ol>
		<li><input type="text" id="gname" placeholder="Group Name" value="<?php echo $grbname;?>"/></li>
	</ol>
</div>

<div class="flist" style="margin-top:0px;padding-top:0px;">
	<input type="text" id="find" placeholder="Search Friends and add to group" onkeyup="seacrhf()"/>
	<br/><hr/>
	<ol id="fresut">
	</ol>
</div>
<input type="hidden" id="memberlist" value="<?php echo $member;?>"/>
<script>
function seacrhf(){
	var find = document.getElementById('find');
	var memberlist = document.getElementById('memberlist').value;
	var re =  /^[a-zA-Z0-9]+$/;
	if (re.test(find.value)){
		document.getElementById('fresut').innerHTML='<li style="padding:10px;text-align:center;"><strong>Searching...</strong></li>';
		$.post(main, {fsearch: true, username:find.value,memberlist:memberlist,friend:1},function(data){
			document.getElementById('fresut').innerHTML=data;
		});
	}else{
		document.getElementById('fresut').innerHTML='<li style="padding:10px;text-align:center;"><strong>User not found.</strong></li>';
	}
}

$('.faddtog').live("click", function(){
	var memlist = document.getElementById('memberlist').value.split(',');
	if(memlist.indexOf(this.id) == -1){
		document.getElementById('memberlist').value+= ','+this.id;
	}
});
</script>