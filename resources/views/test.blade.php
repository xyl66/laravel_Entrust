@extends('layouts.app')

@section('content')
<example></example>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <span>{{$t}}</span>
                    <\br>
                    <span>{{$t1}}</span>
                    <\br>
                    <span>{{$t2}}</span>
                    <\br>
                    <span>{{$t3}}</span>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">测试</div>

                <div class="panel-body">
                    <a href="{{ url('/test') }}">测试</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
