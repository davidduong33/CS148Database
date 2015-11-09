<?php
include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;
// 
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
$studentId = "";
$advisorId = "";
$email = "";
$major = "Computer Science";
$minor = "Computer Science";
$catalogYear = "2016";
$term = "Fall";
$year = "";
$dept = "";
$number = "";




//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$studentIdERROR = false;
$advisorIdERROR = false;
$emailERROR = false;
$yearERROR = false;
$deptERROR = false;
$numberERROR = false;


// SECTION: 1e misc variables
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
// array used to hold form values that will be written to a CSV file
$dataRecord = array();
$mailed = false; // have we mailed the information to the user?
// SECTION: 2 Process for when the form is submitted
if (isset($_POST["btnSubmit"])) {
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    // 

    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }

    // SECTION: 2b Sanitize (clean) data 
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.
    $studentId = htmlentities($_POST["txtStudentId"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $studentId;
    $advisorId = htmlentities($_POST["txtAdvisorId"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $advisorId;
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;
    $major = htmlentities($_POST["lstMajor"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $major;
    $minor = htmlentities($_POST["lstMinor"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $minor;
    $catalogYear = htmlentities($_POST["lstCatalogYear"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $catalogYear;
    $term = filter_var($_POST["txtTerm"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $term;
    $year = filter_var($_POST["txtYear"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $year;
    $dept = filter_var($_POST["txtDept"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $dept;
    $number = filter_var($_POST["txtNumber"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $number;
  

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2c Validation
    //
    // Validation section.
    if ($studentId == "") {
        $errorMsg[] = "Please enter your StudentID";
        $studentIdERROR = true;
    } elseif (!verifyAlphaNum($studentId)) {
        $errorMsg[] = "Your StudentID appears to have extra characters.";
        $studentIdERROR = true;
    }

    if ($advisorId == "") {
        $errorMsg[] = "Please enter your Advisor's ID";
        $advisorIdERROR = true;
    } elseif (!verifyAlphaNum($advisorId)) {
        $errorMsg[] = "Your Advisor's ID appears to have extra characters.";
        $advisorIdERROR = true;
    }

    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address is not in a valid form.";
        $emailERROR = true;
    }
    
     if ($term == "") {
        $errorMsg[] = "Please enter the term";
        $termERROR = true;
    } elseif (!verifyAlphaNum($term)) {
        $errorMsg[] = "Your Term appears to have extra characters.";
        $termERROR = true;
    }
    
     if ($year == "") {
        $errorMsg[] = "Please enter your year";
        $yearERROR = true;
    } elseif (!verifyAlphaNum($year)) {
        $errorMsg[] = "Your Year appears to have extra characters.";
        $yearERROR = true;
    }
    
     if ($dept == "") {
        $errorMsg[] = "Please enter the Department of the course";
        $deptERROR = true;
    } elseif (!verifyAlphaNum($dept)) {
        $errorMsg[] = "Your Department appears to have extra characters.";
        $deptERROR = true;
    }
    
     if ($number == "") {
        $errorMsg[] = "Please enter the number of your course";
        $numberERROR = true;
    } elseif (!verifyAlphaNum($number)) {
        $errorMsg[] = "Your Course Number must be numeric.";
        $numberERROR = true;
    }



  
// SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";
        //dddddddddddddddddddddd
        //
        // SECTION: 2e Save Data
        //insert into tblplan
        $sql = "INSERT INTO tbl4YrPlan(fldMajor, fldMinor, fldCatalogYear, fnkStudentId, fnkAdvisorId) VALUES (?, ?, ?, ?, ?)";
        $data = array($major, $minor, $startYear, $studentNetId, $advisorNetId);
        print "<p>SQL 1: " . $sql;
        $plan = $thisDatabaseWriter->insert($sql, $data, 0, 0, 0, 0, false, false);
        $planId = $thisDatabaseWriter->lastInsert();
        // insert into semester plan

        $sql = "INSERT INTO tblSemesterPlan(pmkYear, pmkTerm, fldDisplayOrder, fnkPlanId) VALUES ";
        $semesterPlanData = array();
        $term = 1;
        $display = 1;
        for ($i = $startYear; $i < $startYear + 4; $i++) {
            if ($term != 1)
                $sql .= ", ";

// fall
            $sql .= "(?, ?, ?, ?), ";
            $semesterPlanData[] = $i;
            if ($term % 2) {
                $semesterPlanData[] = "Fall";
            } else {
                $semesterPlanData[] = "Spring";
            }
            $semesterPlanData[] = $display;
            $semesterPlanData[] = $planId;
            $term++;
            $display++;
// spring
            $sql .= "(?, ?, ?, ?) ";
            $semesterPlanData[] = $i + 1;
            if ($term % 2) {
                $semesterPlanData[] = "Fall";
            } else {
                $semesterPlanData[] = "Spring";
            }
            $semesterPlanData[] = $display;
            $semesterPlanData[] = $planId;
            $term++;
            $display++;
        }

        print "<p>SQL 2: " . $sql;
        print "<p>Data<pre>";
        print_r($semesterPlanData);
        print "</pre></p>";

        $semesterPlan = $thisDatabaseWriter->insert($sql, $semesterPlanData, 0, 0, 0, 0, false, false);

// insert into semester plan course pulling from default courses
        // how doi set the year to be correct
        //get year from plan
        $defaultPlanYear = 2015;

        $difference = $defaultPlanYear - $startYear;

        if ($difference > 0) {
            $difference = "-" . $difference;
        } elseif ($difference < 0) {
            $difference = "+" . ($difference * -1);
        } else {
            $difference = "";
        }
        $sql = "INSERT INTO tblSemesterPlanCourses(fnkPlanId, fnkYear, fnkTerm, fnkCourseId, fldDisplayOrder, fldRequirement) ";
        $sql .= "SELECT " . $planId . " as fnkPlanId, (fnkYear" . $difference . ") as fnkYear, fnkTerm, fnkCourseId, fldDisplayOrder, fldRequirement FROM tblDefaultPlanCourses WHERE fnkDefaultPlanId=" . $defaultPlanId . " ORDER BY tblDefaultPlanCourses.fnkYear ASC, fnkTerm, fldDisplayOrder";
        print "<p>SQL 3: " . $sql;
        $planCourses = $thisDatabaseWriter->insert($sql, "", 1, 1, 0, 0, false, false);


        die("<p>dig it: " . $planId . "</p>");
    } // end valid form
} // end defaul submit


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).
        $message = '<h2>Your information:</h2>';
        foreach ($_POST as $key => $value) {
            if ($key != "btnSubmit") {
                $message .= "<p>";
                $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

                foreach ($camelCase as $one) {
                    $message .= $one . " ";
                }
            }
            if ($value != "Register") {
                $message .= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
            }
        }

        // SECTION: 2g Mail to user
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email; // the person who filled out the form
        $cc = "";
        $bcc = "";
        $from = "<noreply@davidduong.com>";
        // subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = " Thank you!: " . $todaysDate;
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
     // end form is valid
 // ends if form was submitted.
//#############################################################################
//
// SECTION 3 Display Form
//
?>

<article id="main">

<?php
//####################################
//
    // SECTION 3a.
// If its the first time coming to the form or there are errors we are going
// to display the form.
if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
    print "<h1>Your Request has ";
    if (!$mailed) {
        print "not ";
    }
    print "been processed</h1>";
    print "<p>A copy of this message has ";
    if (!$mailed) {
        print "not ";
    }
    print "been sent</p>";
    print "<p>To: " . $email . "</p>";
    print "<p>Mail Message:</p>";
    print $message;
} else {
    //#########################
    // SECTION 3b Error Messages
    // display any error messages before we print out the form
    if ($errorMsg) {
        print '<h3 id = "errorsHead"> Your Mistakes </h3>';
        print '<div id="errors">';
        print "<ol>\n";
        foreach ($errorMsg as $err) {
            print "<li>" . $err . "</li>\n";
        }
        print "</ol>\n";
        print '</div>';
    }

    //####################################
    //
        // SECTION 3c html Form
    //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
      is defined in top.php
      NOTE the line:
      value="<?php print $email; ?>
      this makes the form sticky by displaying either the initial default value (line 35)
      or the value they typed in (line 84)
      NOTE this line:
      <?php if($emailERROR) print 'class="mistake"'; ?>
      this prints out a css class so that we can highlight the background etc. to
      make it stand out that a mistake happened here.
     */
    ?>
        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">

            <fieldset class="wrapper">
                <legend>Four Year Plans</legend>
               

                <fieldset class="wrapperTwo">
                    <legend>Please Fill Out the Following Information</legend>

                    <fieldset class="basicInfo">
                        <legend><span class="number">1</span>
                            Your basic info</legend>

                        <label for="txtStudentId" class="required">Student Id
                            <input type="text" id="txtStudentId" name="txtStudentId"
                                   value="<?php print $studentId; ?>"
                                   tabindex="100" maxlength="45" placeholder="Enter your Student ID"
    <?php if ($studentIdERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label> 

                        <label for="txtAdvisorId" class="required">Advisor Id
                            <input type="text" id="txtAdvisorId" name="txtAdvisorId"
                                   value="<?php print $advisorId; ?>"
                                   tabindex="110" maxlength="45" placeholder="Enter your Advisor's ID"
    <?php if ($advisorIdERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" >

                        </label>  

                        <label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="120" maxlength="45" placeholder="Enter a valid email address"
    <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()">
                        </label>
                        
                         <label for="lstMajor" class="required">Major
                        <select id ="lstMajor"
                                name ="lstMajor"
                                tabindex="150" >
                            <option <?php if ($major == "Computer Science") print "selected";?>
                                value = "Computer Science">Computer Science</option>
                            <option <?php if ($major == "Undeclared") print "selected";?>
                                value = "Undeclared">Undeclared</option>
                            <option <?php if ($major == "Other") print "selected";?>
                                value = "Other">Other</option>
                        </select>
                    </label>
                    
                         <label for="lstMinor" class="required">Minor
                         <select id ="lstMinor"
                                name ="lstMinor"
                                tabindex="180" >
                            <option <?php if ($minor == "Computer Science") print "selected";?>
                                value = "Computer Science">Computer Science</option>
                            <option <?php if ($minor == "Undeclared") print "selected";?>
                                value = "Undeclared">Undeclared</option>
                            <option <?php if ($minor == "Other") print "selected";?>
                                value = "Other">Other</option>
                        </select>
                    </label>
                            
                        <label for="lstCatalogYear" class="required">Catalog Year
                            <select id="lstCatalogYear" 
                                name="lstCatalogYear" 
                                tabindex="250" >
                            <option <?php if ($catalogYear == "2019") print " selected "; ?>
                                value="2019">2019</option>

                            <option <?php if ($catalogYear == "2018") print " selected "; ?>
                                value="2018" >2018</option>

                            <option <?php if ($catalogYear == "2017") print " selected "; ?>
                                value="2017" >2017</option>

                            <option <?php if ($catalogYear == "2016") print " selected "; ?>
                                value="2016" >2016</option>
                            
                            <option <?php if ($catalogYear == "2015") print " selected "; ?>
                                value="2015" >2015</option>
                            
                            <option <?php if ($catalogYear == "2014") print " selected "; ?>
                                value="2014" >2014</option>
                            
                            <option <?php if ($catalogYear == "2013") print " selected "; ?>
                                value="2013" >2013</option>
                        </select>
                        </label>
                        


                    </fieldset> <!-- ends basic info -->

                   

                    
                        
                      
                    <fieldset class="courseInfo">					
                        <legend><span class="number">2</span>Course Information</legend>
                         <label for="lstTerm" class="required">Term
                             <select id ="lstTerm"
                                name ="lstTerm"
                                tabindex="270" >
                            <option <?php if ($term == "Fall") print "selected";?>
                                value = "Fall">Fall</option>
                            <option <?php if ($term == "Spring") print "selected";?>
                                value = "Spring">Spring</option>
                            <option <?php if ($term == "Summer") print "selected";?>
                                value = "Summer">Other</option>
                        </select>
                         </label>
                        
                        <label for="txtYear" class="required">Year
                            <input type="text" id="txtYear" name="txtYear"
                                   value="<?php print $year; ?>"
                                   tabindex="300" maxlength="45" placeholder="Enter the Year"
    <?php if ($yearERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
                            <label for="txtDept" class="required">Department
                            <input type="text" id="txtDepartment" name="txtDepartment"
                                   value="<?php print $dept; ?>"
                                   tabindex="320" maxlength="55" placeholder="Enter Department of Course"
    <?php if ($deptERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                            </label>
                            
                            <label for="txtNumber" class="required">Course Number
                            <input type="text" id="txtNumber" name="txtNumber"
                                   value="<?php print $number; ?>"
                                   tabindex="330" maxlength="45" placeholder="Enter the Course Number"
    <?php if ($numberERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                            </label>
                    </fieldset> <!-- end of Course Info -->

                </fieldset> <!-- ends wrapper Two -->

                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->

            </fieldset> <!-- Ends Wrapper -->
        </form>

    <?php
}
?>

</article>

    <?php include "footer.php"; ?>

</body>
</html>`