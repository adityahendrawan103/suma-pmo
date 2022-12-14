@extends('layouts.main.index')
@section('title','Orders')
@section('subtitle','Purchase Order')
@section('container')
    <div class="row g-0">
        <div class="card card-flush">
            <div class="card-header align-items-center border-0 mt-4 mb-4">
                <h3 class="card-title align-items-start flex-column">
                    <span class="fw-bolder mb-2 text-dark">Purchase Order</span>
                    <span class="text-muted fw-boldest fs-7">Daftar purchase order
                        @if($month == 1) Januari
                        @elseif($month == 2) Februari
                        @elseif($month == 3) Maret
                        @elseif($month == 4) April
                        @elseif($month == 5) Mei
                        @elseif($month == 6) Juni
                        @elseif($month == 7) Juli
                        @elseif($month == 8) Agustus
                        @elseif($month == 9) September
                        @elseif($month == 10) Oktober
                        @elseif($month == 11) November
                        @elseif($month == 12) Desember
                        @endif {{ $year }}
                    </span>
                    @if(trim($kode_sales) != '' || trim($kode_dealer) != '')
                    <div class="d-flex align-items-center mt-4">
                        @if(trim($kode_sales) != '')
                        <span class="badge badge-secondary fs-8 fw-boldest me-2">SALESMAN : {{ strtoupper(trim($kode_sales)) }}</span>
                        @endif
                        @if(trim($kode_dealer) != '')
                        <span class="badge badge-secondary fs-8 fw-boldest me-2">DEALER : {{ strtoupper(trim($kode_dealer)) }}</span>
                        @endif
                    </div>
                    @endif

                </h3>
                <div class="card-toolbar">
                    <button id="btnFilterPof" class="btn btn-primary" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFilter">
                        <i class="bi bi-funnel-fill fs-4 me-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>

        @if(strtoupper(trim($device)) == 'DESKTOP')
            @include('layouts.orders.purchaseorder.desktop.purchaseorderlist')
        @else
        <div id="dataPurchaseOrder">
            @include('layouts.orders.purchaseorder.mobile.purchaseorderlist')
        </div>
        <div id="dataLoadPurchaseOrder"></div>
        @endif
    </div>

    <div class="modal fade" tabindex="-1" id="modalFilter">
        <div class="modal-dialog">
            <div class="modal-content" id="modalFilterContent">
                <form id="formFilter" name="formFilter" autofill="off" autocomplete="off" method="get" action="{{ route('orders.purchase-order') }}">
                    <div class="modal-header">
                        <h5 id="modalTitle" name="modalTitle" class="modal-title">Filter Purchase Order</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-muted svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M6 19.7C5.7 19.7 5.5 19.6 5.3 19.4C4.9 19 4.9 18.4 5.3 18L18 5.3C18.4 4.9 19 4.9 19.4 5.3C19.8 5.7 19.8 6.29999 19.4 6.69999L6.7 19.4C6.5 19.6 6.3 19.7 6 19.7Z" fill="currentColor"/>
                                    <path d="M18.8 19.7C18.5 19.7 18.3 19.6 18.1 19.4L5.40001 6.69999C5.00001 6.29999 5.00001 5.7 5.40001 5.3C5.80001 4.9 6.40001 4.9 6.80001 5.3L19.5 18C19.9 18.4 19.9 19 19.5 19.4C19.3 19.6 19 19.7 18.8 19.7Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="fv-row">
                            <label class="form-label required">Bulan:</label>
                            <select id="selectFilterMonth" name="month" class="form-select">
                                <option value="1" @if($month == 1) {{"selected"}} @endif>Januari</option>
                                <option value="2" @if($month == 2) {{"selected"}} @endif>Februari</option>
                                <option value="3" @if($month == 3) {{"selected"}} @endif>Maret</option>
                                <option value="4" @if($month == 4) {{"selected"}} @endif>April</option>
                                <option value="5" @if($month == 5) {{"selected"}} @endif>Mei</option>
                                <option value="6" @if($month == 6) {{"selected"}} @endif>Juni</option>
                                <option value="7" @if($month == 7) {{"selected"}} @endif>Juli</option>
                                <option value="8" @if($month == 8) {{"selected"}} @endif>Agustus</option>
                                <option value="9" @if($month == 9) {{"selected"}} @endif>September</option>
                                <option value="10" @if($month == 10) {{"selected"}} @endif>Oktober</option>
                                <option value="11" @if($month == 11) {{"selected"}} @endif>November</option>
                                <option value="12" @if($month == 12) {{"selected"}} @endif>Desember</option>
                            </select>
                        </div>
                        <div class="fv-row mt-8">
                            <label class="form-label required">Tahun:</label>
                            <input type="number" id="inputFilterYear" name="year" class="form-control" placeholder="Tahun"
                                @if(isset($year)) value="{{ $year }}" @else value="{{ old('year') }}"@endif>
                        </div>
                        <div class="fv-row mt-8">
                            <label class="form-label">Salesman:</label>
                            <div class="input-group">
                                <input id="inputFilterSalesman" name="salesman" type="search" class="form-control" style="cursor: pointer;" placeholder="Semua Salesman" readonly
                                    @if(isset($kode_sales)) value="{{ $kode_sales }}" @else value="{{ old('kode_sales') }}"@endif>
                                @if($role_id != 'MD_H3_SM')
                                    @if($role_id != 'D_H3')
                                    <button id="btnFilterPilihSalesman" name="btnFilterPilihSalesman" class="btn btn-icon btn-primary" type="button"
                                        data-toggle="modal" data-target="#salesmanSearchModal">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="fv-row mt-8">
                            <label class="form-label">Dealer:</label>
                            <div class="input-group">
                                <input id="inputFilterDealer" name="dealer" type="search" class="form-control" style="cursor: pointer;" placeholder="Semua Dealer" readonly
                                    @if(isset($kode_dealer)) value="{{ $kode_dealer }}" @else value="{{ old('kode_dealer') }}"@endif>
                                @if($role_id != 'D_H3')
                                <button id="btnFilterPilihDealer" name="btnFilterPilihDealer" class="btn btn-icon btn-primary" type="button"
                                    data-toggle="modal" data-target="#dealerSearchModal">
                                    <i class="fa fa-search"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button id="btnFilterReset" class="btn btn-danger" role="button">Reset Filter</button>
                        <div class="text-end">
                            <button id="btnFilterProses" type="submit" class="btn btn-primary">Terapkan</button>
                            <button id="btnFilterClose" name="btnClose" type="button" class="btn btn-light text-end" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.option.optionsalesman')
    @include('layouts.option.optiondealer')

    @push('scripts')
        <script src="{{ asset('assets/js/suma/option/option.js') }}"></script>
        @if(strtoupper(trim($device)) != 'DESKTOP')
            <script src="{{ asset('assets/js/suma/orders/purchaseorder/purchaseordermobile.js') }}?v={{ time() }}"></script>
        @endif

        <script type="text/javascript">
            const url = {
                purchase_order: "{{ route('orders.purchase-order') }}",
                setting_clossing_marketing: "{{ route('setting.setting-clossing-marketing') }}",
            }

            const data_filter = {
                month: "{{ $month }}",
                year: "{{ $year }}",
                salesman: '{{ $kode_sales }}',
                dealer: '{{ $kode_dealer }}',
            }

            function input_kososng() {
                @if ($role_id == 'MD_H3_SM')
                $('#inputFilterDealer').val('');
                @elseif($role_id != 'D_H3')
                $('#inputFilterSalesman').val('');
                $('#inputFilterDealer').val('');
                @endif;
            }
        </script>
        <script src="{{ asset('assets/js/suma/orders/purchaseorder/purchaseorder.js') }}?v={{ time() }}"></script>
    @endpush
@endsection
