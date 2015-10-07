SELECT fldFirstName, fldLastName, COUNT(fnkSectionId) AS fldNumberOfClasses, SUM(fldGrade) / COUNT(fnkSectionId) AS fldGPA 
    FROM tblStudents JOIN tblEnrolls ON pmkStudentId = fnkStudentId 
    WHERE fldState = "VT" GROUP BY fldFirstName, fldLastName HAVING fldGPA > (SELECT SUM(fldGrade) / COUNT(fnkSectionId) 
    FROM tblEnrolls JOIN tblStudents ON fnkStudentId = pmkStudentId WHERE fldState = "VT") 
    ORDER BY fldGPA DESC, fldLastName, fldFirstName