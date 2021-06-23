<?php

namespace App\Services;

use App\Models\Show as Show;
use App\Services\CommonService as CommonService;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ShowRepository;
use App\Repositories\TicketRepository;
use App\Repositories\ScreenRepository;
use App\Services\ScreenService;

class ShowService
{
    private $showModel;
    protected $showRepo;
    protected $screenRepo;
    protected $screenService;

    public function __construct(Show $show, ShowRepository $showRepo, TicketRepository $ticketRepo, ScreenService $screenService, ScreenRepository $screenRepo)
    {
        $this->showModel = $show;
        $this->showRepo = $showRepo;
        $this->ticketRepo = $ticketRepo;
        $this->screenRepo = $screenRepo;
        $this->screenService = $screenService;
    }

    public function addShow($params)
    {
        $seatCount = $this->fetchSeatCount($params);
        $dataArray = [];
        foreach ($params as $param) {
            $movie = $param['movie'];
            foreach ($param['screens'] as $screen) {
                $data = [];
                foreach ($param['showTime'] as $showTime) {
                    $data['movie_id'] = (int)$movie;
                    $data['screen_id'] = (int)$screen;
                    $data['show_time'] = $showTime;
                    $data['number_of_seats'] = $seatCount[$data['screen_id']];
                    $data['tickets_sold'] = 0;
                    $data['booking_status'] = config('constants.show_status.none');

                    array_push($dataArray, $data);
                }
            }
        }
        return $this->showModel->saveShow($dataArray);
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

    public function listShows($request)
    {
        return $this->showRepo->getShows(false);
    }

    public function getShowsForDashboard()
    {
        $now = new DateTime('now');
        $now->modify('-1 hour');
        $result =  $this->showRepo->getShows(true, $now);
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
            $movieArray[$movieId]['screens'][$screenId]['shows'][] = [
                'show_id' => $result->show_id,
                'show_time' => $showTime->format('d-m h:i a'),
                'status' => $result->booking_status,
            ];
        }

        foreach ($movieArray as $index => $movie) {
            $movieArray[$index]['screens'] = array_values($movie['screens']);
        }
        return array_values($movieArray);
    }

    public function getShowDetails($showId)
    {
        return $this->showRepo->getShowDetails($showId);
    }
    public function getBookedSeatDetails($showId)
    {
        return $this->ticketRepo->getBookedSeatDetails($showId);
    }

    public function fetchSeatCount($params)
    {
        $screenIds = [];
        foreach ($params as $param) {
            $screenIds = array_merge($screenIds, $param['screens']);
        }
        $screenIds = array_unique($screenIds);
        $screenCountData = $this->screenRepo->getScreenSeatCount($screenIds);
        $screenIdCountArray = [];
        foreach ($screenCountData as $data) {
            $screenIdCountArray[$data->id] = $data->seat_count;
        }
        return $screenIdCountArray;
    }

    public function calculateShowStatus($ticketsSold, $totalTickets)
    {

        dd("d");
        $percentageOfTicketSold = ($ticketsSold / $totalTickets) * 100;

        if ($percentageOfTicketSold == 0) {
            return config('constants.show_status.none');
        }

        if ($percentageOfTicketSold <= 25) {
            return config('constants.show_status.quarter');
        }

        if ($percentageOfTicketSold <= 50) {
            return config('constants.show_status.half');
        }

        if ($percentageOfTicketSold <= 75) {
            return config('constants.show_status.triquarter');
        }

        if ($percentageOfTicketSold == 100) {
            return config('constants.show_status.full');
        }
    }
}
