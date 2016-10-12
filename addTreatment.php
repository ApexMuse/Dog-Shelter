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
        <title>Add Treatment</title>
        <!--[if lt IE 9]> <script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.js"></script> <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script><![endif]-->
        <link href="styles.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
            <div>
                <h1>Add Treatment</h1>
                
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
                    $personID = $_POST['personID'];
                    $treatmentDate = $_POST['treatmentDate'];
                    $treatmentCost = $_POST['treatmentCost'];
                    $treatmentType = $_POST['treatmentType'];
                    $treatmentDetails = $_POST['treatmentDetails'];
                
                    $checksql = "SELECT TreatmentID
                                   FROM TREATMENTS
                               ORDER BY TreatmentID DESC LIMIT 1";
                    $checkDB = $pdo->prepare($checksql);
                    $checkDB->execute();
                    $treatmentIDRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                
                    $treatmentID = $treatmentIDRow['TreatmentID'];
                
                    if($_POST['treatment_submitted'] == '1') {
                        
                        $newID =  $treatmentID + 1;
                        
                        $checksql = "INSERT INTO `TREATMENTS`(`TreatmentID`, `DogID`, `TreatmentDate`, `TreatmentCost`, `TreatmentTypeCode`, `PersonID`, `TreatmentDetails`) 
                                     VALUES ($newID, '$dogID', '$treatmentDate', '$treatmentCost', '$treatmentType', '$personID', '$treatmentDetails')";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        $checksql = "UPDATE `DOG` 
                                        SET `DogAdoptionPrice`=DogAdoptionPrice+'$treatmentCost'
                                      WHERE DogID = '$dogID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        
                        echo "<h3>Treatment Recorded.</h3>";
                    }
                ?>
                
                <div class="formContainer">
                    <form action="addTreatment.php" method="post">
                        <table class="centerTable">
                            <tr>
                                <td class="alignRight"><label for="dogID">DOG:</label></td>
                                <td class="alignLeft">
                                    <select id="dogID" name="dogID" size="1">
                                        <?php
                                            $checksql = "SELECT DogID, DogName 
                                                           FROM DOG";
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
                                <td class="alignRight"><label for="treatmentDate">DATE:</label></td>
                                <td class="alignLeft"><input id="treatmentDate" type="date" name="treatmentDate" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="treatmentCost">COST: $</label></td>
                                <td class="alignLeft"><input id="treatmentCost" type="number" name="treatmentCost" min="0" max="5000" value="<?php $treatmentCost; ?>" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="treatmentType">TYPE:</label></td>
                                <td class="alignLeft">
                                    <select id="treatmentType" name="treatmentType" size="1">
                                        <?php
                                            $checksql = "SELECT TreatmentTypeCode, TreatmentName
                                                           FROM TREATMENT_TYPE
                                                           ORDER BY TreatmentName";
                                            $checkDB = $pdo->prepare($checksql);
                                            $checkDB->execute();
                                            $treatmentTypeRow = $checkDB->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            foreach($treatmentTypeRow as $treatmentTypeRow) {
                                                echo '<option value="'.$treatmentTypeRow['TreatmentTypeCode'].'">'.$treatmentTypeRow['TreatmentName'].'</option>';
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
                                                           WHERE PersonTypeCode<>'ADP'
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
                                <td class="alignRight"><label for="dogDescriptionText">DETAILS:</label></td>
                                <td class="alignLeft">
                                    <textarea id="dogDescriptionText" name="treatmentDetails" maxlength="300" ><?php $treatmentDetails; ?></textarea>
                                    <div id="textarea_feedback"></div>
                                </td>
                            </tr>
                        </table>
                        <br><br>
                        <input type="hidden" name="treatment_submitted" value="1">
                        <input type="hidden" name="treatmentID" value="<?php $treatmentID; ?>">
                        <input id="submitButton" type="submit" name="submit" value="Submit Treatment"></button>
                        <button id="resetButton" type="reset" value="Reset">Reset Form</button>
                        <a href="index.html"><button id="resetButton" type="button">Back to Main Menu</button></a>
                    </form>
                </div>
            
                <br><br>
            
                <hr>
                                
                <br><br>
                    
                <h2>Treatments Completed</h2>
                
                <table class="centerTable" style="border: 2px solid black;">
                    <tr style="border: 2px solid black">
                        <th>TreatmentID</th>
                        <th>Dog Name</th>
                        <th>Treatment Date</th>
                        <th>Treatment Cost</th>
                        <th>Treatment Type</th>
                        <th>Treatment Description</th>
                        <th>Completed By</th>
                        <th>Treatment Details</th>
                    </tr>
                    
                    <?php
                    
                        $checksql = "SELECT * FROM TREATMENTS
                                   ORDER BY TreatmentDate DESC";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                        $treatmentRows = $checkDB->fetchAll(PDO::FETCH_ASSOC);
                    
                        foreach($treatmentRows as $row) {
                            
                            $thisTreatmentID = $row['TreatmentID'];
                            
                            $checksql = "SELECT DogName 
                                           FROM DOG JOIN TREATMENTS
                                                ON DOG.DogID = TREATMENTS.DogID
                                          WHERE TREATMENTS.TreatmentID = '$thisTreatmentID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $dogRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                            
                            $dog = $dogRow['DogName'];
                            
                            $checksql = "SELECT PersonFirstName, PersonLastName 
                                           FROM PERSON JOIN TREATMENTS
                                                ON PERSON.PersonID = TREATMENTS.PersonID
                                          WHERE TREATMENTS.TreatmentID = '$thisTreatmentID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $personRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                            
                            $person = $personRow['PersonFirstName']." ".$personRow['PersonLastName'];
                            
                            $checksql = "SELECT TreatmentName, TreatmentTypeDescription
                                           FROM TREATMENT_TYPE JOIN TREATMENTS
                                                ON TREATMENT_TYPE.TreatmentTypeCode = TREATMENTS.TreatmentTypeCode
                                          WHERE TREATMENTS.TreatmentID = '$thisTreatmentID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $treatmentRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                            
                            $treatmentName = $treatmentRow['TreatmentName'];
                            $treatmentDescr = $treatmentRow['TreatmentTypeDescription'];
                            
                            echo "<tr style='border: 1px solid black'>";
                            echo "<td>" . $row['TreatmentID'] . "</td>";
                            echo "<td>" . $dog . "</td>";
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
                <br><br>
                <a href="index.html"><button id="resetButton" type="button">Back to Main Menu</button></a>
            
            
            
            </div>
        </div>
        
        <script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="js/dogShelter.js"></script>
        
    </body>
</html>