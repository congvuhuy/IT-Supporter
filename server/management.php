<?php 
session_start();
include("./configuration/connection.php");
if(!isset($_COOKIE['phone_number']))  header("Location:./login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="./css/style.css" media="all" rel="Stylesheet" type="text/css" />
    <link href="./css/management.css" media="all" rel="Stylesheet" type="text/css" />
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="layout">
     <?php include("./components/sidebar.php")?>
     <div class="content__wrapper">
         <div class ="content">
         <header>
             <div class="title">
                 <h2 class= "title__name">Quản lí thành viên</h2>
             </div> 
         </header>
        
         <!-- <?php 
         date_default_timezone_set("Asia/Ho_Chi_Minh");
         echo date("hisYmd")
         ?> -->
            
           
         <div class ="container">
             <div class ="container__search">
                 <form class= "filter__form">
                     <div class="filter__item filter__by_permission">
                         <p class ="title">Chức vụ</p>
                         <select name="permission" id="" >
                             <option value="0" <?php echo $_GET['permission'] ==0 ? "selected" :""; ?> >Tất cả</option>
                             <!-- <option value="1" <?php echo $_GET['permission'] ==1 ? "selected" :""; ?> >Admin</option> -->
                             <option value="2" <?php echo $_GET['permission'] ==2 ? "selected" :""; ?> >Member</option>
                             <option value="3" <?php echo $_GET['permission'] ==3 ? "selected" :""; ?> >Thủ quỹ</option>
                             <option value="4" <?php echo $_GET['permission'] ==4 ? "selected" :""; ?>>CTV</option>
                         </select>
                     </div>
                     <div class="filter__item filter__by_ban">
                         <p class ="title">Ban</p>
                         <select name="ban" id="">
                             <option value="0" <?php echo $_GET['ban'] ==0 ? "selected" :""; ?>>Tất cả</option>
                             <option value="1" <?php echo $_GET['ban'] ==1 ? "selected" :""; ?>>Ban sự kiện</option>
                             <option value="2" <?php echo $_GET['ban'] ==2 ? "selected" :""; ?>>Ban truyền thông</option>
                             <option value="3" <?php echo $_GET['ban'] ==3 ? "selected" :""; ?>>Ban kĩ thuật</option>
                         </select>
                     </div>
                     <div class="filter__item filter__by_status">
                     <p class ="title">Trạng thái</p>
                         <select name="status" id="">
                                 <option value="0" <?php echo $_GET['status'] ==0 ? "selected" :""; ?>>Tất cả</option>
                                 <option value="1" <?php echo $_GET['status'] ==1 ?  "selected" :""; ?>>Đang hoạt động</option>
                                 <option value="2" <?php echo $_GET['status'] ==2 ? "selected" :""; ?>>Đã rời</option>
                             </select>
                     </div>
                     <button type="submit" class= "filter__btn">
                         <i class='bx bx-filter-alt'></i>
                         <span>Lọc</span>
                     </button>
                 </form>
                 <div class="searching__user">
                     <i class='bx bx-search-alt-2 icon_search'></i>
                     <form action="" method ="POST" class='search-form'>
                         <input type="text" placeholder="Search user ..." name="search" oninput="searchUser()">
                     </form>
                 </div>
             </div>
             <div class ="containter__content">
                 <ul class= "list__field_user">
                    <?php
                     $user_id = $_SESSION['user_id'];
                     $sql2 ="SELECT avatar, full_name, TenBan, MaCV   FROM users INNER  JOIN  ban ON users.MaBan  = ban.MaBan WHERE account_id  = '".$user_id."'";
                     $data = $connection->query($sql2);
                     $user= mysqli_fetch_row($data);
                     if($user[3] == 1)
                        $list_user = array("Thông tin cá nhân", "Ban",  'Trạng Thái', "Email", "SĐT", "Hành Động");
                    else 
                    $list_user = array("Thông tin cá nhân", "Ban",  'Trạng Thái', "Email", "SĐT");
                    for($i =0; $i<  count($list_user); $i++)
                    {
                         echo " <li class ='field__name'>
                             <p> ".$list_user[$i]."</p>
                             </li>";
                    }
                     ?>
                 </ul>
                 <ul class ="list__user">
                     <?php
                         $permission_url = $_GET['permission'];
                         $ban_url = $_GET['ban'];
                         $status_url = $_GET['status'];
                         $sql = "SELECT account_id,avatar, full_name, TenCV, TenBan, date_of_joining, status,email, address, phone_number FROM users  INNER JOIN ban  ON  users.MaBan = ban.MaBan INNER  JOIN chuc_vu ON   users.MaCV = chuc_vu.MaCV";
                         if($permission_url == "0" && $ban_url == "0" && $status_url =="0") 
                         {
                            //  if(isset($_POST['search']))
                            //      $sql .= " WHERE full_name LIKE '%".$_POST['search']."%'";
                             
                         }
                         else if($permission_url != "0")
                            $sql .= " WHERE users.MaCV = ".(int)$permission_url."";
                         else if($ban_url != "0")
                            $sql .= " WHERE  users.MaBan = ".(int)$ban_url."";
                         else if($status_url !='0')
                            $sql .= " WHERE status = ".(int)$status_url."";
                         else 
                            $sql .= " WHERE users.MaCV = ".(int)$permission_url." AND users.MaBan = ".(int)$ban_url." AND status = ".(int)$status_url."";
                         $result = $connection->query($sql);
                         if(mysqli_num_rows($result) > 0)
                         {
                            while($matrix_array = mysqli_fetch_all($result, MYSQLI_ASSOC))
                            {
                                 for( $i =0; $i< count($matrix_array); $i++)
                                 {
                                echo " <li class ='user__item ";
                                     echo $matrix_array[$i]['status'] ==0 ? 'disable' :'';
                                        echo "'>
                                        <div class ='user__infor'>
                                            <span class ='user__avatar'> ";
                                                if($matrix_array[$i]["avatar"])
                                                    echo "  <img src='./avatar_users/".$matrix_array[$i]["avatar"]."' alt=''>"; 
                                                else echo "<img src='./assets/images/no_image.png' alt=''>";     
                                                    echo "
                                            </span>
                                            <span class ='user__infor_detail'>
                                                <p class ='user__full-name'>".$matrix_array[$i]["full_name"]."</p>
                                                <p class ='user__permission'>".$matrix_array[$i]['TenCV']."</p>
                                            </span>
                                        </div>
                                        <div class= 'ban'>
                                        <p>
                                           ".$matrix_array[$i]['TenBan']."
                                       </p>
                                    </div>
                            
                                    <div class ='status'>
                                        <p>";
                                           echo $matrix_array[$i]['status'] ==1 ? 'Đang hoạt động' : "Đã rời";
                                        echo "</p>
                                    </div>
                                    <div class ='user__email'>
                                        <p>".$matrix_array[$i]['email']."</p>
                                    </div>
                                    <div class ='user__sdt'>
                                    <p>".$matrix_array[$i]['phone_number']."</p>
                                </div>
                                    ";
                                    $user_id = $_SESSION['user_id'];
                                    $sql2 ="SELECT MaCV   FROM users INNER  JOIN  ban ON users.MaBan  = ban.MaBan WHERE account_id  = '".$user_id."'";
                                    $data = $connection->query($sql2);
                                    $user= mysqli_fetch_row($data);
                                    if($user[0] ==1)
                                    {
                                        echo "<div class ='user__action'>
                                           <form class='my-form'>
                                           <input type='text' value='".$matrix_array[$i]['account_id']."' name='user_id'>";
                                           if($matrix_array[$i]['status'] ==1) echo " <button type='submit' id= 'submit'>Sửa</button>";
                                       echo "</form>
                                       </div>";
                                    }
                                    echo " </li>";
                                 }
                            }
                         }
                     ?>
                 </ul>
                 <div id="postData"></div>
             </div>
         </div>
        
     </div>
     </div>
     <div class="overlay"></div>
 </div>


<script type="text/javascript">
  
  $(document).ready(function() {
    $('.my-form').submit(function(event) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: './components/profile_popup.php',
        data: $(this).serialize(),
        success: function(response) {
          $('.overlay').html(response);
        }
      });
    });
  });
