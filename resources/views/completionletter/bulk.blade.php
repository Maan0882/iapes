<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        @page {
            margin: 0;
            size: A4;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.5;
            background: #fff;
        }

        .page-wrapper {
            position: relative;
            width: 210mm;
            height: 296mm;
            margin: 0 auto;
            padding: 15mm 20mm;
            box-sizing: border-box;
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        .header, .content, .footer { position: relative; z-index: 10; }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            z-index: 0;
            opacity: 0.25;
            pointer-events: none;
        }

        /* Header Styles */
        .header {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 5px;
            border-bottom: 2pt solid #f39200; /* Orange Line */
        }

        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }

        .header-item {
            display: table-cell;
            vertical-align: bottom;
            font-size: 13px;
            font-weight: 600;
        }

        .header-email { text-align: left; width: 35%; }
        .header-logo { text-align: center; width: 30%; }
        .header-tel { text-align: right; width: 35%; }
        .header-logo img { height: 45px; display: inline-block; }

        .content { padding: 10px 0; flex-grow: 1; }
        .title { text-align: center; font-size: 24px; font-weight: 700; text-decoration: underline; margin: 40px 0 30px 0; text-transform: uppercase; }
        .meta-row { display: table; width: 100%; margin-bottom: 25px; font-size: 15px; }
        .meta-left { display: table-cell; width: 60%; line-height: 1.4; }
        .meta-right { display: table-cell; width: 40%; text-align: right; vertical-align: top; }
        p, .content-p { font-size: 15.5px; margin-bottom: 18px; text-align: justify; }
        .skills-list {
            margin-left: 0;
            margin-bottom: 30px;
        }

        .skills-list ul {
            margin-left: 35px;
            padding-left: 0;
        }

        .skills-list li {
            margin-bottom: 5px;
            font-size: 15px;
        }

        /* Signature Styles */
        .sig-container { margin-top: auto; width: 250px; padding-bottom: 40px; }
        .sig-line { border-top: 1px solid #000; margin-bottom: 8px; width: 100%; }
        .sig-name { font-weight: 700; font-size: 16px; margin-bottom: 2px; }
        .sig-title { font-size: 14px; font-weight: 500; }

        /* Footer Styles */
        .footer { text-align: center; }
        .footer-line { border-top: 1px solid #ddd; margin-bottom: 15px; }
        .footer-content { font-size: 11px; color: #666; line-height: 1.4; }
        .footer-brand { font-weight: 700; color: #000; font-size: 12px; }
        .footer-link { color: #444; text-decoration: none; }

        @media print {
            .page-wrapper { margin: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
    @foreach($interns as $intern)
        <div class="page-wrapper">
            <img src="{{ public_path('images/TsLogo.png') }}" class="watermark" alt="Watermark">
            <div class="header">
                <div class="header-top">
                    <div class="header-item header-email">Email: info@techstrota.com</div>
                    <div class="header-item header-logo">
                        <img src="{{ asset('images/TsLogo.png') }}" alt="TechStrota">
                    </div>
                    <div class="header-item header-tel">Tel: +91 81600 72383</div>
                </div>
            </div>

            <div class="content">
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
            </div>

            <div class="sig-container">
                <div class="sig-line"></div>
                <div class="sig-name">Badal Jamod</div>
                <div class="sig-title">CEO/CTO - Techstrota</div>
            </div>

            <div class="footer">
                <div class="footer-line"></div>
                <div class="footer-content">
                    <span class="footer-brand">Techstrota</span> | <a href="https://www.techstrota.com" class="footer-link">www.techstrota.com</a><br>
                    503, Sterling Centre, R C Dutt Road, Near Fairfield Hotel, Alkapuri, Vadodara, Gujarat - 390007
                </div>
            </div>
        </div>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
