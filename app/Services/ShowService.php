<?php

namespace App\Services;

use App\Models\Show as Show;
use App\Services\CommonService as CommonService;
use DateTime;
use Illuminate\Support\Facades\Date;
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
                    $data['show_time'] = $showTime;
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
                // $showTime = strtotime($param['date'] . ' ' . $time);
                $showTime = DateTime::createFromFormat('Y-m-d H:i', $param['date'] . '' . $time);
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
        $now = new DateTime('now');
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

    public function listActiveShows()
    {
        $now = new DateTime('now');
        $now->modify('-1 hour');
        $result =  $this->showModel->getAllActiveShows($now);
        return $this->processListActiveShowsData($result);
    }

    public function processListActiveShowsData($results)
    {
        $movieArray = [];

        foreach ($results as $result) {
            $movieId = $result->movie_id;
            if (!isset($movieArray[$movieId])) {
                $movieArray[$movieId]['movie_id'] = $movieId;
                $movieArray[$movieId]['movie_name'] = $result->movie_name;
            }
            $screenId = $result->screen_id;
            if (!isset($movieArray[$movieId]['screens']['screen_id'])) {
                $movieArray[$movieId]['screens'][$screenId]['screen_id'] = $screenId;
                $movieArray[$movieId]['screens'][$screenId]['screen_name'] = $result->screen_name;
            }
            $showTime = new DateTime($result->show_time);
            $movieArray[$movieId]['screens'][$screenId]['shows'][] = ['show_id' => $result->show_id, 'show_time' => $showTime->format('d-m h:i a')];
        }

        foreach($movieArray as $index=> $movie){
            $movieArray[$index]['screens']=array_values($movie['screens']);
        }
        return array_values($movieArray);
    }
}
