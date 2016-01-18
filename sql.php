<?php

//To connect to a local database, simply fill in dbname, username, and password below.
define('SQL_DSN', 'mysql:dbname=mydbname');
define('SQL_USER', 'myusername');
define('SQL_PASS', 'mypassword');

function sql_start() {
	$db = new PDO(SQL_DSN, SQL_USER, SQL_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//Remove this section to skip checking for table
	if(!tableExists($db, 'youbulletin')) {
		$db->query("
			CREATE TABLE IF NOT EXISTS `youbulletin` (
			`id` int(10) unsigned NOT NULL,
			  `type` tinytext NOT NULL,
			  `data` text NOT NULL,
			  `title` text NOT NULL,
			  `duration` int(10) unsigned NOT NULL,
			  `thumbnail` text NOT NULL,
			  `name` tinytext NOT NULL,
			  `comment` text NOT NULL,
			  `ip` tinytext NOT NULL,
			  `posttime` int(10) unsigned NOT NULL,
			  `views` int(10) unsigned NOT NULL DEFAULT '0',
			  `viewdata` longtext NOT NULL,
			  `comments` int(10) unsigned NOT NULL DEFAULT '0',
			  `commentdata` longtext NOT NULL,
			  `viewtime` double NOT NULL,
			  `flags` text NOT NULL
			) AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

			ALTER TABLE `youbulletin` ADD PRIMARY KEY (`id`);

			ALTER TABLE `youbulletin` MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
		");
	}
	// ^ Remove this section to skip checking for table ^

	return $db;
}

function tableExists($pdo, $table) {
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }
    return $result !== FALSE;
}

?>
