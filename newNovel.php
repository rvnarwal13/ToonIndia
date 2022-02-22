<?php
session_start();

if(isset($_SESSION['username']) && $_SESSION['username']!='' && $_SESSION['username']!=NULL) {
        $cover=$coverErr=$title=$titleErr=$desc=$descErr=$genre=$genreErr="";
        $count=0;

        if($_SERVER["REQUEST_METHOD"]=="POST") {
            $target_dir = "storage/novel/";
            $target_file = $target_dir . basename($_FILES["cover"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            if($imageFileType != "jfif" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $coverErr = "Sorry, only JPG, JPEG, PNG, JFIF & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk != 0) {
                if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
                    $cover = $_FILES['cover'];
                    if(empty($_POST['title'])) {
                        $titleErr = "Title cannot be empty";
                    } else {
                        $title = $_POST['title'];
                        $count++;
                    }
                
                    if(empty($_POST['desc'])) {
                        $descErr = "Description cannot be empty";
                    } else {
                        $desc = $_POST['desc'];
                        $count++;
                    }
                
                    if($_POST['genre']=='genre') {
                        $genreErr="Please select genre";
                    } else {
                        $genre = $_POST['genre'];
                        $count++;
                    }
                
                    if($count==3) {
                        $host='localhost';
                        $user='root';
                        $pass='';
                        $dbname='toonindia';
                        $conn=mysqli_connect($host,$user,$pass,$dbname);
                        if(!$conn) {
                            die("couldn't connect : ".mysqli_connect_error())."<br>";
                        }
                        $file=$cover['name'];
                        $temp = uniqid().".".$imageFileType;
                        rename("storage/novel/$file","storage/novel/$temp");
                        if(isset($_POST['submit'])) {
                            $username = $_SESSION['username'];
                            $time = date('h:i:s');
                            $date = date('d-m-Y');
                            $sql = "INSERT INTO toonnovels(username,cover,title,description,genre,date,time,subscribers,support) VALUES('$username','storage/novel/$temp','$title','$desc','$genre','$date','$time','0','0')";
                            $result = mysqli_query($conn,$sql);
                            if(!$result) {
                                die('Query Failed! '.mysqli_error($conn));
                            }
                            header('location:newNovelChapter.php');
                        }
                        mysqli_close($conn);
                    }
                } else {
                    $coverErr = "Sorry, there was an error uploading your file.";
                }
            }
        }

        function test_input($value) {
            $value=trim($value);
            return $value;
        }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>new novel</title>
    <link rel="stylesheet" type="text/css" href="css/new.css">
</head>
<body>
    <div class="header">
		<h1>toonindia</h1>
	</div>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
    <div class="main">
        <div class="left">
            <p align="center">
                <div class="cover">
                    <img id="output" alt="cover-photo" width=100px height=300px>
                </div>
                <input type="file" accept="image/*" name="cover" id="cover" onChange="loadFile(event)" />
                <p><label style="color:red; font-size:13px;"><?php echo $coverErr; ?></label></p>
            </p>
        </div>
        <div class="right">
            <input type="text" name="title" id="title" placeholder="Title of Novel" class="un"> <br>
            <p><label style="color:red; font-size:13px;"><?php echo $titleErr; ?></label></p>
            <textarea name="desc" id="desc" cols="50" rows="10" placeholder="Description" class="un"></textarea> <br>
            <p><label style="color:red; font-size:13px;"><?php echo $descErr; ?></label></p>
            <select name="genre" id="genre" align="center" class="un">
                <option value="genre" selected align="center">genre</option>
                <option value="si-fic">si-fic</option>
                <option value="romantic">romantic</option>
                <option value="horror">horror</option>
            </select> <br>
            <p><label style="color:red; font-size:13px;"><?php echo $genreErr; ?></label></p>
            <p align="center">
                <input type="submit" name="submit" value="Create" class="btn create" id="create" />
                <a href="profile.php" class="cancel" alink="white" vlink="white">Cancel</a>
            </p>
        </div>
    </div>
    </form>
    <script type="text/javascript" src="js/viewImage.js"></script>
</body>
</html>