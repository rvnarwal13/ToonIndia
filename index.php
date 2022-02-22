<?php
    session_start();


    if(isset($_POST['signin'])) {

        $username = $_POST['loginuser'];
        $password = $_POST['loginpass'];
    
        $host='localhost';
        $user='root';
        $pass='';
        $dbname='toonindia';
        $conn=mysqli_connect($host,$user,$pass,$dbname);
        if(!$conn) {
            die("couldn't connect : ".mysqli_connect_error())."<br>";
        }

        if(isset($_POST['signin'])) {
            $sql = "SELECT username from toonusers where username='$username'";
            $result = mysqli_query($conn,$sql);
            $num = mysqli_num_rows($result);
            if($num) {
                $sql2 = "SELECT * from toonusers where username='$username' AND password='$password'";
                $result2 = mysqli_query($conn,$sql2);
                $num2 = mysqli_num_rows($result2);
                if($num2) {
                    $_SESSION['username']=$username;
                    header('location:index.php');
                } else {
                    ?>
                    <script>
                        alert("Username or Password is wrong");
                    </script>
                    <?php
                }
            } else {
                ?>
                    <script>
                        alert("Username or Password is wrong");
                    </script>
                <?php
            }
        }
        mysqli_close($conn);
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
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <nav class="navbar navbar-expand fixed-top">
        <div class="container-fluid">
            <h4 id="logo">toonindia</h4>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">Click</span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav justify-content-end"> 
                    <?php 
                        if(isset($_SESSION['username']) && $_SESSION['username']!='' && $_SESSION['username']!=NULL) {
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
                            ?>
                                <li class="nav-item" id="active"><a href="index.php">Home</a></li>
                                <li class="nav-item"><a href="profile.php"><?php echo $data['username']; ?></a></li>
                            <?php
                        } else {
                            ?>
                                <li class="nav-item" id="active"><a href="index.php">Home</a></li>
                                <li class="nav-item" data-bs-toggle="modal" data-bs-target="#loginModal"><button class="nav-btn" type="submit"><i class="glyphicon glyphicon-log-in"></i>Login</button></li>
                                <li class="nav-item"><button class="nav-btn" type="submit" onclick="window.location.href='signup.php'"><i class="glyphicon glyphicon-user"></i>Signup</button></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <p class="sign" align="center">Sign in</p>
                <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input class="un" name="loginuser" type="text" placeholder="Email">
                    <input class="pass" name="loginpass" type="password" placeholder="Password"> <br>
                    <p align="center"><button type="submit" name="signin" class="btn btn-secondary">Sign in</button></p>
                    <p class="forgot" align="center"><a href="#">Forgot Password?</a></p>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="tab">
        <button class="tablinks" onclick="openType(event, 'Novels')" id="defaultOpen">Novels</button>
        <button class="tablinks" onclick="openType(event, 'Comics')" id="defaultOpen">Comics</button>
        <button class="tablinks" onclick="openType(event, 'Arts')" id="defaultOpen">Arts</button>
        </div>

        <!-- Novels -->
        <div id="Novels" class="tabcontent">
            <p>Novels</p>
        </div>
        <!-- Comics -->
        <div id="Comics" class="tabcontent">
            <p>Comics</p>
        </div>
        <!-- Arts -->
        <div id="Arts" class="tabcontent">
            <p>Arts</p>
        </div>
    </div>

    <script type="text/javascript" src="js/index.js"></script>

    <script 
     src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" 
     integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" 
     crossorigin="anonymous">
    </script>

</body>
</html>