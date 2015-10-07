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

$columns = 4;
$tableName = "tblStudents";
$query = 'SELECT fldFirstName, fldLastName, COUNT(fnkSectionId) AS fldNumberOfClasses, SUM(fldGrade) / COUNT(fnkSectionId) AS fldGPA 
    FROM ' . $tableName . ' JOIN tblEnrolls ON pmkStudentId = fnkStudentId 
WHERE fldState = "VT" GROUP BY fldFirstName, fldLastName HAVING fldGPA > (SELECT SUM(fldGrade) / COUNT(fnkSectionId) 
    FROM tblEnrolls JOIN tblStudents ON fnkStudentId = pmkStudentId WHERE fldState = "VT") 
    ORDER BY fldGPA DESC, fldLastName, fldFirstName';
$info2 = $thisDatabaseReader->select($query, "", 1, 1, 4, 1, false, false);
print "<h2> Total Records: 12 </h2>";
print '<h2> SQL: SELECT fldFirstName, fldLastName, COUNT(fnkSectionId) AS fldNumberOfClasses, SUM(fldGrade) / COUNT(fnkSectionId) AS fldGPA'
. 'FROM tblStudents JOIN tblEnrolls ON pmkStudentId = fnkStudentId WHERE fldState = "VT" GROUP BY fldFirstName, fldLastName HAVING fldGPA > (SELECT SUM(fldGrade) / COUNT(fnkSectionId) '
        . 'FROM tblEnrolls JOIN tblStudents ON fnkStudentId = pmkStudentId WHERE fldState = "VT") '
. 'ORDER BY fldGPA DESC, fldLastName, fldFirstName';
$fields = array_keys($info2[0]);
$labels = array_filter($fields, "is_string");

print "<table>";

print "<tr>";
foreach ($labels as $label) {
    $camelCase = preg_split('/(?=[A-Z])/', substr($label, 3));
    $message = "";
    foreach ($camelCase as $one) {
        $message .= $one . " ";
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