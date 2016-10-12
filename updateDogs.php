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
        <title>Dog Added</title> 
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


            if ($_POST['form_submitted'] == '1') {
                
                $dogID = $_POST['dogID'];
                $dogName = $_POST['dogName'];
                $dogAge = $_POST['dogAge'];
                $dogDOB = date("Y-m-d", strtotime($_POST['dogDOB']));
                $dogGender = $_POST['dogGender'];
                $dogColors = $_POST['dogColor'];
                $dogWeight = $_POST['dogWeight'];
                $dogHeight = $_POST['dogHeight'];
                $dogDescription = $_POST['dogDescription'];
                $dogArrivalDate = date("Y-m-d", strtotime($_POST['dogArrivalDate']));
                $dogAdoptionPrice = $_POST['dogAdoptionPrice'];
                $newEnclosureID = $_POST['newEnclosureID'];
                $oldEnclosureID = $_POST['oldEnclosureID'];
                
                //Check to see if the dog is already in the database
                $checksql = "SELECT * FROM DOG 
                             WHERE DogID = '$dogID'";
                $checkDB = $pdo->prepare($checksql);
                $checkDB->execute();
                $dogRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                $count = $checkDB->rowCount();
                
                if (($count > 0) && ($dogRow['DogID']==$dogID))  {
                    $sql = "UPDATE `DOG` 
                               SET `DogName`='$dogName',`DogAge`='$dogAge',`DogDateOfBirth`='$dogDOB',`DogGender`='$dogGender',`DogWeight`='$dogWeight',`DogArrivalDate`='$dogArrivalDate',`DogAdoptionPrice`='$dogAdoptionPrice',`DogDescription`='$dogDescription',`DogHeight`='$dogHeight' 
                             WHERE DogID='$dogID'";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                    
                    if($newEnclosureID != $oldEnclosureID) {
                        $sql = "UPDATE `DOGS_IN_SHELTER` 
                                   SET `DateFrom`=CURDATE(),`EnclosureID`='$newEnclosureID' 
                                 WHERE DogID='$DogID'";
                        $insDB = $pdo->prepare($sql);
                        $insDB->execute();

                        $sql = "UPDATE `ENCLOSURE` 
                                   SET `EnclosureCurrentNumber`= EnclosureCurrentNumber+1
                                 WHERE EnclosureID='$newEnclosureID'";
                        $insDB = $pdo->prepare($sql);
                        $insDB->execute();

                        $sql = "UPDATE `ENCLOSURE` 
                                   SET `EnclosureCurrentNumber`= EnclosureCurrentNumber-1
                                 WHERE EnclosureID='$oldEnclosureID'";
                        $insDB = $pdo->prepare($sql);
                        $insDB->execute();
                    }
                }
                else {
                    //Insert the dog into the database
                    $sql = "INSERT INTO `DOG`(`DogID`, `DogName`, `DogAge`, `DogDateOfBirth`, `DogGender`, `DogWeight`, `DogArrivalDate`, `DogAdoptionPrice`, `DogDescription`, `DogHeight`) 
                                 VALUES ('$dogID', '$dogName', '$dogAge', '$dogDOB', '$dogGender', '$dogWeight', '$dogArrivalDate', '50', '$dogDescription', '$dogHeight')";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                    
                    $sql = "INSERT INTO `DOGS_IN_SHELTER`(`DogID`, `DateFrom`, `EnclosureID`) VALUES ('$dogID',CURDATE(),'$newEnclosureID')";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                    
                    $sql = "UPDATE `ENCLOSURE` 
                               SET `EnclosureCurrentNumber`= EnclosureCurrentNumber+1
                             WHERE EnclosureID='$newEnclosureID'";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                    
                    echo "<h3>".$dogName." was successfully added.</h3><br><br>";
                }
                
                $oldEnclosureID = $newEnclosureID;
                
                $sql = "SELECT * 
                          FROM DOG 
                         WHERE DogID = '$dogID'";
                $getDB = $pdo->prepare($sql);
                $getDB->execute();
                $dogRow = $getDB->fetch(PDO::FETCH_ASSOC);
                
                $sql = "SELECT *
                          FROM DOGS_IN_SHELTER
                         WHERE DogID = '$dogID'";
                $getDB = $pdo->prepare($sql);
                $getDB->execute();
                $shelterRow = $getDB->fetch(PDO::FETCH_ASSOC);
                
                $thisDogID = $dogRow['DogID'];
                            
                foreach($_POST['dogBreed'] as $breed) {
                    $sql = "INSERT INTO `DOG_BREED`(`DogID`, `BreedCode`)
                            VALUES ('$thisDogID', '$breed')";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                }
                
                foreach($_POST['dogColor'] as $color) {
                    $sql = "INSERT INTO `DOG_COLOR`(`ColorCode`, `DogID`)
                            VALUES ('$color', '$thisDogID')";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                }
                
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
            }
        ?>
                <div style="text-align: center; margin: 0 auto;">
                    <div style="display: block; width: 50%; text-align: center; margin: 0 auto;">
                        <?php
                            $sql = "SELECT DogImage FROM DOG_IMAGE 
                                    WHERE DogID = '$thisDogID'";
                            $getDB = $pdo->prepare($sql);
                            $getDB->execute();
                            $thisIMG = $getDB->fetch(PDO::FETCH_ASSOC);
                            echo '<img src="data:image/jpeg;base64,'.base64_encode( $thisIMG['DogImage'] ).'"/>';
                        ?>
                    </div>
                    <br>
                </div>
                <div class="formContainer">
                        <h2>About <?php echo $dogRow['DogName'] ?></h2>
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
                            <tr>
                                <td><?php echo $dogRow['DogID']; ?></td>
                                <td><?php echo $dogRow['DogArrivalDate']; ?></td>
                                <td><?php echo $dogRow['DogName'] ?></td>
                                <td><?php echo $dogRow['DogDateOfBirth']; ?></td>
                                <td><?php echo $dogRow['DogAge']; ?></td>
                                <td><?php echo $dogRow['DogGender']; ?></td>
                                <td>
                                    <?php
                                        foreach($dogBreedRow as $breed){
                                            echo $breed['BreedName']."<br>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        foreach($dogColorRow as $color){
                                            echo $color['ColorName']."<br>";
                                        }
                                    ?>
                                </td>
                                <td><?php echo $dogRow['DogWeight']." lbs"; ?></td>
                                <td><?php echo $dogRow['DogHeight']." in"; ?></td>
                                <td class="descrCol"><?php echo $dogRow['DogDescription']; ?></td>
                                <td><?php echo $shelterRow['EnclosureID']; ?></td>
                                <td>$<?php echo $dogRow['DogAdoptionPrice']; ?>.00</td>
                            </tr>
                        </table>
                    
                        <br>
                    
                        <h2><?php echo $dogRow['DogName'] ?>'s Treatments</h2>
                        <table class="centerTable" style="border: 2px solid black;">
                            <tr style="border: 2px solid black">
                                <th>TreatmentID</th>
                                <th>Treatment Date</th>
                                <th>Treatment Cost</th>
                                <th>Treatment Type</th>
                                <th>Treatment Description</th>
                                <th>Completed By</th>
                                <th>Treatment Details</th>
                            </tr>
                            <?php
                    
                                $checksql = "SELECT * FROM TREATMENTS
                                              WHERE DogID = '$thisDogID'";
                                $checkDB = $pdo->prepare($checksql);
                                $checkDB->execute();
                                $treatmentRows = $checkDB->fetchAll(PDO::FETCH_ASSOC);

                                foreach($treatmentRows as $row) {

                                    $thisTreatmentID = $row['TreatmentID'];

                                    $checksql = "SELECT TreatmentName, TreatmentTypeDescription
                                                   FROM TREATMENT_TYPE JOIN TREATMENTS
                                                        ON TREATMENT_TYPE.TreatmentTypeCode = TREATMENTS.TreatmentTypeCode
                                                  WHERE TREATMENTS.TreatmentID = '$thisTreatmentID'";
                                    $checkDB = $pdo->prepare($checksql);
                                    $checkDB->execute();
                                    $treatmentRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                                    
                                    $checksql = "SELECT PersonFirstName, PersonLastName 
                                                   FROM PERSON JOIN TREATMENTS
                                                        ON PERSON.PersonID = TREATMENTS.PersonID
                                                  WHERE TREATMENTS.TreatmentID = '$thisTreatmentID'";
                                    $checkDB = $pdo->prepare($checksql);
                                    $checkDB->execute();
                                    $personRow = $checkDB->fetch(PDO::FETCH_ASSOC);

                                    $person = $personRow['PersonFirstName']." ".$personRow['PersonLastName'];

                                    $treatmentName = $treatmentRow['TreatmentName'];
                                    $treatmentDescr = $treatmentRow['TreatmentTypeDescription'];

                                    echo "<tr style='border: 1px solid black'>";
                                    echo "<td>" . $row['TreatmentID'] . "</td>";
                                    echo "<td>" . $row['TreatmentDate'] . "</td>";
                                    echo "<td>$" . $row['TreatmentCost'] . ".00</td>";
                                    echo "<td>" . $treatmentName . "</td>";
                                    echo "<td class='descrCol'>" . $treatmentDescr . "</td>";
                                    echo "<td>" . $person . "</td>";
                                    echo "<td class='descrCol'>" . $row['TreatmentDetails'] . "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                    
                        <br>
                        
                        <a href="viewDogs.php"><button id="submitButton" type="button">Back to All Dogs</button></a>
                        <a href="index.html"><button id="submitButton" type="button">Back to Main Menu</button></a>
                        <br><br>
                        <form method="post" action="adoptDogs.php">
                                <input type="hidden" name="dogID" value="<?php echo $dogRow['DogID']; ?>">
                                <input type="hidden" name="enclosureID" value="<?php echo $shelterRow['EnclosureID']; ?>">
                                <input type="submit" id="submitButton" name="submit" value="Adopt <?php echo $dogRow['DogName'] ?>">
                        </form>                    
                        <br><br>
                        <form method="post" action="viewDogs.php">
                                <input type="hidden" name="deleteDog" value="1">
                                <input type="hidden" name="dogID" value="<?php echo $dogRow['DogID']; ?>">
                                <input type="hidden" name="enclosureID" value="<?php echo $shelterRow['EnclosureID']; ?>">
                                <input type="submit" id="resetButton" name="submit" value="Delete <?php echo $dogRow['DogName'] ?>">
                        </form>
                        
                        <br><br>
                        <hr>
                        <form action="updateDogs.php" method="post">
                            <h2>Update <?php echo $dogRow['DogName']; ?>'s Information:</h2>
                            <table class="centerTable">
                                <tr>
                                    <td class="alignRight"><label for="dogArrivalDate">ARRIVAL DATE:</label></td>
                                    <td class="alignLeft"><input id="dogArrivalDate" type="date" name="dogArrivalDate" value="<?php echo $dogRow['DogArrivalDate']; ?>" required /></td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogName">NAME:</label></td>
                                    <td class="alignLeft"><input id="dogName" type="text" name="dogName" maxlength="25" value="<?php echo $dogRow['DogName'] ?>" required /></td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogDOB">DOB (if known):</label></td>
                                    <td class="alignLeft"><input id="dogDOB" type="date" name="dogDOB" value="<?php echo $dogRow['DogDateOfBirth']; ?>" /></td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogAge">AGE:</label></td>
                                    <td class="alignLeft"><input id="dogAge" type="number" name="dogAge" min="0" max="20" value="<?php echo $dogRow['DogAge']; ?>" required /></td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogGender">GENDER:</label></td>
                                    <td class="alignLeft">
                                        <select id="dogGender" name="dogGender" selected="<?php echo $dogRow['DogGender']; ?>">
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>                                
                                    </td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label>BREED(S):</label><br>Hold down Ctrl (Windows) /<br>Command (Mac) to select<br>multiple breeds</td>
                                    <td class="alignLeft">
                                        <select id="dogBreed" name="dogBreed[]" multiple required>
                                            <option value="MIX">Mixed</option>
                                            <option value="AMH">American Hound</option>
                                            <option value="AST">American Staffordshire Terrier</option>
                                            <option value="BAS">Basenji</option>
                                            <option value="BAH">Basset Hound</option>
                                            <option value="BGL">Beagle</option>
                                            <option value="BOX">Boxer</option>
                                            <option value="CHI">Chihuahua</option>
                                            <option value="CHW">Chow Chow</option>
                                            <option value="DOB">Doberman Pinscher</option>
                                            <option value="MST">English Mastiff</option>
                                            <option value="GSH">German Shepherd</option>
                                            <option value="GSP">German Shorthaired Pointer</option>
                                            <option value="JRT">Jack Russell Terrier</option>
                                            <option value="LAB">Labrador Retriever</option>
                                            <option value="MCR">Mountain Cur</option>
                                            <option value="PBT">Pit Bull Terrier</option>
                                            <option value="PDL">Poodle</option>
                                            <option value="PUG">Pug</option>
                                            <option value="RTW">Rottweiler</option>
                                            <option value="SHI">Shih Tzu</option>
                                            <option value="HSK">Siberian Husky</option>
                                            <option value="YOR">Yorkshire Terrier</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label>COLOR(S):</label></td>
                                    <td class="alignLeft">
                                        <label><input name="dogColor[]" type="checkbox" value="BL" />Black &nbsp;</label>
                                        <label><input name="dogColor[]" type="checkbox" value="BR" />Brown &nbsp;</label>
                                        <label><input name="dogColor[]" type="checkbox" value="GY" />Gray &nbsp;</label>
                                        <label><input name="dogColor[]" type="checkbox" value="TN" />Tan &nbsp;</label>
                                        <label><input name="dogColor[]" type="checkbox" value="WT" />White</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogWeight">WEIGHT (lbs):</label></td>
                                    <td class="alignLeft"><input id="dogWeight" type="number" name="dogWeight" min="1" max="250" value="<?php echo $dogRow['DogWeight']; ?>" required /></td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogHeight">HEIGHT (inches):</label></td>
                                    <td class="alignLeft"><input id="dogHeight" type="number" name="dogHeight" min="1" max="84" value="<?php echo $dogRow['DogHeight']; ?>" required /></td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogDescriptionText">DESCRIPTION:</label></td>
                                    <td class="alignLeft">
                                        <textarea id="dogDescriptionText" name="dogDescription" maxlength="300"><?php echo $dogRow['DogDescription']; ?></textarea>
                                        <div id="textarea_feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="newEnclosureID">ENCLOSURE:</label></td>
                                    <td class="alignLeft">
                                        <select id="newEnclosureID" name="newEnclosureID" size="1" selected="<?php echo $shelterRow['EnclosureID']; ?>">
                                            <?php
                                                $checksql = "SELECT *
                                                               FROM ENCLOSURE
                                                               WHERE EnclosureCurrentNumber < EnclosureCapacity";
                                                $checkDB = $pdo->prepare($checksql);
                                                $checkDB->execute();
                                                $enclosureRow = $checkDB->fetchAll(PDO::FETCH_ASSOC);

                                                foreach($enclosureRow as $enclosureRow) {
                                                    $openSlots = $enclosureRow['EnclosureCapacity']-$enclosureRow['EnclosureCurrentNumber'];
                                                    echo '<option value="'.$enclosureRow['EnclosureID'].'">Enclosure'." ".$enclosureRow['EnclosureID']." (".$openSlots." out of ".$enclosureRow['EnclosureCapacity']." slots open)".'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="alignRight"><label for="dogAdoptionPrice">ADOPTION PRICE:</label></td>
                                    <td class="alignLeft"><input id="dogAdoptionPrice" type="number" name="dogAdoptionPrice" min="50" max="250" value="<?php echo $dogRow['DogAdoptionPrice']; ?>" required /></td>
                                </tr>
                            </table>
                        <br><br>
                        <input type="hidden" name="form_submitted" value="1">
                        <input type="hidden" name="dogID" value="<?php echo $dogRow['DogID']; ?>">
                        <input type="hidden" name="oldEnclosureID" value="<?php echo $oldEnclosureID ?>">
                        <input id="submitButton" type="submit" name="submit" value="Update <?php echo $dogRow['DogName'] ?>">
                        <button id="resetButton" type="reset" value="Reset">Reset Form</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>