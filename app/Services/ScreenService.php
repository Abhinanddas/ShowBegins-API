<?php

namespace App\Services;

use App\Http\Helper;
use App\Models\Screen as Screen;
use App\Services\CommonService as CommonService;
use App\Repositories\ScreenRepository;
use App\Repositories\ShowRepository;

class ScreenService
{
    protected $screenRepo;
    protected $showRepo;
    private $screeModel;
    protected $commonService;

    public function __construct(Screen $screen, ScreenRepository $screenRepo, CommonService $commonService, ShowRepository $showRepo)
    {
        $this->screeModel = $screen;
        $this->screenRepo = $screenRepo;
        $this->commonService = $commonService;
        $this->showRepo = $showRepo;
    }

    public function addScreen($params)
    {
        $fields = [
            'name' => $params['name'],
            'seating_capacity' => $params['seating_capacity'],
        ];

        return $this->screeModel->saveScreen($fields);
    }

    public function listAllScreens()
    {
        return $this->screeModel->listAllScreens();
    }

    public function remove($id)
    {

        $dataExists = $this->commonService->checkIfDataExists($id, 'screens');

        if (!$dataExists) {
            throw new \App\Exceptions\DataNotFoundExcepetion();
        }

        $deleteBlocked = $this->checkDeleteActionBlockedForScreen($id);

        if ($deleteBlocked) {
            $msg = trans('messages.delete_action_blocked', ['item' => 'Screen']);
            return Helper::prettyApiResponse($msg, 'error');
        }

        $this->screenRepo->remove($id);
        return;
    }

    public function checkDeleteActionBlockedForScreen($id)
    {
        return $this->showRepo->isScereenMappedToShows($id) ? true : false;
    }

    public function getScreenDetails($id)
    {
        $dataExists = $this->commonService->checkIfDataExists($id, 'screens');

        if (!$dataExists) {
            throw new \App\Exceptions\DataNotFoundExcepetion();
        }

        return $this->screenRepo->get($id);
    }

    public function update($id, $request)
    {
        $request->validate([
            'name' => 'required',
            'seating_capacity' => 'required|integer',
        ]);

        $dataExists = $this->commonService->checkIfDataExists($id, 'screens');

        if (!$dataExists) {

            $screen =  $this->addScreen($request->all());
            $status = 'success';
            $msg = trans('messages.insert_success', ['item' => 'Screen']);
            $data = ['id' => $screen];
            $statusCode = 201;
            if (!$screen) {
                $status = 'error';
                $msg = trans('messages.insert_failure', ['item' => 'Screen']);
                $data = [];
                $statusCode = 200;
            }

            return Helper::prettyApiResponse($msg, $status, $data, $statusCode);
        }

        return $this->screenRepo->update($id, $request->all());
    }
}
