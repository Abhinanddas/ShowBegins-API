<?php

namespace App\Services;

use App\Models\Screen as Screen;
use App\Services\CommonService as CommonService;

class ScreenService
{
    private $screeModel;

    public function __construct(Screen $screen)
    {
        $this->screeModel = $screen;
    }

    public function addScreen($params)
    {
        $fields = [
            'name' => $params['name'],
            'seating_capacity' => $params['seating_capacity'],
        ];

        return $this->screeModel->saveScreen($fields);
    }

    public function listAllScreens(){
        return $this->screeModel->listAllScreens();
    }


}
