SELECT DISTINCT fldCourseName FROM tblCourses, tblEnrolls
    WHERE tblCourses.pmkCourseId = tblEnrolls.fnkCourseId AND tblEnrolls.fldGrade = 100 
    ORDER BY tblCourses.fldCourseName tblTeachers