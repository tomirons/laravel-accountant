<h5>Invoices</h5>
<div class="invoices list-group">
    @foreach ($invoices->take(5) as $invoice)
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
    @if ($invoices->count() > 5)
        <a href="{{ url('accountant/invoices/subscription', $subscription->id) }}" class="list-group-item text-center">
            <span class="text-primary">View all invoices <i class="fa fa-arrow-right"></i></span>
        </a>
    @endif
</div>