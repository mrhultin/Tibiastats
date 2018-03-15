<?php
class Blizzard
{

    private $API_KEY = "rf6un9955rjedj5ssdsuxeath2ynz26g";
    private $API_REGION = "eu";
    public function construct_API_URL($path) {
        return "https://".$this->API_REGION.".api.battle.net/wow/" . $path . "?locale=en_GB&apikey=".$this->API_KEY;
    }

    public function Get_CharacterData($name, $server){
        $API_DATA = file_get_contents($this->construct_API_URL("character/".$server."/".$name)."&fields=progression");
        #print_r($API_DATA);

        if(false !== $API_DATA){
            $API_DATA = json_decode($API_DATA);
            if($API_DATA->faction !== 1){
            // Character isn't horde
            }

            $characterData = array(
            "name"   => $name,
            "race"   => $API_DATA->race,
            "heroic" => 0,
            "mythic" => 0,
            );
            /* Gather progression data */
            $d = $API_DATA->progression->raids[39]->bosses; // 39 = Antorus, The burning Throne
            foreach($d as $boss) {
                // Gather and store raid progression per difficulty (Heroic/Mythic)
                if ($boss->heroicKills > 0) {
                     $characterData["heroic"]++;
                }
                if ($boss->mythicKills > 0) {
                    $characterData["mythic"]++;
                }
            }
            return $characterData;
        }


    }


    public function Get_Realms(){
        $API_DATA = file_get_contents($this->construct_API_URL("realm/status"));
        if(false !== $API_DATA){
            $realms = array();
            $API_DATA = json_decode($API_DATA);
            foreach($API_DATA->realms as $realm){
                $realms[] = array(
                    "name" => $realm->name,
                    "name-slug" => $realm->slug,
                 );
            }
            return $realms;
        }
    }
}