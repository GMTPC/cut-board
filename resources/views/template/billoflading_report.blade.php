<div id="billoflading_print">
    <div class="page-a4-layout">
        <div class="container-fluid">
            <div class="printbilloflading">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="header"><b>{{ trans('billading.companybranch') }}</b></h4>
                    </div>
                    <div class="col-md-6">
                        <h4 class="header text-right"><b>{{ trans('billading.reportheader') }}</b></h4>
                        <p class="header text-right">{{ $refReport }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-default" style="border-radius:10px;border-color: #2b2b2b;">
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <p class="tex-center position-fixed"><b>{{ trans('billading.reportlist') }}</b></p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="tex-center position-fixed">{{ $detail }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="tex-center position-fixed"><b>{{ trans('billading.division') }}</b></p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="tex-center position-fixed">{{ $divisoncode }} {{ $divisontitle }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="tex-center position-fixed"><b>{{ trans('billading.project') }}</b></p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="tex-center position-fixed">{{ $projectcode }} {{ $projectname }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="tex-center position-fixed"><b>{{ trans('billading.typeofbol') }}</b></p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="tex-center position-fixed">{{ $type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default" style="border-radius:10px;border-color: #2b2b2b;">
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6 none-pading">
                                            <p class="font-table-report-13 none-pading"><b>{{ trans('billading.diref') }}</b></p>
                                        </div>
                                        <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                                            <p class="font-table-report-13 none-pading">{{ $refReport }}</p>
                                        </div>
                                        <div class="col-md-6 none-pading">
                                            <p class="font-table-report-13 none-pading"><b>{{ trans('billading.direfdate') }}</b></p>
                                        </div>
                                        <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                                            @if (is_array($getreportrefdate) || is_object($getreportrefdate))
                                            <p class="font-table-report-13 none-pading">{{ date_format($getreportrefdate,"d/m/Y") }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6 none-pading">
                                            <p class="font-table-report-13 none-pading"><b>{{ trans('billading.printeddate') }}</b></p>
                                        </div>
                                        <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                                            <p class="font-table-report-13 none-pading">{{ $date }}</p>
                                        </div>
                                        <div class="col-md-6 none-pading">
                                            <p hidden class="font-table-report-13 none-pading"><b>{{ trans('billading.user') }}</b></p>
                                        </div>
                                        <div class="col-md-6 none-pading">
                                            <p hidden class="font-table-report-13 none-pading">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="myTabletransfer" class="table table-hover bg-light table-striped text-center">
                        <thead>
                            <tr>
                                <th class="font-table-report-14"><b>{{ trans('billading.sequence') }}</b></th>
                                <th class="font-table-report-14"><b>{{ trans('billading.itemno') }}</b></th>
                                <th class="font-table-report-14"><b>{{ trans('billading.list') }}</b></th>
                                <th class="font-table-report-14"><b>{{ trans('billading.unitcount') }}</b></th>
                                <th class="font-table-report-14"><b>{{ trans('billading.withdrawamount') }}</b></th>
                                <th class="font-table-report-14"><b>{{ trans('billading.warehouse') }}</b></th>
                            </tr>
                        </thead>
                        <tbody id="reportstore">
                            @foreach ($getreport as $getreports)
                                @if ($getreports->bol_input_qty != '0.00')
                                    <tr>
                                        <td class="font-table-report-13 checkcount">{{ $countgetreport++ }}</td>
                                        <td class="font-table-report-13 checkcode">{{ $getreports->TRD_SH_CODE }}</td>
                                        <td class="font-table-report-13 checkname">{{ $getreports->TRD_SH_NAME }}</td>
                                        <td class="font-table-report-13 checkuoc">{{ $getreports->TRD_UTQNAME }}</td>
                                        <td class="font-table-report-13 checkinput">{{ $getreports->bol_input_qty }}</td>
                                        <td class="font-table-report-13 checkwarehouse">{{ $nw }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <footer class="fix-bottom-footer">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="panel-group">
                                <div class="col-md-4 col-xs-4">
                                    <div class="text-center">
                                        <div class="panel panel-default" style="border-radius:10px;border-color: #2b2b2b; height:115px;">
                                            <div class="panel-body">
                                                <p class="text-center" style="font-size:14px;"><b>{{ trans('billading.applicant') }}</b></p>
                                                <hr class="hr-style-name">
                                                <hr class="hr-style-date">
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="text-center">
                                        <div class="panel panel-default" style="border-radius:10px;border-color: #2b2b2b; height:115px;">
                                            <div class="panel-body">
                                                <p class="text-center" style="font-size:14px;"><b>{{ trans('billading.storeofficer') }}</b></p>
                                                <hr class="hr-style-name">
                                                <hr class="hr-style-date">
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="text-center">
                                        <div class="panel panel-default" style="border-radius:10px;border-color: #2b2b2b; height:115px;">
                                            <div class="panel-body">
                                                <p class="text-center" style="font-size:14px;"><b>{{ trans('billading.approver') }}</b></p>
                                                <hr class="hr-style-name">
                                                <hr class="hr-style-date">
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