</script>


<script>
    
const categories = document.querySelector('.categories');
const categoryItems =document.querySelectorAll('.categories li');
const categoriesLine = document.querySelector('.container .categories__line');
const buttonNewUser = document.querySelector('header .action .new__btn');
const overlay = document.querySelector('.layout .overlay');
const editBtns = document.querySelectorAll('.user__action #submit');
Array.from(categoryItems).forEach((item, index)=>
    item.onclick =function(){
        Array.from(categoryItems).forEach(itemNode=> itemNode.classList.remove('active'));
        item.classList.add('active');
        categoriesLine.style.transform = `translateX(${index * 100}px)`;
    });

    function exitFunc()
    {
        overlay.classList.remove('active');
    }
    editBtns.forEach(btn=>{
        btn.onclick = function()
        {
             overlay.classList.add('active');
        }
      
    }
    )
    const timeoutID= [];
   function searchUser()
   {
        if(timeoutID.length > 0)
        {
            clearTimeout(timeoutID[timeoutID.length -1]);
            timeoutID.pop();
        }
         timeoutID.push( setTimeout(function(){
            var formData = $('.search-form').serialize();
            $.ajax({
                method: 'POST',
                url: './components/noti_item.php',
                data: formData,
                success: function(response) {
                $('.list__user').html(response);
                }
            });
        }, 1000))
   }
   
</script>
</body>
</html>