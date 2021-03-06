<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService as CommonService;
use App\Services\PurchaseOrderService as PurchaseOrderService;
use Illuminate\Support\Facades\Validator;
use App\Http\Helper;

class PurchaseOrderController extends Controller
{

    public $commonService;
    public $purchaseOrderService;
    public function __construct(CommonService $commonService, PurchaseOrderService $purchaseOrderService)
    {
        $this->commonService = $commonService;
        $this->purchaseOrderService = $purchaseOrderService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Helper::prettyApiResponse(
            trans('messages.list_success', ['item' => 'Purchase order']),
            'success',
            $this->purchaseOrderService->getPurchaseHistory($request)
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
        return $this->purchaseOrderService->add($request);
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
