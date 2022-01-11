<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/show.css">
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

                <a href="?controller=users&action=showme&id=<?=$_SESSION['user']['id']?>" class="search" ><?=$_SESSION['user']['name']?></a>
                |
                <a href="?controller=index&action=logout" class="search">Sign out</a>

        </div>
    </div>
</header>

<div class="information">
    <div class="right">
        <h2>My account</h2>
        <img src="<?=$user1['path_to_img']?>" alt="user">
    </div>

    <div class="left leftshowme">
        <form action="?controller=users&action=edit" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?=$user1['id']?>" />

            <div class="row">
                <div class="field">
                    <input type="text" name="name" placeholder="Firstname" value="<?=$user1['name']?>" >
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <input type="text" name="lastname" placeholder="Lastname" value="<?=$user1['lastname']?>">
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <input type="email" name="email" placeholder="Email" readonly="readonly" value="<?=$user1['email']?>">
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <input type="text" name="role" readonly="readonly" value="<?=$user1['role']?>">
                </div>
            </div>

            <div class="row">
                <div class="field">
                    <input type="text" name="password" placeholder="Password" value="<?=$user1['password']?>">
                </div>
            </div>

            <div class="row">
                <div class="file-field input-field">
                    <label for="photo">Download photo</label>
                    <input type="file" name="photo" id="photo"  accept="image/png, image/gif, image/jpeg">
                </div>
            </div>
            <input type="submit" class="btn" value="Save">
            <a class="btn" href="?controller=users">return back</a><br>
            <a class="btn" href="?controller=users&action=delete&id=<?=$user1['id']?>">Delete Account</a>

        </form>
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
                <input type="hidden" name="emailto" value="<?=$user1['email']?>">
                <?php
                $timezone  = +2;
                $date = gmdate('H:i d-m-Y',time()+3600*($timezone));?>
                <input type="hidden" name="date" value="<?=$date?>">
                <input type="hidden" name="id" value="<?=$user1['id']?>">
                <input type="submit" value="Comment">
            </form>
            <?php }?>

            <?php if(!$comments) { ?>
                <div class="user-no-comments"><p>No commentaries</p></div>
            <?php }?>

            <?php  foreach ($comments as $comment):?>

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
                                    <a href="?controller=users&action=show&id=<?=$user1['id']?>&editflag=<?=true?>&commentedit=<?=$comment['comment']?>&commentid=<?=$comment['id']?>" class="editing"><img src="assets/img/pencil.svg" alt="edit"></a>
                                    <a href="?controller=users&action=deletecom&id=<?=$comment['id']?>&id2=<?=$user1['id']?>" class="deleting"><img src="assets/img/bin.svg" alt="delete"></a>
                                </div>
                            <?php }?>
                            <?php if(isset($_SESSION['user']) && ($_SESSION['user']['email']!==$nickname['email']) ){?>
                                <div class="user-abilities">
                                    <a href="?controller=users&action=deletecom&id=<?=$comment['id']?>&id2=<?=$user1['id']?>" class="deleting"><img src="assets/img/bin.svg" alt="delete"></a>
                                </div>
                            <?php }?>

                            <?php break;
                        }
                    }?>
                </div>
            <?php endforeach;?>

    </div>
</div>
</body>
</html>
