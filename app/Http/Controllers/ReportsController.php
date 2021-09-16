<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Return report view and report url for print report
     */
    public function printReport(Request $request) {
        
        $rptUrl = config('reports.rpt_server')
        .'/flow.html?_flowId=viewReportFlow'
        .'&j_username='.config('reports.rpt_username')
        .'&j_password='.config('reports.rpt_user_password')
        .'&decorate=no'
        .'&viewAsDashboardFrame=false';

        $rptUrl .= '&reportUnit='. urlencode($request->report);

        if (sizeof($request->except(['report', 'token'])) > 0) {
            foreach ($request->except('_token', 'report') as $key => $value) {
                $rptUrl .= '&'.$key.'='.$value;
            }
        }

        return view('reports.view')->with('report_url', $rptUrl);
    }
}
