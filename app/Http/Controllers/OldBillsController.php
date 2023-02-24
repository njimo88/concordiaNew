<?php

namespace App\Http\Controllers;

use App\Models\old_bills;
use App\Http\Requests\Storeold_billsRequest;
use App\Http\Requests\Updateold_billsRequest;

class OldBillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeold_billsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeold_billsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\old_bills  $old_bills
     * @return \Illuminate\Http\Response
     */
    public function show(old_bills $old_bills)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\old_bills  $old_bills
     * @return \Illuminate\Http\Response
     */
    public function edit(old_bills $old_bills)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateold_billsRequest  $request
     * @param  \App\Models\old_bills  $old_bills
     * @return \Illuminate\Http\Response
     */
    public function update(Updateold_billsRequest $request, old_bills $old_bills)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\old_bills  $old_bills
     * @return \Illuminate\Http\Response
     */
    public function destroy(old_bills $old_bills)
    {
        //
    }
}
