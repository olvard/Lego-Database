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

    <?php
    //h�mta s�krutans text(Oliver)
        $search = $_GET['search'];
        
        $search = strip_tags($search);
    ?>
    
    <div class="bigbar">

    <a href="index.html"><img src="arrow.png" alt="ARROW" width="50" height="50"></a>
    <?php
    //A-Z  eller Z-A, f�r att sortera sidan alfabetiskt, anv�nder den h�mtade search f�r att beh�lla samma s�k resultat(annars f�rsvinner det)(Oliver)
    print("<a href='search_set.php?search=$search&order=asc'>A-Z</a>");
    print("<a href='search_set.php?search=$search&order=desc'>Z-A</a>");
                
    ?>

    </div>
     
    <div class="set_res">

    <?php
    //Connecta till databasen(Oliver)
    $connect = mysqli_connect("mysql.itn.liu.se","lego","","lego");

    if (!$connect)
    {
    die('Could not connect: ' . mysql_error());
    }


    //h�mta order fr�n A-Z l�nken p� line 24 och 25.(Oliver)
    $order = $_GET['order'];
    
    
    if(!isset($order))
    { 
        $order = DESC;
    }
   
    //deklarerar variabler f�r sid visning.(Oliver)

   $total_reg = "10";

   //h�mta page fr�n line 138, 143.(Oliver)
   //om ej tryckt p� �r den 1.(Oliver)

   $page = $_GET['page'];
   if(!$page)
   {
       $pc = "1";
   }
   else 
   {
       $pc = $page;
   }

   //begin �r vilken sida den b�rjar p�, med -1 kommer den b�rja p� 1 ist f�r 0.(Oliver)

   $begin = $pc - 1;
   $begin = $begin * $total_reg;

   //fr�ga till databasen om att h�mta alla set som liknar det man s�kt p� och sortera p� setnamn enligt variabeln order.(Oliver)

   $query = "SELECT * FROM sets WHERE SetID LIKE '%$search%' OR Setname LIKE '%$search%' ORDER BY Setname $order LIMIT $begin, $total_reg";

   $lego_result = mysqli_query($connect, $query);

   //samma fr�ga utan limit f�r att variablen tr som r�knar antalet totala rader i fr�gan ska vara korrekt.(Oliver)
   
   $query2 = mysqli_query($connect, "SELECT * FROM sets WHERE SetID LIKE '%$search%' OR Setname LIKE '%$search%' ORDER BY Setname $order");

   $tr = mysqli_num_rows($query2);

   $tp = $tr / $total_reg;

   //Om det inte finns n�got resultat(om raderna �r 0 f�r det man s�kt p�) s� skickas man till error sidan.(Oliver)
    if(mysqli_num_rows($lego_result) == 0)
    {
    
    header('Location: http://www.student.itn.liu.se/~olilu316/databas/V1/error.html');
    exit;

    }     

    else
    {
        $start_time = (float) round(microtime(true) * 10000,0);

        print("<div class='tableone'>");
        
        //While loop f�r att skriva ut allt som hittas. Variablen row �r hela arrayen.(Oliver)
        while($row = mysqli_fetch_array($lego_result))
        {

        //deklarera variabler h�mta set id cat id och namn och �r.(Oliver)

        $setid = $row['SetID'];
        $setname = $row['Setname'];
        $year = $row['Year'];
        $prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";

        $setimage = mysqli_query($connect, "SELECT * FROM images WHERE ItemTypeID='S' AND ItemID='$setid'");

        $setinfo = mysqli_fetch_array($setimage);

        if($setinfo['has_jpg']) 
            { 
	            $file = "S/$setid.jpg";
            } 
            else if($setinfo['has_gif']) 
            {
	            $file = "S/$setid.gif";
            } 
            else 
            { 
	            $file = "noimage_small.png";
            }

        //skriv ut en rad(Oliver)

        print("<form class='form' action='search_match.php' method='GET'>");

        print("<button class='butt' name='click' value='$setid'>");

        print("<img class='setbild' src=\"$prefix$file\" alt=\"Part $setid\"/>");

        print("<br>");

        print("<span>$setid </span>");

        //raden setname �r �ven en knapp som tar dig till det set du klickat p� genom skicka me setid som value.(Oliver)

        print("<span>$setname </span>");

        print("<span>$year</span>");

        print("</button>");

        print("</form>");

        }

        print("</div>");

        //l�nkar f�r sidovisning(Oliver)

        $previous = $pc - 1;
        $next = $pc +1;

        print("<div class='pages'>");

        //om antalet sidor �r st�rre �n 1 kan man klicka tillbaks(Oliver)

        if($pc>1)
        {
            print("<a href='search_set.php?search=$search&order=$order&page=$previous'>BACK </a>");
        }
        

        //om antalet sidor �r st�rre �n 1 dyker next knappen upp.(Oliver)

        if($pc<$tp)
        {
            print("<a href='search_set.php?search=$search&order=$order&page=$next'> NEXT</a>");
        }


        print("</div>");

        // Displaya number of results och tiden queryn tog och displaya tiden det tog att ta fram resultaten. (Filip) 
		
        $finish_time = (float) round(microtime(true) * 10000,0);
		$time = ($finish_time - $start_time) / 10000;
		
        print("<div class='number'>");
        
        print("<p>Number of results: $tr</p>");

        print("<p> Completed in: $time seconds </p>");

		
		print("</div>");
		
		
        
    }

    ?>

    </div>

    </body>
</html>