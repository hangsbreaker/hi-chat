<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
if(isset($_POST['usr'])){
// ============================= CHAT LOGIN ====================
	require 'co.php';
	
	$usr = $_POST['usr'];
	$pwd = md5($_POST['pwd']);

	co_open();
	$q = mysql_query("SELECT * FROM users WHERE username='$usr' AND password='$pwd'");
	$found = mysql_num_rows($q);
	co_close();

	if($found == 1) {
		session_start();
		$dt = mysql_fetch_array($q);
		$_SESSION['usr'] = $usr;
		$_SESSION['usrid'] = $dt['Id'];
		co_open();
		mysql_query("update users set authenticationTime = NOW(), IP = '".$_SERVER["REMOTE_ADDR"]."', port = 15145 where Id = ".$dt['Id']." limit 1");
		co_close();
		echo TRUE;
		exit();
	}else{
		echo FALSE;
		exit();
	}
	exit();
}else if(isset($_POST['msg'])){
// ============================= SEND MESSAGE ====================
	require 'co.php';
	//echo $_SESSION['usrid'];
	if($_POST['msg'] != ""){
		co_open();
		// update time
		$sources = array("&lt;i&gt;", "&lt;b&gt;", "&lt;u&gt;", "&lt;marquee&gt;", "&lt;h1&gt;", "&lt;h2&gt;", "&lt;h3&gt;", "&lt;h4&gt;", "&lt;/i&gt;", "&lt;/b&gt;", "&lt;/u&gt;", "&lt;/marquee&gt;", "&lt;/h1&gt;", "&lt;/h2&gt;", "&lt;/h3&gt;", "&lt;/h4&gt;");
		$replaces = array("<i>", "<b>", "<u>", "<marquee>", "<h1>", "<h2>", "<h3>", "<h4>", "</i>", "</b>", "</u>", "</marquee>", "</h1>", "</h2>", "</h3>", "</h4>");
		$msg = str_replace("'","\'",str_replace("\n","<br>",htmlentities(closetags($_POST['msg']),ENT_QUOTES)));
		$msg = str_replace($sources, $replaces, $msg);
		mysql_query("update users set authenticationTime = NOW(), IP = '".$_SERVER["REMOTE_ADDR"]."', port = 15145 where Id = ".$_SESSION['usrid']." limit 1");
		if(isset($_POST['touid'])){
			$qry = mysql_query("INSERT INTO `messages` (`fromuid`, `touid`, `sentdt`, `messagetext`) VALUES ('".$_SESSION['usrid']."', '".$_POST['touid']."', '".date("Y-m-d H:i:s")."', '".$msg."');");
		}else if(isset($_POST['togid'])){
			$qry = mysql_query("INSERT INTO `group_messages` (`fromuid`, `togid`, `sentdt`, `messagetext`) VALUES ('".$_SESSION['usrid']."', '".$_POST['togid']."', '".date("Y-m-d H:i:s")."', '".$msg."');");
		}
		co_close();
		if($qry){
			echo TRUE;
			exit();
		}else{
			echo FALSE;
			exit();
		}
	}
	exit();
}else if(isset($_POST['getdata'])){
// ============================= CHAT CONVERSATION ====================
	require 'co.php';
	require 'time.php';
	co_open();
	
	$mnum = $_POST['num'];
  	$num = mysql_num_rows(mysql_query("select * from messages where (fromuid=".$_SESSION['usrid']." and touid=".$_SESSION['tousrid'].") or (fromuid=".$_SESSION['tousrid']." and touid=".$_SESSION['usrid'].")"));
	
	if($mnum < $num){
		$qry = mysql_query("select * from messages where (fromuid=".$_SESSION['usrid']." and touid=".$_SESSION['tousrid'].") or (fromuid=".$_SESSION['tousrid']." and touid=".$_SESSION['usrid'].") order by sentdt asc limit ".($_POST['num']).",".$num);
		while($data=mysql_fetch_array($qry)){
			$usr = mysql_fetch_array(mysql_query("select * from users where Id='".$data['fromuid']."'"));
			$msg = ($data['fromuid']==$_SESSION['usrid']?'self':'other');
			
			$text = $data['messagetext'];//closetags(strip_tags($data['messagetext'],'<br><b><u><i><marquee><font>'));
		
			if($text!="" || $text!=null){
				echo '<li class="'.$msg.'">
							<div class="messages">
							<div class="time">
								<time datetime="'.$data['sentdt'].'">'.$usr['username'].' &#8226; '.(get_timeago(strtotime($data['sentdt']))).'</time>
							</div>
								<div id="content">'.$text.'</div>
							</div>
						</li>';
				$mnum++;
			}
		}
		//$text.'||spliting||'.$msg.'||spliting||'.$data['sentdt'].'||spliting||'.$usr['username'].'||spliting||'.(get_timeago(strtotime($data['sentdt']))).
	}
	echo '||spliting||'.$mnum;
	co_close();
	exit();
}else if(isset($_POST['getgdata'])){
// ============================= CHAT GROUP CONVERSATION ====================
	require 'co.php';
	require 'time.php';
	co_open();
	
	$mnum = $_POST['num'];
  	$num = mysql_num_rows(mysql_query("select * from group_messages where togid=".$_SESSION['togrpid']));
	
	if($mnum < $num){
		$qry = mysql_query("select * from group_messages where togid=".$_SESSION['togrpid']." order by sentdt asc limit ".($_POST['num']).",".$num);
		while($data=mysql_fetch_array($qry)){
			$usr = mysql_fetch_array(mysql_query("select * from users where Id='".$data['fromuid']."'"));
			$msg = ($data['fromuid']==$_SESSION['usrid']?'self':'other');
			
			$text = $data['messagetext'];//closetags(strip_tags($data['messagetext'],'<br><b><u><i><marquee><font>'));
		
			if($text!="" || $text!=null){
				echo '<li class="'.$msg.'">
							<div class="messages">
							<div class="time">
								<time datetime="'.$data['sentdt'].'">'.$usr['username'].' &#8226; '.(get_timeago(strtotime($data['sentdt']))).'</time>
							</div>
								<div id="content">'.$text.'</div>
							</div>
						</li>';
				$mnum++;
			}
		}
		//$text.'||spliting||'.$msg.'||spliting||'.$data['sentdt'].'||spliting||'.$usr['username'].'||spliting||'.(get_timeago(strtotime($data['sentdt']))).
	}
	echo '||spliting||'.$mnum;
	co_close();
	exit();
}else if(isset($_POST['message'])){
// ============================= OPEN CHAT AND UPDATE READ ON FRIEND ====================
	require 'co.php';
	co_open();
	$_SESSION['tousrid'] = $_POST['id'];
	$qread = mysql_query("UPDATE messages set `read`=1,`readdt` = '".DATE("Y-m-d H:i")."' WHERE `touid`= ".$_POST['id']." AND `fromuid`=".$_SESSION['usrid']." AND `read` = 0");
	co_close();
	echo 'chat.php';
	exit();
}else if(isset($_POST['gmessage'])){
// ============================= OPEN GROUP CHAT AND UPDATE READ ON FRIEND ====================
	require 'co.php';
	co_open();
	$_SESSION['togrpid'] = $_POST['id'];
	$qread = mysql_query("UPDATE group_messages set `read`=1,`readdt` = '".DATE("Y-m-d H:i")."' WHERE `fromuid`= ".$_SESSION['usrid']." AND `read` = 0");
	co_close();
	echo 'gchat.php';
	exit();
}else if(isset($_POST['cuser'])){
// ============================= CHECK USER ====================
	require 'co.php';
	co_open();
	$quser = mysql_num_rows(mysql_query("SELECT username FROM users WHERE username='".$_POST['user']."'"));
	co_close();
	if($quser==1){
		echo TRUE;
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['cemail'])){
// ============================= CHECK EMAIL ====================
	require 'co.php';
	co_open();
	$qmail = mysql_num_rows(mysql_query("SELECT email FROM users WHERE email='".$_POST['email']."'"));
	co_close();
	if($qmail==1){
		echo TRUE;
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['register'])){
// ============================= SIGN UP NEW USER ====================
	require 'co.php';
	co_open();
	$addnew = mysql_query("INSERT INTO users (`username`,`password`,`email`) VALUES ('".$_POST['user']."',md5('".$_POST['pwd']."'),'".$_POST['email']."')");
	co_close();
	if($addnew==1){
		echo TRUE;
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['fsearch'])){
// ============================= SEARCH FRIENDS ====================
	require 'co.php';
	co_open();
	$qfind = mysql_query("select * from users where username like '%".$_POST['username']."%' and username!='".$_SESSION['usr']."' order by username limit 100");
	$nfind = mysql_num_rows($qfind);
	if($nfind > 0){
		while($dfind=mysql_fetch_array($qfind)){
			$qfriend = mysql_fetch_array(mysql_query("select * from friends where (providerId=".$dfind['Id']." and requestId=".$_SESSION['usrid'].") or (providerId=".$_SESSION['usrid']." and requestId=".$dfind['Id'].")"));
			if($_POST['friend'] == 1){
				if($qfriend['status']==1){
				$memberlist = json_decode('['.$_POST['memberlist'].']');
				echo '<li>
							<strong>
							<a href="#" id="'.$dfind['Id'].'" style="min-width:85%;">'.$dfind['username'].'</a>
							'.(!in_array($dfind['Id'],$memberlist)?'<a href="#" id="'.$dfind['Id'].'" class="faddtog" onclick="remove()">+</a>':'').'
							</strong>
						</li>';
				}
			}else{
			echo '<li>
						<strong><a href="#" id="'.$dfind['Id'].'" class="friend">'.$dfind['username'].'</a>
						'.($qfriend['status']==0?'<a href="#" id="'.$dfind['Id'].'" class="fadd" onclick="remove()">+</a>':'').'
						</strong>
					</li>';
			}
		}
	}else{
		echo '<li style="padding:10px;text-align:center;">
					<strong>User not found.</strong>
				</li>';
	}
	co_close();
	exit();
}else if(isset($_POST['fapv'])){
// ============================= APPROVE FRIENDS ====================
	require 'co.php';
	co_open();
	$aprv = mysql_query("UPDATE friends set status=1 where providerId=".$_POST['id']." and requestId=".$_SESSION['usrid']);
	co_close();
	if($aprv==1){
		echo TRUE;
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['fadd'])){
// ============================= ADD FRIENDS ====================
	require 'co.php';
	co_open();
	$qadd = mysql_query("INSERT INTO friends (providerId,requestId,status) VALUES (".$_SESSION['usrid'].",".$_POST['id'].",0)");
	co_close();
	if($qadd==1){
		echo TRUE;
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['delfriend'])){
// ============================= DELETE FRIENDS ====================
	require 'co.php';
	co_open();
	$delf = mysql_query("DELETE FROM friends WHERE providerId=".$_POST['id']." or requestId=".$_POST['id']);
	co_close();
	if($delf==1){
		echo 'friends.php';
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['exitgroup'])){
// ============================= EXIT GROUP ====================
	require 'co.php';
	co_open();
	// get data group
	$dtgroup = mysql_fetch_array(mysql_query("SELECT * FROM groups WHERE id=".$_POST['id']));
	// get member change to json as array
	$member = json_decode($dtgroup['member']);
	$del = array($_SESSION['usrid']);
	// diff then reset array index
	$member = array_values(array_diff($member,$del));
	// change back to string and update member
	$member = json_encode($member);
	$delf = mysql_query("UPDATE groups SET member='$member' WHERE id=".$_POST['id']);
	co_close();
	if($delf==1){
		echo 'groups.php';
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['creategroup'])){
// ============================= CREATE GROUP ====================
	require 'co.php';
	co_open();
	$gadd = mysql_query("INSERT INTO groups (name,admin,member) VALUES ('".$_POST['gname']."','[".$_SESSION['usrid']."]','[".$_POST['member']."]')");
	co_close();
	if($gadd==1){
		echo TRUE;
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['savegroup'])){
// ============================= SAVE EDIT GROUP ====================
	require 'co.php';
	co_open();
	$gadd = mysql_query("UPDATE groups SET name='".$_POST['gname']."', member='[".$_POST['member']."]' WHERE id=".$_SESSION['togrpid']);
	co_close();
	if($gadd==1){
		echo TRUE;
	}else{
		echo FALSE;
	}
	exit();
}else if(isset($_POST['signup'])){
	echo 'signup.php';
	exit();
}else if(isset($_POST['friends'])){
	echo 'friends.php';
	exit();
}else if(isset($_POST['frinfo'])){
	echo 'frinfo.php';
	exit();
}else if(isset($_POST['grinfo'])){
	echo 'grinfo.php';
	exit();
}else if(isset($_POST['groups'])){
	$_SESSION['togrpid'] = 0;
	echo 'groups.php';
	exit();
}else if(isset($_POST['newgroup'])){
	$_SESSION['togrpid'] = $_POST['id'];
	echo 'newgroup.php';
	exit();
}else if(isset($_POST['search'])){
	echo 'search.php';
	exit();
}else if(isset($_POST['logout'])){
	echo 'logout.php';
	exit();
}

if(!isset($_SESSION['usr']) && !isset($_SESSION['pwd'])){
	include 'login.php';
}else{
?>
<div class="page">
	<!-- Checkbox to toggle the menu -->
	<input type="checkbox" id="tm" />
	<!-- The menu -->
	<ul class="sidenav">
		<li><a href="#" class="friends"><b>Friends</b></a></li>
		<li><a href="#" class="groups"><b>Groups</b></a></li>
		<li><a href="#" class="search"><b>Search</b></a></li>
		<li><hr></li>
		<li><a href="#" id="logout"><b>Logout</b></a></li>
	</ul>
	<!-- Content area -->
	<section>
		<!-- Label for #tm checkbox -->
		<label for="tm" id="side">&equiv;</label>
		<header class="top-bar">
			 <div class="left">
				  <h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hi</h1>
			 </div>
			 
			 <div class="right">
				  <h1><div id="rmenu"><a href="#" class="search">&#10061;</a></div></h1>
			 </div>
		</header>
	</section>
</div>
<div id="datas">
<?php include 'friends.php';?>
</div>
<?php
}
?>