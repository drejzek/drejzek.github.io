<?php

require '../../config.php';
// require 'class.maintence.php';

class Maintence
{
    private $mode = null;
    public $status_addresses = [];
    public $status_security = [];

    function __construct($mode)
    {
        $this->mode = $mode;
    }

    function start()
    {
        global $con;
        switch($this->mode){
            case 1:
                $sql = "SELECT * FROM places";
                $res = mysqli_query($con, $sql);

                $sql1 = "SELECT * FROM places";
                $res1 = mysqli_query($con, $sql1);
                while($i = mysqli_fetch_array($res))
                {
                    $inner_sql = "SELECT * FROM places_addresses WHERE place_id = " . $i['id'];
                    $inner_res = mysqli_query($con, $inner_sql);
                    echo $i['id'] . "<br>";
                    if($inner_res->num_rows == 0)
                    {
                       $status_addresses[$i['id']] = 0;
                    }
                    else
                    {
                        $status_addresses[$i['id']] = 1;
                    }
                }
                while($i = mysqli_fetch_array($res1))
                {
                    $inner_sql = "SELECT * FROM places_security WHERE place_id = " . $i['id'];
                    $inner_res = mysqli_query($con, $inner_sql);
                    echo $i['id'] . "<br>";
                    if($inner_res->num_rows == 0)
                    {
                       $status_security[$i['id']] = 0;
                    }
                    else
                    {
                       $status_security[$i['id']] = 1;
                    }
                }
            break;
        }
    }
}

$m = new Maintence(1);
$m->start();
print_r($m->status_addresses);
print_r($m->status_security);