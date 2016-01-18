<?php include 'common_head.php'; ?>
		<title><?=PAGE_TITLE?></title>
<?php include 'common_body.php'; ?>

	<div class="comment">
		<form action="post.php" method="post">
			<div class="floatcontainer">
				<h2>Post a video</h2>
				<div class="formtext">
					Nickname:<br>Video:<br>Comment:
				</div>
				<div class="forminputs">
					<input type="hidden" name="type" value="video">
					<input type="text" name="name" value="<?=$nickname?>"><br>
					<input type="text" name="video" placeholder="https://www.youtube.com/watch?v=..."><br>
					<textarea name="comment"></textarea>
				</div>
			</div>
			<div class="center"><input type="submit"></div>
		</form>
	</div>

	<?php
	$found = $db->query("SELECT * FROM youbulletin ORDER BY viewtime DESC LIMIT ".VIDEO_COUNT)->fetchAll(PDO::FETCH_ASSOC);
	foreach($found as $post) {
	?>
	<div class="comment thread">
		<div class="info">
			<div class="title"><?=htmlspecialchars($post["title"])?></div>
			<div class="postedby">posted by <span class="namefont"><?=htmlspecialchars($post["name"])?></span></div>
			<div class="date"><?=timeago($post["posttime"])?> ago</div>
			<div class="replylink"><a href="thread.php?id=<?=$post["id"]?>">[ <?=$post["comments"]?> comment<?=splural($post["comments"])?> ]</a></div>
			<div class="views"><?=$post["views"]?> view<?=splural($post["views"])?></div>	
			<div class="id">ID: <?=$post["id"]?></div>
		</div>
		<div class="container">
			<iframe id="video<?=$post["id"]?>" src="https://www.youtube.com/embed/<?=htmlspecialchars($post["data"])?>?showinfo=0&amp;enablejsapi=1" frameborder="0" allowfullscreen></iframe>
		</div>
		<div class="info"><div class="note"><?=htmlspecialchars($post["comment"])?></div></div>
	</div>
	<?php } ?>

<?php include 'common_foot.php'; ?>
