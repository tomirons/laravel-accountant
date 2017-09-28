@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>{{ $subscription->customer->email }} <span class="text-muted">on</span> {{ $subscription->plan->name }}</h4>
            @if ($subscription->status == 'active')
                <h5>
                    <span class="text-primary">Active</span>
                    @if ($subscription->cancel_at_period_end)
                         - Will end at {{ Accountant::formatDate($subscription->current_period_end) }}
                    @endif
                </h5>
            @endif
        </div>
        <div class="panel-body">
            <h5>Details</h5>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">ID:</span> {{ $subscription->id }}
                            </li>
                            <li>
                                <span class="attribute-label">Customer:</span> <a href="{{ url('accountant/customers', $subscription->customer->id) }}">{{ $subscription->customer->email }}</a>
                            </li>
                            <li>
                                <span class="attribute-label">Plan:</span> {{ $subscription->plan->name }} (${{ Accountant::formatAmount($subscription->plan->amount) . ($subscription->plan->interval_count > 1 ? ' every ' . str_plural($subscription->plan->interval) : '/' . $subscription->plan->interval) }})
                            </li>
                            <li>
                                <span class="attribute-label">Quantity:</span> {{ $subscription->quantity }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">Current period:</span> {{ Accountant::formatDate($subscription->current_period_start, 'Y/m/d') }} to {{ Accountant::formatDate($subscription->current_period_end, 'Y/m/d') }}
                            </li>
                            <li>
                                <span class="attribute-label">Created:</span> {{ Accountant::formatDate($subscription->created, 'Y/m/d') }}
                            </li>
                            <li>
                                <span class="attribute-label">Tax percent:</span> {!! $subscription->tax_percent > 0 ? $subscription->tax_percent : '<span class="not-available">No tax applied</em>' !!}
                            </li>
                            <li>
                                <span class="attribute-label">Billing:</span> {{ $subscription->billing == 'charge_automatically' ? 'Charge automatically' : 'Send invoice' }}
                            </li>
                            @if ($subscription->days_until_due)
                                <li>
                                    <span class="attribute-label">Days until due:</span> {{ $subscription->days_until_due }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @if ($subscription->invoices->count())
                <h5>Invoices</h5>
                <div class="invoices list-group">
                    @foreach ($subscription->invoices->take(5) as $invoice)
                        <a href="{{ url('accountant/invoices', $invoice->id) }}" class="list-group-item">
                            <span class="text-primary">${{ Accountant::formatAmount($invoice->total)}}</span>
                            @if ($invoice->paid)
                                <span class="label label-primary">Paid</span>
                            @else
                                <span class="label label-default">Unpaid</span>
                            @endif
                            <span>{{ $invoice->customer }}</span>
                            <span class="hidden-xs hidden-sm">{{ $invoice->number ? $invoice->number : $invoice->id }}</span>
                            <span class="pull-right hidden-xs hidden-sm">{{ Accountant::formatDate($invoice->date) }}</span>
                        </a>
                    @endforeach
                    @if ($subscription->invoices->count() > 5)
                        <a href="{{ url('accountant/invoices/subscription', $subscription->id) }}" class="list-group-item text-center">
                            <span class="text-primary">View all invoices <i class="fa fa-arrow-right"></i></span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection