@extends('accountant::app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>${{ format_amount($charge->amount) }} <small class="text-uppercase">{{ $charge->currency }}</small>
                @if($charge->refunded)
                    <div class="label label-default pull-right">Refunded</div>
                @elseif(!$charge->paid)
                    <div class="label label-danger pull-right">Failed</div>
                @else
                    <div class="label label-success pull-right">Paid</div>
                @endif
            </h4>
        </div>
        <div class="panel-body">
            <h5>Payment Details</h5>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">ID:</span> {{ $balance->source }}
                            </li>
                            <li>
                                <span class="attribute-label">Amount:</span> ${{ format_amount($balance->amount) }} <span class="text-uppercase">{{ $balance->currency }}</span>
                            </li>
                            @if($charge->refunded)
                                <li>
                                    <span class="attribute-label">Amount Refunded:</span> ${{ format_amount($charge->amount_refunded) }} <span class="text-uppercase">{{ $balance->currency }}</span>
                                </li>
                            @endif
                            <li>
                                <span class="attribute-label">Fee:</span> ${{ $charge->refunded ? '0.00' : format_amount($balance->fee) }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">Date:</span> {{ Carbon\Carbon::createFromTimestamp($balance->created)->format('Y/m/d h:i:s') }}
                            </li>
                            <li>
                                <span class="attribute-label">Description:</span> {!! $charge->description ?? '<span class="not-available">Not Available</em>' !!}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <h5>Card</h5>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">ID:</span> {{ $charge->source->id }}
                            </li>
                            <li>
                                <span class="attribute-label">Name:</span> {!! $charge->source->name ?? '<span class="not-available">Not Available</em>' !!}
                            </li>
                            <li>
                                <span class="attribute-label">Number:</span> ************{{ $charge->source->last4 }}
                            </li>
                            <li>
                                <span class="attribute-label">Fingerprint:</span> {{ $charge->source->fingerprint }}
                            </li>
                            <li>
                                <span class="attribute-label">Expires:</span> {{ $charge->source->exp_month . '/' . $charge->source->exp_year }}
                            </li>
                            <li>
                                <span class="attribute-label">Type:</span> {{ $charge->source->brand }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li>
                                <span class="attribute-label">Zip code:</span> {{ $charge->source->address_zip }}
                            </li>
                            <li>
                                <span class="attribute-label">Zip check:</span> <i class="fa fa-{{ $charge->source->address_zip_check == 'pass' ? 'check text-success' : 'close text-danger' }}"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if($charge->refunds->total_count)
                <h5>Refunds</h5>
                @foreach($charge->refunds->data as $refund)
                    <div class="panel panel-default refunds">
                        <div class="panel-body">
                            <div class="col-sm-6">
                                <ul class="list-unstyled">
                                    <li>
                                        <span class="attribute-label">ID:</span> {{ $refund->id }}
                                    </li>
                                    <li>
                                        <span class="attribute-label">Amount:</span> ${{ format_amount($refund->amount) }} <span class="text-uppercase">{{ $refund->currency }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="list-unstyled">
                                    <li>
                                        <span class="attribute-label">Reason:</span> {{ $refund->reason ? ucfirst(str_replace('_', ' ', $refund->reason)) : 'Other' }}
                                    </li>
                                    <li>
                                        <span class="attribute-label">Date:</span> {{ Carbon\Carbon::createFromTimestamp($refund->created)->format('Y/m/d h:i:s') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection