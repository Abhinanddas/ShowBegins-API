<?php

namespace App\Services;

use App\Models\Show as Show;
use App\Services\CommonService as CommonService;
use DateTime;
use Illuminate\Support\Facades\Validator;

class ShowService
{
    private $showModel;

    public function __construct(Show $show)
    {
        $this->showModel = $show;
    }

    public function addShow($params)
    {
        $dataArray = [];
        foreach ($params as $param) {
            $movie = $param['movie'];
            foreach ($param['screens'] as $screen) {
                $data = [];
                foreach ($param['showTime'] as $showTime) {
                    $data['movie_id'] = (int)$movie;
                    $data['screen_id'] = (int)$screen;
                    $data['show_time'] = DateTime::createFromFormat('U', $showTime);
                    array_push($dataArray, $data);
                }
            }
        }
        return $this->showModel->saveShow($dataArray);
    }

    public function listShows()
    {
        return $this->movieModel->listAllMovies();
    }

    public function validateShowParams($params)
    {
        $isValid = true;
        foreach ($params as $param) {
            $requiredFields = [
                'movie' => 'required',
                'screen' => 'required',
                'time' => 'required',
                'date' => 'required',
            ];

            $requiredFieldsPresent = Validator::make($param, $requiredFields);
            if ($requiredFieldsPresent->fails()) {
                $isValid = false;
                break;
            }
        }
        return $isValid;
    }

    public function processShowTimeParam($params)
    {

        $inputParam = [];
        foreach ($params as $param) {
            $inputArray = [];

            $inputArray['movie'] = $param['movie'];
            $inputArray['screens'] = $param['screen'];

            $showTimeArray = [];
            foreach ($param['time'] as $time) {
                $showTime = strtotime($param['date'] . ' ' . $time);
                array_push($showTimeArray, $showTime);
            }
            $inputArray['showTime'] = $showTimeArray;
            array_push($inputParam, $inputArray);
        }
        return $inputParam;
    }

    public function validateShowTimings($params)
    {
        $isValid = true;
        $now = strtotime('now');
        foreach ($params as $param) {

            foreach ($param['showTime'] as $showTime) {
                if ($showTime < $now) {
                    $isValid = false;
                    break;
                }
            }
        }
        return $isValid;
    }
}
