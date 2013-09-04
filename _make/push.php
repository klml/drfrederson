<?php
/*
 * @name push.php
 * @description push file to GitHub (cannot handle delete operation)
 * @author yqtaku
 * @date 28 DEC, 2012
 */

	exec('cd ..');
	exec('git add .');
	exec('git commit -m "update"');
	exec('git push');
?>
