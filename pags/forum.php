<?php
	$idForum = str_replace('-', ' ', $explode['1']);

	$forum = new forum($con);
	$forum->get_forum($idForum['0']);
?>