@extends('completionletter.wrapper')

@section('content')
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
        This is to certify that <strong>{{ $intern->offerLetter->name ?? $intern->application->name }}</strong> studying at <strong>{{ $intern->offerLetter->college ?? $intern->application->college }}</strong>, 
        pursuing <strong>{{ $intern->offerLetter->degree ?? $intern->application->degree ?? 'N/A' }}</strong> degree has completed internship successfully for the period of 
        (<strong>{{ \Carbon\Carbon::parse($intern->offer_letters->joining_date)->format('d/m/Y') }} till {{ \Carbon\Carbon::parse($intern->offer_letters->completion_date)->format('d/m/Y') }}</strong>).
    </div>

    @if($intern->project_description)
        <div class="skills-list">
            {!! $intern->project_description !!}
        </div>
    @endif
@endsection
