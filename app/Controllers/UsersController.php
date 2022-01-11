<?php
class UsersController
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db->getConnect();
    }

    public function index()
    {
        include_once 'app/Models/UserModel.php';

        // отримання користувачів
        $users = (new User())::all($this->conn);

        include_once 'views/users.php';

    }

    public function addForm(){
        include_once 'views/addUser.php';
    }




    public function add()
    {
        include_once 'app/Models/UserModel.php';
        $target_dir = "public/uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $filePath = '';
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $filePath = $target_dir . basename($_FILES["photo"]["name"]);
        }
        if($filePath==null){
            $filePath="public/uploads/user.png";
        }

        // блок з валідацією
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // exceptions
        $users = (new User())::all($this->conn);
        $exception='';

        if(!$name || !$lastname || !$email || !$password || !$password2){
            $exception="Not all fields are filled";
            include_once 'views/addUser.php';
        }

        foreach ($users as $exceptionUser){
            if($name===$exceptionUser['name'] && $lastname===$exceptionUser['lastname']){
                $exception="This nickname is already exist, please choose another";
                include_once 'views/addUser.php';
                break;
            }
            if($email===$exceptionUser['email']){
                $exception="This email is already used, please enter another";
                include_once 'views/addUser.php';
                break;
            }
        }

        if(strlen($password)<6){
            $exception="Length of password can't be less than 6 symbols";
            include_once 'views/addUser.php';
        }
        if($password!==$password2){
            $exception="Repeated password is wrong";
            include_once 'views/addUser.php';
        }



        if(!$exception){
            if (trim($name) !== "" && trim($email) !== "" && trim($role) !== "") {
                // додати користувача
                $user = new User($name,$lastname, $email, $role,$filePath,$password);
                $user->add($this->conn);
            }
            header('Location: ?controller=users');
        }
    }

    public function delete() {
        include_once 'app/Models/UserModel.php';
        // блок з валідацією
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $adminid = filter_input(INPUT_GET, 'adminid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (trim($id) !== "" && is_numeric($id)) {
            (new User())::delete($this->conn, $id);
        }

        if ($_SESSION['auth']=='user' || ($adminid==$id)){
            $_SESSION['auth']="nobody";
            $_SESSION['user']=null;
        }
        header('Location: ?controller=users');
    }


    public function show(){
        include_once 'app/Models/UserModel.php';
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //for changing commentaries
        $commentid=filter_input(INPUT_GET, 'commentid');
        $editflag=false;
        $editflag=filter_input(INPUT_GET,'editflag');
        $commentedit=filter_input(INPUT_GET,'commentedit');

        if (trim($id) !== "" && is_numeric($id)) {
            $user= (new User())::byId($this->conn, $id);
            // for comment display
            $comments = (new Comment())::comment($this->conn,$user['email']);
            foreach ($comments as $comment){
                $tmp=(new User())::byEmail($this->conn,$comment['emailfrom']);
                $usersCommentaries[]=[
                    "name" => $tmp['name'],
                    "lastname"=>$tmp['lastname'],
                    "email" => $tmp['email'],
                    "path_to_img" => $tmp['path_to_img']
                ];
            }

        }
        if(isset($_SESSION['user']) && $id==$_SESSION['user']['id'])$this->showme();
        else include_once 'views/showUser.php';

    }

    public function showme(){
        include_once 'app/Models/UserModel.php';
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //for changing commentaries
        $commentid=filter_input(INPUT_GET, 'commentid');
        $editflag=false;
        $editflag=filter_input(INPUT_GET,'editflag');
        $commentedit=filter_input(INPUT_GET,'commentedit');

        if (trim($id) !== "" && is_numeric($id)) {
            $user1= (new User())::byId($this->conn, $id);

            // for comment display
            $comments = (new Comment())::comment($this->conn,$user1['email']);
            foreach ($comments as $comment){
                $tmp=(new User())::byEmail($this->conn,$comment['emailfrom']);
                $usersCommentaries[]=[
                    "name" => $tmp['name'],
                    "lastname"=>$tmp['lastname'],
                    "email" => $tmp['email'],
                    "path_to_img" => $tmp['path_to_img']
                ];
            }

        }
        include_once 'views/showMe.php';
    }

    public function edit(){
        include_once 'app/Models/UserModel.php';

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password= filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        $path=(new User())::byId($this->conn,$id);
        $target_dir = "public/uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $filePath='';
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $filePath = $target_dir . basename($_FILES["photo"]["name"]);
        }
        if($filePath==null){
            $filePath=$path['path_to_img'];
        }

        $data=['name'=>$name,'lastname'=>$lastname,'email'=>$email,'role'=>$role,'path_to_img'=>$filePath,'password'=>$password];
        if (trim($id) !== "" && is_numeric($id)) {
            (new User())::update($this->conn, $id,$data);
        }

        header('Location: ?controller=users');
    }

    public function search(){
        include_once 'app/Models/UserModel.php';

        $search=filter_input(INPUT_POST,'search',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $users = (new User())::search($this->conn,$search);

        include_once 'views/users.php';
    }




// for commentaries
    public function addcom(){
        include_once 'app/Models/UserModel.php';

        // блок з валідацією
        $id=filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $emailfrom = $_SESSION['user']['email'];
        $emailto = filter_input(INPUT_POST, 'emailto', FILTER_SANITIZE_EMAIL);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (trim($comment) !== "" ) {
            // додати comment
            $comment = new Comment($emailto,$emailfrom,$date,$comment);
            $comment->addcom($this->conn);
        }
        header("Location:?controller=users&action=show&id=".$id);
    }

    public function deletecom() {
        include_once 'app/Models/UserModel.php';
        // блок з валідацією
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id2 = filter_input(INPUT_GET, 'id2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (trim($id) !== "" && is_numeric($id)) {
            (new Comment())::deletecom($this->conn, $id);
        }
        header("Location:?controller=users&action=show&id=".$id2);
    }

    public function editcom(){
        include_once 'app/Models/UserModel.php';

        $id = filter_input(INPUT_POST, 'commentid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data=['comment'=>$comment,'date'=>$date];
        if (trim($id) !== "" && is_numeric($id)) {
            (new Comment())::updatecom($this->conn, $id,$data);
        }
        header("Location:?controller=users&action=show&id=2");
    }

}

