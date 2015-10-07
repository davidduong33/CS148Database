SELECT DISTINCT fldDays, fldStart, fldStop FROM tblSections JOIN tblTeachers ON tblSections.fnkTeacherNetId = tblTeachers.pmkNetID 
    WHERE tblTeachers.fldLastName = "Horton" AND tblTeachers.fldFirstName = "Jackie Lynn" 
    ORDER BY tblSections.fldStart