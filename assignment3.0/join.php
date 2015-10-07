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
<p><a href="q06.php">q06:</a> SELECT fldFirstName, fldPhone, fldSalary FROM tblTeachers 
    WHERE fldSalary < (SELECT AVG(fldSalary) FROM tblTeachers) 
    ORDER BY fldSalary DESC</p>
<p><a href="q07.php">q07:</a> SELECT fldFirstName, fldLastName, COUNT(fnkSectionId) AS fldNumberOfClasses, SUM(fldGrade) / COUNT(fnkSectionId) AS fldGPA 
    FROM tblStudents JOIN tblEnrolls ON pmkStudentId = fnkStudentId 
    WHERE fldState = "VT" GROUP BY fldFirstName, fldLastName HAVING fldGPA > (SELECT SUM(fldGrade) / COUNT(fnkSectionId) 
    FROM tblEnrolls JOIN tblStudents ON fnkStudentId = pmkStudentId WHERE fldState = "VT") 
    ORDER BY fldGPA DESC, fldLastName, fldFirstName </p>
<p><a href="q08.php">q08:</a> SELECT fldFirstName, fldLastName, COUNT(fnkStudentId) AS fldTotal, fldSalary, fldSalary / COUNT(fnkStudentId) AS fldIBB
    FROM tblTeachers JOIN tblSections ON fnkTeacherNetId = pmkNetId 
        JOIN tblEnrolls ON tblSections.fnkCourseId = tblEnrolls.fnkCourseId AND fldCRN = fnkSectionId WHERE fldType <> "LAB" 
        GROUP BY fldFirstName, fldLastName ORDER BY fldIBB </p>




<?php
include "footer.php";
?>







</body>
</html>