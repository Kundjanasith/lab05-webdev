<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dreamhome</title>
</head>
<?php include('utils.php') ?>
<body>
        <?php
        try {
                $conn = connectDB();
                $cityList = array();
                $typePropertyList = array();
                $typeMapper = array();
                $query = "SELECT DISTINCT city, type FROM PropertyForRent";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result)> 0) {
                    foreach($result as $row){
                        if(!in_array($row['city'],$cityList)){
                            array_push($cityList,$row['city']);
                            $typeMapper [ $row['city'] ] = array();
                        }
                        if(!in_array($row['type'],$typePropertyList)){
                            array_push($typePropertyList,$row['type']);
                        }

                        if(!in_array($row['type'],$typeMapper[$row['city']])){
                            array_push($typeMapper[$row['city']],$row['type']);
                        }
                    }
                } else {
                    consoleLog('0 results');
                }
                consoleLog(implode(",",$cityList));
                consoleLog(implode(",",$typePropertyList));
                foreach($typeMapper as $key => $value){
                    consoleLog("key ".$key."     value  ".implode(",",$value));
                }
                closeConnection($conn);
            }
        catch(PDOException $e)
            {
            consoleLog("Connection failed: " . $e->getMessage());
            }
            $text = 'init'
        ?> 
        <form action="/property.php" method="GET">
            <div>
                City :  <select  name="city_name" id="city-name" onchange="onChange()">
                </select>
            </div>
            </br>
            <div>
                Property Type  : <select  name="property_type" id="property-type">
                </select>
            </div>
            </br>
        <button name="name" value="value" type="submit">Search</button>
        </form>
</body>
<script>
<?php
    echo 'var typeMapper = '.json_encode($typeMapper).';';
?>
function init(){
    let selectCityName = document.getElementById('city-name');
    for (let item in typeMapper){
        console.log(item);
        let opt = document.createElement('option');
        opt.value = item;
        opt.innerHTML = item;
        selectCityName.appendChild(opt);
    }
    setOptionInPropertyType()
}
init()
function setOptionInPropertyType(){
    let selectType = document.getElementById('property-type');
    selectType.innerHTML="";
    let selectedCity = document.getElementById('city-name').value;
    console.log('selected',selectedCity)
    for (let item of typeMapper[selectedCity]){
        let opt = document.createElement('option');
        opt.value = item;
        opt.innerHTML = item;
        selectType.appendChild(opt);
    }
    console.log(typeMapper);
}
function onChange(){
    setOptionInPropertyType()
}
</script>

</html>