<?php

//require_once '../config.php';

class Data
{
    private $id = null;
    public $rtrn = [];

    function __construct($id)
    {
        $this->id = $id;
    }

    // UPDATE METHODS
    function UpdateGeneral($name, $ableToUrbex, $wasExplored, $identifier, $shortcut)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
                "UPDATE `places` SET
                `name`='$name',
                `able_to_urbex`='$ableToUrbex',
                `was_explored`='$wasExplored',
                `identifier`='$identifier',
                `shortcut`='$shortcut'
                WHERE id = " . $this->id;
            return $result = mysqli_query($con, $sql);
        }
    }

    function UpdateMap($coordinates, $species, $status)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
                "UPDATE `places` SET
                `coordinates`='$coordinates',
                `species`='$species',            
                `status`='$status'
                WHERE id = " . $this->id;
            return $result = mysqli_query($con, $sql);
        }
    }

    function UpdateImg($img)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
                "UPDATE `places` SET
                `header_img_path`='$img'
                WHERE id = " . $this->id;
            return $result = mysqli_query($con, $sql);
        }
    }

    function UpdateSecurity($overall_status = '', $statistics = '', $neighbours = '', $catchnum = '', $entrance_difficulty = '', $cctv = '', $security_sys = '', $security = '', $dogs = '', $homelessOrDrugsMen = '', $paranormal = '')
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
                "UPDATE 
                `places_security` SET 
                `overall_status`= '$overall_status',
                `statics`= '$statistics',
                `neighbours`= '$neighbours',
                `catchnum`= '$catchnum',
                `entramce_difficulty`= '$entrance_difficulty',
                `cctv`= '$cctv',
                `security_sys`= '$security_sys',
                `security`= '$security',
                `dogs`= '$dogs',
                `homelessOrDrugsMen`= '$homelessOrDrugsMen',
                `paranormal`= '$paranormal'
                WHERE place_id = " . $this->id;

            return mysqli_query($con, $sql);
        }
    }
    
    function UpdateAddress($street, $streetnum, $city, $country, $zip_code)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
            "UPDATE `places_addresses` SET 
            `street`='$street',
            `street_num`='$streetnum',
            `city`='$city',
            `country`='$country',
            `zip_code`='$zip_code'
            WHERE place_id = " . $this->id;

            return mysqli_query($con, $sql);
        }
    }

    
    function UpdateOwner($ownerID)
    {
        global $con;
        if($this->id != null)
        {
            $sql = "UPDATE `places` SET `owner_id`='$ownerID' WHERE id = " . $this->id;
            $result = mysqli_query($con, $sql);
            return $result;
        }
    }

    function UpdatePlaceDesc($desc)
    {
        global $con;
        if($this->id != null)
        {
            $sql = "UPDATE `places` SET `descr`='$desc' WHERE id = " . $this->id;
            $result = mysqli_query($con, $sql);
            return $result;
        }
    }

    //GET METHODS

    function getGeneralData()
    {
        global $con;
        $sql = "SELECT * FROM places WHERE id = " . $this->id;
        $r = mysqli_query($con, $sql);
        $dt = mysqli_fetch_array($r);

        return $dt;
    }

    function getSecurityData()
    {
        global $con;
        $sql = "SELECT * FROM places_security WHERE place_id = " . $this->id;
        $r = mysqli_query($con, $sql);
        $dt = mysqli_fetch_array($r);

        return $dt;
    }

    function getAddressData()
    {
        global $con;
        $sql = "SELECT * FROM places_addresses WHERE id = " . $this->id;
        $r = mysqli_query($con, $sql);
        $dt = mysqli_fetch_array($r);

        return $dt;
    }

    function getOwnerData()
    {
        global $con;
        $sql = "SELECT * FROM places WHERE id = " . $this->id;
        $r = mysqli_query($con, $sql);
        $iid = mysqli_fetch_array($r)['owner_id'];

        if($iid != "")
        {    
            $sql = "SELECT * FROM places_owner WHERE id = " . $iid;
            $r = mysqli_query($con, $sql);
            $dt = mysqli_fetch_array($r);
            return $dt;
        }

    }
    function getLinks()
    {
        global $con;
        $sql = "SELECT * FROM places_links WHERE place_id = " . $this->id;
        $r = mysqli_query($con, $sql);
        return $r;
    }

    function getAllLinks()
    {
        global $con;
        $sql = "SELECT * FROM places_links";
        $r = mysqli_query($con, $sql);
        return $r;
    }

    function getLocNameByPID($id)
    {
        global $con;
        $sql = "SELECT * FROM places WHERE id = $id";
        $r = mysqli_query($con, $sql);
        $loc = mysqli_fetch_array($r)['name'];
        return $loc;
    }

    function getTimeline()
    {
        global $con;
        $sql = "SELECT * FROM places_timeline WHERE place_id = " . $this->id;
        $r = mysqli_query($con, $sql);
        return $r;
    }

    function getOwners($id)
    {
        if(isset($id))
        {
            global $con;
            $sql = "SELECT * FROM places_owner WHERE id = $id";
            $result = mysqli_query($con, $sql);
            while($r = mysqli_fetch_array($result))
            {
                array_push($this->rtrn, $r);
            }
            return $this->rtrn;
        }
        else
        {
            global $con;
            $sql = "SELECT * FROM places_owner";
            $result = mysqli_query($con, $sql);
            while($r = mysqli_fetch_array($result))
            {
                array_push($this->rtrn, $r);
            }
            return $this->rtrn;
        }
    }

    function getPlacesNum()
    {
        global $con;
        $sql = "SELECT * FROM places";
        return mysqli_query($con, $sql)->num_rows;

    }
    function getOwnersNum()
    {
        global $con;
        $sql = "SELECT * FROM places_owner";
        return mysqli_query($con, $sql)->num_rows;

    }

    function getLastMaintenceDate()
    {
        global $con;
        $sql = "SELECT * FROM data_maintence WHERE id = 1";
        return mysqli_fetch_array(mysqli_query($con, $sql))['last_maintence'];

    }

    //INSERT METHODS

    function InsertRow($name)
    {
        global $con;
        $id = "";
        $status = [];
        $error = "";
        
        $sql_general = "INSERT INTO `places` (`name`) VALUES ('$name')";

        $result = mysqli_query($con, $sql_general);
        if($result)
        {
            $sql = "SELECT * FROM places WHERE name = '$name'";
            $res = mysqli_query($con, $sql);
            if($res)
            {
                $id = mysqli_fetch_array($res)['id'];
                $sql = "SELECT * FROM places where id = " . $id;
                $sql_addr = "INSERT INTO places_addresses (place_id) VALUES ('$id')";
                $sql_sec = "INSERT INTO places_security (place_id) VALUES ('$id')";
                $sql_links = "INSERT INTO places_links (place_id) VALUES ('$id')";
                $sql_timeline = "INSERT INTO places_timeline (place_id) VALUES ('$id')";

                $r_addr = mysqli_query($con, $sql_addr);
                array_push($status, $r_addr ? 1 : 0);

                $r_sec = mysqli_query($con, $sql_sec);
                array_push($status, $r_sec ? 1 : 0);

                return ($r_addr && $r_sec) ? 1 : 0;
            }
        }
    }

    function InsertLinks($title, $type, $url, $desc)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
            "INSERT INTO `places_links`
            (`title`,
            `type`, 
            `url`, 
            `descr`, 
            `place_id`) 
            VALUES 
            ('$title',
            '$type',
            '$url',
            '$desc',
            '" . $this->id ."')";

            return mysqli_query($con, $sql);
        }
    }

    function InsertTimeline($title, $date, $desc)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
            "INSERT INTO `places_timeline`
            (`title`,
            `date`, 
            `descr`, 
            `place_id`) 
            VALUES 
            ('$title',
            '$date',
            '$desc',
            '" . $this->id ."')";

            return mysqli_query($con, $sql);
        }
    }


    //DELETE METHODS

    function DeleteRow()
    {
        global $con;
        if($this->id != null)
        {
            $s_g = 0; 
            $s_s = 0; 

            $sql = "DELETE FROM places WHERE id = " . $this->id;
            $r = mysqli_query($con, $sql);
            $s_g = $r ? 1 : 0;

            $sql = "DELETE FROM places_security WHERE place_id = " . $this->id;    
            $r = mysqli_query($con, $sql);
            $s_s = $r ? 1 : 0;

            if($s_g && $s_s){
                $sql = "DELETE FROM places_links WHERE place_id = " . $this->id;
                $r = mysqli_query($con, $sql);

                $sql = "DELETE FROM places_timeline WHERE place_id = " . $this->id;
                $r = mysqli_query($con, $sql);
                
                return 1; 
            }
            else return 0;
        }
    }

    function DeleteLinks($link)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
            "DELETE FROM `places_links`
            WHERE id = $link";

            return mysqli_query($con, $sql);
        }
    }

    function DeleteTimeline($tl)
    {
        global $con;
        if($this->id != null)
        {
            $sql = 
            "DELETE FROM `places_timeline`
            WHERE id = $tl";

            return mysqli_query($con, $sql);
        }
    }
  
    //CONDITION
    
    function isRowExists($id, $table_name){
        global $con;
        $sql = "SELECT * FROM $table_name WHERE id = $id";
        $r = mysqli_query($con, $sql);
        return ($r->num_rows > 0) ? 1 : 0;
    }


    // CHANGES HISTORY

    function updateHistory($name, $type, $subject_id, $old_value, $new_value, $change_type, $user_id){
        global $con;
        if($this->id != null){
            $sql = 
            "INSERT INTO `changes_history`
                (`name`, `type`, `subject_id`, `old_value`, `new_value`, `change_type`, `user_id`)
            VALUES
                ('$name', '$type', '$subject_id', '$old_value', '$new_value', '$change_type', '$user_id')";

            $r = mysqli_query($con, $sql);
            return $r;
        }

        // Change types numbering:
        //     0 - change
        //     1 - add
        //     2 - delete
    }
}