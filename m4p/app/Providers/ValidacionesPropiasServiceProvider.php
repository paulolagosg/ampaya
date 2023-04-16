<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidacionesPropiasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valida_rut', function($attribute, $value, $parameters,$validator) {
        //verificar si trae digito verificador
        if ( !preg_match("/^[0-9]+-[0-9kK]{1}/",$value)) return false;
        $rut = explode('-', $value);
        //
        $M=0;
        $S=1;
        $rutnum = $rut[0];
        for(;$rutnum;$rutnum=floor($rutnum/10))
            $S=($S+$rutnum%10*(9-$M++%6))%11;
        $dv = $S?$S-1:'k';
        if (strtolower($rut[1]) == $dv){
            return true;
        }else{
            return false;
        }
        //MENORES A 50MILLONES FALTA
        });

        Validator::extend('valida_rut_institucion', function($attribute, $value, $parameters,$validator) {
            //verificar si trae digito verificador

            if ( !preg_match("/^[0-9]+-[0-9kK]{1}/",$value)) return false;
            $rut = explode('-', $value);
            //
            $M=0;
            $S=1;
            $rutnum = $rut[0];
            for(;$rutnum;$rutnum=floor($rutnum/10))
                $S=($S+$rutnum%10*(9-$M++%6))%11;
            $dv = $S?$S-1:'k';
            if (strtolower($rut[1]) == $dv){
                //mayor a 50millones
                if ($rut[0]>50000000){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        });
    }
}
