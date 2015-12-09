<?php
include "top.php";
?>
<div id ="gallery_wrapper">
 <?php
  $query = 'Select pmkName, fldSize, fldView, fldImage,fldDescr1,fldDescr2, fldDescr3 from tblShoe
          where fldApproved  = 1 Order by fldBrand';
  
  #$thisDatabaseReader->testQuery($query, "", 0,0,0,0, false, false);
  $records = $thisDatabaseReader->select($query, "", 1,1,0,0, false, false);


    $fields = array_keys($records[0]);

$labels = array_filter($fields, "is_string");
$columns = count($labels);

if(is_array($records)) {
        foreach ($records as $r){
            print'<div class="gallery_item">';
            print'<h3><a href="'.$r['fldView'].'">' .$r['pmkName'].'</a></h3>';
            print '<img class = "img_small" src="images/'.$r['fldImage']. '"alt = "'.$r['pmkName'].'"/>';
            print '<ul>';
            print'<li>' .$r['fldDescr1']. '</li>';
            print'<li>' .$r['fldDescr2']. '</li>';
            print'<li>' .$r['fldDescr3']. '</li>';
            print '</ul>';
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