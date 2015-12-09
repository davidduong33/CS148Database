<?php

//##############################################################################
//
// This page lists your tables and fields within your database. if you click on
// a database name it will show you all the records for that table. 
// 
// 
// This file is only for class purposes and should never be publicly live
//##############################################################################
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
if(!$adminStatus) {
    print " <h2> Sorry you do not have access to this page. Get out. </h2>";
}
$field='pmkName';
$sort='ASC';
if(isset($_GET['sorting']))
{
  if($_GET['sorting']=='ASC')
  {
  $sort='DESC';
  }
  else
  {
    $sort='ASC';
  }
}
if($_GET['field']=='pmkName')
{
   $field = "pmkName"; 
}
elseif($_GET['field']=='fldBrand')
{
   $field = "fldBrand";
}
elseif($_GET['field']=='fldSize')
{
   $field="fldSize";
}
elseif($_GET['field']=='fldWing')
{
   $field="fldWing";
}
elseif($_GET['field']=='fldColumn')
{
   $field="fldColumn";
}
elseif($_GET['field']=='fldRow')
{
   $field="fldRow";
}


$query = 'Select pmkName,pmkShoeId, fldBrand, fldSize, fldWing, fldColumn, fldRow, fldView from tblShoe, tblShoeLocation where pmkName = fnkName Order By '. $field .' '.$sort;
$total = $thisDatabaseReader->select($query, "", 1, 1, 0, 0, false, false);



$fields = array_keys($total[0]);

$labels = array_filter($fields, "is_string");
$columns = count($labels);



// print out the results
print "<table id = 'shoelist'>\n";
print "<tr class = 'headers'>\n";

// -----------------------------------------------------------------------------
// set links up to do onclick for ajax, but still work if onclick does not by
// haveing the same href. I make it easy on myself by passing in the field names
print '<th><a href="table.php?sorting='.$sort.'&field=pmkName">Name</a></th>';
print '<th><a href="table.php?sorting='.$sort.'&field=fldBrand">Brand</a></th>';
print '<th><a href="table.php?sorting='.$sort.'&field=fldSize">Size</a></th>';
print '<th><a href="table.php?sorting='.$sort.'&field=fldWing">Wing</a></th>';
print '<th><a href="table.php?sorting='.$sort.'&field=fldColumn">Column</a></th>';
print '<th><a href="table.php?sorting='.$sort.'&field=fldRow">Row</a></th>';




print "</tr>\n";

// print out the records
foreach ($total as $shoe) {
    print "<tr class = 'data'>\n";
    print '<td> <a href="'.$shoe['fldView'].'">' .$shoe['pmkName'].'</a>';
    print '<a id = "edit" href= "delete2.php?id=' .$shoe['pmkShoeId']. '">[Delete]</a></td>\n';
    print "<td>" . $shoe['fldBrand'] . "</td>\n";
    print "<td>" . $shoe['fldSize'] . "</td>\n";
    print "<td>" . $shoe['fldWing'] . "</td>\n";
    print "<td>" . $shoe['fldColumn'] . "</td>\n";
    print "<td>" . $shoe['fldRow'] . "</td>\n";
    print "</tr>\n";
}
print "</table>\n";


include "footer.php";
?>