<?php
class User {
    private $name;
    private $lastname;
    private $email;
    private $role;
    private $path_to_img;
    private $password;

    public function __construct($name = '',$lastname = '', $email = '', $role = '',$path_to_img='',$password='')
    {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->role = $role;
        $this->path_to_img=$path_to_img;
        $this->password = $password;
    }

    public function add($conn) {
        $sql = "INSERT INTO users (email, name,lastname, role, password, path_to_img)
           VALUES ('$this->email', '$this->name','$this->lastname','$this->role', '$this->password', '$this->path_to_img')";
        $res = mysqli_query($conn, $sql);
        if ($res) {
            return true;
        }
    }

    public static function all($conn) {
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql); //виконання запиту
        if ($result->num_rows > 0) {
            $arr = [];
            while ( $db_field = $result->fetch_assoc() ) {
                $arr[] = $db_field;
            }
            return $arr;
        } else {
            return [];
        }
    }


    public static function update($conn, $id, $data) {
        $sql = "UPDATE users SET email='".$data['email']."',name='".$data['name']."',lastname='".$data['lastname']."',role='".$data['role']."',password='".$data['password']."',path_to_img='".$data['path_to_img']."'  WHERE id=$id";
        $res = mysqli_query($conn,$sql);
        if($res){
            return true;
        }
    }

    public static function delete($conn, $id) {
        $sql = "DELETE FROM users WHERE id=$id";
        $res = mysqli_query($conn, $sql);
        if ($res) {
            return true;
        }
    }

    public static function byId($conn, $id) {
        $sql = "SELECT * FROM users WHERE id=$id";
        $result = $conn->query($sql); //виконання запиту
        if ($result->num_rows > 0) {
            $arr = $result->fetch_assoc();
            return $arr;
        } else {
            return [];
        }
    }

    public static function byEmail($conn, $email) {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql); //виконання запиту
        if ($result->num_rows > 0) {
            $arr = $result->fetch_assoc();
            return $arr;
        } else {
            return [];
        }
    }

    public static function search($conn,$search){
        $sql = "SELECT * FROM users WHERE name='$search' OR lastname='$search'";
        $result = $conn->query($sql); //виконання запиту
        if ($result->num_rows > 0) {
            $arr = [];
            while ($db_field = $result->fetch_assoc()) {
                $arr[] = $db_field;
            }
            return $arr;
        } else {
            return [];
        }
    }
}

class Comment{
    private $emailto;
    private $emailfrom;
    private $date;
    private $comment;

    public function __construct($emailto = '',$emailfrom = '', $date = '', $comment = '')
    {
        $this->emailto = $emailto;
        $this->emailfrom = $emailfrom;
        $this->date = $date;
        $this->comment = $comment;
    }

    public function addcom($conn) {
        $sql = "INSERT INTO comments (emailfrom, emailto,comment, date)
           VALUES ('$this->emailfrom', '$this->emailto','$this->comment','$this->date')";
        $res = mysqli_query($conn, $sql);
        if ($res) {
            return true;
        }
    }


    public static function comment($conn,$emailto){
        $sql = "SELECT * FROM comments WHERE emailto='$emailto'";

        $result = $conn->query($sql); //виконання запиту
        if($result->num_rows > 0){
            $arr = [];
            while ( $db_field = $result->fetch_assoc() ) {
                $arr[] = $db_field;
            }
            return $arr;
        } else {
            return [];
        }
    }

    public static function deletecom($conn, $id) {
        $sql = "DELETE FROM comments WHERE id=$id";
        $res = mysqli_query($conn, $sql);
        if ($res) {
            return true;
        }
    }

    public static function updatecom($conn, $id, $data) {
        $sql = "UPDATE comments SET comment='".$data['comment']."',date='".$data['date']."' WHERE id=$id";
        $res = mysqli_query($conn,$sql);
        if($res){
            return true;
        }
    }
}
