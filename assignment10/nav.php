<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ul>
        <?php
         
$query = 'SELECT pmkNetId FROM tblAdmin';
$admins = $thisDatabaseReader->select($query,"",0,0,0,0,false,false);
        // This sets the current page to not be a link. Repeat this if block for
        //  each menu item 
        if ($path_parts['filename'] == "index") {
            print '<li class="activePage">Home</li>';
        } else {
            print '<li><a href="index.php">Home</a></li>';
        }
        if ($path_parts['filename'] == "view") {
            print '<li class="activePage">View Shoes</li>';
        } else {
            print '<li><a href="view.php">View Shoes</a></li>';
        }
        if ($path_parts['filename'] == "table") {
            print '<li class="activePage">table</li>';
        } else {
            print '<li><a href="table.php">table</a></li>';
        }
        if ($path_parts['filename'] == "form") {
            print '<li class="activePage">Form</li>';
        } else {
            print '<li><a href="form.php">form</a></li>';
        }
        
         foreach ($admins as $a) {
        // foreach ($adminIds as $adminId) {
        //   print $adminId;
        // }
        for ($i = 0; $i < 1; $i++) {
          // print $adminIds[$i];
          if ($username == $a[$i]) {
            // print 1;
            if ($path_parts['filename'] == "admin") {
                print '<li class="activePage">Admin</li>';
            } else {
                print '<li><a href="admin.php">Admin</a></li>';
            } 
          }
        }
      }
        
        
        ?>
    </ul>
    <div class="clear"></div>
</nav>
  </div> <!-- end logo -->
</div>  <!-- end marg -->
<!-- #################### Ends Main Navigation    ########################## -->

