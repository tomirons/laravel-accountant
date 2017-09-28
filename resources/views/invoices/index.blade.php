@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th>Amount</th>
                    <th>Number</th>
                    <th>Customer</th>
                    <th>Due</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($invoices as $invoice)
                    <tr class="clickable" data-href="{{ url('accountant/invoices', $invoice->id) }}">
                        <th scope="row">
                            @if ($invoice->paid)
                                <span class="label label-primary">Paid</span>
                            @else
                                <span class="label label-default">Unpaid</span>
                            @endif
                        </th>
                        <td>${{ Accountant::formatAmount($invoice->total) }}</td>
                        <td>{{ $invoice->number ? $invoice->number : $invoice->id }}</td>
                        <td>{{ $invoice->customer }}</td>
                        <td>{{ $invoice->due_date ? Accountant::formatDate($invoice->due_date, 'Y/m/d') : '-' }}</td>
                        <td>{{ Accountant::formatDate($invoice->date, 'Y/m/d') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
@endsection