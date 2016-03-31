<?php
require 'session.php';
require 'co.php';
?>
<div class="flist">
	<ol>
<?php
$userid=$_SESSION['usrid'];
co_open();
$qry = mysql_query("select u.Id, u.username, (NOW()-u.authenticationTime) as authenticateTimeDifference, u.IP, 
							f.providerId, f.requestId, f.status, u.port 
							from friends f left join users u on 
							u.Id = if ( f.providerId = ".$userid.", f.requestId, f.providerId ) 
							where f.providerId = ".$userid."  or f.requestId = ".$userid." order by u.username asc");

if(mysql_num_rows($qry) > 0){
$usrid = array();
while($data=mysql_fetch_array($qry)){
	$status = "offline";
	if ($data['status']==0 && $data['providerId']==$userid){
		$status = "Waiting";
	}else if ($data['status']==0){
		$status = "Request";
	}else if($data['authenticateTimeDifference'] < 120){
		$status = "Online";
	}
	if(!in_array($data['username'],$usrid)){
		echo '<li>
					<a href="#" id="'.$data['Id'].'" class="friend">'.$data['username'].' <span style="float:right;" id="s'.$data['Id'].'">'.$status.'</span></a>
					'.($status=='Request'?'<a href="#" id="'.$data['Id'].'" class="fapv" onclick="remove()">+</a>':'').'
				</li>';
		array_push($usrid,$data['username']);
	}
}
}else{
		echo '<li><a href="#" class="search">You don\'t have any Friends.</a></li>';
}
co_close();
?>
	</ol>
</div>