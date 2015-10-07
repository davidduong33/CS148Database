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

$columns = 3;
$tableName = "tblSections";
$query = 'SELECT tblTeachers.fldFirstName, tblTeachers.fldLastName,  count(tblStudents.fldFirstName) as total FROM ' . $tableName .
        ' JOIN tblEnrolls on tblSections.fldCRN  = tblEnrolls.fnkSectionId
JOIN tblStudents on pmkStudentId = fnkStudentId
JOIN tblTeachers on tblSections.fnkTeacherNetId=pmkNetId
WHERE fldType != "LAB"
GROUP BY fnkTeacherNetId
ORDER BY total DESC';

$info2 = $thisDatabaseReader->select($query, "", 0, 1, 2, 0, false, false);
print "<h2> Total Records: 14 </h2>";
print '<h2> SQL:SELECT tblTeachers.fldFirstName, tblTeachers.fldLastName,  count(tblStudents.fldFirstName) as total
        FROM tblSections JOIN tblEnrolls on tblSections.fldCRN  = tblEnrolls.fnkSectionId
JOIN tblStudents on pmkStudentId = fnkStudentId
JOIN tblTeachers on tblSections.fnkTeacherNetId=pmkNetId
WHERE fldType != "LAB"
GROUP BY fnkTeacherNetId
ORDER BY total DESC';
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