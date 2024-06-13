<?php


function UpdateSecurity($statistics = '', $neighbours = '', $catchnum = '', $entrance_difficulty = '', $cctv = '', $security_sys = '', $security = '', $dogs = '', $homelessOrDrugsMen = '', $paranormal = '')
{
            $sql =
            "UPDATE
            `places_security` SET " . 
            ($statistics != "" ? "`statics`= '$statistics'" : "") .
            ($neighbours != "" ? "`neighbours`= '$neighbours'" : "") .
            ($catchnum != "" ? "`catchnum`= '$catchnum'" : "") .
            ($entrance_difficulty != "" ? "`entramce_difficulty`= '$entrance_difficulty'" : "") .
            ($cctv != "" ? "`cctv`= '$cctv'" : "") .
            ($security_sys != "" ? "`security_sys`= '$security_sys'" : "") .
            ($security != "" ? "`security`= '$security'" : "") .
            ($dogs != "" ? "`dogs`= '$dogs'" : "") .
            ($homelessOrDrugsMen != "" ? "`homelessOrDrugsMen`= '$homelessOrDrugsMen'" : "") .
            ($paranormal != "" ? "`paranormal`= '$paranormal'" : "") .
            " WHERE id = 1";
        return $sql;
}

echo UpdateSecurity($catchnum = 'ahooj');