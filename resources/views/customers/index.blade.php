@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Card</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($customers as $customer)
                    <tr class="clickable" data-href="{{ url('accountant/customers', $customer->id) }}">
                        <td><span class="text-primary">{{ $customer->email }}</span> <span class="text-muted">- {{ $customer->id }}</span></td>
                        <td>{{ $customer->card->brand }} - {{ $customer->card->last4 }} - {{ $customer->card->exp_month }}/{{ $customer->card->exp_year }}</td>
                        <td>{{ Accountant::formatDate($customer->created) }}</td>
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