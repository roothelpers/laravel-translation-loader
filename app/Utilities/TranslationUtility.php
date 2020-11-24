<?php namespace App\Utilities;

use App\Models\LanguageLine;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Translation\Translator;

class TranslationUtility extends Translator {

    public function get($key, array $replace = array(), $locale = NULL, $fallback = true)
    {
        $route=  app('request')->route();
        if(isset($route)){
            $actionName = $route->getAction();
            if(isset($actionName['controller'])){
                $controlerName = class_basename($actionName['controller']);
                list($controlerName, $actionName) = explode('@', $controlerName);
            }else{
                $controlerName="Layout";
                $actionName="Shared";
            }

        $translate = Cache::get('language')->where('controlerName', '=',$controlerName)->where('actionName', '=',$actionName)->where('key', '=',$key)->first();

        if(!isset($translate)){
            $LanguageLine = new LanguageLine();
            $LanguageLine->actionName=$actionName;
            $LanguageLine->controlerName=$controlerName;
            $LanguageLine->key=$key;
            $LanguageLine->valueEn=$key;
            $LanguageLine->valueAr="arabic.".$key;
            $LanguageLine->save();
            \Artisan::call('cache:clear');
            if( App::getLocale()=='en')
                return $LanguageLine->valueEn;
            else
                return $LanguageLine->valueAr;

        }else{
            if( App::getLocale()=='en')
                return $translate->valueEn;
            else
                return $translate->valueAr;
        }
        }
        else{
            return parent::get( $key);
        }

    }
}
