<?php
include "top.php";

$adminStatus = false;
$username = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
$query = 'SELECT pmkNetId FROM tblAdmin';
$adminArray = $thisDatabaseReader->select($query,"",0,0,0,0,false,false);

foreach ($adminArray as $admins) {
	for ($i = 0; $i < 1; $i++) {
		if ($username == $admins[$i]) {
			$adminStatus = true;
		}
	}
}
// if they are not admin, dont allow access to page
if(!adminStatus) {
    print " <h2> Sorry you do not have access to this page. Get out. </h2>";
}
else{
    print "<h2> Hello Admin. Below are options you have that regular users do not. Enjoy! </h2>";
    print " <ul id = 'admin_list'>";
    print " <li><a href = 'https://dduong.w3.uvm.edu/cs148develop/assignment10/add.php'> Add </a></li>";
    print " <li><a href = 'https://dduong.w3.uvm.edu/cs148develop/assignment10/delete.php'> Delete </a></li>";
    print " </ul>";
}

?>
<?php
include "footer.php";
?>







</body>
</html>