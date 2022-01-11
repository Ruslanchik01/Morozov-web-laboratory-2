<?php
class IndexController
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db->getConnect();
    }

    public function index()
    {
        // виклик відображення
        include_once 'views/home.php';
    }

    public function auth()
    {
        include_once 'app/Models/UserModel.php';
        $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password=filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $_SESSION['auth']="nobody";
        $_SESSION['user']=null;
        $user['email']='not_exist';
        $user['password']='not_exist';
        $users_general = (new User())::all($this->conn);
        foreach ($users_general as $user_search){
            if($user_search['email']===$email && $user_search['password']===$password){
                $id=$user_search['id'];
                $user= (new User())::byId($this->conn, $id);
            }
        }

        if($_POST['email']!=$user['email'] || $_POST['password']!=$user['password'])
        {
            $users = (new User())::all($this->conn);
            include_once 'views/users.php';
        }
        else
        {
            if($user['role']==="user")$_SESSION['auth']="user";
            if($user['role']==="admin")$_SESSION['auth']="admin";
            $user1= (new User())::byId($this->conn, $id);
            $_SESSION['user']=$user1;
            $users = (new User())::all($this->conn);
            include_once 'views/users.php';
        }
    }

    public function logout(){
        include_once 'app/Models/UserModel.php';

        $_SESSION['auth']="nobody";
        $_SESSION['user']=null;
        $users = (new User())::all($this->conn);
        include_once 'views/users.php';
    }

}

