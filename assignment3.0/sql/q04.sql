SELECT fnkSectionId, fldFirstName, fldLastName FROM tblEnrolls 
    JOIN tblStudents on tblEnrolls.fnkStudentId = tblStudents.pmkStudentId 
    WHERE tblEnrolls.fnkCourseId = 392 ORDER BY tblEnrolls.fnkSectionId