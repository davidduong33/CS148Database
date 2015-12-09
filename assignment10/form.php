<?php
include 'top.php';
?>
<?php
// Initialize variables
//  Here we set the default values that we want our form to display

$debug = false;

if (isset($_GET["debug"])) { // this just helps me out if you have it
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";


// this would be the full url of your form
// See top.php for variable declartions
$yourURL = $domain . $phpSelf;

$firstName = "";
$lastName = "";
$email = "";
$quality = "Average";
$adminEmail = "dduong@uvm.edu";

//checkbox initializers

$functionality = false;
$code = false;
$interface = false;
$sability = false;

$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;




if (isset($_POST["btnSubmit"])) {

    if (!securityCheck()) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }

    
    $errorMsg = array();

    $dataRecord = array();

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");

    if (isset($_POST["chkFunctionality"])) {
        $functionality = true;
    } else {
        $functionality = false;
    }
    if (isset($_POST["chkCode"])) {
        $code = true;
    } else {
        $code = false;
    }
    if (isset($_POST["chkInterface"])) {
        $interface = true;
    } else {
        $interface = false;
    }
    if (isset($_POST["chkUsability"])) {
        $usability = true;
    } else {
        $usability = false;
    }

    $quality= htmlentities($_POST["radQuality"], ENT_QUOTES, "UTF-8");

    $dataRecord[] = $email;
    $dataRecord[] = $firstName;
    $dataRecord[] = $lastName;
    $dataRecord[] = $quality;

    // sent through html entities to be safe
    if (isset($_POST["chkFunctionality"])) {
        $dataRecord[] = htmlentities($_POST["chkFunctionality"], ENT_QUOTES, "UTF-8");
    } else {
        $dataRecord[] = null;
    }
    if (isset($_POST["chkCode"])) {
        $dataRecord[] = htmlentities($_POST["chkCode"], ENT_QUOTES, "UTF-8");
    } else {
        $dataRecord[] = null;
    }
    if (isset($_POST["chkInterface"])) {
        $dataRecord[] = htmlentities($_POST["chkInterface"], ENT_QUOTES, "UTF-8");
    } else {
        $dataRecord[] = null;
    }
    if (isset($_POST["chkUsability"])) {
        $dataRecord[] = htmlentities($_POST["chkUsability"], ENT_QUOTES, "UTF-8");
    } else {
        $dataRecord[] = null;
    }

  
    
    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address is in an invalid format.";
        $emailERROR = true;
    }
  
    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    }
    if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    }

    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";


        //  In this block I am just putting all the forms information
        //  into a variable so I can print it out on the screen
        //
        //  the substr function removes the 3 letter prefix
        //  preg_split('/(?=[A-Z])/',$str) add a space for the camel case
        //  see: http://stackoverflow.com/questions/4519739/split-camelcase-word-into-words-with-php-preg-match-regular-expression

        $message = '<h2>Help me Improve the Site.</h2>';

        foreach ($_POST as $key => $value) {
            if ($key != "btnSubmit") {
                $message .= "<p>";

                $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

                foreach ($camelCase as $one) {
                    $message .= $one . " ";
                }
                $message .= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
            }
        }

        // Save to the database
        print_r($dataRecord);
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();

            $query = 'INSERT INTO tblContact (pmkEmail,fldFirstName,fldLastName,fldQuality,fldImprove1,fldImprove2,fldImprove3) values (?,?,?,?,?,?,?) ';

            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($dataRecord);
                print"</pre></p>";
            }
            $results = $thisDatabase->insert($query, $dataRecord);
            // all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();

            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
        }
        // mail the forms information to the person who filled it out
        // if you want a copy you need to add yourself to the bcc
        // in mailMessage.php

        include_once('lib/mailMessage.php');
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
                            // SECTION: 2g Mail to user
        //
                            // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $adminEmail; // the person who filled out the form
        $cc = $email;
        $bcc = "";
        $from = "Shoe Inventory";
        $subject = "Do not reply to this email!";
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // ends form is valid
} // ends if form was submitted. We will be adding more information ABOVE this
?>
<article id ="main">

    <?php
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
// display the form, notice the closing } bracket at the end just before the 
// closing body tag
        ?>
        

        <form action="<?php print $phpSelf; ?>" 
              method="post"
              id="frmRegister">
            <fieldset class="wrapper">
                <legend>Help Me Improve the Site</legend>
            <fieldset class="basicInfo">
                <legend>Contact Information</legend>
                <label for="txtEmail" >Email
                    <input type="text" id="txtEmail" name="txtEmail" value="<?php echo $email; ?>"
                           tabindex="120" maxlength="45"
                           placeholder="Please enter a valid email"
                           onfocus="this.select()"  ></label>

                <label for="txtFirstName">First Name
                    <input type="text" id="txtFirstName" name="txtFirstName" 
                           value="<?php echo $firstName; ?>"
                           tabindex="122" maxlength="30"
                           placeholder="First Name"
                           onfocus="this.select()" ></label>

                <label for="txtLastName" > Last Name
                    <input type="text" id="txtLastName" name="txtLastName" 
                           value="<?php echo $lastName; ?>"
                           tabindex="124" maxlength="45"
                           placeholder="Last Name"
                           onfocus="this.select()" ></label>        
            </fieldset>

            <fieldset class = "radQuality">
                <legend>How Would You Rate the Overall Quality of the Site?</legend>
                <label class = "quality"><input type="radio" id="radPoor" name="radQuality" 
                    <?php if ($quality == "Poor") echo 'checked="checked"'; ?>
                              value="Poor"
                              tabindex="204">Poor</label>

                <label class = "quality"><input type="radio" id="radAverage" name="radQuality" 
                    <?php if ($quality == "Average") echo 'checked="checked"'; ?>
                              value="Average"
                              tabindex="206">Average</label>  

                <label class = "quality"><input type="radio" id="radGood" name="radQuality" 
                    <?php if ($quality == "Good") echo 'checked="checked"'; ?>
                              value="Good"
                              tabindex="208">Good</label>  
                <label class = "quality"><input type="radio" id="radGreat" name="radQuality" 
                    <?php if ($quality == "Excellent") echo 'checked="checked"'; ?>
                              value="Excellent"
                              tabindex="209">Excellent</label>  

            </fieldset>

            <fieldset class="checkbox">
                <legend>Tips to Improve :</legend>
                <label><input type="checkbox" id="chkFunctionality" name="chkFunctionality" value="Functionality"
                    <?php if ($functionality) echo 'checked="checked"'; ?>
                              tabindex="260"> Functionality of the Site </label>

                <label><input type="checkbox" id="chkCode" name="chkCode" value="Code"
                    <?php if ($code) echo 'checked="checked"'; ?>
                              tabindex="262" > Better Coding </label>

                <label><input type="checkbox" id="chkInterface" name="chkInterface" value="Interface"
                    <?php if ($interface) echo 'checked="checked"'; ?>
                              tabindex="264" > Interface of site</label> 

                <label><input type="checkbox" id="chkUsability" name="chkUsability" value="Usability"
                    <?php if ($usability) echo 'checked="checked"'; ?>
                              tabindex="266" > Usability of the site</label>  
            </fieldset>

           				
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" 
                       tabindex="991" class="button">

              
</fieldset> <!-- ends wrapper-->
        </form>

        <?php
    }
    if ($debug)
        print"<p>END OF PROCESSING</p>";
    print"</article>";
    ?>

    




<?php
include 'footer.php';
?>


</body>
</html>