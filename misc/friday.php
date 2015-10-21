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
//now print out each record
$numRecords = 10;
$start = (int) $_GET["start"];

if (isset($_GET['start'])) {
    $start= (int) $_GET['start'];
} 
else {
    $start = 0;
}



$query = 'SELECT pmkStudentId, fldFirstName, fldLastName,fldStreetAddress, fldCity, fldState, fldZip, fldGender
    FROM tblStudents ORDER BY fldLastName, fldFirstName LIMIT ' . $numRecords . ' OFFSET ' .$start;
$total = $thisDatabaseReader->select($query, "", 0, 1, 0, 0, false, false);

print '<h2>SQL: ' . $query . '</h2>';
print '<h3>Displaying records ';
print ($start + 1) . ' - ' . ($start + $numRecords);
print ' of 5000 </h3>';
print '<ol class = "button">';
print '<li';
if ($start - $numRecords < 0) {
    print ' class="invalid"';
}
print '><a href="?start=' . ($start - $numRecords) . '">';
print 'Previous 10 Records</a></li>';
print '<li';
if ($start + $numRecords >= 5000) {
    print ' class="invalid"';
}
print '><a href="?start=' . ($start + $numRecords) . '">';
print 'Next 10 Records</a></li>';
print '</ol>';


$fields = array_keys($total[0]);

$labels = array_filter($fields, "is_string");
$columns = count($labels);



print "<table>";

print "<tr>";
foreach ($labels as $label) {
    $camelCase = preg_split('/(?=[A-Z])/', substr($label, 3));
    $message = "";
    foreach ($camelCase as $one) {
        $message .= $one ." ";
  
    }
          print "<th>";
        print $message;
        print "</th>";
}
print"</tr>";
$highlight = 0; // used to highlight alternate rows
foreach ($total as $rec) {
    $highlight++;
    if ($highlight % 2 != 0) {
        $style = ' odd ';
    } else {
        $style = ' even ';
    }
    print '<tr class="' . $style . '">';
    for ($i = 0; $i < $columns; $i++) {
        print '<td>' . $rec[$i] . '</td>';
    }
    print '</tr>';
}

// all done
print '</table>';


include "footer.php";
?>