@extends('accountant::app')

@section('content')
    <dashboard-component :refreshing="'{{ Accountant::isCacheRefreshing() }}'"></dashboard-component>
@endsection