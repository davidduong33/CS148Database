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


$query = 'Select pmkName, fldBrand, fldSize, fldWing, fldColumn, fldRow from tblShoe, tblShoeLocation where fnkName = pmkName';
$total = $thisDatabaseReader->select($query, "", 1, 0, 0, 0, false, false);



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