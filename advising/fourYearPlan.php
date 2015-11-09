<?php
  include "top.php";
  $query = 'SELECT pmkPlanId, pmkYear, pmkTerm, fnkCourseId, fldDepartment, fldCourseNumber, fldCredits
FROM tbl4YrPlan 
INNER JOIN tblSemesterPlan on pmkPlanId = tblSemesterPlan.fnkPlanId
INNER JOIN tblSemesterPlanCourses on tblSemesterPlan.fnkPlanId = tblSemesterPlanCourses.fnkPlanId 
AND pmkYear = fnkYear 
AND pmkTerm = fnkTerm
INNER JOIN tblCourses on fnkCourseId = pmkCourseId
WHERE pmkPlanId = 1
ORDER BY pmkYear, tblSemesterPlan.fldDisplayOrder ASC, tblSemesterPlanCourses.fldDisplayOrder ASC';

  #$thisDatabaseReader->testQuery($query, "", 0,0,0,0, false, false);
  $records = $thisDatabaseReader->select($query, "", 0,0,0,0, false, false);


    $fields = array_keys($records[0]);

$labels = array_filter($fields, "is_string");
$columns = count($labels);


  $semester = "";
    $year = "";
    $semesterCredits = 0;
    $totalCredits = 0;
    
    
    if(is_array($records)) {
        foreach ($records as $row){
        if ($semester != $row["pmkTerm"] . $row["pmkYear"]){
            if($semester != "") {
                print "</ol>";
                print "<p>Total Credits: " . $semesterCredits;
                print "</section>";
                
            }
            
            if ($semester != "" AND ( $row["pmkTerm"] == "Fall")) {
                echo "</div>" . LINE_BREAK;
                
            }
            if ($row["pmkTerm"] == "Fall"){
                print "<div class='floatLeft'>";
                
            }
            print '<section class="fourColumns ';
            print $row["pmkTerm"];

            print '">';
            print '<h3>' . $row["pmkTerm"] . " " . $row["pmkYear"];
            $semester = $row["pmkTerm"] . $row["pmkYear"];
            $year = $row["pmkYear"];
            $semesterCredits = 0;
            
            
            print "<ol>";
        }
        
        
        print '<li>';
        print $row["fldDepartment"] . " " . $row["fldCourseNumber"];
        print '</li>' . LINE_BREAK;
        $semesterCredits = $semesterCredits + $row["fldCredits"];
        $totalCredits = $totalCredits + $row["fldCredits"];
        }
        print "</ol>";
        print "</section></div>" . LINE_BREAK;
    }
    

    

    
    
    
include "footer.php";
?>