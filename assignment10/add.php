<?php
/* the purpose of this page is to display a form to allow a poet and allow us
 * to add a new poet or update an existing poet 
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu
 
 */

include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
$update = false;

// SECTION: 1a.
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form

if (isset($_GET["id"])) {
    $pmkShoeId = (int) htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

    $query = 'Select pmkName, fldBrand, fldSize, fldWing, fldColumn, fldRow from tblShoe, '
            . 'tblShoeLocation where fnkName = pmkName and fldApproved = 1';

    $results = $thisDatabase->select($query, array($pmkShoeId), 1, 0, 0, 0, false, false);

    $name = $results[0]["pmkName"];
    $brand = $results[0]["fldBrand"];
    $size = $results[0]["fldSize"];
    $wing = $results[0]["fldWing"];
    $column = $results[0]["fldColumn"];
    $row = $results [0]["fldRow"];
} else {
    $name =" ";
    $brand = " ";
    $size = " ";
    $wing = " ";
    $column = " ";
    $row = " ";
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$nameERROR = false;
$brandERROR = false;
$sizeERROR = false;
$wingERROR = false;
$columnERROR = false;
$rowERROR = false;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$data = array();
$dataEntered = false;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
//
    /*    if (!securityCheck(true)) {
      $msg = "<p>Sorry you cannot access this page. ";
      $msg.= "Security breach detected and reported</p>";
      die($msg);
      }
     */
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.
    $pmkShoeId= (int) htmlentities($_POST["hidShoeId"], ENT_QUOTES, "UTF-8");
    if ($pmkShoeId > 0) {
        $update = true;
    }
    // I am not putting the ID in the $data array at this time

    $name = htmlentities($_POST["txtName"], ENT_QUOTES, "UTF-8");
    $data[] = $name;

    $brand = htmlentities($_POST["txtBrand"], ENT_QUOTES, "UTF-8");
    $data[] = $brand;

    $size = htmlentities($_POST["txtSize"], ENT_QUOTES, "UTF-8");
    $data[] = $size;
    
     
    
   

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//

    if ($name == "") {
        $errorMsg[] = "Please enter the name of your shoe";
        $nameERROR = true;
    } 

    if ($brand == "") {
        $errorMsg[] = "Please enter the brand";
        $brandERROR = true;
    } elseif (!verifyAlphaNum($brand)) {
        $errorMsg[] = "Your Brand seems to have extra characters.";
        $brandERROR = true;
    }

    if ($size == "") {
        $errorMsg[] = "Please enter the shoe size";
        $sizeERROR = true;
    }// should check to make sure its the correct date format
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//
    if (!$errorMsg) {
        if ($debug) {
            print "<p>Form is valid</p>";
        }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2e Save Data
//

        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();

            if ($update) {
                $query = 'UPDATE tblShoe SET ';
            } else {
                $query = 'INSERT INTO tblShoe SET ';
            }

            $query .= 'pmkName = ?, ';
            $query .= 'fldBrand = ?, ';
            $query .= 'fldSize = ? ';

            if ($update) {
                $query .= 'WHERE pmkShoeId = ?';
                $data[] = $pmkPoetId;

                if ($_SERVER["REMOTE_USER"] == 'dduong') {
                    $results = $thisDatabase->update($query, $data, 1, 0, 0, 0, false, false);
                }
            } else {
                if ($_SERVER["REMOTE_USER"] == 'dduong'){
                    $results = $thisDatabase->insert($query, $data);
                    $primaryKey = $thisDatabase->lastInsert();
                    if ($debug) {
                        print "<p>pmk= " . $primaryKey;
                    }
                }
            }

            // all sql statements are done so lets commit to our changes
            //if($_SERVER["REMOTE_USER"]=='rerickso'){
            $dataEntered = $thisDatabase->db->commit();
            // }else{
            //     $thisDatabase->db->rollback();
            // }
            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
        }
    } // end form is valid
} // ends if form was submitted.
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
//
//
//
//
// If its the first time coming to the form or there are errors we are going
// to display the form.
    if ($dataEntered) { // closing of if marked with: end body submit
        print "<h1>Record Saved</h1> ";
    } else {
//####################################
//
// SECTION 3b Error Messages
//
// display any error messages before we print out the form
        if ($errorMsg) {
            print '<div id="errors">';
            print '<h1>Your form has the following mistakes</h1>';

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
                <legend>Shoes</legend>

                <input type="hidden" id="hidPoetId" name="hidShoeId"
                       value="<?php print $pmkShoeId; ?>"
                       >

                <label for="txtName" class="required">Name
                    <input type="text" id="txtFirstName" name="txtName"
                           value="<?php print $name; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter the Shoe Name"
    <?php if ($nameERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           autofocus>
                </label>

                <label for="txtBrand" class="required">Brand
                    <input type="text" id="txtLastName" name="txtBrand"
                           value="<?php print $brand; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter your Brand"
    <?php if ($brandERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           >
                </label>

                <label for="txtSize" class="required">Size
                    <input type="text" id="txtBirthday" name="txtSize"
                           value="<?php print $size; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter your Size"
    <?php if ($sizeERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           >
                </label>                
            </fieldset> <!-- ends contact -->
            </fieldset> <!-- ends wrapper Two -->
            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Save" tabindex="900" class="button">
            </fieldset> <!-- ends buttons -->
            </fieldset> <!-- Ends Wrapper -->
        </form>
        <?php
    } // end body submit
    ?>
</article>

<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>