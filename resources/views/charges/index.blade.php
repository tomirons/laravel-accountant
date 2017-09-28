@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($charges as $charge)
                        <tr class="clickable" data-href="{{ url('accountant/charges', $charge->id) }}">
                            <th scope="row">
                                @if ($charge->refunded)
                                    <span class="label label-default">Refunded</span>
                                @elseif (!$charge->paid)
                                    <span class="label label-danger">Failed</span>
                                @else
                                    <span class="label label-success">Paid</span>
                                @endif
                            </th>
                            <td>${{ Accountant::formatAmount($charge->amount) }}</td>
                            <td>{{ $charge->id }}</td>
                            <td>{{ Accountant::formatDate($charge->created) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $charges->links() }}
            </div>
        </div>
    </div>
@endsection