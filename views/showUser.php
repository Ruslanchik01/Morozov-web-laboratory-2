<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/show.css">

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
            <span>Site for lab 2 </span>
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


<div class="container">
    <!-- Show one of user -->

    <div class="information">
        <div class="right">
            <h2>User <?=$user['name']?></h2>
            <input type="hidden" name="id" value="<?=$user['id']?>" />
            <img src="<?=$user['path_to_img']?>" alt="user">
        </div>

        <div class="left">
            <p>Name: <?=$user['name']?></p>
            <p>Surname: <?=$user['lastname']?></p>
            <p>Email: <?=$user['email']?></p>
            <p>Role:<?=$user['role']?></p>
            <a class="btn" href="?controller=users">return back</a>
            <?php if($_SESSION['auth']=='admin' && $user['role']!='admin') {?>
            <a class="btn" href="?controller=users&action=delete&id=<?=$user['id']?>&adminid=<?=$_SESSION['user']['id']?>">Delete Account</a>
            <?php }?>

        </div>
    </div>

<!--    block of comments -->
    <div class="comments">
        <div class="commenting">
            <?php if (isset($_SESSION['auth']) && ($_SESSION['auth']=="user" || $_SESSION['auth']=="admin")){?>

            <?php if (!$editflag){?><form action="?controller=users&action=addcom" method="post"><?php }?>
            <?php if ($editflag){?><form action="?controller=users&action=editcom" method="post">
                    <input type="hidden" name="commentid" value="<?=$commentid?>"><?php }?>

                    <div class="row">
                        <div class="field">
                            <?php if (!$editflag){?><input type="text" name="comment" placeholder="Comment here"><?php }?>
                            <?php if ($editflag){?><input type="text" name="comment" placeholder="Comment here" value="<?=$commentedit?>"><?php }?>
                        </div>
                    </div>
                    <input type="hidden" name="emailto" value="<?=$user['email']?>">
                    <?php
                    $timezone  = +2;
                    $date = gmdate('H:i d-m-Y',time()+3600*($timezone));?>
                    <input type="hidden" name="date" value="<?=$date?>">
                    <input type="hidden" name="id" value="<?=$user['id']?>">
                    <input type="submit" value="Comment">
                </form>
            <?php }?>


            <?php if(!$comments) { ?>
                <div class="user-no-comments"><p>No commentaries</p></div>
            <?php }?>

            <?php foreach ($comments as $comment):?>

                <div class="user-whole-comment">
                        <?php
                        foreach ($usersCommentaries as $nickname){
                            if($comment['emailfrom']===$nickname['email']){ ?>

                                <div class="user-info">
                                    <div class="user-avatar"><img src="<?=$nickname['path_to_img']?>" alt="avatar"></div>
                                    <div class="user-comment-information">
                                        <div class="user-nickname"><?=$nickname['name']?> <?=$nickname['lastname']?></div>
                                        <div class="user-date"><?=$comment['date']?></div>
                                        <div class="user-comment"><p><?=$comment['comment']?></p></div>
                                    </div>
                                </div>
                                    <?php
                                        if(isset($_SESSION['user']) && $_SESSION['user']['email']===$nickname['email']){?>
                                            <div class="user-abilities">
                                                <a href="?controller=users&action=show&id=<?=$user['id']?>&editflag=<?=true?>&commentedit=<?=$comment['comment']?>&commentid=<?=$comment['id']?>" class="editing"><img src="assets/img/pencil.svg" alt="edit"></a>
                                                <a href="?controller=users&action=deletecom&id=<?=$comment['id']?>&id2=<?=$user['id']?>" class="deleting"><img src="assets/img/bin.svg" alt="delete"></a>
                                            </div>
                                        <?php }?>
                                    <?php if(isset($_SESSION['user']) && ($_SESSION['user']['email']!==$nickname['email']) && $_SESSION['auth']=='admin'){?>
                                    <div class="user-abilities">
                                        <a href="?controller=users&action=deletecom&id=<?=$comment['id']?>&id2=<?=$user['id']?>" class="deleting"><img src="assets/img/bin.svg" alt="delete"></a>
                                    </div>
                                    <?php }?>

                                <?php break;
                            }
                        }?>
                </div>
            <?php endforeach;?>

        </div>
    </div>

</div>
</body>
</html>
