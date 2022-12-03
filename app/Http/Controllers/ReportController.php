<?php

namespace App\Http\Controllers;

use App\Models\report;
use App\Http\Requests\StorereportRequest;
use App\Http\Requests\UpdatereportRequest;
use App\Http\Resources\ReportResource;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getReportPageSort($sort,$num)
    {
        return ReportResource::collection(report::orderBy($sort, 'asc')->paginate($num));
    }

    public function getNoProcessReport()
    {
        $count = report::select(DB::raw('count(*) AS num_report'))->where('trangthai', '=', 'Chưa xác nhận')->orWhere('trangthai', '=', 'Đã xác nhận')->get();
        return response([
            'count' => $count
        ]);
    }

    public function getNumReport()
    {
        $count = report::select(DB::raw('count(*) AS num_report'))->get();
        return response(['num_report' => $count]);
    }
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
     * @param  \App\Http\Requests\StorereportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorereportRequest $request)
    {
        $data = $request->validated();
        $data['trangthai'] = 'Chưa xác nhận';
        $report = report::create($data);
        return new ReportResource($report);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(report $report)
    {
        return new ReportResource($report);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatereportRequest  $request
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatereportRequest $request, report $report)
    {
        $report->update($request->all());
        return new ReportResource($report);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(report $report)
    {
        $report->delete();
        return response('success', 200);
    }
}
