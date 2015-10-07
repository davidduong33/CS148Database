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

$columns = 5;
$tableName = "tblTeachers";
$query = 'SELECT fldFirstName, fldLastName, COUNT(fnkStudentId) AS fldTotal, fldSalary, fldSalary / COUNT(fnkStudentId) AS fldIBB
    FROM ' . $tableName . ' JOIN tblSections ON fnkTeacherNetId = pmkNetId 
        JOIN tblEnrolls ON tblSections.fnkCourseId = tblEnrolls.fnkCourseId AND fldCRN = fnkSectionId WHERE fldType <> "LAB" 
        GROUP BY fldFirstName, fldLastName ORDER BY fldIBB';
$info2 = $thisDatabaseReader->select($query, "", 1, 2, 2, 2, false, false);
print "<h2> Total Records: 14 </h2>";
print '<h2> SQL: SELECT fldFirstName, fldLastName, COUNT(fnkStudentId) AS fldTotal, fldSalary, fldSalary / COUNT(fnkStudentId) AS fldIBB '
. 'FROM tblTeachers JOIN tblSections ON fnkTeacherNetId = pmkNetId '
        . 'JOIN tblEnrolls ON tblSections.fnkCourseId = tblEnrolls.fnkCourseId AND fldCRN = fnkSectionId WHERE fldType <> "LAB" '
        . 'GROUP BY fldFirstName, fldLastName ORDER BY fldIBB';
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