<?

session_start(); 
// generate random string
function generateCode($length=5) {
    $chars = "I want to sleep";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
    }
    return $code;
} 
// connect to db
$link=mysqli_connect("localhost", "root", "", "users"); 
// if login
if (isset($_POST['submit']) & ($_GET['url'] == 'login' || $_GET['url'] == '')) {
    $query = mysqli_query($link,"SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query); 
    if ($data['user_password'] === md5(md5($_POST['password']))) {
        $hash = md5(generateCode(10));
        mysqli_query($link, "UPDATE users SET user_hash='".$hash."' "." WHERE user_id='".$data['user_id']."'"); 
        // cookies
        setcookie("id", $data['user_id'], time()+60*60*24*30, "/");
        setcookie("hash", $hash, time()+60*60*24*30, "/", "gallery", false, true);
        $_SESSION['auth'] = true;
        $_GET['url'] = 'gallery';
        Route::start();
    }
    else
        print "Вы ввели неправильный логин/пароль";
}
// if register
if (isset($_POST['submit']) & $_GET['url'] == 'register') {
    $err = [];
    if (strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    if (mysqli_num_rows($query) > 0)
        $err[] = "Пользователь с таким логином уже существует";
    $query = mysqli_query($link, "SELECT user_email FROM users WHERE user_email='".mysqli_real_escape_string($link, $_POST['email'])."'");
    if (mysqli_num_rows($query) > 0)
        $err[] = "Пользователь с таким email уже существует";
    if (count($err) == 0) {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = md5(md5(trim($_POST['password']))); 
        mysqli_query($link,"INSERT INTO users SET user_login='".$login."', user_password='".$password."', user_email='".$email."'");
        $_SESSION['auth'] = true;
        $_GET['url'] = 'gallery';
        Route::start();
       
    }
    else {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";
        foreach($err as $error)
            print $error."<br>";
    }
}

?> 

