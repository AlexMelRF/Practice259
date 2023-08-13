<?php

session_start();
class Route {
	public static function start() {
		$controller_name = 'login';
		$action_name = 'index';
		$routes = $_GET['url'];
		if (!empty($routes))
            //user get login page if he is not logged
            if (($_SESSION['auth'] & ($routes == 'register' || $routes == 'login')))
                $routes = 'login';       
		$controller_name = $routes;
		$model_name = 'model_'.$controller_name;
		$controller_name = 'controller_'.$controller_name;
		$action_name = 'action_'.$action_name;
		$model_file = strtolower($model_name).'.php';
		$model_path = "application/models/".$model_file;
		if (file_exists($model_path))
			include "application/models/".$model_file;
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "application/controllers/".$controller_file;
		if (file_exists($controller_path)) { 
			include "application/controllers/".$controller_file;
			$controller = new $controller_name;
			$action = $action_name;
			$controller -> $action();
		}
		else
			Route::ErrorPage404();
	} 
	public static function ErrorPage404() {
		$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
	}
}

?>