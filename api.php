<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if(isset($_GET['r'])) {
            $output = "";
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
        else{
            header("HTTP/1.1 404 Not Found");
        }
        break;
}








?>