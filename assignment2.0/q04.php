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
    $tableName = "tblCourses";
    $query = 'Select * from ' . $tableName .' where pmkCourseId = "392" ' ;
    $info2 = $thisDatabaseReader->select($query, "", 1, 0, 2, 0, false, false);
    print "<h2> Total Records: 1 </h2>";
    print "<h2> SQL: Select * from tblCourses where pmkCourseId = '392'</h2>";
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