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

if (isset($_GET["pmkShoeId"])) {
    $pmkShoeId = (int) htmlentities($_GET["pmkShoeId"], ENT_QUOTES, "UTF-8");

    $query = 'Select pmkShoeId,pmkName, fldBrand,fnkName, fldSize, fldWing, fldColumn, fldRow from tblShoe, '
            . 'tblShoeLocation where fnkName = pmkName and fldApproved = 1';

    $results = $thisDatabase->select($query, array($pmkShoeId), 1, 0, 0, 0, false, false);
    $id = $results[0]["pmkShoeId"];
    $fname = $results[0]["fnkName"];
    $name = $results[0]["pmkName"];
    $brand = $results[0]["fldBrand"];
    $size = $results[0]["fldSize"];
    $wing = $results[0]["fldWing"];
    $column = $results[0]["fldColumn"];
    $row = $results [0]["fldRow"];
} else {
    $id = -1;
    $name =" ";
    $fname = " ";
    $size = " ";
    $brand = "Nike";
    $wing = "North";
    $column = "1";
    $row = "1";
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$nameERROR = false;
$fnameERROR = false;
$sizeERROR = false;
$brandERROR = false;
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
$data2= array();
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
     $fname = htmlentities($_POST["txtFname"], ENT_QUOTES, "UTF-8");
    $data2[] = $fname;
    $size = htmlentities($_POST["txtSize"], ENT_QUOTES, "UTF-8");
    $data[] = $size;
    $brand = htmlentities($_POST["radBrand"], ENT_QUOTES, "UTF-8");
    $data[] = $brand;
    $wing = htmlentities($_POST["lstWing"], ENT_QUOTES, "UTF-8");
    $data2[] = $wing;
    $column = htmlentities($_POST["lstColumn"], ENT_QUOTES, "UTF-8");
    $data2[] = $column;
     $row = htmlentities($_POST["lstRow"], ENT_QUOTES, "UTF-8");
    $data2[] = $row;
    
     
    
   

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//

    if ($name == "") {
        $errorMsg[] = "Please enter the name of your shoe";
        $nameERROR = true;
    } 
    
     if ($fname == "") {
        $errorMsg[] = "Please enter the name of your shoe";
        $fnameERROR = true;
    } 

    if ($size== "") {
        $errorMsg[] = "Please enter your size";
        $sizeERROR = true;
    } elseif (!verifyAlphaNum($brand)) {
        $errorMsg[] = "Your size seems to have extra characters.";
        $sizeERROR = true;
    }

    
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
            $thisDatabaseWriter->db->beginTransaction();
   
    
            
                $query = 'DELETE FROM tblShoe WHERE ';
                $query .= 'pmkName = ? ';
                $data[] = $pmkName;
                $results = $thisDatabaseWriter->delete($query, $data, 1, 0, 0, 0, false, false);
                
                $primaryKey = $thisDatabaseWriter->lastInsert();
                    if ($debug) {
                        print "<p>pmk= " . $primaryKey;
                    }
                $dataEntered = $thisDatabaseWriter->db->commit();
            //else{
               //  $thisDatabaseWriter->db->rollback();
            
            if ($debug)
                print "<p>transaction complete ";
            } 
            
            

            
         catch (PDOEcxeption $e) {
            $thisDatabaseWriter->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accepting your data please contact us directly.";
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
    
//####################################
//
// SECTION 3c html Form

        ?>
        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">
            <fieldset class="wrapper">
                <legend>Are you sure you want to delete this Shoe?</legend>
                
               
                 <input type="hidden" id="hidShoeId" name="hidShoeId"
                       value="<?php print $pmkShoeId; ?>"

                >
                 
                 
            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Yes" tabindex="900" class="button">
            </fieldset> <!-- ends buttons -->
            </fieldset> <!-- Ends Wrapper -->
        </form>
        <?php
     // end body submit
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