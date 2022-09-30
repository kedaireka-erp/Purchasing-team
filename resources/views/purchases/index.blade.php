@extends('layout.sidebar')

@section('judul-laman', 'Dashboard Purchase Request')

@section('datatable')
    {{-- Style Datatable --}}
    <link href="{{ asset('assets/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection


@section('title_content', 'Purchase Request')

@section('wrap_title')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Request</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Purchase Request</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="card">
        <div id="chead">
            <div class="row">
                <div class="col-9">
                    <div class="card-header">
                        <h4 class="card-title">Data Purchase Request</h4>
                    </div>
                </div>
                <div class="col-3">
                    <div id="button_add">
                        <a href="{{ url('purchase_request/create') }}" class="btn btn-success" id="add"> +Add Data
                        </a>
                    </div>
                </div>
            </div>
            <hr>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table id="example3" class="display" style="width:100%">
                    <thead>
                        <tr class="content-control-md" align="right">
                            <td width="15%" align="left">Nomor PR</td>
                            <td width="10%">Pengajuan</td>
                            <td width="10%">Deadline</td>
                            <td width="20%">Requester</td>
                            <td width="10%">Divisi</td>
                            <td width="10%">Type</td>
                            <td width="20%" align="center">Status</td>
                            <td width="5%" align="center"></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase_requests as $no => $purchase_request)
                            <tr align="right">
                                <td class="content-control" align="left">{{ $purchase_request->no_pr }}</td>
                                <td class="content-control">
                                    {{ \Carbon\Carbon::parse($purchase_request->created_at)->format('d/m/Y') }}</td>
                                <td class="content-control">
                                    {{ \Carbon\Carbon::parse($purchase_request->deadline_date)->format('d/m/Y') }}</td>
                                <td class="content-control">{{ $purchase_request->requester }}</td>
                                <td class="content-control">{{ $purchase_request->Prefixe->divisi }}</td>
                                <td class="content-control">{{ $purchase_request->type }}</td>
                                <td class="content-control" align="left">
                                    @include('purchases.status')
                                </td>

                                <td class="py-2 text-end">
                                    <div class="dropdown text-sans-serif"><button class="btn btn-primary tp-btn-light sharp"
                                            type="button" id="order-dropdown-1" data-bs-toggle="dropdown"
                                            data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><span><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                                    viewbox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24">
                                                        </rect>
                                                        <circle fill="#000000" cx="5" cy="12" r="2">
                                                        </circle>
                                                        <circle fill="#000000" cx="12" cy="12" r="2">
                                                        </circle>
                                                        <circle fill="#000000" cx="19" cy="12" r="2">
                                                        </circle>
                                                    </g>
                                                </svg></span></button>
                                        <div class="dropdown-menu dropdown-menu-end border py-0"
                                            aria-labelledby="order-dropdown-1">
                                            <div class="py-2">

                                                <a class="dropdown-item"
                                                    href="{{ route('purchase_request.view', $purchase_request->id) }}">Detail</a>

                                                <form
                                                    action="{{ route('purchase_request.destroy', $purchase_request->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>



        <!-- Required vendors -->
        <script src="{{ asset('assets/vendor/global/global.min.js') }}"></script>

        <!-- Datatable -->
        <script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins-init/datatables.init.js') }}"></script>



    @endsection
