@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Invoice</h4>
            <h5 class="text-muted">{{ $invoice->id }}</h5>
        </div>
        <div class="panel-body">
            <h5>Details</h5>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">ID:</span> {{ $invoice->id }}
                            </li>
                            @if ($invoice->number)
                                <li>
                                    <span class="attribute-label">Number:</span> {{ $invoice->number }}
                                </li>
                            @endif
                            <li>
                                <span class="attribute-label">Date:</span> {{ Accountant::formatDate($invoice->date) }}
                            </li>
                            <li>
                                <span class="attribute-label">Period:</span> {{ Accountant::formatDate($invoice->period_start) }} to {{ Accountant::formatDate($invoice->period_end) }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">Customer:</span> <a href="{{ url('accountant/customers', $invoice->customer->id) }}">{{ $invoice->customer->id }}</a>
                            </li>
                            <li>
                                <span class="attribute-label">Subscription:</span> <a href="{{ url('accountant/subscriptions', $invoice->subscription) }}">{{ $invoice->subscription }}</a>
                            </li>
                            <li>
                                <span class="attribute-label">Description:</span> {!! $invoice->description ?? '<span class="not-available">No description</em>' !!}
                            </li>
                            <li>
                                <span class="attribute-label">Billing:</span> {{ $invoice->billing == 'charge_automatically' ? 'Charge automatically' : 'Send invoice' }}
                            </li>
                            @if ($invoice->due_date)
                                <li>
                                    <span class="attribute-label">Due date:</span> {{ Accountant::formatDate($invoice->due_date, 'Y/m/d') }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <h5>Status</h5>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">Status:</span>
                                @if ($invoice->paid)
                                    Paid
                                @else
                                    Awaiting payment.
                                @endif
                            </li>
                            @if ($invoice->charge && $invoice->paid)
                                <li>
                                    <span class="attribute-label">Payment:</span> <a href="{{ url('accountant/charges', $invoice->charge) }}">{{ $invoice->charge }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @if ($invoice->attempt_count > 1 && $invoice->paid)
                        <div class="col-sm-12">
                            <a href="{{ url('accountant/charges', ['invoice', $invoice->id]) }}" class="btn btn-primary btn-xs view-all">View all attempts <i class="fa fa-arrow-right"></i></a>
                        </div>
                    @endif
                </div>
            </div>
            <h5>Items</h5>
            <div class="panel panel-default">
                <div class="panel-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Period</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td class="text-primary">${{ Accountant::formatAmount($item->amount) }}</td>
                                    <td>Subscription to {{ Accountant::planToReadable($item->plan) }}</td>
                                    <td>{{ Accountant::formatDate($item->period->start, 'Y/m/d') }} to {{ Accountant::formatDate($item->period->end, 'Y/m/d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <h5>Summary</h5>
            <ul class="list-group">
                @if ($invoice->tax)
                    <li class="list-group-item">
                        Tax
                        <span>${{ Accountant::formatAmount($invoice->tax) }}</span>
                    </li>
                @endif
                <li class="list-group-item">
                    Subtotal
                    <span class="pull-right">${{ Accountant::formatAmount($invoice->subtotal) }}</span>
                </li>
                <li class="list-group-item">
                    Total
                    <span class="pull-right">${{ Accountant::formatAmount($invoice->total) }}</span>
                </li>
                <li class="list-group-item">
                    <strong>Amount Due</strong>
                    <span class="pull-right"><strong>${{ Accountant::formatAmount($invoice->amount_due) }}</strong></span>
                </li>
            </ul>
        </div>
    </div>
@endsection