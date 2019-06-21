<?php
    session_start();
    include 'config.php';
    
    $update=false;
    
    $id="";
    $name="";
    $email="";
    $phone="";
    $photo="";
    //daca s-a apasat butonul executa tot
    if (isset($_POST['add']))
    {
        $name=$_POST['name'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $photo=$_FILES['photo']['name'];
        $upload="uploads/".$photo;
        
        $query="INSERT INTO crud(name, email, phone, photo) VALUES(?,?,?,?)";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("ssss", $name,$email, $phone, $upload);
        $stmt->execute();
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload);
        $_SESSION['response']="Succesfully inserted into database";
        $_SESSION['res_type']="success";
        header('location: index.php');
        
    }
    
    if (isset($_POST['update']))
    {
        $id=$_POST['id'];
        $name=$_POST['name'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $oldimage=$_POST['oldimage'];
        
        if(isset($_FILES['photo']['name'])&&($_FILES['photo']['name']!=""))
        {
            $newimage="uploads/".$_FILES['photo']['name'];
            unlink($oldimage);
            move_uploaded_file($_FILES['photo']['tmp_name'], $newimage);
        }
        else{
            $newimage=$oldimage;
        }
        
        $query="UPDATE crud SET name=?, email=?, phone=?, photo=? WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("ssssi", $name,$email, $phone, $newimage, $id);
         $stmt->execute();
         
         $_SESSION['response']="Updated succesfully!!!";
         $_SESSION['res_type']="primary";
         header('location: index.php');
        
        
        
        
        
        
    }
    if (isset($_GET['delete']))
    {
        $id=$_GET['delete'];
        
        //stergem poza din folderul Uploads
        $sql="SELECT photo FROM crud WHERE id=?";
        $stmt1=$conn->prepare($sql);
        $stmt1->bind_param('i', $id);
        $stmt1->execute();
        $result1=$stmt1->get_result();
        $row=$result1->fetch_assoc();
        $imagepath=$row['photo'];
        unlink($imagepath);
        
        //stergem inregistrarea din baza de date
        $query="DELETE FROM crud WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $_SESSION['response']="Succesfully deleted from database";
        $_SESSION['res_type']="danger";
        header('location: index.php');
    }
    if (isset($_GET['edit']))
    {
        $id=$_GET['edit'];
        $query="SELECT * FROM crud WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result=$stmt->get_result();
        $row=$result->fetch_assoc();
        
        $id=$row['id'];
        $name=$row['name'];
        $email=$row['email'];
        $phone=$row['phone'];
        $photo=$row['photo'];
        
        $update=true;
    }
    if (isset($_GET['details'])){
        $id=$_GET['details'];
        $query="SELECT * FROM crud WHERE id=?";
        $stmt=$conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result=$stmt->get_result();
        $row=$result->fetch_assoc();
        
        $vid=$row['id'];
        $vname=$row['name'];
        $vemail=$row['email'];
        $vphone=$row['phone'];
        $vphoto=$row['photo'];
    }

?>