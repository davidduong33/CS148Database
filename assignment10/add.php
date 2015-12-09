<?php
/* the purpose of this page is to display a form to allow a poet and allow us
 * to add a new poet or update an existing poet 
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu
 
 */

include "top.php";
$adminStatus = false;
$query = 'SELECT pmkNetId FROM tblAdmin';
$admins = $thisDatabaseReader->select($query,"",0,0,0,0,false,false);

foreach ($admins as $a) {
	for ($i = 0; $i < 1; $i++) {
		if ($username == $a[$i]) {
			$adminStatus = true;
		}
	}
}
// if they are not admin, dont allow access to page
if(!$adminStatus) {
    print " <h2> Sorry you do not have access to this page. Get out. </h2>";
}
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
            $thisDatabase->db->beginTransaction();

            if ($update) {
                $query = 'UPDATE tblShoe SET ';
                $query2 = 'UPDATE tblShoeLocation SET ';
            } else {
                $query = 'INSERT INTO tblShoe SET ';
                $query2 = 'INSERT INTO tblShoeLocation SET ';
            }
            $query .= 'pmkShoeId = ?, ';
            $query .= 'pmkName = ?, ';
            $query .= 'fldBrand = ?, ';
            $query .= 'fldSize = ? ';
            
            $query2 .= 'fldWing = ?, ';
            $query2 .= 'fldColumn = ?, ';
            $query2 .= 'fldRow = ? ';

            if ($update) {
                $query .= 'WHERE pmkShoeId = ?';
                $query2.= 'WHERE fnkName = ?';
                $data[] = $pmkShoeId;
                $data2[] = $fnkName;
                

                $results = $thisDatabaseWriter->update($query, $data, 1, 0, 0, 0, false, false);
                 $results2 = $thisDatabaseWriter->update($query2, $data2, 1, 0, 0, 0, false, false);
            } else {
                
                    $results = $thisDatabase->insert($query, $data);
                    $primaryKey = $thisDatabase->lastInsert();
                    if ($debug) {
                        print "<p>pmk= " . $primaryKey;
                    }
                    $results2 = $thisDatabaseWriter->insert($query2, $data2);
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
                <legend>Add Shoe</legend>
                 <fieldset class="basicInfo">
                <legend><span class="number">1</span>Shoes</legend>

                <input type="hidden" id="hidPoetId" name="hidShoeId"
                       value="<?php print $pmkShoeId; ?>"
                       >

                <label for="txtName" class="required">Name
                    <input type="text" id="txtName" name="txtName"
                           value="<?php print $name; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter the Shoe Name"
    <?php if ($nameERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           autofocus>
                </label>

                <label for="txtSize" class="required">Size
                    <input type="text" id="txtSize" name="txtSize"
                           value="<?php print $size; ?>"
                           tabindex="100" maxlength="45" placeholder="Enter your Size"
    <?php if ($sizeERROR) print 'class="mistake"'; ?>
                           onfocus="this.select()"
                           >
                </label>

         
                </label>     
                 </fieldset> <!--ends info -->
            
             <fieldset class="radBrand">
                        <legend><span class="number">2</span>
                            Brand</legend>
                        <label class="brand"><input type="radio" 
                                                     id="radBrandNike" 
                                                     name="radBrand" 
                                                     value="Nike"
    <?php if ($brand == "Nike") print 'checked' ?>
                                                     tabindex="130">Nike</label>
                        <label class = "brand"><input type="radio" 
                                                       id="radBrandAdidas" 
                                                       name="radBrand" 
                                                       value="Adidas"
    <?php if ($brand == "Adidas") print 'checked' ?>
                                                       tabindex="140">Adidas</label>
                        <label class = "brand"><input type="radio" 
                                                       id="radBrandUnder" 
                                                       name="radBrand" 
                                                       value="Under Armour"
    <?php if ($brand == "Under Armour") print 'checked' ?>
                                                       tabindex="150">Under Armour</label>
                 <label class = "brand"><input type="radio" 
                                                       id="radBrandJordan" 
                                                       name="radBrand" 
                                                       value="Jordan"
    <?php if ($brand == "Jordan") print 'checked' ?>
                                                       tabindex="160">Jordan</label>
                 <label class = "brand"><input type="radio" 
                                                       id="radBrandOther" 
                                                       name="radBrand" 
                                                       value="Other"
    <?php if ($brand == "Other") print 'checked' ?>
                                                       tabindex="170">Other</label>

                    </fieldset><!-- ends radBrand-->
                    <fieldset  class="listbox">	
                        <legend><span class="number">3</span>Location</legend>
                        <label for="lstWing">Wing
                        <select id="lstWing" 
                                name="lstWing" 
                                tabindex="180" >
                            <option <?php if ($wing == "North") print " selected "; ?>
                                value="North">North</option>

                            <option <?php if ($wing == "East") print " selected "; ?>
                                value="East" >East</option>

                            <option <?php if ($wing == "West") print " selected "; ?>
                                value="West" >West</option>

                            <option <?php if ($wing == "South") print " selected "; ?>
                                value="South" >South</option>
                         
                        </select>
                        </label>
                        
                        <label for="lstColumn">Column
                        <select id="lstColumn" 
                                name="lstColumn" 
                                tabindex="190" >
                            <option <?php if ($column == "1") print " selected "; ?>
                                value="1">1</option>

                            <option <?php if ($column == "2") print " selected "; ?>
                                value="2" >2</option>

                            <option <?php if ($column == "3") print " selected "; ?>
                                value="3" >3</option>

                            <option <?php if ($column == "4") print " selected "; ?>
                                value="4" >4</option>
                         
                        </select>
                        </label>
                        
                        <label for="lstRow"> Row
                        <select id="lstRow" 
                                name="lstRow" 
                                tabindex="190" >
                            <option <?php if ($row == "1") print " selected "; ?>
                                value="1">1</option>

                            <option <?php if ($row == "2") print " selected "; ?>
                                value="2" >2</option>

                            <option <?php if ($row == "3") print " selected "; ?>
                                value="3" >3</option>

                            <option <?php if ($row == "4") print " selected "; ?>
                                value="4" >4</option>
                         
                        </select>
                        </label>
                        
                        
                    </fieldset> <!-- End of List --->
            
            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Save" tabindex="900" class="button">
            </fieldset> <!-- ends buttons -->
            
          </fieldset> <!-- ends wrapper-->
            
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