<?php
    session_start();

    if(isset($_SESSION['username']) && $_SESSION['username']!='' && $_SESSION['username']!=NULL) {

        if(isset($_POST['logout'])) {
            session_destroy();
            unset($_SESSION['username']);
            header('location:index.php');
        }

        $host='localhost';
        $user='root';
        $pass='';
        $dbname='toonindia';
        $conn=mysqli_connect($host,$user,$pass,$dbname);
        if(!$conn) {
            die("couldn't connect : ".mysqli_connect_error())."<br>";
        }

        $username = $_SESSION['username'];
        $sql = "SELECT * from toonusers where username='$username'";
        $result = mysqli_query($conn,$sql);
        $data = mysqli_fetch_assoc($result);
        $id = $data['id'];

        $updateCover=$updateCoverErr=$updateName=$updateNameErr=$updateUsername=$updateUsernameErr=$updateEmail=$updateEmailErr=$updatePhone=$updatePhoneErr="";
        
        $count=0;
        if($_SERVER["REQUEST_METHOD"]=="POST") {

            function test($input,$match) {
                if(empty($_POST[$input])) {
                    return 1;
                } else {
                    $test = test_input($_POST[$input]);
                    if(preg_match($match,$test)) {
                        return 2;
                    } else {
                        return 3;
                    }
                }
            }

            function test_input($value) {
                $value=trim($value);
                return $value;
            }
    
            $updateNameErr = test('updateName','/^[a-zA-Z ]{3,15}$/');
            $updateUsernameErr = test('updateUsername','/^[a-zA-Z0-9._-]{3,16}$/');
            $updateEmailErr = test('updateEmail','/^[a-z0-9.]{5,}@[a-z]{3,10}.[a-z]{2,8}(.[a-z]{2,8})?$/');
            $updatePhoneErr = test('updatePhone','/^[0-9]{10}$/');
    
            if($updateNameErr==2) {
                $updateName = $_POST['updateName'];
                $updateNameErr="";
                $count++;
            }
            if($updateUsernameErr==2) {
                $updateUsername = $_POST['updateUsername'];
                $updateUsernameErr="";
                $count++;
            }
            if($updateEmailErr==2) {
                $updateEmail = $_POST['updateEmail'];
                $updateEmailErr="";
                $count++;
            }
            if($updatePhoneErr==2) {
                $updatePhone = $_POST['updatePhone'];
                $updatePhoneErr="";
                $count++;
            }
            
            function value($input,$field) {
                if($input==1) {
                    return "$field cannot be empty.";
                } else if($input==3) {
                    return "Please enter valid $field";
                }
            }
    
            $updateNameErr = value($updateNameErr,'updateName');
            $updateUsernameErr = value($updateUsernameErr,'updateUsername');
            $updateEmailErr = value($updateEmailErr,'updateEmail');
            $updatePhoneErr = value($updatePhoneErr,'updatePhone');
            
            // upload new profile
            $target_dir = "storage/user/";
            $target_file = $target_dir . basename($_FILES["updateCover"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            if($imageFileType != "jfif" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $updateCoverErr = "Sorry, only JPG, JPEG, PNG, JFIF & GIF files are allowed.";
                $uploadOk = 0;
            }

            if($count==4) {
                if(isset($_POST['update'])) {
                    $sql3="";
                    if(move_uploaded_file($_FILES["updateCover"]["tmp_name"], $target_file) && $uploadOk != 0) {
                        move_uploaded_file($_FILES["updateCover"]["tmp_name"], $target_file);
                        $updateCover=$_FILES['updateCover']['name'];
                        $temp = uniqid().".".$imageFileType;
                        rename("storage/user/$updateCover","storage/user/$temp");
                        $sql3 = "UPDATE toonusers SET profile='storage/user/$temp', name='$updateName', username='$updateUsername', email='$updateEmail', phone='$updatePhone' where id='$id';";
                    } else {
                        $sql3 = "UPDATE toonusers SET name='$updateName', username='$updateUsername', email='$updateEmail', phone='$updatePhone' where id='$id';";
                    }
                    $sql2 = "SELECT username from toonusers where username='$updateUsername';";
                    $result2 = mysqli_query($conn,$sql2);
                    $data = mysqli_fetch_assoc($result2);
                    $num = mysqli_num_rows($result2);
                    if(!$num || $data['username']==$username) {
                        $result3 = mysqli_query($conn,$sql3);
                        if(!$result3) {
                            die('Query Failed! '.mysqli_error($conn));
                        }
                        $_SESSION['username']=$updateUsername;
                        header('location:profile.php');
                    } else {
                        $updateUsernameErr = "Username already exists";
                    }
                }
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>toonindia</title>
    <link 
     href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" 
     rel="stylesheet" 
     integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" 
     crossorigin="anonymous"
    />
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/contribution.css">
    <link rel="stylesheet" href="css/settings.css">
    <link rel="stylesheet" href="css/faqs.css">
    <link rel="stylesheet" href="css/update.css">
</head>
<body>
    <nav class="navbar-expand navbar fixed-top">
        <div class="container-fluid">
            <h4 id="logo">toonindia</h4>
            <ul class="nav navbar-nav justify-content-end">
                <li class="nav-item"><img src="<?php echo $data['profile']; ?>" alt="profile"></li>
                <li class="nav-item"><?php echo $data['username']; ?> <br><span>Unique id : <?php echo $data['id']; ?></span></li>
            </ul>
        </div>
    </nav>
    <div class="main">
        <div class="tab">
            <button onclick="window.location.href = 'index.php'">Home</button>
            <button class="tablinks" onclick="openCity(event, 'contribution')" id="defaultOpen">Contribution</button>
            <button class="tablinks" onclick="openCity(event, 'notification')">Notification</button>
            <button class="tablinks" onclick="openCity(event, 'invite')">Invite Friends</button>
            <button class="tablinks" onclick="openCity(event, 'setting')">Settings</button>
            <form action="" method="POST"><button type="submit" name="logout">Logout</button></form>
            <button class="tablinks" onclick="openCity(event, 'qna')">QnA</button>
        </div>
        <div id="contribution" class="tabcontent">
            <div class="mywork">
                <div class="tab2">
                    <button class="tablinks2" onclick="openType(event, 'Novels')" id="defaultOpen2">Novels</button>
                    <button class="tablinks2" onclick="openType(event, 'Comic')" id="defaultOpen2">Comic</button>
                    <button class="tablinks2" onclick="openType(event, 'Arts')" id="defaultOpen2">Arts</button>
                </div>
                <div id="Novels" class="tabcontent2">
                    <div>
                        <button class="new" onclick="window.location.href = 'newNovel.php'"><span>Add new Novel</span></button>
                    </div>
                    <div class="my row d-flex" id="myNovels">
                        <?php
                            $sql2 = "SELECT * from toonnovels where username='$username'";
                            $result2 = mysqli_query($conn,$sql2);
                            while($data2 = mysqli_fetch_assoc($result2)) {
                        ?>
                            <div class="card col-xl-lg-3 col-md-sm-4 col-6" style="width: 18rem;">
                                <img src="<?php echo $data2['cover']; ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $data2['title']; ?></h5>
                                    <p class="card-text"> ❤ <?php echo $data2['subscribers']; ?> Subscribers</p>
                                    <a href="#" class="btn btn-primary"><?php echo $data2['genre']; ?></a>
                                </div>
                            </div>
                        <?php
                            } 
                        ?>
                        
                    </div>
                </div>
                <div id="Comic" class="tabcontent2">
                    <div>
                        <button class="new" onclick="window.location.href = 'newComic.php'"><span>Add new Comic</span></button>
                    </div>
                    <div class="my" id="myComic">
                        <div>
                            
                        </div>
                    </div>
                </div>
                <div id="Arts" class="tabcontent2">
                    <div>
                        <button class="new" onclick="window.location.href = 'newArt.php'"><span>Add new Art</span></button>
                    </div>
                    <div class="my" id="myArts">
                        <div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="notification" class="tabcontent">
            <p>Notifications</p>
        </div>
        <div id="invite" class="tabcontent">
            <p>Invite Friends</p>
        </div>
        <div id="setting" class="tabcontent">
        <!-- update info -->
            <div class="container">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <!-- Update profile pic -->
                    <div class="cover">
                        <img id="output" alt="cover-photo" width=300px height=300px>
                    </div>
                    <input type="file" class="form-control" accept="image/*" name="updateCover" id="cover" onChange="loadFile(event)" />
                    <!-- update other info -->
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="updateName" value="<?php echo $data['name']; ?>" />
                    <p style="color:red; font-size:13px;"><?php echo $updateNameErr; ?></p>
                    <label for="">Username</label>
                    <input type="text" class="form-control" name="updateUsername" value="<?php echo $data['username']; ?>" />
                    <p style="color:red; font-size:13px;"><?php echo $updateUsernameErr; ?></p>
                    <label for="">Email</label>
                    <input type="text" class="form-control" name="updateEmail" value="<?php echo $data['email']; ?>" />
                    <p style="color:red; font-size:13px;"><?php echo $updateEmailErr; ?></p>
                    <label for="">Phone</label>
                    <input type="text" class="form-control" name="updatePhone" value="<?php echo $data['phone']; ?>" />
                    <p style="color:red; font-size:13px;"><?php echo $updatePhoneErr; ?></p>
                    <button type="submit" name="update" class="btn btn-secondary">Update</button>
                </form>
            </div>
        </div>
        <div id="qna" class="tabcontent">
            <div class="faq">
                <h2>Author QAs</h2>
                <p>
                    Hello, dear friends, it has been a long time since the launch of MangaToon novel zone. At present, a large number of works have been published on MangaToon and won a large number of fans' support, for which we are deeply grateful.<br>
                    During the communication with the authors, we found some questions need to be cleared, and we will list the common problems one by one for you to review.
                </p>
                <button class="accordion"><b>1. What are the requirements for publishing a novel on MangaToon?</b></button>
                <div class="panel">
                    <p>
                        First of all, your work must be original and not copied from others. Temporarily the translation works are not accepted.<br>
                        Secondly, do not publish content that is too obscene, violent, or affects any national or religious interests.
                    </p>
                </div>
                <br>
                <button class="accordion"><b>2. About audit</b></button>
                <div class="panel">
                    <p>
                        After the creation of the work, you should add chapters as soon as possible. Works with chapters will get the review earlier.
                    </p>
                </div>
                <br>
                <button class="accordion"><b>3. Must it be a finished work?</b></button>
                <div class="panel">
                    <p>
                        We welcome serial works as well. MangaToon is eager to grow with you.
                    </p>
                </div>
                <br>
                <button class="accordion"><b>4. How to report problems in a novel? (illegal content, cover, plagiarism, etc.)</b></button>
                <div class="panel">
                    <p>
                        The Report can be submitted at the bottom of the description page. Once verified, editors will immediately deal with the work.
                    </p>
                </div>
                <br>
                <button class="accordion"><b>5. How to get the opportunity to show on the homepage</b></button>
                <div class="panel">
                    <p>
                        Maintain a steady update will help you gain popularity and recommendations.
                    </p>
                </div>
                <br>
                <button class="accordion"><b>6. How to apply for a contract?</b></button>
                <div class="panel">
                    <p>
                        Please go to the MangaToon official website to apply for a contract. Currently , any author who has publishes more than 20 chapters can apply for a contract. We will review the work after receiving the application. After that, we will contact you and discuss the details about the contract.
                    </p>
                </div>
                <br>
                <button class="accordion"><b>7. Is it true that publishing a novel on MangaToon will generate revenue?</b></button>
                <div class="panel">
                    <p>
                        Of course it’s true. MangaToon will give real money to reward the author. As long as your work is read, you will have income. And you can find your income information on toonindia's official website.
                    </p>
                </div>
                <br>
                <button class="accordion"><b>8. How do I draw my income</b></button>
                <div class="panel">
                    <p>
                        Fill in the receiving account information on the website, and MangaToon will give you the payment when the withdrawal standard is reached!
                    </p>
                </div>
                <br>
                <h3>Release novels on MangaToon and create new possibilities for your work.</h3>
                <p>We have created a character image for signed works, and there will be comic stories online later, please stay tuned!!!</p>
                <br>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="js/profile.js"></script>
    <script type="text/javascript" src="js/contribution.js"></script>
    <script src="js/viewImage.js"></script>
    <script src="js/faqs.js"></script>
    <script 
     src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" 
     integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" 
     crossorigin="anonymous">
    </script>
</body>
</html>

<?php

    } else {
        header('location:index.php');
    }

?>