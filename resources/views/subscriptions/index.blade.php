@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Customer</th>
                    <th>Billing</th>
                    <th>Plan</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($subscriptions as $subscription)
                    <tr class="clickable" data-href="{{ url('accountant/subscriptions', $subscription->id) }}">
                        <td><span class="text-primary">{{ $subscription->customer->email }}</span></td>
                        <th><span class="label label-primary">{{ $subscription->billing == 'charge_automatically' ? 'Auto' : 'Send' }}</span></th>
                        <td>{{ $subscription->plan->name }}</td>
                        <td>{{ Carbon\Carbon::createFromTimestamp($subscription->created)->format('Y/m/d h:i:s') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
@endsection