@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Card</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                @Foreach($customers as $customer)
                    @php
                        $card = collect($customer->sources->data)->first();
                    @endphp
                    <tr class="clickable" data-href="{{ url('accountant/customers', $customer->id) }}">
                        <td><span class="text-primary">{{ $customer->email }}</span> - {{ $customer->id }}</td>
                        <td>{{ $card->brand }} - {{ $card->last4 }} - {{ $card->exp_month }}/{{ $card->exp_year }}</td>
                        <td>{{ Carbon\Carbon::createFromTimestamp($customer->created)->format('Y/m/d h:i:s') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection