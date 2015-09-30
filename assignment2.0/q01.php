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

    $columns = 1;
    $tableName = "tblTeachers";
    $query = 'SELECT pmkNetId FROM ' . $tableName;
    $info2 = $thisDatabaseReader->select($query, "", 0, 0, 0, 0, false, false);
    print "<h2> Total Records: 1010 </h2>";
    print "<h2> SQL: Select pmkNetId from tblTeachers </h2>";
print "<table>";
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