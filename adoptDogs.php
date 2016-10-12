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
        <title>Adoptions</title> 
        <!--[if lt IE 9]> <script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.js"></script> <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script><![endif]-->
        <link href="styles.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
            <div>
                
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
                
                    if($_POST['adoptDog'] == '1') {
                        
                        $dogID = $_POST['dogID'];
                        $enclosureID = $_POST['enclosureID'];
                        $personID = $_POST['personID'];
                        $adoptionDate = $_POST['adoptionDate'];
                        
                        $checksql = "INSERT INTO `ADOPTION`(`PersonID`, `DogID`, `AdoptionDate`) 
                                     VALUES ('$personID', '$dogID','$adoptionDate')";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        $checksql = "DELETE FROM `DOGS_IN_SHELTER`
                                      WHERE `DogID` = '$dogID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        $checksql = "UPDATE `ENCLOSURE` 
                                        SET `EnclosureCurrentNumber`= `EnclosureCurrentNumber`+1
                                      WHERE `EnclosureID`='$enclosureID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        echo "<h3>Adoption Recorded.</h3>";
                    }
                ?>
                
                <h2>Enter Adoption</h2>
                
                <form method="post" action="adoptDogs.php">
                     <table class="centerTable">
                        <tr>
                            <td class="alignRight"><label for="dogID">DOG:</label></td>
                            <td class="alignLeft">
                                <select id="dogID" name="dogID" size="1">
                                    <?php
                                        $checksql = "SELECT DogID, DogName 
                                                       FROM DOG
                                                      WHERE DOG.DogID NOT IN
                                                            (SELECT DogID
                                                               FROM ADOPTION)";
                                        $checkDB = $pdo->prepare($checksql);
                                        $checkDB->execute();
                                        $dogNameRow = $checkDB->fetchAll(PDO::FETCH_ASSOC);

                                        foreach($dogNameRow as $dogNameRow) {
                                            echo '<option value="'.$dogNameRow['DogID'].'">'.$dogNameRow['DogName'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personID">PERSON:</label></td>
                            <td class="alignLeft">
                                <select id="personID" name="personID" size="1">
                                    <?php
                                        $checksql = "SELECT PersonID, PersonFirstName, PersonLastName
                                                       FROM PERSON
                                                       ORDER BY PersonLastName";
                                        $checkDB = $pdo->prepare($checksql);
                                        $checkDB->execute();
                                        $personRow = $checkDB->fetchAll(PDO::FETCH_ASSOC);

                                        foreach($personRow as $personRow) {
                                            echo '<option value="'.$personRow['PersonID'].'">'.$personRow['PersonFirstName']." ".$personRow['PersonLastName'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                         <tr>
                            <td class="alignRight"><label for="adoptionDate">ADOPTION DATE:</label></td>
                            <td class="alignLeft"><input id="adoptionDate" type="date" name="adoptionDate" required /></td>
                        </tr>
                    </table>
                    <br><br>
                    <input type="hidden" name="adoptDog" value="1">
                    <input type="submit" id="submitButton" name="submit" value="Submit Adoption">
                    <a href="addPerson.html"><button id="resetButton" type="button">Add New Person</button></a>
                    <a href="index.html"><button id="resetButton" type="button">Back to Main Menu</button></a>
                </form>
                
                <br><br>
                
                
                
                <br><br>
                
                <hr>
                                
                <br><br>
                    
                <h2>Team 4 Shelter - Adopted Dogs</h2>
                
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
                        <th>Adoption Price</th>
                        <th>AdoptionDate</th>
                        <th>Adopted By</th>
                    </tr>
                    
                    <?php
                    
                        $checksql = "SELECT * FROM DOG
                                      WHERE DOG.DogID IN
                                            (SELECT DogID
                                               FROM ADOPTION)";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        $dogRows = $checkDB->fetchAll(PDO::FETCH_ASSOC);
                    
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
                            
                            $checksql = "SELECT AdoptionDate 
                                           FROM ADOPTION
                                          WHERE DogID = '$thisDogID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $adoptRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                            
                            $adoptionDate = $adoptRow['AdoptionDate'];
                            
                            $checksql = "SELECT PersonFirstName, PersonLastName 
                                           FROM PERSON JOIN ADOPTION
                                                ON PERSON.PersonID = ADOPTION.PersonID
                                          WHERE ADOPTION.DogID = '$thisDogID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $adopterRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                            
                            $adoptedBy = $adopterRow['PersonFirstName']." ".$adopterRow['PersonLastName'];
                            
                            echo "<tr style='border: 1px solid black'>";
                            echo "<td>" . $row['DogID'] . "</td>";
                            echo "<td>" . $row['DogArrivalDate'] . "</td>";
                            echo "<td>" . $row['DogName'] ."</td>";
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
                            echo "<td>$" . $row['DogAdoptionPrice'] . ".00</td>";
                            echo "<td>" . $adoptionDate . "</td>";
                            echo "<td>" . $adoptedBy . "</td>";
                            echo "</tr>";
                        }
                    ?>
                    
                </table>
                <br><br>
                <a href="index.html"><button id="resetButton" type="button">Back to Main Menu</button></a>
                
            </div>
        </div>
        <script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="js/dogShelter.js"></script>
    </body>
</html>