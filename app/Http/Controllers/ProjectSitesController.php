<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectSite;

class ProjectSitesController extends BaseController
{
    public function getProjectSites(Request $request){

        $data = ProjectSite::where('status', true)->orderBy('name')->get();
        $arr = [];

        foreach($data as $item){
            $arr [] = [
                'id' => $item->id,
                'title' => $item->name,
            ];
        }

        return $this->sendResponse( $arr, 'Project sites.' );
    }
}
