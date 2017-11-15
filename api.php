<?php
$conn = new mysqli("meddev.rdsm6y2ok4ji834.rds.bj.baidubce.com","gsj","ranknow123","gsjdb");
if ($conn->connect_error){
    die("Connection failed:".$conn->connect_error);
}
if(!isset($_GET['do'])) {
    exit(json_encode(['err'=>1, 'errmsg'=>'缺少参数do']));  //TODO jsfesfle
}
$getdo =$_GET['do'];

switch ($getdo){
    case 'log in': 
    case 'login':
        if(isset($_GET['username']) && isset($_GET['password'])){
            $username= $_GET['username'];
            $password= $_GET['password'];
            if(empty($username) || empty($password)){               //
                $errmsg5 = ['err'=>2, 'errmsg'=>'用户名或密码是空=null'];
                echo json_encode($errmsg5);
                exit();
            }

            $ret = login($username,$password);
            echo json_encode($ret);
        }   else{
            $errmsg = ['err'=>1, 'errmsg'=>'用户名或密码错误' ];
             echo json_encode($errmsg);
        }
        break;
    case 'register':
        if(isset($_GET['username']) && isset($_GET['password'])){
            $u = $_GET['username'];
            $p = $_GET['password'];
            if(empty($u)||empty($p)){
                $errmsg3 = ['err'=>3, 'errmsg'=>'register_username/password is empty'];
                echo json_encode($errmsg3);
                exit();
            }
            $regport = register($u,$p);
            echo json_encode($regport);
        }   else {
            $errmsg2 = ['err' => 2, 'errmsg'=>'no username/password' ];
            echo json_encode($errmsg2);
        }
        break;
    case 'recordlist':
        $ret = recordlist();
        echo json_encode($ret);
        break;
    case 'add_comment':
        session_start();
        if(!isset($_SESSION['userid'])){
            exit(json_encode(['err'=>3,'errmsg'=>'用户未登录']));
        } 
        $userid_add = $_SESSION['userid'];
        
        if(isset($_GET['title']) && isset($_GET['comment'])) {
            $title = $_GET['title'];
            $comment = $_GET['comment'];
                
            
            if (empty($title) || empty($comment)) {
                $errmsg6 = ['err' => 2, 'errmsg' => '标题或内容缺失'];
                echo json_encode($errmsg6);
                exit();
            }
            
            $add_comment1 = add_comment($title, $comment, $userid_add);
            echo json_encode($add_comment1);
        }else{
            $errmsg7 = ['err'=>2,'errmsg'=>'无标题或内容'];
            echo json_encode($errmsg7);
        }
        break;

    default:
        echo "{}";
}

function login($username,$password){
 global $conn;
 $sql = "Select * from login WHERE username='$username' ";
 $res = $conn->query($sql);

 if ($conn->affected_rows<=0) {
     $ret= ['err'=>1, 'errmsg'=>'用户名未找到1'];
     //alert($ret);
 } else{
     $row = $res->fetch_array();
     if ($row['password'] == $password) {
         $ret = ['err'=>0, 'errmsg'=>'success'];
         session_start();
         $_SESSION['userid']=$row['userid'];
         $_SESSION['username']=$row['username'];
     } else{
         $ret= ['err'=>1, 'errmsg'=>'密码错误'];
     }
 }
 return $ret;
}

function register($username_reg,$password_reg){
    global $conn;
    $sql2 = "Select * from login WHERE username='$username_reg' ";
      //select 返回的是数据集
    $res2 = $conn->query($sql2);

    if($conn->affected_rows<=0) {
            $sql3 = "INSERT INTO login (username,password) 
              VALUES ('$username_reg','$password_reg')";
            
            $res3 = $conn->query($sql3);
                 //echo $res3+'1';
            if($res3){
                $ret= ['err'=>0, 'errmsg'=>'success'];
                // echo $res3+'2';
            } else {
                $ret = ['err'=>1, 'errmsg'=>'注册失败'];
                    // echo $res3+'3';
            }
    } else {
           $ret= ['err'=>1, 'errmsg'=>'用户名已存在'];
                    //  echo $res3+'4';
    }
    return $ret;
}

function add_comment($title,$comment, $userid_add) {
    global $conn;
    
    
        $sql6= "INSERT INTO commentboard (title,comment,userid)
                      VALUES ('$title','$comment','$userid_add')";
        $res6 = $conn-> query($sql6);
        if($res6){
            $ret = ['err'=>0, 'errmsg'=>'success'];
        }   else{
            $ret = ['err'=>1, 'errmsg'=>'unable to insert into Mysql'];
        }
        return $ret;
}


function recordlist (){
    /*$ret= ['err'=>1, 'errmsg'=>'用户名已存在'];
    return $ret;*/
    global $conn;
    session_start();

    if(!isset($_SESSION['userid'])){
        return ['err'=>1, 'errmsg'=>'用户没登陆'];
    }

    $userid4= $_SESSION['userid'];
    $sql4 = "SELECT * from commentboard WHERE userid =$userid4";

    $res4 = $conn->query($sql4);
    $records= [];
    if ($conn->affected_rows>0) {
        while ($row = $res4->fetch_array()){
            $records[]=$row;
            /*echo "<table>";
            echo "<tr><td>ID: </td><td>".$row["ID"]."</td></tr>";
            echo "<tr><td>名字: </td><td>".$row["name"]."</td></tr>";
            echo "<tr><td>时间: </td><td>".$row["entrytime"]."</td></tr>";
            echo "<tr><td>标题: </td><td>".$row["title"]."</td></tr>";
            echo "</table>";
            //echo "<br>";   */
        }
    }
    $ret= ['err'=>0, 'data'=>['records'=>$records]];
    return $ret;
}