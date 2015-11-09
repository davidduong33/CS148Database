<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        // This sets the current page to not be a link. Repeat this if block for
        //  each menu item 
        if ($path_parts['filename'] == "index") {
            print '<li class="activePage">Home</li>';
        } else {
            print '<li><a href="index.php">Home</a></li>';
        }
        
        if ($path_parts['filename'] == "tables") {
            print '<li class="activePage">Display Tables</li>';
        } else {
            print '<li><a href="tables.php">Display Tables</a></li>';
        }
         if ($path_parts['filename'] == "fourYearPlan") {
            print '<li class="activePage">Four Year Plan</li>';
        } else {
            print '<li><a href="fourYearPlan.php">Four Year Plan</a></li>';
        }
          if ($path_parts['filename'] == "form") {
            print '<li class="activePage">Create Plan</li>';
        } else {
            print '<li><a href="form.php">Create Plan</a></li>';
        }
        
    
        ?>
    </ol>
</nav>
<!-- #################### Ends Main Navigation    ########################## -->

