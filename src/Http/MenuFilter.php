<?php

namespace Support\Http;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust;
use Request;
use Session;

class MenuFilter implements FilterInterface
{
    public function transform($item)
    {
        // if (isset($item['permission']) && ! Laratrust::can($item['permission'])) {
        //     return false;
        // }
//         if (!isset($item['header']) && $item['text']!=="Dashboard" && $item['text']!=="Visitas" && $item['text']!=="Plugins" && $item['text']!=="Others" )
        // dd($item);
        $user = Auth::user();

        if (!$this->verifySection($item, $user)) {
            return false;
        }
        
        // if (!$this->verifyLevel($item, $user)) {
        //     return false;
        // }

        // if (!$this->verifySpace($item, $user)) {
        //     return false;
        // }

        // Translate
        if (isset($item["text"])) {
            $item["text"] = _t($item["text"]);
        }
        if (isset($item["header"])) {
            $item["header"] = _t($item["header"]);
        }
        return $item;
    }

    private function verifySection($item, $user)
    {
        $actualSection = Request::segment(1);
        $section = null;
        if (isset($item['section']) && $actualSection !== $item['section']) {
            return false;
        }

        if (isset($item['dontSection']) && $actualSection === $item['dontSection']) {
            return false;
        }

        return true;
    }

    private function verifySpace($item, $user)
    {
        $space = null;
        if (isset($item['space'])) {
            $space = $item['space'];
        }

        if (empty($space)) {
            return true;
        }

        return $space == app('support.router')->getRouteSpace(); //Session::get('space');
    }

    private function verifyLevel($item, $user)
    {
        $level = 0;
        if (isset($item['level'])) {
            $level = (int) $item['level'];
        }

        // Possui level inteiro e usuario nao logado
        if ($level>0 && !$user) {
            return false;
        }
        if ($level<=0) {
            return true;
        }

        if (!$user || $level > $user->getLevelForAcessInBusiness()) {
            return false;
        }

        return true;
    }
}
