SELECT fldFirstName, fldLastName, COUNT(fnkStudentId) AS fldTotal, fldSalary, fldSalary / COUNT(fnkStudentId) AS fldIBB
    FROM tblTeachers JOIN tblSections ON fnkTeacherNetId = pmkNetId 
        JOIN tblEnrolls ON tblSections.fnkCourseId = tblEnrolls.fnkCourseId AND fldCRN = fnkSectionId WHERE fldType <> "LAB" 
        GROUP BY fldFirstName, fldLastName ORDER BY fldIBB 