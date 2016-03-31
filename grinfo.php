<?php
require 'session.php';
require 'co.php';
require 'time.php';
$togrpid = $_SESSION['togrpid'];
co_open();
$grp = mysql_fetch_array(mysql_query("select * from groups where id='$togrpid'"));

echo '<div class="flist">
			<ol>
				<li><a href="#" class="llist editgroup" id="'.$togrpid.'">Group Name: '.$grp['name'].'</a></li>
			</ol>
		</div>';
echo '<div class="flist">
			<ol>';
			$member = json_decode($grp['member']);
			foreach($member as $usr){
				$dtusr = mysql_fetch_array(mysql_query("select username from users where Id=".$usr));
				echo '<li><a href="#" id="'.$usr.'" class="friend">'.$dtusr['username'].'</a></li>';
			}
echo '	</ol>
		</div>
		<div class="flist">
			<ol>
				<li style="text-align:center;background:#f33;font-weight:bold;">
					<a href="#" class="llist exitgroup" style="color:#fff;" id="'.$togrpid.'">Exit Group</a>
				</li>
			</ol>
		</div>';
co_close();
?>