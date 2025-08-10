<?php  
//Connect to database
require 'connectDB.php';

// select passenger 
if (isset($_GET['select'])) {

    $Finger_id = $_GET['Finger_id'];

    $sql = "SELECT fingerprint_select FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select";
        exit();
    } else {
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {

            $sql = "UPDATE users SET fingerprint_select=0";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_Select";
                exit();
            } else {
                mysqli_stmt_execute($result);

                $sql = "UPDATE users SET fingerprint_select=1 WHERE fingerprint_id=?";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_select_Fingerprint";
                    exit();
                } else {
                    mysqli_stmt_bind_param($result, "s", $Finger_id);
                    mysqli_stmt_execute($result);

                    echo "User Fingerprint selected";
                    exit();
                }
            }
        } else {
            $sql = "UPDATE users SET fingerprint_select=1 WHERE fingerprint_id=?";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_select_Fingerprint";
                exit();
            } else {
                mysqli_stmt_bind_param($result, "s", $Finger_id);
                mysqli_stmt_execute($result);

                echo "User Fingerprint selected";
                exit();
            }
        }
    } 
}
if (isset($_POST['Add'])) {
     
    $Uname = $_POST['name'];
    $Number = $_POST['number'];
    $Email= $_POST['email'];

    //optional
    $Timein = $_POST['timein'];
    $Gender= $_POST['gender'];

    //check if there any selected user
    $sql = "SELECT username FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
      echo "SQL_Error";
      exit();
    } else {
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {

            if ($row['username']=="Name") {

                if (!empty($Uname) && !empty($Number) && !empty($Email)) {
                    //check if there any user had already the Serial Number
                    $sql = "SELECT serialnumber FROM users WHERE serialnumber=?";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    } else {
                        mysqli_stmt_bind_param($result, "d", $Number);
                        mysqli_stmt_execute($result);
                        $resultl = mysqli_stmt_get_result($result);
                        if (!$row = mysqli_fetch_assoc($resultl)) {
                            $sql="UPDATE users SET username=?, serialnumber=?, gender=?, email=?, user_date=CURDATE(), time_in=? WHERE fingerprint_select=1";
                            $result = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($result, $sql)) {
                                echo "SQL_Error_select_Fingerprint";
                                exit();
                            } else {
                                mysqli_stmt_bind_param($result, "sdsss", $Uname, $Number, $Gender, $Email, $Timein );
                                mysqli_stmt_execute($result);

                                echo "A new User has been added!";
                                exit();
                            }
                        } else {
                            echo "The serial number is already taken!";
                            exit();
                        }
                    }
                } else {
                    echo "Empty Fields";
                    exit();
                }
            } else {
                echo "This Fingerprint is already added";
                exit();
            }    
        } else {
            echo "There's no selected Fingerprint!";
            exit();
        }
    }
}
// Add user Fingerprint
if (isset($_POST['Add_fingerID'])) {

    $fingerid = $_POST['fingerid'];

    // Default values
    $Uname = "Name";
    $Number = "000000";
    $Email = "Email";
    $Timein = "00:00:00";
    $Gender = "Gender";

    if ($fingerid == 0) {
        echo "Enter a Fingerprint ID!";
        exit();
    } else {
        if ($fingerid > 0 && $fingerid < 128) {
            // Check if the fingerprint ID already exists
            $sql = "SELECT fingerprint_id FROM users WHERE fingerprint_id=?";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error";
                exit();
            } else {
                mysqli_stmt_bind_param($result, "i", $fingerid);
                mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
                if (!$row = mysqli_fetch_assoc($resultl)) {
                    // Insert new fingerprint
                    $sql = "INSERT INTO users (username, serialnumber, gender, email, fingerprint_id, fingerprint_select, user_date, time_in, del_fingerid, add_fingerid) VALUES (?, ?, ?, ?, ?, 0, CURDATE(), ?, 0, 1)";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    } else {
                        mysqli_stmt_bind_param($result, "sdssis", $Uname, $Number, $Gender, $Email, $fingerid, $Timein);
                        mysqli_stmt_execute($result);
                        echo "The ID is ready to get a new Fingerprint";
                    }
                } else {
                    echo "This ID already exists! Delete it from the scanner";
                }
            }
        } else {
            echo "The Fingerprint ID must be between 1 & 127";
        }
    }
}
// Update an existing user 
if (isset($_POST['Update'])) {

    $Uname = $_POST['name'];
    $Number = $_POST['number'];
    $Email= $_POST['email'];

    //optional
    $Timein = $_POST['timein'];
    $Gender= $_POST['gender'];

    if ($Number == 0) {
        $Number = -1;
    }
    //check if there any selected user
    $sql = "SELECT * FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
      echo "SQL_Error";
      exit();
    } else {
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {

            if (empty($row['username'])) {
                echo "First, You need to add the User!";
                exit();
            } else {
                if (empty($Uname) && empty($Number) && empty($Email) && empty($Timein)) {
                    echo "Empty Fields";
                    exit();
                } else {
                    //check if there any user had already the Serial Number
                    $sql = "SELECT serialnumber FROM users WHERE serialnumber=?";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    } else {
                        mysqli_stmt_bind_param($result, "d", $Number);
                        mysqli_stmt_execute($result);
                        $resultl = mysqli_stmt_get_result($result);
                        if (!$row = mysqli_fetch_assoc($resultl)) {

                            if (!empty($Uname) && !empty($Email) && !empty($Timein)) {

                                $sql="UPDATE users SET username=?, serialnumber=?, gender=?, email=?, time_in=? WHERE fingerprint_select=1";
                                $result = mysqli_stmt_init($conn);
                                if (!mysqli_stmt_prepare($result, $sql)) {
                                    echo "SQL_Error_select_Fingerprint";
                                    exit();
                                } else {
                                    mysqli_stmt_bind_param($result, "sdsss", $Uname, $Number, $Gender, $Email, $Timein );
                                    mysqli_stmt_execute($result);

                                    echo "The selected User has been updated!";
                                    exit();
                                }
                            } else {
                                if (!empty($Timein)) {
                                    $sql="UPDATE users SET gender=?, time_in=? WHERE fingerprint_select=1";
                                    $result = mysqli_stmt_init($conn);
                                    if (!mysqli_stmt_prepare($result, $sql)) {
                                        echo "SQL_Error_select_Fingerprint";
                                        exit();
                                    } else {
                                        mysqli_stmt_bind_param($result, "ss", $Gender, $Timein );
                                        mysqli_stmt_execute($result);

                                        echo "The selected User has been updated!";
                                        exit();
                                    }
                                } else {
                                    echo "The User Time-In is empty!";
                                    exit();
                                }    
                            }  
                        } else {
                            echo "The serial number is already taken!";
                            exit();
                        }
                    }
                }
            }    
        } else {
            echo "There's no selected User to update!";
            exit();
        }
    }
}
// delete user 
if (isset($_POST['delete'])) {

    $sql = "SELECT fingerprint_select FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select";
        exit();
    } else {
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {
            $sql = "DELETE FROM users WHERE fingerprint_select=1";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_Delete";
                exit();
            } else {
                mysqli_stmt_execute($result);
                echo "User deleted";
                exit();
            }
        } else {
            echo "No user selected!";
            exit();
        }
    }
}

// Clear fingerprints
if (isset($_POST['Clear'])) {

    $sql = "DELETE FROM users";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Clear";
        exit();
    } else {
        mysqli_stmt_execute($result);
        echo "All fingerprints cleared";
        exit();
    }
}

// Set fingerprint template
if (isset($_POST['Set_template'])) {
    
    $template_id = $_POST['template_id'];

    if ($template_id == 0) {
        echo "Enter a Template ID!";
        exit();
    } else {
        if ($template_id > 0 && $template_id < 128) {
            // Check if the template ID already exists
            $sql = "SELECT template_id FROM users WHERE template_id=?";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error";
                exit();
            } else {
                mysqli_stmt_bind_param($result, "i", $template_id);
                mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
                if (!$row = mysqli_fetch_assoc($resultl)) {
                    // Insert new template
                    $sql = "INSERT INTO users (template_id, user_date, template_set) VALUES (?, CURDATE(), 1)";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    } else {
                        mysqli_stmt_bind_param($result, "i", $template_id);
                        mysqli_stmt_execute($result);
                        echo "Template ID set";
                    }
                } else {
                    echo "This Template ID already exists!";
                }
            }
        } else {
            echo "The Template ID must be between 1 & 127";
        }
    }
}

?>

