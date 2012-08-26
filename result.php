<?php
  
  $winename = $_GET['winename'];
  $wineryname = $_GET['wineryname'];
  $regionname = $_GET['regionname'];
  $grapevariety = $GET['grapevariety'];
  $startyear = $_GET['startyear'];
  $endyear = $_GET['endyear'];
  $minimumstock = $_GET['minimumstock'];
  $minimumInOrder = $GET['minimumInOrder'];
  $minimumcost = $_GET['minimumcost'];
  $maximumcost = $_GET['maximumcost'];
   
 require 'db_pdo.php';

try{
    $pdo = new PDO($dsn, DB_USER, DB_PW);
    
    // all errors will throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
   // Start a query ...
    $query = "SELECT DISTINCT wine.wine_name AS winename,cost,on_hand,qty,
			  wine.year AS wineyear,
              winery.winery_name AS wineryname,
              region.region_name AS regionname,
              wine.wine_id AS wineID
              FROM wine,grape_variety,winery,region,wine_variety,inventory,items
              WHERE wine.winery_id = winery.winery_id AND
			  wine_variety.variety_id = grape_variety.variety_id AND
			  wine_variety.wine_id = wine.wine_id AND
			  winery.region_id = region.region_id AND
			  wine.wine_id = items.wine_id AND
			  inventory.wine_id = wine.wine_id "; 

// 
if(isset($winename) && $winename !="")
$query .="AND wine.wine_name like \"%{$winename}%\""; 

if(isset($wineryname) && $wineryname != "") 
$query .= "AND winery.winery_name like \"%{$wineryname}%\""; 

if(isset($regionname) && $regionname != "All") 
$query .= "AND region.region_name = \"{$regionname}\""; 

if(isset($startyear) && $startyear != "") 
$query .= "AND wine.year >= \"{$startyear}\""; 

if(isset($endyear)  && $endyear != "") 
$query .= "AND wine.year <= \"{$endyear}\""; 

if($startyear > $endyear){
   die('Invaild input, start year must less than end year');
  } 

if(isset($minimumstock) && $minimumstock != "") 
$query .= " AND inventory.on_hand >= \"{$minimumstock}\""; 

if(isset($minimumcost) && $minimumcost != "") 
$query .= " AND invertory.cost >= \"{$minimumcost}\""; 

if(isset($maximumcost) && $maximumcost !="") 
$query .=" AND inventory.cost <= \"{$maximumcost}\""; 

if($minimumcost > $maximumcost){
   print "('Invaild input, minimum cost must less than maximum cost')";
   }
       
        
        $query.=" GROUP BY wine.wine_id".
	       " ORDER BY winename,wineyear,wineryname,regionname";
        $statement = $pdo->prepare($query);
  	$statement->execute($values);
 	$results = $statement->fetchAll(PDO::FETCH_ASSOC);
 	
 
 	
 	$row = $statement->rowCount();
	
 	if ($row>0){
 		print "<h1>There are {$row} records found:</h1>";
                print "<a href=winestore.php>Index Page</a>";
 		print "\n<table border=1 align=\"center\">\n".
              "<tr>".
		      "<td>Wine Name </td>".
 		      "<td>Variety1 </td>".
 		      "<td>Variety2 </td>".
 		      "<td>Variety3 </td>".
 		      "<td>WineYear </td>".
		      "<td>WineryName </td>".
		      "<td>Region </td>".
			  "<td>cost </td>".
			  "<td>Availble bottles number </td>".
			  "<td>sales revenue </td>".
              "</tr>";
                
			 
			 
 	    //get all grape variety of this wine;
 		foreach ($results as $data){
 			$queryID="select variety from wine, wine_variety, grape_variety
                       where wine.wine_id=wine_variety.wine_id
                       and wine_variety.variety_id=grape_variety.variety_id
                       and wine.wine_id=?";
			$value = array($data["wineID"]);
			$statement = $pdo->prepare($queryID);
		  	$statement->execute($value);
		    $variety = $statement->fetchAll(PDO::FETCH_ASSOC);
 
		 	print "\n<tr><form>".
		          "<td>{$data["winename"]}</td>".
		  		  "<td>{$variety[0]["variety"]}</td>".
				  "<td>{$variety[1]["variety"]}</td>".
				  "<td>{$variety[2]["variety"]}</td>".
				  "<td>{$data["wineyear"]}</td>".
				  "<td>{$data["wineryname"]}</td>".
				  "<td>{$data["regionname"]}</td>".
				  "<td>{$data["cost"]}</td>".
				  "<td>{$data["on_hand"]}</td>".
				  "<td>{$data["qty"]}</td></form></tr>";
	}
    
		 print"\n</table>";
 	}
 	else {
 		print "<h1>Sorry, there are no matches.</h1>";
 	}
 	
    // close the connection by destroying the object
       $pdo = null;
    }catch (PDOException $e) {
 		 echo $e->getMessage();
 		 exit;
    }
    
    
?>	
