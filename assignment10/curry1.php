<?php
include "top.php";
?>
<div id ="gallery_wrapper">
 <?php
  $query = 'Select pmkName, fldSize,fldBrand, fldImage,fldDescr1,fldDescr2, fldDescr3 from tblShoe
          where fldApproved  = 1 and pmkName = "Under Armour Curry 1"';
  
  #$thisDatabaseReader->testQuery($query, "", 0,0,0,0, false, false);
  $records = $thisDatabaseReader->select($query, "", 1,1,2,0, false, false);


    $fields = array_keys($records[0]);

$labels = array_filter($fields, "is_string");
$columns = count($labels);

if(is_array($records)) {
        foreach ($records as $r){
            print'<div class="view_item">';
            print'<section class = "left"';
            print'<h2 id = "title">'.$r['pmkName'].'</h2>';
            print '<img src="images/'.$r['fldImage']. '"alt = "'.$r['pmkName'].'"/>';
            print '</section><aside class = "description"';
            print '<ul>';
            print'<li> Size: '.$r['fldSize']. '</li>';
            print'<li> Brand: '.$r['fldBrand']. '</li>';
            print'<li>' .$r['fldDescr1']. '</li>';
            print'<li>' .$r['fldDescr2']. '</li>';
            print'<li>' .$r['fldDescr3']. '</li>';
            print '</ul></aside>';
            print '</div>';
        }
}
?>
</div>
<?php
include "footer.php";
?>







</body>
</html>