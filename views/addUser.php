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
            <?php if (!isset($_SESSION['auth']) || $_SESSION['auth']!="admin"){?>
                <a href="#" class="search" onclick="shower()">Sign In</a>
                |
                <a href="?controller=users&action=addForm" class="search">Sign Up</a>
            <?php }?>

            <?php if (isset($_SESSION['auth']) && $_SESSION['auth']=="admin"){?>
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

<div class="container">
    <!-- Form to add User -->
    <h3>Registration</h3>
    <form action="?controller=users&action=add" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="field">
                <input type="text" name="name" placeholder="Firstname" <?php if(isset($name)){?> value="<?=$name?>" <?php }?>>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <input type="text" name="lastname" placeholder="Lastname" <?php if(isset($lastname)){?> value="<?=$lastname?>" <?php }?>>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <input type="email" name="email" placeholder="Email" <?php if(isset($email)){?> value="<?=$email?>" <?php }?>>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <select name="role">
                    <option selected disabled value="user">Choose your role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <input type="password" name="password" placeholder="Password">
            </div>
        </div>

        <div class="row">
            <div class="field">
                <input type="password" name="password2" placeholder="Repeat password">
            </div>
        </div>

        <div class="row">
            <div class="file-field input-field">
                <label for="photo">Download photo</label>
                    <input type="file" name="photo" id="photo"  accept="image/png, image/gif, image/jpeg" <?php if(isset($filePath)){?> value="<?=$filePath?>" <?php }?>>
            </div>
        </div>

        <?php if(isset($exception)) { ?>
            <div class="exception"><p><?=$exception?></p></div>
        <?php }?>
        <input type="submit" class="btn" value="Registration">
    </form>


</div>
</body>
</html>

