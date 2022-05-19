<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require_once 'config/config.php';
header('Content-Type: application/json; charset=utf-8');
if (1===2){ //($_GET['api_key'] === $api_key){
    header("HTTP/1.1 401 Unauthorized");
}
else{
    session_start();
    switch ($_SERVER['REQUEST_METHOD']) {
        case "GET":
            if(isset($_GET['r'])) {
                $output = "";
                header("HTTP/1.1 200 OK");
                $r = $_GET['r'];
                $x = "initX1;initX1d;initX2;initX2d;0";
                if (isset($_SESSION['x'])) {
                    $x = $_SESSION['x'];
                }
                exec('octave-cli --eval "pkg load control; m1 = 2500; m2 = 320;
            k1 = 80000; k2 = 500000;
            b1 = 350; b2 = 15020;
            A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];
            B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];
            C=[0 0 1 0]; D=[0 0];
            Aa = [[A,[0 0 0 0]\'];[C, 0]];
            Ba = [B;[0 0]];
            Ca = [C,0]; Da = D;
            K = [0 2.3e6 5e8 0 8e6];
            sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);
            
            t = 0:0.01:5;
            r =' . $r . ';
            initX1=0;
            initX1d=0;
            initX2=0;
            initX2d=0;
            [y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[' . $x . ']);new = x(size(x,1),:); [x(:,1),x(:,3)]"', $output);

                $string;
                $x1 = [];
                $x2 = [];
                foreach ($output as $o) {
                    $string = str_replace("   ", " ", $o);
                    $string = str_replace("  ", " ", $string);
                    $string = trim($string);
                    $string = explode(' ', $string, 2);
                    if ($string[0] !== 'ans' && count($string) == 2) {
                        $x1[] = (float)$string[0];
                        $x2[] = (float)$string[1];
                    }
                }
                $values = array('x1' => $x1, 'x2' => $x2);
                $output = '';
                exec('octave-cli --eval "pkg load control; m1 = 2500; m2 = 320;
            k1 = 80000; k2 = 500000;
            b1 = 350; b2 = 15020;
            A=[0 1 0 0;-(b1*b2)/(m1*m2) 0 ((b1/m1)*((b1/m1)+(b1/m2)+(b2/m2)))-(k1/m1) -(b1/m1);b2/m2 0 -((b1/m1)+(b1/m2)+(b2/m2)) 1;k2/m2 0 -((k1/m1)+(k1/m2)+(k2/m2)) 0];
            B=[0 0;1/m1 (b1*b2)/(m1*m2);0 -(b2/m2);(1/m1)+(1/m2) -(k2/m2)];
            C=[0 0 1 0]; D=[0 0];
            Aa = [[A,[0 0 0 0]\'];[C, 0]];
            Ba = [B;[0 0]];
            Ca = [C,0]; Da = D;
            K = [0 2.3e6 5e8 0 8e6];
            sys = ss(Aa-Ba(:,1)*K,Ba,Ca,Da);
            
            t = 0:0.01:5;
            r =' . $r . ';
            initX1=0;
            initX1d=0;
            initX2=0;
            initX2d=0;
            [y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t,[' . $x . ']);x(size(x,1),:)"', $output);
                $string = str_replace("   ", " ", $output[2]);
                $string = str_replace("  ", " ", $string);
                $string = trim($string);
                $_SESSION['x'] = $string;
                echo json_encode($values);
            }
            elseif(isset($_GET['input'])){
                $output = '';
                exec('octave-cli --eval "pkg load control;'.$_GET['input'].'"',$output);
                header("HTTP/1.1 200 OK");
                $answer = implode('', $output);
                $success = 1;
                if(strlen($answer)<1){
                    $success = 0;
                }
                $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $con->prepare("INSERT INTO logs (command, success) VALUES (:command, :success)");
                $stmt->bindParam(":command", $_GET['input']);
                $stmt->bindParam(":success", $success);
                $stmt->execute();
                echo json_encode(array("output"=>$answer));
            }
            elseif(isset($_GET['email'])){

                $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $con->prepare("SELECT command, created_at, success from logs");
                $stmt->execute();

                $filename = "logs.csv";
                $delimiter = ",";

                $f = fopen($filename, 'w');
                ftruncate($f, 0);

                $fields = array('command','created_at','success');
                fputcsv($f, $fields, $delimiter);

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $status = ($row['success'] == 1)?'successful':'unsuccessful';
                    $lineData = array($row['command'], $row['created_at'], $status);
                    fputcsv($f, $lineData, $delimiter);
                }
                 $mail = new PHPMailer(true);
                 try {
                    //Server settings
                     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                     $mail->isSMTP();                                            //Send using SMTP
                     $mail->Host       = 'smtp.azet.sk';                     //Set the SMTP server to send through
                     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                     $mail->Username   = 'webtech2022@azet.sk';                     //SMTP username
                     $mail->Password   = 'hesloheslo';                               //SMTP password
                     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                     $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                     //Recipients
                     $mail->setFrom('webtech2022@azet.sk');
                     $mail->addAddress($email);     //Add a recipient

                     //Attachments
                     $mail->addAttachment('logs.csv');         //Add attachments

                     //Content
                     $mail->isHTML(false);                                  //Set email format to HTML
                     $mail->Subject = 'Logs';
                     $mail->Body    = 'Logs in csv file.';
                     $mail->Send();
                     echo 'Message has been sent';
                 } catch (Exception $e) {
                     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                 }


            }
            else{
                header("HTTP/1.1 404 Not Found");
            }
            break;
    }
}
?>