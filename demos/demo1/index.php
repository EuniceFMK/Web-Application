<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- <form action="demo1.php" method="post">
        Name: <input type="text" name="name"> <br>
        E-mail: <input type="text" name="email"> <br>
        <input type="submit" value="Submit">
    </form> -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        Name: <input type="text" name="name"> <br>
        E-mail: <input type="text" name="email"> <br>
        <input type="submit" value="Submit">
    </form>
    <?php 
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        // $name=$_REQUEST['name'];
        // if(empty($name)){
        //     echo "Name is empty";
        // }
        // else{
        //     echo $name;
        // }
        //Advanced validation
        if(isset($_POST["name"])&& strlen($_POST["name"])>0){
            $name = strip_tags(trim($_POST["name"]));
            echo $name;
        }
        else{
            echo "The name is empty";
        }
    }
    ?>
</body>
</html>