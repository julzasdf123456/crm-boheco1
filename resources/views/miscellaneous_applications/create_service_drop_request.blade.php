@php
    use App\Models\IDGenerator;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Create Service Drop Purchase Request</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item" title="Service Drop Purchasing Request Home"><a
                                href="{{ route('miscellaneousApplications.service-drop-purchasing') }}">SDPR Home</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('miscellaneousApplications.create-service-drop-purchasing') }}">Create
                                SDPR</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-2">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Form</span>
                </div>
                <div class="card-body">
                    <form class="row" method="POST"
                        action="{{ route('miscellaneousApplications.store-service-drop-purchase') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">
                        <input type="hidden" name="Application" value="Service Drop Purchase">
                        <input type="hidden" name="Status" value="Received">
                        <input type="hidden" name="UserId" value="{{ Auth::id() }}">
                        {{-- NAME --}}
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    <label for="ConsumerName">Consumer Name <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        {!! Form::text('ConsumerName', null, [
                                            'class' => 'form-control form-control-sm',
                                            'required' => true,
                                            'maxlength' => 255,
                                            'maxlength' => 255,
                                            'autofocus' => true,
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Town Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('Town', 'Town') !!} <span class="text-danger"><strong> *</strong></span>
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        {!! Form::select('Town', $towns, null, ['class' => 'form-control form-control-sm', 'required' => 'true']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barangay Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('Barangay', 'Barangay') !!} <span class="text-danger"><strong> *</strong></span>
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        {!! Form::select('Barangay', [], null, ['class' => 'form-control form-control-sm', 'required' => 'true']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sitio Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('Sitio', 'Sitio') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        {!! Form::text('Sitio', null, [
                                            'class' => 'form-control form-control-sm',
                                            'maxlength' => 1000,
                                            'maxlength' => 1000,
                                            'placeholder' => 'Sitio',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('Notes', 'Notes/Remarks') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
                                        </div>
                                        {!! Form::text('Notes', null, [
                                            'class' => 'form-control form-control-sm',
                                            'maxlength' => 1000,
                                            'maxlength' => 1000,
                                            'placeholder' => 'Notes or Remarks',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>
                        </div>

                        <!-- ServiceDropLength Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('ServiceDropLength', 'SDW Length') !!} <span class="text-danger"><strong> *</strong></span>
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                        </div>
                                        {!! Form::number('ServiceDropLength', null, [
                                            'class' => 'form-control form-control-sm',
                                            'step' => 'any',
                                            'required' => true,
                                            'maxlength' => 1000,
                                            'maxlength' => 1000,
                                            'placeholder' => 'Service Drop Length in Meters',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Price Per Meter Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('PricePerQuantity', 'Price per Meter') !!} <span class="text-danger"><strong> *</strong></span>
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        {!! Form::number('PricePerQuantity', 33.0, [
                                            'class' => 'form-control form-control-sm',
                                            'step' => 'any',
                                            'required' => true,
                                            'maxlength' => 1000,
                                            'maxlength' => 1000,
                                            'placeholder' => 'Price per Meter',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- VAT (12%) Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('VAT', 'VAT (12%)') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        {!! Form::number('VAT', null, [
                                            'class' => 'form-control form-control-sm',
                                            'step' => 'any',
                                            'readonly' => true,
                                            'maxlength' => 1000,
                                            'maxlength' => 1000,
                                            'placeholder' => 'VAT (12%)',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TOTAL Field -->
                        <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('TotalAmount', 'TOTAL') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        {!! Form::number('TotalAmount', null, [
                                            'class' => 'form-control form-control-sm',
                                            'step' => 'any',
                                            'readonly' => true,
                                            'maxlength' => 1000,
                                            'maxlength' => 1000,
                                            'placeholder' => 'TOTAL',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-sm float-right">Save <i
                                    class="fas fa-check-circle"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#ServiceDropLength').on('change', function() {
                getEvatAndTotal()
            })

            $('#ServiceDropLength').on('keyup', function() {
                getEvatAndTotal()
            })

            $('#PricePerQuantity').on('change', function() {
                getEvatAndTotal()
            })

            $('#PricePerQuantity').on('keyup', function() {
                getEvatAndTotal()
            })
        })

        function getEvatAndTotal() {
            if (jQuery.isEmptyObject($('#ServiceDropLength').val())) {
                $('#VAT').val("0")
                $('#TotalAmount').val("0")
            } else {
                var sdwLength = parseFloat($('#ServiceDropLength').val())
                var price = parseFloat($('#PricePerQuantity').val())
                var amnt = sdwLength * price
                var vat = amnt * .12

                $('#VAT').val(Math.round((vat + Number.EPSILON) * 100) / 100)
                $('#TotalAmount').val(Math.round(((vat + amnt) + Number.EPSILON) * 100) / 100)
            }
        }
    </script>
@endpush
