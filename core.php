<?php
//butun error kodlari
$errors = array();
//Sayta daxil olanin sehifeni gore bilmesi ucun qeydiyyatdan kecmeli oldugunu xeber vern func.
function mustSign() {
  return '<div class="row addTerminMess">
    <p>Termin elave etmek ucun<br/>
    <a href="#" data-toggle="modal" data-target="#loginModal" id="signupli">Qeydiyyat</a>
    dan kecmeli ve ya <a href="#" data-toggle="modal" data-target="#loginModal">Login</a> olmalisiniz</p>
  </div>';
}
//user termin elave ede bilmesi ucun form
function addTermin(){
  return '<form class="terminForm" action="#" method="POST">
<div class="form-group">
  <label for="terminInput">Termin</label>
  <input type="text" class="form-control" id="terminInput" placeholder="Termin" name="termin">
</div>
<div class="form-group">
  <label for="termDesc">Izahatı</label>
  <textarea type="password" class="form-control" id="termDesc" placeholder="Qısa izahı daxil edin" name="termin_desc"></textarea>
</div>
<div class="form-group">
  <label for="kateqoriya">Kateqoriya</label>
  <select type="text" class="form-control"  id="kateqoriya" placeholder="Əgər varsa"  name="ter_cat">
    <option value="Digər">Digər</option>
    <option value="IT">IT</option>
    <option value="Tibb">Tibb</option>
    <option value="Kimya">Kimya</option>
    <option value="Riyaziyyat">Riyaziyyat</option>
    <option value="Mədəniyyət">Mədəniyyət</option>
    <option value="Tarix">Tarix</option>
    <option value="Coğrafiya">Coğrafiya</option>
    <option value="Biologiya">Biologiya</option>
    <option value="Fizika">Fizika</option>
    <option value="Neft">Neft</option>
    <option value="Kino">Kino</option>
  </select>
</div>
<div class="form-group">
  <label for="source">Mənbə</label>
  <input type="text" class="form-control" name="termin_source" id="source" placeholder="Əgər varsa">
</div>
<div class="form-group">
  <label for="keyWord">Açar söz (arasında vergül qoymaqla)</label>
  <input type="text" class="form-control" name="keyWord" id="keyWord" placeholder="Tags">
</div>
<button type="submit" class="btn btn-default" name="submit">Göndər</button>
</form>';
}
function myTermin(){
  include 'db.php';
  $start = 0;
  $limit = 3;

  if(isset($_GET['id'])) {
    $id=$_GET['id'];
    $start=($id-1)*$limit;
  }

  $userID = $_SESSION['user_id'];
  //echo myTermin();
  $connection = mysqli_select_db($db_connection,$dbname);

//$sqlMyTer = "SELECT * FROM termin WHERE user_id = $userID";
  $query=mysqli_query($db_connection,"SELECT * FROM termin WHERE user_id= $userID LIMIT $start, $limit");

  while ($query2 = mysqli_fetch_assoc($query)) {

             echo '<div class="my_termin">
  				   	<h3 class="disp_in-block">
  				   		<a href="" id='."termin:".$query2['termin_id'].' contenteditable=true data-type="textarea">'.$query2["termin"].'</a>
  				   	</h3>

                  <button class="glyphicon glyphicon-pencil edit_button"></button>
                
  				   	<div class="disp_in-block float_r">
  				   		
  				   		<div>
  				   			<a href="profile/delete.php?id='.$query2["termin_id"].'" ><button  class="glyphicon glyphicon-trash delete_glyphico"></button></a>
  				   		</div>
  				   	</div>
  				   	<div class="disp_in-block float_r date_div">
  				   		<p class="disp_in-block date">Add date:&nbsp;</p>
  				   		<p class="disp_in-block date_time">9-12-2015</p>
  				   	</div>
  				   	<strong><p class=>Izahat:&nbsp;</p></strong>
  				   	<div id="div1">
  				   		<p class="desct" id="'."termin_desc:".$query2["termin_id"].'" contenteditable=true data-type="textarea">'.$query2["termin_desc"].'</p>
                <button class="glyphicon glyphicon-pencil edit_button"></button>
  				   	</div>
  				   </div><br>';
  }

      $rows=mysqli_num_rows(mysqli_query($db_connection,"SELECT * FROM termin WHERE user_id= $userID"));
      $total=ceil($rows/$limit);
      if ($total > 1) {
        echo "<ul class='pagination page'>";
        for($i=1;$i<=$total;$i++)
        {
        if($i==$id) { echo "<li class='current'><a>".$i."</a></li>"; }

        else { echo "<li><a href='?id=".$i."'>".$i."</a></li>"; }
        }
        echo "</ul>";
      }

}
function bestWriter(){
  include 'db.php';
  $connection = mysqli_select_db($db_connection,$dbname);
  return $query=mysqli_query($db_connection,"SELECT * FROM user WHERE status = 'yazar' GROUP BY num_post DESC LIMIT 5");
}



