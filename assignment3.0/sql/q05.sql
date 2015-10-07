SELECT tblTeachers.fldFirstName, tblTeachers.fldLastName,  count(tblStudents.fldFirstName) as total
    FROM tblSections JOIN tblEnrolls on tblSections.fldCRN  = tblEnrolls.fnkSectionId
    JOIN tblStudents on pmkStudentId = fnkStudentId
    JOIN tblTeachers on tblSections.fnkTeacherNetId=pmkNetId
    WHERE fldType != "LAB"
    GROUP BY fnkTeacherNetId
    ORDER BY total DESC