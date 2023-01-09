@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Change Meter - Update Meter Information</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card shadow-none">
                    {!! Form::model($ticket, ['route' => ['tickets.update', $ticket->id], 'method' => 'patch']) !!}
                    <div class="card-header">
                        <p style="margin: 0; padding: 0;">Consumer Name: <strong>{{ $ticket->ConsumerName }}</strong></p>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="ConsumerName" value="{{ $ticket->ConsumerName }}">
                        <input type="hidden" name="Town" value="{{ $ticket->Town }}">
                        <input type="hidden" name="Barangay" value="{{ $ticket->Barangay }}">
                        <input type="hidden" name="Ticket" value="{{ $ticket->Ticket }}">
                        <input type="hidden" name="ContactNumber" value="{{ $ticket->ContactNumber }}">   
                        <div class="row">
                            <!-- CurrentMeterNo Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('CurrentMeterNo', 'Old Meter No:') !!}
                                    {!! Form::text('CurrentMeterNo', $ticket->CurrentMeterNo, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100, 'readonly' => true]) !!}
                                </div>
                            </div>

                            <!-- NewMeterNo Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('NewMeterNo', 'New Meter No:') !!}
                                    {!! Form::text('NewMeterNo', $ticket->NewMeterNo, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- CurrentMeterBrand Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('CurrentMeterBrand', 'Old Meter Brand:') !!}
                                    {!! Form::text('CurrentMeterBrand', $ticket->CurrentMeterBrand, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100, 'readonly' => true]) !!}
                                </div>
                            </div>

                            <!-- NewMeterBrand Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('NewMeterBrand', 'New Meter Brand:') !!}
                                    {!! Form::text('NewMeterBrand', $ticket->NewMeterBrand, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- CurrentMeterReading Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('CurrentMeterReading', 'Old Meter Pullout Reading:') !!}
                                    {!! Form::text('CurrentMeterReading', $ticket->CurrentMeterReading, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- NewMeterReading Field -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('NewMeterReading', 'New Meter Start Reading:') !!}
                                    {!! Form::text('NewMeterReading', $ticket->NewMeterReading, ['class' => 'form-control form-control-sm','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('tickets.index') }}" class="btn btn-default">Cancel</a>
                    </div>
    
                    {!! Form::close() !!}    
                </div>
            </div>
        </div>
    </div>
@endsection
