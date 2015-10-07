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

$columns = 8;
$tableName = "tblStudents";
$query = 'SELECT pmkStudentId, fldFirstName, fldLastName,fldStreetAddress, fldCity, fldState, fldZip, fldGender '
        . 'FROM ' . $tableName . " ORDER BY fldLastName, fldFirstName LIMIT 10 OFFSET 999";
$info2 = $thisDatabaseReader->select($query, "", 0, 1, 0, 0, false, false);
print "<h2> Total Records: 10 </h2>";

$fields = array_keys($info2[0]);
print("<p><pre>");
print_r($fields);
$labels = array_filter($fields, "is_string");
print("<p><pre>");
print_r($labels);

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
foreach ($info2 as $rec) {
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