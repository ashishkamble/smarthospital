<?php
    //Connect to database
    require('connectDB.php');
//**********************************************************************************************
    //Get current date and time
    date_default_timezone_set('Asia/Kolkata');
    $d = date("Y-m-d");
    $t = date("h:i:s");
//**********************************************************************************************
    $Tarrive = mktime(1,15,00);
    $TimeArrive = date("H:i:sa", $Tarrive);
//**********************************************************************************************   
    $Tleft = mktime(02,30,00);
    $Timeleft = date("H:i:sa", $Tleft);
//**********************************************************************************************
    
if(!empty($_GET['CardID']))
    {
       $Card = $_GET['CardID'];
       $sql = "SELECT * FROM users WHERE CardID='$Card'";
       $result = mysqli_query($conn,$sql);
       
       if (mysqli_num_rows($result) > 0 )
        { 
            $row = mysqli_fetch_assoc($result);

            if (!empty($row['username']) && !empty($row['SerialNumber'])) 
                {
                    
                $sqll = "SELECT * FROM logs WHERE CardNumber='$Card' AND DateLog=CURDATE()";
                $resultl = mysqli_query($conn,$sqll);

                $rowl = mysqli_fetch_assoc($resultl);

                if ( mysqli_num_rows($resultl) > 0 )
                    {   
                        if ($t >= $Timeleft && $rowl['TimeIn'] <= $TimeArrive) 
                                {
                                $UserStat = "Arrived and Left on time";
                                }
                        elseif ($t < $Timeleft && $rowl['TimeIn'] > $TimeArrive)
                                {   
                                $UserStat = "Arrived late and Left early";
                                }
                        elseif ($t < $Timeleft && $rowl['TimeIn'] <= $TimeArrive) 
                                {
                                $UserStat = "Arrived on time and Left early";
                                }
                        elseif ($t >= $Timeleft && $rowl['TimeIn'] > $TimeArrive) 
                                {
                                $UserStat = "Arrived late and Left on time";
                                }

                        $sqlll="UPDATE logs SET TimeOut=CURTIME(), UserStat ='$UserStat' WHERE CardNumber='$Card' AND DateLog=CURDATE()";
                        if ($conn->query($sqlll) === true)
                            {
                            echo "logout";
                            }
                    }
                //*******************************************************************************
                else
                    {
                    if ($t <= $TimeArrive) 
                        {
                        $UserStat = "Arrived on time";
                        }
                    else
                        {
                        $UserStat = "Arrived late";
                        }
                    $Uname = $row['username'];
                    $Number = $row['SerialNumber'];

$sqll = "INSERT INTO logs (CardNumber, Name, SerialNumber, DateLog, TimeIn, UserStat) "
                . "VALUES ('$Card' ,'$Uname', '$Number', CURDATE(), CURTIME(), '$UserStat')";
                    if ($conn->query($sqll) === true)
                        {
                        echo "login";
                        }
                    } 
                }
            //**********************************************************************************
            else
                {
            echo "Cardavailable";
                }
        }
//**********************************************************************************************
        else 
        {           
        $sql = "INSERT INTO users (CardID) " . "VALUES ('$Card')";
    
        if ($conn->query($sql) === true)
            {
                echo "succesful";
            }
        }
    }
    else{
    	echo "Empty Card ID";
    }
?>