<?php
require 'session.php';
require 'co.php';
require 'time.php';
$fromusrid = $_SESSION['usrid'];
$tousrid = $_SESSION['tousrid'];
?>

<div class="module">
	<ol class="discussion" id="discussion">
  	<?php
	co_open();
  	$num = mysql_num_rows(mysql_query("select * from messages where (fromuid=$fromusrid and touid=$tousrid) or (fromuid=$tousrid and touid=$fromusrid)"));
	$numshow = 200;
	$from = ($num-$numshow)<0?0:($num-$numshow);
  	$qry = mysql_query("select * from messages where (fromuid=$fromusrid and touid=$tousrid) or (fromuid=$tousrid and touid=$fromusrid) order by sentdt asc limit $from, $numshow");
  	while($data=mysql_fetch_array($qry)){
		$usr = mysql_fetch_array(mysql_query("select * from users where Id='".$data['fromuid']."'"));
		$msg = ($data['fromuid']==$fromusrid?'self':'other');
		
		//$text = closetags(strip_tags($data['messagetext'],'<br><b><u><i><marquee><font>'));
		$text = $data['messagetext'];
		
		if($text!="" || $text!=null){
			echo '<li class="'.$msg.'">
					  <div class="messages">
							<div class="time">
								 <time datetime="'.$data['sentdt'].'">'.$usr['username'].' &#8226; '.(get_timeago(strtotime($data['sentdt']))).'</time>
							</div>
							<div id="content">
								 '.(str_replace('<br/>','\r',$text)).'
							</div>
					  </div>
				 </li>';
		}
   }
	co_close();
   ?>
	</ol>
</div>
<div class="msgbox">
	<table width="100%"><tr>
		<td valign="top"><input type="hidden" id="touid" value="<?php echo $tousrid;?>"/>
		<textarea id="msg" class="msg" placeholder="Your message"></textarea></td>
		<td valign="top" width="55px"><button type="button" id="send" class="btn send">Send</button></td>
	</tr></table>
</div>
<script>
/*var discussion = document.getElementById('discussion');
discussion.scrollTop = discussion.scrollHeight;*/
document.getElementById('msg').focus();
window.scrollTo(0,document.body.scrollHeight);
var nums=<?php echo $num;?>;
var timer;
// Crete Timer Interval check every 4s
function startTime(){
	timer = setInterval(function () {
		stopTimer();
		$.post('ui.php', {getdata:true,num:nums},function(data){
			//alert(data);
			if(data != ''){
				datas = data.split('||spliting||');
				if(datas.length > 1){
					nums=datas[1];
					document.getElementById("discussion").innerHTML += datas[0];
					if(sndck){
						window.scrollTo(0,document.body.scrollHeight);
						sndck=false;
					}else if(datas[0] != ''){
						window.scrollTo(0,window.pageYOffset+50);
					}
				}
			}
			startTime();
		});
	}, 4000);
}
// Stoping Timer Interval
function stopTimer(){
	clearInterval(timer);
}
startTime();
</script>