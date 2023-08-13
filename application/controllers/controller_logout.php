<?php

class Controller_Logout extends Controller {
	public function action_index() {
		session_start();
		$_SESSION = [];
		session_destroy();
		$this -> view -> generate('login_view.php', 'template_view.php');
		$_GET['url'] = 'login';
	}
}

?>