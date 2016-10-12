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
        <title>Update Person</title> 
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
                
                $personID = $_POST['personID'];
                $firstName = $_POST['personFirstName'];
                $lastName = $_POST['personLastName'];
                $street = $_POST['personStreet'];
                $city = $_POST['personCity'];
                $state = $_POST['personState'];
                $zip = $_POST['personZip'];
                $email = $_POST['personEmail'];
                $phone = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $_POST['personPhone']);
                $type = $_POST['personType'];
                
                //Check to see if the dog is already in the database
                $checksql = "SELECT * FROM PERSON 
                             WHERE PersonID = '$personID'";
                $checkDB = $pdo->prepare($checksql);
                $checkDB->execute();
                $personRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                $count = $checkDB->rowCount();
                
                if (($count > 0) && ($personRow['PersonID']==$personID))  {
                    $sql ="UPDATE PERSON
                            SET PersonFirstName='$firstName', 
                                PersonStreet='$street', 
                                PersonLastName='$lastName', 
                                PersonCity='$city', 
                                PersonState='$state', 
                                PersonZipCode='$zip', 
                                PersonEmail='$email', 
                                PersonPhone='$phone', 
                                PersonTypeCode='$type'
                            WHERE PersonID='$personID'";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                }
                else {
                    //Insert the person into the database
                    $sql = "INSERT INTO PERSON (PersonID, PersonFirstName, PersonStreet, PersonLastName, PersonCity, PersonState,  PersonZipCode, PersonEmail, PersonPhone, PersonTypeCode)
                            VALUES 
                            ('$personID', '$firstName', '$street', '$lastName', '$city', '$state', '$zip', '$email', '$phone', '$type')";
                    $insDB = $pdo->prepare($sql);
                    $insDB->execute();
                    
                    echo "<h3>".$firstName." ".$lastName." was successfully added to our database.</h3>";
                }
                
                $sql = "SELECT * 
                          FROM PERSON 
                         WHERE PersonID = '$personID'";
                $getDB = $pdo->prepare($sql);
                $getDB->execute();
                $personRow = $getDB->fetch(PDO::FETCH_ASSOC);
                
                $thisPersonID = $personRow['PersonID'];
                
                $checksql = "SELECT PersonTypeDescription
                               FROM PERSON_TYPE JOIN PERSON
                                    ON PERSON_TYPE.PersonTypeCode = PERSON.PersonTypeCode
                              WHERE PERSON.PersonID = '$thisPersonID'";
                $checkDB = $pdo->prepare($checksql);
                $checkDB->execute();
                $personTypeRow = $checkDB->fetch(PDO::FETCH_ASSOC);
            }
        ?>
                <h2><?php echo $personRow['PersonFirstName']." ".$personRow['PersonLastName'] ?></h2>
                
                <table class="centerTable" style="border: 2px solid black;">
                    <tr style="border: 2px solid black">
                        <th>PersonID</th>
                        <th>Street Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip Code</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                    </tr>
                    <tr>
                        <td><?php echo $personRow['PersonID']; ?></td>
                        <td><?php echo $personRow['PersonStreet']; ?></td>
                        <td><?php echo $personRow['PersonCity']; ?></td>
                        <td><?php echo $personRow['PersonState']; ?></td>
                        <td><?php echo $personRow['PersonZipCode']; ?></td>
                        <td><?php echo $personRow['PersonEmail']; ?></td>
                        <td><?php echo $personRow['PersonPhone']; ?></td>
                        <td><?php echo $personTypeRow['PersonTypeDescription']; ?></td>
                    </tr>
                </table>
                <br>
                <a href="index.html"><button id="submitButton" type="button">Back to Main Menu</button></a>
                <br><br>
                <form method="post" action="viewPeople.php">
                        <input type="hidden" name="deletePerson" value="1">
                        <input type="hidden" name="personID" value="<?php echo $personRow['PersonID']; ?>">
                        <input type="submit" id="resetButton" name="submit" value="Delete <?php echo $personRow['PersonFirstName']; ?>">
                </form>

                <br><br>
                
                <hr>
                
                <form action="updatePerson.php" method="post">
                    <h2>Update <?php echo $personRow['PersonFirstName']; ?>'s Information:</h2>
                    <table class="centerTable">
                        <tr>
                            <td class="alignRight"><label for="personFirstName">FIRST NAME:</label></td>
                            <td class="alignLeft"><input id="personFirstName" type="text" name="personFirstName" maxlength="25" value="<?php echo $personRow['PersonFirstName']; ?>" required /></td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personLastName">LAST NAME:</label></td>
                            <td class="alignLeft"><input id="personLastName" type="text" name="personLastName" maxlength="25"  value="<?php echo $personRow['PersonLastName']; ?>" required /></td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personStreet">STREET ADDRESS:</label></td>
                            <td class="alignLeft"><input id="personStreet" type="text" name="personStreet" maxlength="50"  value="<?php echo $personRow['PersonStreet']; ?>" required /></td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personCity">CITY:</label></td>
                            <td class="alignLeft"><input id="personCity" type="text" name="personCity" maxlength="25"  value="<?php echo $personRow['PersonCity']; ?>" required /></td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personState">STATE:</label></td>
                            <td class="alignLeft">
                                <select id="personState" name="personState" size="1">
                                  <option value="AL">Alabama</option>
                                  <option value="AK">Alaska</option>
                                  <option value="AZ">Arizona</option>
                                  <option value="AR">Arkansas</option>
                                  <option value="CA">California</option>
                                  <option value="CO">Colorado</option>
                                  <option value="CT">Connecticut</option>
                                  <option value="DE">Delaware</option>
                                  <option value="DC">Dist of Columbia</option>
                                  <option value="FL">Florida</option>
                                  <option value="GA" selected>Georgia</option>
                                  <option value="HI">Hawaii</option>
                                  <option value="ID">Idaho</option>
                                  <option value="IL">Illinois</option>
                                  <option value="IN">Indiana</option>
                                  <option value="IA">Iowa</option>
                                  <option value="KS">Kansas</option>
                                  <option value="KY">Kentucky</option>
                                  <option value="LA">Louisiana</option>
                                  <option value="ME">Maine</option>
                                  <option value="MD">Maryland</option>
                                  <option value="MA">Massachusetts</option>
                                  <option value="MI">Michigan</option>
                                  <option value="MN">Minnesota</option>
                                  <option value="MS">Mississippi</option>
                                  <option value="MO">Missouri</option>
                                  <option value="MT">Montana</option>
                                  <option value="NE">Nebraska</option>
                                  <option value="NV">Nevada</option>
                                  <option value="NH">New Hampshire</option>
                                  <option value="NJ">New Jersey</option>
                                  <option value="NM">New Mexico</option>
                                  <option value="NY">New York</option>
                                  <option value="NC">North Carolina</option>
                                  <option value="ND">North Dakota</option>
                                  <option value="OH">Ohio</option>
                                  <option value="OK">Oklahoma</option>
                                  <option value="OR">Oregon</option>
                                  <option value="PA">Pennsylvania</option>
                                  <option value="RI">Rhode Island</option>
                                  <option value="SC">South Carolina</option>
                                  <option value="SD">South Dakota</option>
                                  <option value="TN">Tennessee</option>
                                  <option value="TX">Texas</option>
                                  <option value="UT">Utah</option>
                                  <option value="VT">Vermont</option>
                                  <option value="VA">Virginia</option>
                                  <option value="WA">Washington</option>
                                  <option value="WV">West Virginia</option>
                                  <option value="WI">Wisconsin</option>
                                  <option value="WY">Wyoming</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personZip">ZIP CODE:</label></td>
                            <td class="alignLeft"><input id="personZip" type="text" name="personZip" maxlength="5"  value="<?php echo $personRow['PersonZipCode']; ?>" required /></td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personEmail">EMAIL:</label></td>
                            <td class="alignLeft"><input id="personEmail" type="email" name="personEmail" maxlength="50"  value="<?php echo $personRow['PersonEmail']; ?>" /></td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personPhone">PHONE:</label></td>
                            <td class="alignLeft"><input id="personPhone" type="tel" name="personPhone" maxlength="14"  value="<?php echo $personRow['PersonPhone']; ?>" required /></td>
                        </tr>
                        <tr>
                            <td class="alignRight"><label for="personType">ROLE:</label></td>
                            <td class="alignLeft">
                                <select id="personType" name="personType" size="1" required>
                                  <option value="ADP" selected>Adopter</option>
                                  <option value="FUL">Full Time Employee</option>
                                  <option value="PAR">Part Time Employee</option>
                                  <option value="VOL">Volunteer</option>
                                  <option value="FTV">Full Time Veterinarian</option>
                                  <option value="OCV">On Call Veterinarian</option>
                                  <option value="OWN">Owner</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <input type="hidden" name="form_submitted" value="1">
                    <input type="hidden" name="personID" value="<?php echo $personRow['PersonID']; ?>">
                    <input id="submitButton" type="submit" name="submit" value="Update Person">
                    <button id="resetButton" type="reset" value="Reset">Reset Form</button>
                    <a href="index.html"><button id="resetButton" type="button">Back to Main Menu</button></a>
                </form>
                </div>
            </div>
        </div>
    </body>
</html>