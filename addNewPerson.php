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
        <meta name=author content="Team 6"> 
        <meta name=description content="Database Systems Team 6 Final Project"> 
        <title>Add New Person</title> 
        <!--[if lt IE 9]> <script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.js"></script> <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script><![endif]-->
        <link href="styles.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
            <div>
                <h1>Add New Person</h1>
                
                <div class="formContainer">
                    <form action="updatePerson.php" method="post">
                        <table class="centerTable">
                            <tr>
                                <td class="alignRight"><label for="personFirstName">FIRST NAME:</label></td>
                                <td class="alignLeft"><input id="personFirstName" type="text" name="personFirstName" maxlength="25" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="personLastName">LAST NAME:</label></td>
                                <td class="alignLeft"><input id="personLastName" type="text" name="personLastName" maxlength="25" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="personStreet">STREET ADDRESS:</label></td>
                                <td class="alignLeft"><input id="personStreet" type="text" name="personStreet" maxlength="50" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="personCity">CITY:</label></td>
                                <td class="alignLeft"><input id="personCity" type="text" name="personCity" maxlength="25" required /></td>
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
                                <td class="alignLeft"><input id="personZip" type="text" name="personZip" maxlength="5" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="personEmail">EMAIL:</label></td>
                                <td class="alignLeft"><input id="personEmail" type="email" name="personEmail" maxlength="50" /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="personPhone">PHONE:</label></td>
                                <td class="alignLeft"><input id="personPhone" type="tel" name="personPhone" maxlength="14" required /></td>
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
                        
                            $checksql = "SELECT PersonID
                                           FROM PERSON
                                       ORDER BY PersonID DESC LIMIT 1";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $personIDRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                        
                            $newID = $personIDRow['PersonID'] + 1;
                        ?>
                        
                        <input type="hidden" name="form_submitted" value="1">
                        <input type="hidden" name="personID" value="<?php echo $newID; ?>">
                        <input id="submitButton" type="submit" name="submit" value="Submit Person"></button>
                        <button id="resetButton" type="reset" value="Reset">Reset Form</button>
                        <a href="index.html"><button id="resetButton" type="button">Back to Main Menu</button></a>
                    </form>
                </div>
            </div>
        </div>
        
        <script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="js/dogShelter.js"></script>
        
    </body>
</html>