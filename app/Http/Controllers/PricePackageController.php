<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use Illuminate\Http\Request;
use App\Services\PricePackageService;
use PHPUnit\TextUI\Help;

class PricePackageController extends Controller
{
    protected $pricePackageService;

    public function __construct(PricePackageService $pricePackageService)
    {
        $this->pricePackageService = $pricePackageService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Helper::prettyApiResponse(
            trans('messages.list_success', ['item' => 'Price package']),
            'success',
            $this->pricePackageService->list()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Helper::prettyApiResponse(
            trans('messages.insert_success', ['item' => 'Price Package']),
            'success',
            $this->pricePackageService->store($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
