<?php
require 'session.php';
require 'co.php';
?>
<div class="flist">
	<ol>
<?php
$userid=$_SESSION['usrid'];
co_open();
$qry = mysql_query("select * from groups order by name asc");
$ngroup = 0;
if(mysql_num_rows($qry)>0){
	while($data=mysql_fetch_array($qry)){
		$member = json_decode($data['member']);
		if(in_array($userid,$member)){
			echo '<li>
						<a href="#" id="'.$data['id'].'" class="group">'.$data['name'].'</a>
					</li>';
			$ngroup++;
		}
	}
}
if($ngroup==0){
		echo '<li><a href="#">You don\'t have any Group.</a></li>';
}
co_close();
?>
	</ol>
</div>