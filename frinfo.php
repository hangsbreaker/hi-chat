<?php
require 'session.php';
require 'co.php';
require 'time.php';
$tousrid = $_SESSION['tousrid'];
co_open();
$frn = mysql_fetch_array(mysql_query("select * from users where Id='$tousrid'"));
co_close();
echo '<div class="flist">
			<ol>
				<li><a href="#" class="llist">Last Login: '.$frn['authenticationTime'].'</a></li>
				<li><a href="#" class="llist">Username: '.$frn['username'].'</a></li>
				<li><a href="#" class="llist">Email: '.$frn['email'].'</a></li>
			</ol>
		</div>
		<div class="flist">
			<ol>
				<li style="text-align:center;background:#f33;font-weight:bold;">
					<a href="#" class="llist delfriend" style="color:#fff;" id="'.$tousrid.'">Delete Friend</a>
				</li>
			</ol>
		</div>';
?>