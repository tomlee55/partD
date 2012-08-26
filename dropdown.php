<?php function dropdown ($table, $attribute, $Value) {
    require 'db_pdo.php';
    
    $pdo = new PDO($dsn, DB_USER, DB_PW);
 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
    $query = "SELECT DISTINCT {$attribute} FROM {$table} order by 
{$attribute}";
    $results = $pdo->query($query);
   
 
    while ($row = $results->fetch(PDO::FETCH_OBJ)) {
     $data = $row->$attribute;
     if (isset($Value) && $data == $Value)
       
       print "\n\t<option selected value=\"{$data}\">{$data}";
     else
     
       print "\n\t<option value=\"{$data}\">{$data}";
     print "</option>";
     
    }
 }
?>
