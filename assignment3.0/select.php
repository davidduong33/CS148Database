<?php
include "top.php";
?>

<p><a href="q01.php">q01:</a> SELECT DISTINCT fldCourseName FROM tblCourses, tblEnrolls
    WHERE tblCourses.pmkCourseId = tblEnrolls.fnkCourseId AND tblEnrolls.fldGrade = 100 
    ORDER BY tblCourses.fldCourseName</p>
<p><a href="q02.php">q02:</a> SELECT DISTINCT fldDays, fldStart, fldStop FROM tblSections JOIN tblTeachers ON tblSections.fnkTeacherNetId = tblTeachers.pmkNetID 
    WHERE tblTeachers.fldLastName = "Snapp" AND tblTeachers.fldFirstName = "Robert Raymond" 
    ORDER BY tblSections.fldStart</p>
<p><a href =q03.php>q03:</a> SELECT DISTINCT fldDays, fldStart, fldStop FROM tblSections JOIN tblTeachers ON tblSections.fnkTeacherNetId = tblTeachers.pmkNetID 
    WHERE tblTeachers.fldLastName = "Horton" AND tblTeachers.fldFirstName = "Jackie Lynn" 
    ORDER BY tblSections.fldStart</p>
<p><a href="q04.php">q04:</a> SELECT fnkSectionId, fldFirstName, fldLastName FROM tblEnrolls 
    JOIN tblStudents on tblEnrolls.fnkStudentId = tblStudents.pmkStudentId 
    WHERE tblEnrolls.fnkCourseId = 392 ORDER BY tblEnrolls.fnkSectionId</p>
<p><a href="q05.php">q05:</a> SELECT tblTeachers.fldFirstName, tblTeachers.fldLastName,  count(tblStudents.fldFirstName) as total
    FROM tblSections JOIN tblEnrolls on tblSections.fldCRN  = tblEnrolls.fnkSectionId
    JOIN tblStudents on pmkStudentId = fnkStudentId
    JOIN tblTeachers on tblSections.fnkTeacherNetId=pmkNetId
    WHERE fldType != "LAB"
    GROUP BY fnkTeacherNetId
    ORDER BY total DESC </p>
<p><a href="q06.php">q06:</a> Select fldCourseName from tblCourses where NOT (fldDepartment = 'CS') and fldCourseName like '%data%'</p>
<p><a href="q07.php">q07:</a> Select count(Distinct fldDepartment) from tblCourses </p>
<p><a href="q08.php">q08:</a> Select distinct fldBuilding, Count(fldSection) as Number_Sections from tblSections group by fldBuilding </p>
<p><a href="q09.php">q09:</a> Select distinct fldBuilding, COUNT(fldNumStudents) FROM tblSections WHERE fldDays LIKE "%W%" GROUP BY fldBuilding ORDER BY count(fldNumStudents) DESC </p>
<p><a href="q10.php">q10:</a> Select distinct fldBuilding, COUNT(fldNumStudents) FROM tblSections WHERE fldDays LIKE "%F%" GROUP BY fldBuilding ORDER BY count(fldNumStudents) DESC </p>




<?php
include "footer.php";
?>







</body>
</html>