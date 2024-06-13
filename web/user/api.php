<?php 

class Pokémon{

    public string $PokémonName;

    public function __construct($name)
    {
        $this->PokémonName = $name;
    }

    public function GetData()
    {
        $id = $this->PokémonName;

        if(isset($id))
        {
            if($id != "")
            {
                $style  = "block";
                $sstyle = "none";
                $headers = @get_headers('https://pokeapi.co/api/v2/pokemon/' . $id);
                $status = explode(" ", $headers[0]);

                if ($headers)
                {
                    if($status[1] == 200)
                    {
                        $str = file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $id);
                        $pokemonData = json_decode($str, true);

                        // Získání požadovaných vlastností
                        $desiredProperties = [
                            "name" => $pokemonData["name"],
                            "id" => $pokemonData["id"],
                            "height" => $pokemonData["height"],
                            "weight" => $pokemonData["weight"],
                            "front_default" => $pokemonData["sprites"]["other"]["official-artwork"]["front_default"]
                        ];
                        $typecolors = [
                            'bug' => '#729F3F',
                            'dragon' => '#F16E57',
                            'fairy' => '#FDB9E9',
                            'fire' => '#FD7D24',
                            'ghost' => '#7B62A3',
                            'ground' => '#8F4521',
                            'normal' => '#A4ACAF',
                            'psychic' => '#F366B9',
                            'steel' => '#9EB7B8',
                            'dark' => '#707070',
                            'electric' => '#EED535',
                            'fighting' => '#D56723',
                            'flying' => '#729FB8',
                            'grass' => '#9BCC50',
                            'ice' => '#51C4E7',
                            'poison' => '#B97FC9',
                            'rock' => '#A38C21',
                            'water' => '#4592C4'
                        ];
                        $types = [];
                        foreach ($pokemonData["types"] as $type) 
                        {
                            $types[] = $type["type"]["name"];
                        }
                        $desiredProperties["types"] = $type;
                        
                        $desiredProperties["typecolors"] = $typecolors;

                        $abilities = [];
                        foreach ($pokemonData["abilities"] as $ability) 
                        {
                            $abilities[] = $ability["ability"]["name"];
                        }
                        $desiredProperties["abilities"] = $ability;
                        return $desiredProperties;
                    }
                    else if($status[1] == 404)
                    {
                        trigger_error("Pokémon or Pokémon ID $id was not found; $headers[0]", E_USER_ERROR);
                    }
                    else
                    {
                        trigger_error("Something wet wrong; $headers[0]", E_USER_ERROR);
                    }
                } 
                else 
                {
                    trigger_error("Can't get status code", E_USER_ERROR);
                }
            }
            else
            {
                trigger_error("Argument \$id is non-empty!", E_USER_ERROR);
            }
        }
        else
        {
            trigger_error("Argument \$id is required!", E_USER_ERROR);
        }
    }
}

?>