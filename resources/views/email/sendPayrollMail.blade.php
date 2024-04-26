@component('mail::message')
Basic Pay - {{$data['totalDays']}}<br>
@if (count($data2) > 0)
    @foreach ($data2 as $d2)
        {{ $d2->header_name }} - {{$d2->amount}} <br>
    @endforeach
@endif

Total Deduction - {{$data['totalDeduction']}}<br>
Total Earning - {{$data['totalPayment']}}<br>
Total Net Pay - {{$data['totalNetPay']}}<br>

Thanks,<br>
{{ $data['receivers_email'] }}

@endcomponent