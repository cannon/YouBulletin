<?php include 'common_head.php'; 

	$post = $db->query("SELECT * FROM youbulletin WHERE id=".$db->quote($_GET["id"]))->fetch(PDO::FETCH_ASSOC);
	
	?>

	<title><?=PAGE_TITLE?> - <?php if(empty($post)) { echo "Video Not Found"; } else { echo $post["title"]; } ?></title>

<?php include 'common_body.php'; ?>

	<?php if(empty($post)) { ?>
	<div class="comment">
		<h2>Video not found!</h2>
	</div>
	<?php } else { ?>
	<div class="comment thread">		
		<div class="info">
			<div class="title"><?=htmlspecialchars($post["title"])?></div>
			<div class="postedby">posted by <span class="namefont"><?=htmlspecialchars($post["name"])?></span></div>
			<div class="date"><?=timeago($post["posttime"])?> ago</div>
			<div class="replylink"><?=$post["comments"]?> comment<?=splural($post["comments"])?></div>
			<div class="views"><?=$post["views"]?> view<?=splural($post["views"])?></div>	
			<div class="id">ID: <?=$post["id"]?></div>
		</div>
		<div class="container">
			<iframe id="video<?=$post["id"]?>" src="https://www.youtube.com/embed/<?=htmlspecialchars($post["data"])?>?showinfo=0&amp;enablejsapi=1" frameborder="0" allowfullscreen></iframe>
		</div>
		<div class="info"><div class="note"><?=htmlspecialchars($post["comment"])?></div></div>
	</div>
	<div class="comment">
		<form action="post.php?id=<?=$_GET["id"]?>" method="post">
			<div class="floatcontainer">
				<h2>Leave a comment</h2>
				<div class="formtext">
					Nickname:<br>Comment:
				</div>
				<div class="forminputs">
					<input type="hidden" name="type" value="comment">
					<input type="text" name="name" value="<?=$nickname?>"><br>
					<textarea name="comment"></textarea>
				</div>
			</div>
			<div class="center"><input type="submit"></div>
		</form>
	</div>
		<?php
		$commentdata = json_decode($post["commentdata"],true);
		if($commentdata) {
		foreach($commentdata as $comment) {
		?>
		<div class="comment">
			<div class="info">
				<div class="postedby"><span class="namefont"><?=htmlspecialchars($comment["name"])?></span></div>
				<div class="date"><?=timeago($comment["time"])?> ago</div>
				<div class="id">#<?=$comment["id"]?></div>
			</div>
			<div class="info"><div class="note"><?=htmlspecialchars($comment["comment"])?></div></div>
		</div>
		<?php } } ?>
	<?php } ?>

<?php include 'common_foot.php'; ?>
