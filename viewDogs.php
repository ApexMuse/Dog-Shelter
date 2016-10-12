<!DOCTYPE html>
<html lang=en> 
    <head> 
        <meta charset=utf-8> 
        <meta http-equiv=X-UA-Compatible content="IE=edge"> 
        <meta name=viewport content="width=device-width, initial-scale=1"> 
        <meta name=ROBOTS content="NOINDEX, NOFOLLOW">
        <meta http-equiv=”Pragma” content=”no-cache”>
        <meta http-equiv=”Expires” content=”-1″>
        <meta http-equiv=”CACHE-CONTROL” content=”NO-CACHE”>
        <meta name=author content="Team 4"> 
        <meta name=description content="Database Systems Team 4 Final Project"> 
        <title>View Dogs</title> 
        <!--[if lt IE 9]> <script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.js"></script> <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script><![endif]-->
        <link href="styles.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
            <div>
                <h1>Dogs In Team 6 Shelter</h1>
                
                <a href="index.html"><button id="submitButton" type="button">Back to Main Menu</button></a>
                
                <br><br>
                
                <?php
                    // Create variables for DB login
                    $dsn = 'mysql:dbname=DogShelter;host=localhost';
                    $user = 'Team6';
                    $password = 'Team6FinalProject';

                    // Create PDO object, use exception if there is a connection error
                    try {
                        $pdo = new PDO($dsn, $user, $password);
                    } 
                    catch (PDOException $e) {
                        echo 'Database connection failed: ' . $e->getMessage();
                    }
                
                    $dogID = $_POST['dogID'];
                
                    if($_POST['deleteDog'] == 1) {
                        
                        $enclosureID = $_POST['enclosureID'];
                        
                        $checksql = "DELETE FROM `DOG` 
                                      WHERE `DogID` = '$dogID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        $checksql = "DELETE FROM `DOGS_IN_SHELTER`
                                      WHERE `DogID` = '$dogID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        $checksql = "DELETE FROM `DOG_BREED`
                                      WHERE `DogID` = '$dogID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        $checksql = "DELETE FROM `DOG_COLOR`
                                      WHERE `DogID` = '$dogID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        $checksql = "UPDATE `ENCLOSURE` 
                                        SET `EnclosureCurrentNumber`= `EnclosureCurrentNumber`+1
                                      WHERE `EnclosureID`='$enclosureID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                    }
                
                    $checksql = "SELECT * FROM DOG
                                  WHERE DOG.DogID NOT IN
                                        (SELECT DogID
                                           FROM ADOPTION)";
                    $checkDB = $pdo->prepare($checksql);
                    $checkDB->execute();
                    $dogRows = $checkDB->fetchAll(PDO::FETCH_ASSOC);
                ?>
                
                <table class="centerTable" style="border: 2px solid black;">
                    <tr style="border: 2px solid black">
                        <th>DogID</th>
                        <th>Arrival Date</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Breed(s)</th>
                        <th>Color(s)</th>
                        <th>Weight</th>
                        <th>Height</th>
                        <th>Description</th>
                        <th>Enclosure #</th>
                        <th>Adoption Price</th>
                    </tr>
                    
                    <?php
                    
                        foreach($dogRows as $row) {
                            
                            $thisDogID = $row['DogID'];
                            
                            $checksql = "SELECT BreedName 
                                           FROM BREED JOIN DOG_BREED
                                                ON BREED.BreedCode = DOG_BREED.BreedCode
                                          WHERE DOG_BREED.DogID = '$thisDogID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $dogBreedRow = $checkDB->fetchAll(PDO::FETCH_ASSOC);

                            $checksql = "SELECT ColorName 
                                           FROM COLOR JOIN DOG_COLOR
                                                ON COLOR.ColorCode = DOG_COLOR.ColorCode
                                          WHERE DOG_COLOR.DogID = '$thisDogID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $dogColorRow = $checkDB->fetchAll(PDO::FETCH_ASSOC);

                            $sql = "SELECT *
                                      FROM DOGS_IN_SHELTER
                                     WHERE DogID = '$thisDogID'";
                            $getDB = $pdo->prepare($sql);
                            $getDB->execute();
                            $shelterRow = $getDB->fetch(PDO::FETCH_ASSOC);
                            
                            echo "<tr style='border: 1px solid black'>";
                            echo "<td>" . $row['DogID'] . "</td>";
                            echo "<td>" . $row['DogArrivalDate'] . "</td>";
                            echo "<td>";
                                echo "<form method='post' action='updateDogs.php'>";
                                    echo "<input type='hidden' name='form_submitted' value='1'>";
                                    echo "<input type='hidden' name='dogID' value='" . $row['DogID'] . "'>";
                                    echo "<input type='hidden' name='dogArrivalDate' value='". $row['DogArrivalDate'] . "'>";
                                    echo "<input type='hidden' name='dogName' value='". $row['DogName'] . "'>";
                                    echo "<input type='hidden' name='dogDOB' value='". $row['DogDateOfBirth'] . "'>";
                                    echo "<input type='hidden' name='dogAge' value='". $row['DogAge'] . "'>";
                                    echo "<input type='hidden' name='dogGender' value='". $row['DogGender'] . "'>";
                                    echo "<input type='hidden' name='dogWeight' value='". $row['DogWeight'] . "'>";
                                    echo "<input type='hidden' name='dogHeight' value='". $row['DogHeight'] . "'>";
                                    echo "<input type='hidden' name='dogDescription' value='". $row['DogDescription'] . "'>";
                                    echo "<input type='hidden' name='newEnclosureID' value='". $shelterRow['EnclosureID'] . "'>";
                                    echo "<input type='hidden' name='dogAdoptionPrice' value='". $row['DogAdoptionPrice'] . "'>";
                                    echo "<input id='updateButton' type='submit' name='submit' value='". $row['DogName'] . "'>";
                                echo "</form>";
                            echo "</td>";
                            echo "<td>" . $row['DogDateOfBirth'] . "</td>";
                            echo "<td>" . $row['DogAge'] . "</td>";
                            echo "<td>" . $row['DogGender'] . "</td>";
                            echo "<td>"; 
                                foreach($dogBreedRow as $breed){echo $breed['BreedName']."<br>";}
                            echo "</td>";
                            echo "<td>"; 
                                foreach($dogColorRow as $color){echo $color['ColorName']."<br>";}
                            echo "</td>";
                            echo "<td>" . $row['DogWeight'] . "</td>";
                            echo "<td>" . $row['DogHeight'] . "</td>";
                            echo "<td class='descrCol'>" . $row['DogDescription'] . "</td>";
                            echo "<td>" . $shelterRow['EnclosureID'] . "</td>";
                            echo "<td>$" . $row['DogAdoptionPrice'] . ".00</td>";
                            echo "</tr>";
                        }
                    ?>
                    
                </table>
                <br><br>
                <a href="index.html"><button id="submitButton" type="button">Back to Main Menu</button></a>
                
            </div>
        </div>
        <script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="js/dogShelter.js"></script>
    </body>
</html>