<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css"/>
        <script src="background.js"></script>
        <title>Result</title>
    </head>
 
    <body onload="setbackground()"> 
    <div class="bigbar">
    <a href="index.html">HOME</a>
    </div>

    <?php
        //hämta value(setid) från search_set sidan.(Oliver)
        $clik = $_GET['click'];
        $connect = mysqli_connect("mysql.itn.liu.se","lego","","lego");
        if (!$connect) 
        {
        die('Could not connect: ' . mysql_error());
        }

        //fråga till databasen för att visa alla delar i ett set.(Oliver)
        $lego_result = mysqli_query($connect, "SELECT inventory.Quantity, inventory.ItemTypeID, inventory.ItemID, inventory.ColorID, colors.Colorname, parts.Partname FROM inventory, parts, colors WHERE inventory.SetID='$clik' AND inventory.ItemTypeID='P' AND inventory.ItemID=parts.PartID AND inventory.ColorID=colors.ColorID");

        print("<div class='results'>");
        //visa hur många delar i detta set(Oliver)
        print("<h3>Parts in set: $clik</h3>");
         
        print("</div>")
    ?>

        <div class="table_cloth">

        <table class="tabletwo">

        <thead>
           <tr>
            <th>Quantity</th>

            <th>Picture</th>

            <th>Color</th>

            <th>Part name</th>
           </tr>
        </thead>

 <?php
   
 
 if(mysqli_num_rows($lego_result) == 0)
 {
    print("<h3>Can not find parts for this set.</h3>\n");
 } 
 else 
 {

        while($row = mysqli_fetch_array($lego_result))
        {

            //Hämta och deklarera variabler för tabellen(OLive)

            $Quantity = $row['Quantity'];

            $Colorname = $row['Colorname'];

            $Partname = $row['Partname'];
   
            $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";

            $ItemID = $row['ItemID'];

            $ColorID = $row['ColorID'];

            //hämta bilder(OLiver)
   
            $imagesearch = mysqli_query($connect, "SELECT * FROM images WHERE ItemTypeID='P' AND ItemID='$ItemID' AND ColorID=$ColorID");
   
            $imageinfo = mysqli_fetch_array($imagesearch);

            //kolla om bilden är jpg eller gif(olika adresser)(OLiver)

            if($imageinfo['has_jpg']) 
            { 
	            $filename = "P/$ColorID/$ItemID.jpg";
            } 
            else if($imageinfo['has_gif']) 
            {
	            $filename = "P/$ColorID/$ItemID.gif";
            } 
            else 
            { 
	            $filename = "noimage_small.png";
            }

            print("<tr>");

            print("<td>$Quantity</td>");

            print("<td><img src=\"$prefix$filename\" alt=\"Part $ItemID\"/></td>");

            print("<td>$Colorname</td>");

            print("<td>$Partname</td>");

            print("</tr>\n");

        }
  
 }
 
 ?>

        </table>

       </div>

    </body>

</html>
