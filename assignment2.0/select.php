<?php
include "top.php";
?>

<p><a href="q01.php">q01:</a> Select pmkNetId from tblTeachers </p>
<p><a href="q02.php">q02:</a> Select fldDepartment from tblCourses where fldCourseName like 'Introduction%</p>
<p><a href =q03.php>q03:</a> Select * from tblSections where fldStart = '13:10' and fldBuilding = 'KALKIN'</p>
<p><a href="q04.php">q04:</a> Select * from tblCourses where pmkCourseId = '392' </p>
<p><a href="q05.php">q05:</a> Select fldFirstName, fldLastName from tblTeachers where pmkNetId like 'r%%o' </p>
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