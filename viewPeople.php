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
        <title>View People</title> 
        <!--[if lt IE 9]> <script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.js"></script> <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script><![endif]-->
        <link href="styles.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
            <div>
                <h1>Team 6 Shelter - Our People</h1>
                
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
                
                    $personID = $_POST['personID'];
                
                    if($_POST['deletePerson'] == 1) {
                        
                        $checksql = "DELETE FROM `PERSON` 
                                      WHERE `PersonID` = '$personID'";
                        $checkDB = $pdo->prepare($checksql);
                        $checkDB->execute();
                    }
                
                    $checksql = "SELECT * FROM PERSON";
                    $checkDB = $pdo->prepare($checksql);
                    $checkDB->execute();
                    $personRows = $checkDB->fetchAll(PDO::FETCH_ASSOC);
                ?>
                
                <table class="centerTable" style="border: 2px solid black;">
                    <tr style="border: 2px solid black">
                        <th>PersonID</th>
                        <th>Name</th>
                        <th>Street Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip Code</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                    </tr>
                    
                    <?php
                    
                        foreach($personRows as $row) {
                            
                            $thisPersonID = $row['PersonID'];
                            
                            $checksql = "SELECT PersonTypeDescription
                                           FROM PERSON_TYPE JOIN PERSON
                                                ON PERSON_TYPE.PersonTypeCode = PERSON.PersonTypeCode
                                          WHERE PERSON.PersonID = '$thisPersonID'";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $personTypeRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                            
                            $role = $personTypeRow['PersonTypeDescription'];
                            
                            echo "<tr style='border: 1px solid black'>";
                            echo "<td>" . $row['PersonID'] . "</td>";
                            echo "<td>";
                                echo "<form method='post' action='updatePerson.php'>";
                                    echo "<input type='hidden' name='form_submitted' value='1'>";
                                    echo "<input type='hidden' name='personID' value='" . $row['PersonID'] . "'>";
                                    echo "<input type='hidden' name='personFirstName' value='". $row['PersonFirstName'] . "'>";
                                    echo "<input type='hidden' name='personLastName' value='". $row['PersonLastName'] . "'>";
                                    echo "<input type='hidden' name='personStreet' value='". $row['PersonStreet'] . "'>";
                                    echo "<input type='hidden' name='personCity' value='". $row['PersonCity'] . "'>";
                                    echo "<input type='hidden' name='personState' value='". $row['PersonState'] . "'>";
                                    echo "<input type='hidden' name='personZip' value='". $row['PersonZipCode'] . "'>";
                                    echo "<input type='hidden' name='personEmail' value='". $row['PersonEmail'] . "'>";
                                    echo "<input type='hidden' name='personPhone' value='". $row['PersonPhone'] . "'>";
                                    echo "<input type='hidden' name='personZip' value='". $row['PersonZipCode'] . "'>";
                                    echo "<input type='hidden' name='personType' value='". $row['PersonTypeCode'] . "'>";
                                    echo "<input id='personUpdateButton' type='submit' name='submit' value='". $row['PersonFirstName'] . " " . $row['PersonLastName'] . "'>";
                                echo "</form>";
                            echo "</td>";
                            echo "<td>" . $row['PersonStreet'] . "</td>";
                            echo "<td>" . $row['PersonCity'] . "</td>";
                            echo "<td>" . $row['PersonState'] . "</td>";
                            echo "<td>" . $row['PersonZipCode'] . "</td>";
                            echo "<td>" . $row['PersonEmail'] . "</td>";
                            echo "<td>" . $row['PersonPhone'] . "</td>";
                            echo "<td>" . $role . "</td>";
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