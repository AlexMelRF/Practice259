<?
// Функция для генерации случайной строки
function generateCode($length=5) {
    $chars = "I want to sleep";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
    }
    return $code;
} 
// Соединяемся с БД
$link=mysqli_connect("localhost", "root", "", "users"); 
if(isset($_POST['submit']) & $_GET['url'] == 'login') {
    // Вытаскиваем из БД запись, у которой логин равняется введенному
    $query = mysqli_query($link,"SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query); 
    // Сравниваем пароли
    if($data['user_password'] === md5(md5($_POST['password']))) {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));
        // Записываем в БД новый хеш авторизации
        mysqli_query($link, "UPDATE users SET user_hash='".$hash."' "." WHERE user_id='".$data['user_id']."'"); 
        // Ставим куки
        setcookie("id", $data['user_id'], time()+60*60*24*30, "/");
        setcookie("hash", $hash, time()+60*60*24*30, "/", "gallery", false, true);
        // Переадресовываем браузер на страницу проверки нашего скрипта
        //header("Location: check.php"); exit();
        $_SESSION['auth'] = true;
        $_GET['url'] = 'gallery';
        Route::start();

    }
    else
        print "Вы ввели неправильный email/пароль";
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Страница регистрации нового пользователя 
// Соединяемся с БД
//$link=mysqli_connect("localhost", "root", "", "users"); 
if(isset($_POST['submit']) & $_GET['url'] == 'register') {
    $err = [];
    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    // проверяем, не существует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0)
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    // проверяем, не существует ли пользователя с таким email
    $query = mysqli_query($link, "SELECT user_email FROM users WHERE user_email='".mysqli_real_escape_string($link, $_POST['email'])."'");
    if(mysqli_num_rows($query) > 0)
        $err[] = "Пользователь с таким email уже существует в базе данных";
    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0) {
        $login = $_POST['login'];
        $email = $_POST['email'];
        // Убираем лишние пробелы и делаем двойное хэширование (используем старый метод md5)
        $password = md5(md5(trim($_POST['password']))); 
        mysqli_query($link,"INSERT INTO users SET user_login='".$login."', user_password='".$password."', user_email='".$email."'");
        //header("Location: login.php"); exit();
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

