<style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            th, td {
                padding: 5px;
                text-align: left;    
            }
</style>
<?
ob_start();
?>
<?php
include('utils.php');
$isShow = true;
echo $Username.$SMTP_HOST;
$Username = getenv('EMAIL_USERNAME');
$Password = getenv('EMAIL_PASSWORD');
$SMTP_HOST = getenv('SMTP_HOST');
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{
        consoleLog('GET-param : '.$_GET['city_name']);
        $cityname = $_GET['city_name'];
        $propertyType = $_GET['property_type'];
        $conn = connectDB();
        $query = "SELECT Client.clientno, Client.fname as cfname, Client.lname as clname, telno, preftype, maxrent, email, postcode, viewdate, Staff.fname, Staff.lname, PropertyForRent.city
        From PropertyForRent INNER JOIN Viewing ON PropertyForRent.propertyno = Viewing.propertyno
                    INNER JOIN Client  ON Client.clientno = Viewing.clientno
                    INNER JOIN Staff ON Staff.staffno = PropertyForRent.staffno
                    WHERE :cityname=PropertyForRent.city AND :propertyType=PropertyForRent.type";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':cityname', $cityname);
            $stmt->bindParam(':propertyType', $propertyType);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result)> 0) {
            $isShow = false;
            echo "<table style='width:100%'>";
            echo "<tr>";
            echo "<th>Client Number</th>";
            echo "<th>Client Name</th>";
            echo "<th>Telephone Number</th>";
            echo "<th>Property Type</th>";
            echo "<th>Max Rent</th>";
            echo "<th>Email</th>";
            echo "<th>Postcode</th>";
            echo "<th>Viewdate</th>";
            echo "<th>Staff Name</th>";
            echo "<th>City</th>";
            echo "<tr>";
            foreach($result as $row){
                // echo implode(",",$row)."<br>";
                echo "<tr>";
                echo "<td>".$row['clientno']."</td>";
                echo "<td>".$row['cfname'].' '.$row['clname']."</td>";
                echo "<td>".$row['telno']."</td>";
                echo "<td>".$row['preftype']."</td>";
                echo "<td>".$row['maxrent']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['postcode']."</td>";
                echo "<td>".$row['viewdate']."</td>";
                echo "<td>".$row['fname'].' '.$row['lname']."</td>";
                echo "<td>".$row['city']."</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</br>";
            echo "<div><button>Print</button></div>";
            echo "</br>";
        } else {
            $query = "SELECT * From PropertyForRent as P, Staff
                    WHERE :cityname=P.city AND :propertyType=P.type AND P.staffno = Staff.staffno";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':cityname', $cityname);
            $stmt->bindParam(':propertyType', $propertyType);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result)> 0) {
                echo "<table style='width:100%'>";
                echo "<tr>";
                echo "<th>Property Number</th>";
                echo "<th>Street</th>";
                echo "<th>City</th>";
                echo "<th>Postcode</th>";
                echo "<th>Property Type</th>";
                echo "<th>Rooms</th>";
                echo "<th>Rent</th>";
                echo "<th>Owner Number</th>";
                echo "<th>Staff Number</th>";
                echo "<th>Staff Name</th>";
                echo "<th>Branch Number</th>";
                echo "</tr>";
                echo "</tr>";
                foreach($result as $row){
                    echo "<tr>";
                    echo "<td>".$row['propertyno']."</td>";
                    echo "<td>".$row['street']."</td>";
                    echo "<td>".$row['city']."</td>";
                    echo "<td>".$row['postcode']."</td>";
                    echo "<td>".$row['type']."</td>";
                    echo "<td>".$row['rooms']."</td>";
                    echo "<td>".$row['rent']."</td>";
                    echo "<td>".$row['ownerno']."</td>";
                    echo "<td>".$row['staffno']."</td>";
                    echo "<td>".$row['fname'].' '.$row['lname']."</td>";
                    echo "<td>".$row['branchno']."</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</br>";
                echo "New property! you are invited to stop over to view it.";
                echo "</br>";
            }
            else{
                $isShow = false;
                echo "Not found.";   
            }
        }
        closeConnection($conn);
    }
    catch(PDOException $e){
        consoleLog("Connection failed: " . $e->getMessage());
    }
}
?> 
<?php
if($isShow){
    echo '<form action="/request_view.php" METHOD="POST">
        <div>email : <input type="text" placeholder="ex: name@gmail.com" name="request_email" required> <button type="submit">Send</button> </div>
        </form>';
}
?>
<div>
<a href="/index.php"><button>Back to home</button></a>
</div>