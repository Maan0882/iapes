@extends('completionletter.wrapper')

@section('content')
    @php
        $internName       = $intern->offer_letters->name ?? $intern->application->name   ?? 'Intern';
        $internCollege    = $intern->offer_letters->college ?? $intern->application->college ?? '';
        $internDegree     = $intern->offerLetter->degree ?? $intern->application->degree ?? '';
        
        $startDate = \Carbon\Carbon::parse($intern->offer_letters->joining_date);
        $endDate   = \Carbon\Carbon::parse($intern->offer_letters->completion_date);
        
        // Count working days (excluding Sundays)
        $workingDays = 0;
        $tempDate = $startDate->copy();
        while ($tempDate <= $endDate) {
            if ($tempDate->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
                $workingDays++;
            }
            $tempDate->addDay();
        }

        // One month or less check (calendar days <= 31)
        $calendarDays = $startDate->diffInDays($endDate) + 1;
        $isShortTerm = ($calendarDays <= 31);
        
        $workingHoursPerDay = $intern->offer_letters->working_hours ?: 5;
        // Total hours calculation: Working Days * Hours per day
        $totalHours = ($workingHoursPerDay > 40) ? $workingHoursPerDay : ($workingDays * $workingHoursPerDay);
    @endphp

    <div class="title">INTERNSHIP COMPLETION LETTER</div>

    <div class="meta-row">
        <div class="meta-left">
            <strong>From:</strong> Techstrota<br>
            <strong>Issued on:</strong> {{ \Carbon\Carbon::parse($intern->issuing_date)->format('d/m/Y') }}
        </div>
        <div class="meta-right">
            <strong>Certificate ID:</strong> {{ $intern->intern_code }}
        </div>
    </div>

    <div class="content-p">
        This is to certify that <strong>{{ $internName }}</strong>@if($internCollege), a student of <strong>{{ $internDegree }}</strong>,@endif has successfully completed the <strong>{{ $workingDays }} Days @if($isShortTerm) ({{ $totalHours }} Hours) with Grade {{ $intern->grade ?? 'A' }}@endif</strong>. 
        The internship was carried out for the course titled <strong>“{{ $intern->offer_letters->internship_role }}”</strong>, conducted by <strong>Techstrota</strong>@if($internCollege) and facilitated by <strong>{{ $internCollege }}@if($intern->offer_letters->university), {{ $intern->offer_letters->university }}@endif</strong>@endif. 
        The internship duration was from <strong>{{ $startDate->format('d/m/Y') }}</strong> to <strong>{{ $endDate->format('d/m/Y') }}</strong> at Techstrota. 156, K-10 Atlantis, Near Genda Circle, Vadodara, Gujarat – 390007
    </div>

    @if($intern->project_description)
        <div class="skills-list">
            {!! $intern->project_description !!}
        </div>
    @endif
@endsection