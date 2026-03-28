@extends('completionletter.wrapper')

@section('content')
        @php
                $internName       = $intern->offer_letters->name ?? $intern->application->name   ?? 'Intern';
                $internCollege    = $intern->offer_letters->college ?? $intern->application->college ?? '';
                $internDegree     = $intern->offerLetter->degree ?? $intern->application->degree ?? '';
                    
        @endphp

    <div class="title">INTERNSHIP COMPLETION CERTIFICATE</div>

    <div class="meta-row">
        <div class="meta-left">
            <strong>From:</strong> TechStrota<br>
            <strong>Issued on:</strong> {{ \Carbon\Carbon::parse($intern->completion_date)->format('d/m/Y') }}
        </div>
        <div class="meta-right">
            <strong>Certificate ID:</strong> {{ $intern->intern_code }}
        </div>
    </div>

    <div class="content-p">
            This is to certify that <strong>{{ $internName }}</strong>
            @if($internCollege)
                studying at <strong>{{ $internCollege }}</strong>,
                    @if($internDegree) 
                        pursuing <strong>{{ $intern->offerLetter->degree ?? $intern->application->degree ?? 'N/A' }}</strong> degree 
                    @endif
            @endif
            has completed internship successfully for the period of 
            (<strong>{{ \Carbon\Carbon::parse($intern->offer_letters->joining_date)->format('d/m/Y') }} till {{ \Carbon\Carbon::parse($intern->offer_letters->completion_date)->format('d/m/Y') }}</strong>).
    </div>

    @if($intern->project_description)
        <div class="skills-list">
            {!! $intern->project_description !!}
        </div>
    @endif
@endsection