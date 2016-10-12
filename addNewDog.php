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
        <title>Add New Dog</title> 
        <!--[if lt IE 9]> <script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.js"></script> <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script><![endif]-->
        <link href="styles.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
            <div>
                <h1>Add New Dog</h1>
                
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
                
                ?>
                
                <div class="formContainer">
                    <form action="updateDogs.php" method="post">
                        <table class="centerTable">
                            <tr>
                                <td class="alignRight"><label for="dogArrivalDate">ARRIVAL DATE:</label></td>
                                <td class="alignLeft"><input id="dogArrivalDate" type="date" name="dogArrivalDate" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="dogName">NAME:</label></td>
                                <td class="alignLeft"><input id="dogName" type="text" name="dogName" maxlength="25" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="dogDOB">DOB (if known):</label></td>
                                <td class="alignLeft"><input id="dogDOB" type="date" name="dogDOB" /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="dogAge">AGE:</label></td>
                                <td class="alignLeft"><input id="dogAge" type="number" name="dogAge" min="0" max="20" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="dogGender">GENDER:</label></td>
                                <td class="alignLeft">
                                    <select id="dogGender" name="dogGender">
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
                                <td class="alignLeft"><input id="dogWeight" type="number" name="dogWeight" min="1" max="250" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="dogHeight">HEIGHT (inches):</label></td>
                                <td class="alignLeft"><input id="dogHeight" type="number" name="dogHeight" min="1" max="84" required /></td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="dogDescriptionText">DESCRIPTION:</label></td>
                                <td class="alignLeft">
                                    <textarea id="dogDescriptionText" name="dogDescription" maxlength="300" ></textarea>
                                    <div id="textarea_feedback"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="alignRight"><label for="newEnclosureID">ENCLOSURE:</label></td>
                                <td class="alignLeft">
                                    <select id="newEnclosureID" name="newEnclosureID" size="1">
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
                        </table>
                        <br><br>
                        
                        <?php 
                            $checksql = "SELECT DogID
                                           FROM DOG
                                       ORDER BY DogID DESC LIMIT 1";
                            $checkDB = $pdo->prepare($checksql);
                            $checkDB->execute();
                            $dogIDRow = $checkDB->fetch(PDO::FETCH_ASSOC);
                        
                            $newID = $dogIDRow['DogID'] + 1;
                        ?>
                        
                        <input type="hidden" name="form_submitted" value="1">
                        <input type="hidden" name="dogID" value="<?php echo $newID; ?>">
                        <input type="hidden" name="dogAdoptionPrice" value="50">
                        <input id="submitButton" type="submit" name="submit" value="Submit Dog">
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