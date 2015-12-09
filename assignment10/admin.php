<?php
include "top.php";

$adminStatus = false;
$query = 'SELECT pmkNetId FROM tblAdmin';
$admins = $thisDatabaseReader->select($query,"",0,0,0,0,false,false);

foreach ($admins as $a) {
	for ($i = 0; $i < 1; $i++) {
		if ($username == $a[$i]) {
			$adminStatus = true;
		}
	}
}
// if they are not admin, dont allow access to page
if(!adminStatus) {
    print " <h2> Sorry you do not have access to this page. Get out. </h2>";
}
else{
    print " <div id = 'admin_list'>";
    print "<h2> Hello Admin. Below are options you have that regular users do not. Enjoy! </h2>";
    print " <ul id = 'admin_list'>";
    print " <li id = 'admin_list'><a href = 'add.php'> Add </a></li>";
    print " <li id = 'admin_list'><a href = 'delete.php'> Delete </a></li>";
    print " </ul>";
    print "</div>";
}

?>
<?php
include "footer.php";
?>







</body>
</html>