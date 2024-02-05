<?php

namespace App\BusinessLogic\Telcountrycode;

use Illuminate\Support\Facades\Log;

/*
 * Class ini adalah Bug Fix sementara  sampai package Telecountrycode yg ada di packagist diupdate
 * */
class Telcountrycode
{
    private static function getJson(){
        return json_decode(file_get_contents('telcountrycode.json', true),TRUE);
    }

    private static function jsonTelco($iso_ccode)
    {
        $telco = [];
        $data = json_decode(file_get_contents('telco.json', true),TRUE);

        foreach ($data as $key => $value) {
            if($value['iso_ccode']==$iso_ccode){
                $telco[]=[
                    "mno_id" => $value['mno_id'],
                    "mno_shortname" => $value['mno_shortname'],
                    "mno_fullname" => $value['mno_fullname'],
                    "mno_prefix" => $value['mno_prefix'],
                ];
            }
        }
        
        return $telco;
    }

    /**
     * Get Country Code by $index
     *
     * @param [iso] $index
     * @param [ccode] $index
     * @param [prefix] $index
     */
    public static function Country($index){
        foreach (self::getJson() as $value) {
            $isoc[$value['iso']]=[
                "iso" => $value['iso'],
                "iso_ccode" => $value['iso_ccode'],
                "iso_prefix" => $value['iso_prefix'],
                "name" => $value['name'],
            ];
            $ccode[$value['iso_ccode']]=[
                "iso" => $value['iso'],
                "iso_ccode" => $value['iso_ccode'],
                "iso_prefix" => $value['iso_prefix'],
                "name" => $value['name'],
            ];
            $prefix[$value['iso_prefix']]=[
                "iso" => $value['iso'],
                "iso_ccode" => $value['iso_ccode'],
                "iso_prefix" => $value['iso_prefix'],
                "name" => $value['name'],
            ];
        }

        if(array_key_exists($index,$isoc)){
            return $isoc[$index];
        }elseif(array_key_exists($index,$ccode)){
            return $ccode[$index];
        }elseif(array_key_exists($index,$prefix)){
            return $prefix[$index];
        }else{
            return null;
        }
    }

    public static function msisdnCountry($msisdn){
        foreach (self::getJson() as $k => $c) {
            if(preg_match("/^".$c['iso_prefix']."/", $msisdn)){
                $c['msisdn']=$msisdn;
                return $c;
            }
        }
    }

    public static function getTelco($msisdn){
        $basic = [];

        $msisdn = trim(preg_replace("/^\+/", "", $msisdn));
        foreach (self::getJson() as $k => $c) {
            if(preg_match("/^".$c['iso_prefix']."/", $msisdn)){
                $c['msisdn']=$msisdn;
                $basic = $c;
            }
        }

        if($basic!=null){
            $telcos = self::jsonTelco($basic['iso_ccode']);
            $telco =[
                "mno_id" => 0,
                "mno_shortname" =>$basic['iso_ccode'],
                "mno_fullname" =>$basic['name'],
                "country_prefix" => $basic['iso_prefix'],
                "mno_prefix" => $basic['iso_prefix'],
                "msisdn" => $msisdn
            ];

            foreach($telcos as $pf){
                $parsing_pf = '^'.$basic['iso_prefix'].str_replace(',','|^'.$basic['iso_prefix'],$pf['mno_prefix']);
                if(preg_match('/'.$parsing_pf.'/',$msisdn))
                {
                    $telco = $pf;
                    $telco['iso_ccode'] = $basic['iso_ccode'];
                    $telco['iso_prefix'] = $basic['iso_prefix'];
                    $telco['country'] = $basic['name'];
                    $telco['msisdn'] = $msisdn;
                    break;
                }
            }
            return $telco;
        }

        return null;
    }

    public static function getPrefix($msisdn){
        $basic = [];

        $msisdn = trim(preg_replace("/^\+/", "", $msisdn));
        foreach (self::getJson() as $k => $c) {
            if(preg_match("/^".$c['iso_prefix']."/", $msisdn)){
                $c['msisdn']=$msisdn;
                $basic = $c;
            }
        }

        if($basic!=null){
            $telcos = self::jsonTelco($basic['iso_ccode']);
            $telco =[
                "mno_id" => 0,
                "mno_shortname" =>$basic['iso_ccode'],
                "mno_fullname" =>$basic['name'],
                "country_prefix" => $basic['iso_prefix'],
                "mno_prefix" => $basic['iso_prefix'],
                "msisdn" => $msisdn
            ];

            foreach($telcos as $pf){
                $parsing_pf = '^'.$basic['iso_prefix'].str_replace(',','|^'.$basic['iso_prefix'],$pf['mno_prefix']);
                if(preg_match('/'.$parsing_pf.'/',$msisdn))
                {
                    $telco = $pf;
                    $telco['iso_ccode'] = $basic['iso_ccode'];
                    $telco['iso_prefix'] = $basic['iso_prefix'];
                    $telco['country'] = $basic['name'];
                    $telco['msisdn'] = $msisdn;
                    $freg = $parsing_pf;
                    break;
                }
            }
        }

        return $freg;
    }

    public static function getTelcoByMnoId($mno_id)
    {
        $telco = [];
        $data = json_decode(file_get_contents('telco.json', true), TRUE);

        foreach ($data as $value)
        {
            if ($value['mno_id'] == $mno_id) {
                $telco = [
                    "mno_id" => $value['mno_id'],
                    "mno_shortname" => $value['mno_shortname'],
                    "mno_fullname" => $value['mno_fullname'],
                    "mno_prefix" => $value['mno_prefix'],
                ];

                break;
            }

        }

        return $telco;
    }
}
