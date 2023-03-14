<?php

require_once '../app.php';

use App\Auth\Auth;

$auth = new Auth;
$id = $_REQUEST['id']; 
   $add = $auth->orderAddress($id);  
   $pro = $auth->getproduct($id); 
   echo "<h5><b>Address: </b> $add->address1 &nbsp; &nbsp; <b>City: </b>$add->city</h5>" ; 
echo '<table class="table">
    <thead>
        <tr> 
            <td><b>#Order ID</b></td>
            <td><b>Product name</b></td>
            <td><b>Price($)</b></td>
            <td><b>Quantity Ordered</b></td>
        <tr>
    </thead>
    </tbody>';

foreach ($pro as $p) {
    echo '<tr>';
        echo '<td>'. $id.'</td>';
        echo '<td>'. $p['name'] .'</td>';
        echo '<td>'. $p['price'] .'</td>';
        echo '<td>'. $p['quantity'] .'</td>';
    echo '</tr>'; 
}
 echo '</tbody>
  <tfoot>
        <tr> 
           <td><b>#Order ID</b></td>
            <td><b>Product name</b></td>
            <td><b>Price($)</b></td>
            <td><b>Quantity Ordered</b></td>
        <tr>
    </tfoot>';
 echo '</table>';