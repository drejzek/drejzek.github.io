<?php

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
                while($i = mysqli_fetch_array($res))
                {
                    $inner_sql = "SELECT * FROM places_addresses WHERE place_id = " . $i['id'];
                    $inner_res = mysqli_query($con, $inner_sql);
                    if($inner_res->num_rows == 0)
                    {
                       $status_addresses[$i['id']] = 0;
                    }
                    else
                    {
                        $status_addresses[$i['id']] = 1;
                    }
                }
                while($i = mysqli_fetch_array($res))
                {
                    $inner_sql = "SELECT * FROM places_security WHERE place_id = " . $i['id'];
                    $inner_res = mysqli_query($con, $inner_sql);
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