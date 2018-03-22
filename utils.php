<?php 
    function consoleLog($text){
        echo "<script>console.log('$text')</script>";
    }
    function connectDB(){
        $username = 'root';
        $password = 'password';
        $servername = '158.108.38.91:61919';
        $conn = new PDO("mysql:host=$servername;dbname=dreamhome", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        consoleLog("Connected successfully");
        return $conn;
    }
    function closeConnection($conn){
        $conn = null;
        return $conn;
    }
?>