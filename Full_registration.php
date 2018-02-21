<?php
    function generalSalt(){
        $salt = '';
        $saltLength=8;
        for ($i=0;$i<$saltLength;$i++){
            $salt.=chr(mt_rand(33,126));
        }
        return $salt;
    }
    function getSalt($login,$password,$salt){
        $saltPassword = md5(md5($password).md5($salt));
        return $saltPassword;
    }

    if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['password_conf'])){
        $login =$_POST['login'];
        $password =$_POST['password'];
        $conf =$_POST['password_conf'];
        $name =$_POST['name'];
        $surname=$_POST['surname'];
        $age = $_POST['age'];
        $email =$_POST['mail'];
        $city = $_POST['city'];
        $lang = $_POST['lang'];
        $log =preg_match('#[a-zA-Z]{4,12}#', $_POST['login']);
        $pass =preg_match('#[a-zA-Z0-9]{6,10}#', $_POST['password']);
        $pass_conf = preg_match('#[a-zA-Z0-9]{6,10}#', $_POST['password_conf']);
        $data = date('Y.m.d');
        if($pass==$pass_conf &&  $log=='1' && $pass=='1' &&$pass_conf=='1'){
            $link = mysqli_connect('','','','');
            mysqli_query($link, "SET NAMES 'utf8'");
            $query='SELECT * FROM user WHERE login="'.$login.'"';
           $result= mysqli_query($link,$query);
          if(empty(mysqli_fetch_assoc($result))){
              mysqli_query($link, "SET NAMES 'utf8'");
              $salt = generalSalt();
              $newpass =  getSalt($login,$password,$salt);
              $into ="INSERT INTO user (login,password,salt,Name,Surname,Age,Email,City,Language) 
              VALUES ('$login','$newpass','$salt','$name','$surname','$age','$email','$city','$lang')";
              mysqli_query($link,$into);
               $update = "UPDATE user SET date='$data'";
            mysqli_query($link,$update);
        echo 'регистрация прошла успешно!';

          }else{
               echo 'Такой login уже существует';
           }
        }else{
            echo 'Пароли не совпадают';
        }
    }if(empty($_POST['login']) && empty($_POST['password']) && empty($_POST['password_conf'])){
      //  echo 'Обязательные поля не должны быть пустыми';
    }

?>
<?php session_start();
if (!empty($_POST['lang'])){
    $_SESSION['lang']=$_POST['lang'];
}

?>
<form action='Program.php' method='POST'>
    <?php if(!empty($_POST['name'])){if(!preg_match('#[а-яА-ЯЁё]{3,12}#', $_POST['name'])){echo '<span >Некорректно введенное имя</span><br>';}}?>
    <span>Имя <input type="text" name="name" value="<?php  if (!empty($_POST['name'])){
             echo $_POST['name'];
        }?>" <?php if(!empty($_POST['name'])){if(!preg_match('#[а-яА-ЯЁё]{3,12}#', $_POST['name'])){echo 'style="border: 1px solid red;"';}}?>></span> <br><br>
    <?php if(!empty($_POST['surname'])){if(!preg_match('#[а-яА-ЯЁё]{3,12}#', $_POST['surname'])){echo '<span >Некорректно введенна фамилия</span><br>';}}?>
    <span>Фамилия</span><input type="text" name="surname" value="<?php if (!empty($_POST['surname'])){
        echo $_POST['surname'];
    }?>" <?php if(!empty($_POST['surname'])){if(!preg_match('#[а-яА-ЯЁё]{3,12}#', $_POST['surname'])){echo 'style="border: 1px solid red;"';}}?>><br><br>
    <?php if(!empty($_POST['login'])){if(!preg_match('#[a-zA-Z]{4,12}#', $_POST['login'])){echo '<span >Некорректно введен login</span><br>';}}?>
    <span>Введите login</span>
    <input name='login' placeholder="Login" value="<?php if (!empty($_POST['login'])){
        echo $_POST['login'];
    } ?>" <?php if(!empty($_POST['login'])){if(!preg_match('#[a-zA-Z]{4,12}#', $_POST['login'])){echo 'style="border: 1px solid red;"';}}?>><br><br>
    <?php if(!empty($_POST['password'])){if(!preg_match('#[a-zA-Z0-9]{6,10}#', $_POST['password'])){echo '<span >Некорректно введен password</span><br>';}}?>
    <span>Введите пароль</span>
    <input name='password' type='password' placeholder="Password" value="<?php
    if (!empty($_POST['password'])){echo $_POST['password'];}?>" <?php if(!empty($_POST['password'])){if(!preg_match('#[a-zA-Z0-9]{6,10}#', $_POST['password'])){echo 'style="border: 1px solid red;"';}}?>><br><br>
    <?php if(!empty($_POST['password_conf'])){if(!preg_match('#[a-zA-Z0-9]{6,10}#', $_POST['password_conf'])){echo '<span >Некорректно введен password</span><br>';}}?>
    <span>Подтвердите пароль</span>
    <input name='password_conf' type='password' placeholder="Password" value="<?php
    if (!empty($_POST['password_conf'])){echo $_POST['password_conf'];}?>" <?php if(!empty($_POST['password_conf'])){if(!preg_match('#[a-zA-Z0-9]{6,10}#', $_POST['password_conf'])){echo 'style="border: 1px solid red;"';}}?>><br><br>
    <span>Возраст</span><input type="text" name="age" value="<?php
    if (!empty($_POST['age'])){echo $_POST['age'];}?>"><br><br>
    <?php if(!empty($_POST['mail'])){if(!preg_match('#[a-z0-9]{1,15}@[a-z]{6}\.[a-z]{2,3}#', $_POST['mail'])){echo '<span >Некорректно введен Email</span><br>';}}?>
    <span>Email</span><input type="email" name="mail" value="<?php
    if (!empty($_POST['mail'])){echo $_POST['mail'];}?>" <?php if(!empty($_POST['mail'])){if(!preg_match('#[a-z0-9]{1,15}@[a-z]{6}\.[a-z]{2,3}#', $_POST['mail'])){echo 'style="border: 1px solid red;"';}}?>> <br><br>
    <span>Город</span><input type="text" name="city" value="<?php
    if (!empty($_POST['city'])){echo $_POST['city'];}?>"> <br><br>
    <span>Язык</span><select name="lang" >
        <option name="lang[]" value="Русский" <?php if($_SESSION['lang']=="Русский"){echo 'selected';}?>>Русский</option>
        <option name="lang[]" value="Украинский" <?php if($_SESSION['lang']=="Украинский"){echo 'selected';}?>>Украинский</option>
        <option name="lang[]" value="Английский" <?php if($_SESSION['lang']=="Английский"){echo 'selected';}?>>Английский</option>
        <option name="lang[]" value="Испанский" <?php if($_SESSION['lang']=="Испанский"){echo 'selected';}?>>Испанский</option>
        <option name="lang[]" value="Французский" <?php if($_SESSION['lang']=="Французский"){echo 'selected';}?>>Французский</option>
    </select> <br><br>
    <input type='submit' value='Отправить'>
</form>
