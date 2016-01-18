<?php include 'common_head.php'; ?>
		<title><?=PAGE_TITLE?></title>
<?php include 'common_body.php'; ?>

	<div class="comment">
		<h2>
		<?php 
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if($_POST["type"] == 'video') {
				if(preg_match("/(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/",$_POST["video"],$matches)) {
					$data = substr($matches[1],0,11);
					$name = substr($_POST["name"],0,32);
					setcookie("nickname",$name,time()+60*60*24*30);
					$comment = substr($_POST["comment"],0,1000);
					$vdata = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=".$data."&key=AIzaSyAsaxjTcUmDigVGFxPO-MdZijXdI8CmUEc&part=snippet,contentDetails,status&videoEmbeddable=true&videoSyndicated=true"),true)["items"];
					if(isset($vdata[0])) {
						$vdata = $vdata[0];
						$title = $vdata["snippet"]["title"];
						$thumbnail = $vdata["snippet"]["thumbnails"]["medium"]["url"];
						$type = "youtube";
						if($vdata["snippet"]["liveBroadcastContent"]=="none") {
							$duration = ideal_8601toseconds($vdata["contentDetails"]["duration"]);
						} else {
							$type = "youtubelive";
							$duration = 0;
						}
						$match = $db->query("SELECT * FROM youbulletin WHERE data=".$db->quote($data)." AND posttime>".$db->quote(time()-3600)."LIMIT 1")->fetch(PDO::FETCH_ASSOC);
						if(empty($match)) {
							$match = $db->query("SELECT * FROM youbulletin WHERE ip=".$db->quote($_SERVER['REMOTE_ADDR'])." AND posttime>".$db->quote(time()-60)."LIMIT 1")->fetch(PDO::FETCH_ASSOC);
							if(empty($match)) {
								$db->query("INSERT INTO youbulletin (type,data,title,duration,thumbnail,name,comment,ip,posttime,viewtime)
											VALUES (".$db->quote($type).",".$db->quote($data).",".$db->quote($title).",".$db->quote($duration).",".$db->quote($thumbnail).",".$db->quote($name).",".$db->quote($comment).",".$db->quote($_SERVER['REMOTE_ADDR']).",".$db->quote(time()).",".$db->quote(microtime(TRUE)).")");
								echo "Video \"".htmlspecialchars($title)."\" added successfully!";
							} else {
								echo "Please wait before adding another video!";
							}
						} else {
							echo "Video was posted recently!";
						}
					} else {
						echo "Invalid video!";
					}
				} else {
					echo "Invalid video!";
				}
				echo '<meta http-equiv="refresh" content="1; url=index.php">';
			} else { //post type comment
				$post = $db->query("SELECT * FROM youbulletin WHERE id=".$db->quote($_GET["id"]))->fetch(PDO::FETCH_ASSOC);
				if(!empty($post)){
					$commentdata = json_decode($post["commentdata"],true);
					$fail=false;
					if($commentdata) {
						foreach($commentdata as $comment) {
							if($comment["time"]>time()-10 && $comment["ip"]==$_SERVER['REMOTE_ADDR']) {
								$fail=true;
							}
						}
					}
					if($fail) {
						echo "Please wait before commenting!";
					} else {
						$commentdata[] = array("name"=>substr($_POST["name"],0,32),
												"comment"=>substr($_POST["comment"],0,1000),
												"ip"=>$_SERVER['REMOTE_ADDR'],
												"time"=>time(),
												"id"=>count($commentdata)+1);
						setcookie("nickname",substr($_POST["name"],0,32),time()+60*60*24*30);
						$db->query("UPDATE youbulletin SET comments=".$db->quote($post["comments"]+1).",commentdata=".$db->quote(json_encode($commentdata)).",viewtime=".$db->quote(microtime(TRUE))." WHERE id=".$db->quote($_GET["id"]));
						echo "Comment added!";
					}
				} else {
					echo "Invalid thread!";
				}
				echo '<meta http-equiv="refresh" content="1; url=thread.php?id='.$_GET["id"].'">';
			}
		} ?>
		</h2>
	</div>

<?php include 'common_foot.php'; ?>
