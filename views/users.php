<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/main.css">
    <script>
        function hider() {
            let hide = document.getElementById("window");
            hide.style.display = "none";
        }
    </script>
    <script>
        function shower() {
            let show = document.getElementById("window");
            show.style.display = "block";
        }
    </script>
</head>
<body>

<header>
    <div class="header" style="margin-bottom: 20px;">
        <a href="?controller=users" class="logo">
            <img src="assets/img/icon.png" alt="logo">
            <span>Site for lab 2</span>
        </a>
        <div class="login">
            <form action="?controller=users&action=search" method="post">
                <input type="search" class="search" name="search" placeholder="search">
                <button type="submit" class="search" value="searching"><img src="assets/img/search_icon.png" alt="search"></button>
            </form>
            <?php if (!isset($_SESSION['auth']) || $_SESSION['auth']=="nobody"){?>
            <a href="#" class="search" onclick="shower()">Sign In</a>
            |
            <a href="?controller=users&action=addForm" class="search">Sign Up</a>
            <?php }?>

            <?php if (isset($_SESSION['auth']) && ($_SESSION['auth']=="user" || $_SESSION['auth']=="admin")){?>
                <a href="?controller=users&action=showme&id=<?=$_SESSION['user']['id']?>" class="search" ><?=$_SESSION['user']['name']?></a>
                |
                <a href="?controller=index&action=logout" class="search">Sign out</a>
            <?php }?>
        </div>
    </div>
</header>



<div class="window" id="window">
    <div class="row3">
        <a href="#" onclick="hider()">X</a>
    </div>

    <form action="?controller=index&action=auth" method="post">
        <div class="row">
            <div class="field">
                <input type="email" name="email" placeholder="Email">
            </div>
        </div>

        <div class="row">
            <div class="field">
                <input type="password" name="password" placeholder="Password">
            </div>
        </div>
        <input type="submit" class="btn" value="Sign in">
    </form>
</div>



<div class="container2">
    <div class="row">
        <?php if(!$users) { ?>
            <div class="no-users"><p>No Users</p></div>
        <?php }?>

        <?php if($users) { ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>

                <?php  foreach ($users as $user):?>
                    <tr>
                        <td><a href="?controller=users&action=show&id=<?=$user['id']?>"><?=$user['id']?></a></td>
                        <td><?=$user['name']?></td>
                        <td><?=$user['lastname']?></td>
                        <td><?=$user['email']?></td>
                        <td><?=$user['role']?></td>
                    </tr>
                <?php endforeach;?>
            </table>
        <?php }?>
    </div>

    <div class="row2">
            <?php if(isset($_SESSION['auth']) && $_SESSION['auth']=="admin"){ ?>
        <a class="btn2" href="?controller=users&action=addForm">add new user</a>
            <?php }?>
    </div>

</div>
</body>
</html>
