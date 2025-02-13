<?
//require the routing page
require_once('Pages/routes.php');


?>

<html>
    <Body class = "container" >
    <?

        //display the navigation bar
        echo $nav;

        //display any messages provided by the page
        echo $result[0];
        
        //display the page content itselfgszdgzsdgZd
        echo $result[1];
    

    ?>
    </Body>
</html>