function newestTermin(){
  include 'db.php';
  $connection = mysqli_select_db($db_connection,$dbname);
  return $query=mysqli_query($db_connection,"SELECT * FROM termin GROUP BY ter_pub_date DESC LIMIT 5");
}
function mostRead(){
  include 'db.php';
  $connection = mysqli_select_db($db_connection,$dbname);
  return $query=mysqli_query($db_connection,"SELECT * FROM termin GROUP BY ter_num_view DESC LIMIT 5");
}


function tags(){

}

	/*
	 *bu funksiya hansisa bir userin
	 *log olub olmadigini yoxlayir
	 */
	function logged_in() {
		/*
		 *sessiyanin stausunu yoxlayir, yeni bundan evvel start olub-
		 *olmadigini bilmek uchun. eger bir defe bir sehifede start olunubsa
		 *onda ehtiyac yoxdur yeninden start etmeye
		 */
		if (session_status() == PHP_SESSION_NONE) {
	    	session_start();
		}
		return ((isset($_SESSION['username'])) ? true : false);
	}

	/**
	 *bu funkiyada userin basada olub ve ya
	 *olmamasi yoxlanilir
	 *@return: true ve ya false deyer qaytarir
	 */
		function user_exists($username) {
				//initialize valiables from global
				global $table_users, $db_connection;

				$sql = "SELECT COUNT(*) as num_of_users FROM $table_users WHERE username = '$username'";

				echo $sql."<br>";
				$result_query = mysqli_query($db_connection, $sql);

				$row = mysqli_fetch_assoc($result_query);
				$num_of_users = $row['num_of_users'];
				echo $num_of_users;
				return $num_of_users;
			}
	/**
	 *bu funkiya userin bazada tesdiq olundugunu ve ya
	 *olmadigini yoxlayir
	 *@return: true ve ya false deyer qaytarir
	 */
	function user_active($username) {
			global $table_users, $db_connection;

			$sql = "SELECT user_verified as num_of_users FROM $table_users WHERE username = '$username' AND user_verified=1";
			$result_query = mysqli_query($db_connection, $sql);

			if ($result_query)
			     return true;
			else
				 return false;
	}

    /**
     *bu funksiya yoxlayir bu email bazada var ya yox
     *@return sorgunun neticesini qaytarir. yeni sorgunun neticesi 0,
     *yeni bele email-den 0 denedir, yeni false. eks halda, 1 qaytaracag, yeni true
	 */
	function email_exists($email) {
			global $table_users, $db_connection;

			$sql = "SELECT COUNT(*) as num_of_emails FROM $table_users WHERE email = '$email'";
			$result_query = mysqli_query($db_connection, $sql);

			$row = mysqli_fetch_assoc($result_query);
			$num_of_emails = $row['num_of_emails'];
			return $num_of_emails;
	}

	/**
	 *@param register_data userin daxil etdiyi deyerlerden ibaret olan array
	 *bu funksiya yeni useri user table-na elave etsek
	 *@return bazaya daxil etse inserted yazir, eks halda db error-u gosterir
	 */
	function register_user($register_data) {

				global $table_users, $db_connection;
        date_default_timezone_set('Asia/Baku');
				$today = date("Y-m-d  H:i:s");

				$table_columns = "(username, firstname,lastname,birthdate,email,gender,password,user_photo,reg_date)";
				$table_values = "('$register_data[username]',
								  '$register_data[name]',
                  '$register_data[surname]',
                  '$register_data[birth]',
								  '$register_data[email]',
                  '$register_data[gender]',
								  '$register_data[password]',
                  '$register_data[pphoto]',
								  '$today')";
				// echo $table_values;

				$sql = "INSERT INTO $table_users ".$table_columns. " VALUES ".$table_values;

				$query = mysqli_query($db_connection, $sql);

				if ($query) {
					if (!isset($_SESSION)) {
        				session_start();
   					}
					$_SESSION['username'] = $register_data['username'];
					$_SESSION['user_reg_date'] = $today;
					//qeydiyyatdan kechmish userin yeni profiline kech
					header('Location: profile.php');
				} else {
					$errors[] =  "Error: " . $sql . mysqli_error($db_connection). "<br>";
				}

	}
	function elaveTermin() {
  		  include('db.php');
       
  			$termin = $_POST['termin'];
  			$termin_desc = $_POST['termin_desc'];
  			$ter_cat = $_POST['ter_cat'];
        $termin_source = $_POST['termin_source'];
        //eger boshdursa onda userid adi menbe olacag
        if (empty($termin_source)) $termin_source = $_SESSION['username'];
        
        if (empty($termin) || empty($termin_desc)) {
          echo "Xanaları boş buraxmayın";
        }
        else {
          $selecet= "SELECT * FROM termin WHERE termin='$termin'";
          $result=mysqli_query($db_connection,$selecet);
           $num_rows=mysqli_num_rows ($result);
           //echo $num_rows;

            if($num_rows>0){
            echo " termin artiq movcuddur";

             } else {

              $user=$_SESSION['user_id'];

              $today = date("Y-m-d");
                $add = "INSERT INTO termin(user_id,termin, termin_desc, ter_cat,ter_pub_date, ter_source)
                               VALUES('$user','$termin', '$termin_desc', '$ter_cat','$today', '$termin_source')";
                $insert = mysqli_query($db_connection,$add);

                 if($insert){
                   echo "Termin elave olundu!!!!";
                     header("Refresh:0");

                 }else{

                   echo 'Termin elave olunmadi!!!';

             }
           }
         }
       }

    /**
     *bu funksiya edilen like ve ya dislike bazada, yeni muvafig cedvelde qeyd edir
     *@param user_id like ve ya dislike eden userin id-si
     *@param term_id like ve ya dislike edilen terminin id-si
     *@param daxil edilen table-in adi: ya termin_like, ya da termin_dislike
     *@return boolean deyer: true => insert olundu, false => insert zamani hansisa xeta bash verdi
     */
    function insert_like($user_id, $term_id, $table_name) {
        include 'db.php';

        $table_columns = "(termin_id, user_id)";
        $table_values = "('$term_id', '$user_id')";

        $sql = "INSERT INTO $table_name $table_columns VALUES $table_values";

        $query = mysqli_query($db_connection, $sql);

        mysqli_close($db_connection);
        if ($query)

          return true;
        else
          return false;
    }

    /**
     *bu funksiya edilen like ve ya dislike evvel olunub
     *ve ya olunmadigini yoxlayir
     *@param user_id like ve ya dislike eden userin id-si
     *@param term_id like ve ya dislike edilen terminin id-si
     *@param daxil edilen table-in adi: ya termin_like, ya da termin_dislike
     *@return boolean deyer: true => qaytarilan setirlerin sayin 0-dan choxdur,
     *false => qaytarilan setirlerin sayin 0-a beraberdir
    */
    function previously_liked($user_id, $term_id, $table_name) {
        include 'db.php';

        $sql = "SELECT * FROM $table_name WHERE user_id=$user_id AND termin_id=$term_id";
        $query = mysqli_query($db_connection, $sql);

        // mysqli_close($db_connection);
        if (mysqli_num_rows($query) != 0)
          return true;
        else
          return false;
    }
    /**
     *bu funksiya termin sehifesinde lazim olan terminin like
     *ve ya dislike sayini artirir
     *@param term_id like ve ya dislike edilen terminin id-si
     *@param like ve ya dislike-dan asili olarag artirilmali olan sutun
     */
    function update_num_of_likes($term_id, $table_column) {
        include 'db.php';

        $table_name = "termin";

        $sql = "UPDATE $table_name SET $table_column=$table_column+1 WHERE termin_id=$term_id";

        $query = mysqli_query($db_connection, $sql);

        mysqli_close($db_connection);

    }

    function decrease_num_of_likes($term_id, $opposite_column) {
        include 'db.php';

        $table_name = "termin";

        $sql = "UPDATE $table_name SET $opposite_column=$opposite_column-1 WHERE termin_id=$term_id";

        $query = mysqli_query($db_connection, $sql);

        mysqli_close($db_connection);
    }

    function change_status_like($user_id, $term_id, $opposite_table) {
        include 'db.php';

        $sql = "DELETE FROM $opposite_table WHERE user_id=$user_id AND termin_id=$term_id";

        $query = mysqli_query($db_connection, $sql);

        mysqli_close($db_connection);
    }


    function signin($user_name,$user_password) {

  		include 'db.php';
  		echo "username: ".$user_name."<br>";
  		echo "password: ".$user_password."<br>";
  		$sql = "SELECT * FROM user WHERE username='$user_name'";
  		$query = mysqli_query($db_connection, $sql);

  		$numrow = mysqli_num_rows($query);

  		if ($numrow === 1) {

  			while ($row = mysqli_fetch_assoc($query)) {
  				$dbusername = $row['username'];
  				$dbpassword = $row['password'];
  				$dbregdate = $row['reg_date'];
  				$dbuserID = $row['id'];
  				$dbFirst = $row['firstname'];
  				$dbLast = $row['lastname'];
  				$dbMail = $row['email'];
  				$dbBirth = $row['birthdate'];
  				$dbGender = $row['gender'];
  			}

  			echo "username ".$user_name." dbusername ". $dbusername."<br>";
  			echo "password ".$user_password." dbpassword ". $dbpassword."<br>";
  			//check with the given data

  			if ($dbusername==$user_name && $dbpassword==$user_password) {
  				echo "You are now logged in<br>";
  				/*session-a yuklenen butun datalar profile sehifesinde
  				 * lazim olacag ki muvafig yerlerde gosterilsin
  				 */
  				if (!isset($_SESSION)) {
          			session_start();
     				}

  				$_SESSION['username'] = $user_name;
  				$_SESSION['user_id'] = $dbuserID;
  				$_SESSION['user_reg_date'] = $dbregdate;
  				$_SESSION['firstname'] = $dbFirst;
  				$_SESSION['lastname'] = $dbLast;
  				$_SESSION['email'] = $dbMail;
  				$_SESSION['pass'] = $dbpassword;
  				$_SESSION['birthdate'] = $dbBirth;
  				$_SESSION['gender'] = $dbGender;
  				//redirect user to new Welcome user page
  				header('Location: profile.php?id=1');
  			} else {
  				echo "incorrect password";
  			}
   		} else {
  			die("That user does not exist");
  		}
  	}
function userData(){
  include 'db.php';
  global $userIDedit;
  $userIDedit = $_SESSION['user_id'];
  $connection = mysqli_select_db($db_connection,$dbname);
  $sql = "SELECT * FROM user WHERE id='$userIDedit'";
  return $queryProfile = mysqli_query($db_connection, $sql);
}
function uploadPhoto(){
  include 'db.php';


}
 ?>
