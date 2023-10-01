@extends('layouts.app')

@section('title' , __('auth.two factor authentication'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            @include('partials.alerts')
            <div class="card">
                <div class="card-header">
                    @lang('auth.two factor authentication')
                </div>
                <div class="card-body text-center">
                    <div>
                        <small>
                            @lang('auth.two factor is inactive' , ['number' => Auth::user()->phone_number])
                        </small>
                    </div>
                    <a href="{{route('auth.two.factor.activate')}}"
                       class="btn btn-primary mt-5">@lang('auth.activate')</a>
                </div>

            </div>
        </div>
    </div>

@endsection
