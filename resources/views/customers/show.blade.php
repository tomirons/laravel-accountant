@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>{{ $customer->email }} <small class="text-muted"> - {{ $customer->id }}</small></h4>
        </div>
        <div class="panel-body">
            <h5>Details</h5>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">ID:</span> {{ $customer->id }}
                            </li>
                            <li>
                                <span class="attribute-label">Created:</span> {{ Accountant::formatDate($customer->created) }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">Email:</span> {{ $customer->email }}
                            </li>
                            <li>
                                <span class="attribute-label">Description:</span> {!! $customer->description ?? '<span class="not-available">Not available</em>' !!}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($customer->cards->count())
                <h5>Cards</h5>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach ($customer->cards as $card)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ $card->id }}" aria-expanded="false" aria-controls="{{ $card->id }}">
                                        <span class="text-uppercase">{{ $card->brand }}</span>
                                    </a>
                                    <span class="pull-right">****{{ $card->last4 }} - {{ $card->exp_month }}/{{ $card->exp_year }}</span>
                                </h4>
                            </div>
                            <div id="{{ $card->id }}" class="panel-collapse collapse" role="tabpanel">
                                <div class="panel-body">
                                    <div class="col-sm-6">
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="attribute-label">ID:</span> {{ $card->id }}
                                            </li>
                                            <li>
                                                <span class="attribute-label">Name:</span> {!! $card->name ?? '<span class="not-available">No name provided</em>' !!}
                                            </li>
                                            <li>
                                                <span class="attribute-label">Number:</span> ****{{ $card->last4 }}
                                            </li>
                                            <li>
                                                <span class="attribute-label">Fingerprint:</span> {{ $card->fingerprint }}
                                            </li>
                                            <li>
                                                <span class="attribute-label">Expires:</span> {{ $card->exp_month }}/{{ $card->exp_year }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6">
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="attribute-label">Type:</span> {{ $card->brand }}
                                            </li>
                                            <li>
                                                <span class="attribute-label">Postal code:</span> {{ $card->address_zip }}
                                            </li>
                                            <li>
                                                <span class="attribute-label">Origin:</span> {!! $card->address_country ?? '<span class="not-available">Not available</em>' !!}
                                            </li>
                                            <li>
                                                <span class="attribute-label">CVC check:</span> <i class="fa fa-{{ $card->cvc_check == 'pass' ? 'check text-success' : 'close text-danger' }}"></i>
                                            </li>
                                            <li>
                                                <span class="attribute-label">Zip check:</span> <i class="fa fa-{{ $card->address_zip_check == 'pass' ? 'check text-success' : 'close text-danger' }}"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($customer->subscriptions->count())
                <h5>Active Subscriptions</h5>
                <div class="list-group">
                    @foreach ($customer->subscriptions as $subscription)
                        <a href="{{ url('accountant/subscriptions', $subscription->id) }}" class="list-group-item">
                            <span class="text-primary">{{ $subscription->plan->name }} (${{ Accountant::formatAmount($subscription->plan->amount) . ($subscription->plan->interval_count > 1 ? ' every ' . str_plural($subscription->plan->interval) : '/' . $subscription->plan->interval) }})</span>
                            <span class="pull-right">
                                @if ($subscription->canceled_at)
                                    Cancels {{ Accountant::formatDate($subscription->canceled_at, 'Y/m/d') }}
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($customer->invoices->count())
                <h5>Invoices</h5>
                <div class="invoices list-group">
                    @foreach ($customer->invoices->take(5) as $invoice)
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
                    @if ($customer->invoices->count() > 5)
                        <a href="{{ url('accountant/invoices/subscription', $subscription->id) }}" class="list-group-item text-center">
                            <span class="text-primary">View all invoices <i class="fa fa-arrow-right"></i></span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection