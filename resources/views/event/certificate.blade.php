@php 
    $isPdf = $isPdf ?? true; 
    
    if (isset($registration)) {
        $registrations = collect([$registration]);
    } else {
        $registrations = $registrations ?? collect();
    }

    $logoPath = $isPdf ? public_path('images/TsLogo.png') : asset('images/TsLogo.png');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Cinzel+Decorative:wght@700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html, body { margin: 0; padding: 0; background: #ffffff; }
        
        @media screen {
            .cert {
                transform: scale(0.6); 
                transform-origin: top center;
                margin-bottom: -80mm; 
                box-shadow: 0 0 40px rgba(0,0,0,0.15);
            }
            #certificate-wrapper {
                background: #f1f5f9;
                padding: 60px 20px; 
                display: flex; flex-direction: column; align-items: center;
            }
        }
        
        @media print {
            @page { size: A4 landscape; margin: 0; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }

        .cert {
            position: relative; width: 297mm; height: 210mm; background: #ffffff;
            overflow: hidden; box-sizing: border-box; font-family: 'Inter', sans-serif;
        }

        /* Border Decoration */
        .b-outer { position: absolute; top: 8mm; left: 8mm; right: 8mm; bottom: 8mm; border: 2pt solid #FFA000; z-index: 1; }
        .b-inner { position: absolute; top: 10.5mm; left: 10.5mm; right: 10.5mm; bottom: 10.5mm; border: 5pt double #880E4F; z-index: 1; }

        /* Corner SVGs */
        .corner { position: absolute; width: 40mm; height: 40mm; fill: #880E4F; opacity: 0.8; z-index: 5; }
        .top-left { top: 12mm; left: 12mm; }
        .top-right { top: 12mm; right: 12mm; transform: rotate(90deg); }
        .bottom-left { bottom: 12mm; left: 12mm; transform: rotate(-90deg); }
        .bottom-right { bottom: 12mm; right: 12mm; transform: rotate(180deg); }

        /* Watermark */
        .watermark {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 130mm; opacity: 0.04; z-index: 0; pointer-events: none;
        }

        .content-wrapper { 
            position: relative; z-index: 10; width: 100%; height: 100%; 
            display: flex; flex-direction: column; align-items: center; 
            padding: 22mm 35mm; text-align: center; box-sizing: border-box; 
        }
        
        .main-title { 
            font-family: 'Cormorant Garamond', serif; font-size: 38pt; font-weight: 900; 
            color: #073c70; text-transform: uppercase; letter-spacing: 2pt; margin-top: 5mm; 
        }
        
        .recipient { font-family: 'Cinzel Decorative'; font-weight: 700; color: #db4f03; margin: 5mm 0; }
        .underline { width: 70%; height: 1.5pt; background: #FFA000; margin: 2mm auto 8mm; }
        
        .body-text { 
            font-size: 18pt; line-height: 1.6; color: #1a202c; max-width: 85%; 
            font-family: 'Cormorant Garamond', serif; font-style: italic; 
        }

        /* Improved Footer Alignment */
        .footer { 
            position: absolute; 
            bottom: 25mm; 
            left: 30mm; 
            right: 30mm; 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-end; 
        }
        
        .sig-box { width: 70mm; text-align: left; }
        .sig-line { border-top: 1.5pt solid #073c70; margin-bottom: 3mm; width: 100%; }
        
        .qr-section { 
            text-align: right; 
            display: flex; 
            flex-direction: column; 
            align-items: flex-end; 
        }
        
        .qr-wrapper {
            background: white;
            padding: 1.5mm;
            border: 0.5pt solid #e2e8f0;
            display: inline-block;
            margin-bottom: 2mm;
        }
    </style>
</head>

<body>
    <div id="certificate-wrapper">
        @foreach($registrations as $registration)
            <div class="cert-scale-wrapper" style="{{ !$loop->last ? 'page-break-after: always;' : '' }}">
                <div class="cert">
                    <svg class="corner top-left" viewBox="0 0 100 100"><path d="M0 0 L100 0 L100 10 L10 10 L10 100 L0 100 Z"/></svg>
                    <svg class="corner top-right" viewBox="0 0 100 100"><path d="M0 0 L100 0 L100 10 L10 10 L10 100 L0 100 Z"/></svg>
                    <svg class="corner bottom-left" viewBox="0 0 100 100"><path d="M0 0 L100 0 L100 10 L10 10 L10 100 L0 100 Z"/></svg>
                    <svg class="corner bottom-right" viewBox="0 0 100 100"><path d="M0 0 L100 0 L100 10 L10 10 L10 100 L0 100 Z"/></svg>

                    <img src="{{ $logoPath }}" class="watermark">

                    <div class="b-outer"></div>
                    <div class="b-inner"></div>

                    <div class="content-wrapper">
                        <img src="{{ $logoPath }}" alt="TechStrota" style="height:85px; margin-bottom:5mm; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                        
                        <div class="main-title">Certificate of Excellence</div>
                        
                        <p style="font-size:16pt; margin:10mm 0 2mm; color: #4a5568; letter-spacing: 1pt;">This document is proudly presented to</p>

                        <div class="recipient" style="font-size: {{ strlen($registration->name) > 20 ? '36pt' : '44pt' }};">
                            {{ Str::upper($registration->name) }}
                        </div>

                        <div class="underline"></div>

                        <div class="body-text">
                            for their outstanding participation and commitment during the 
                            <span style="color:#073c70; font-weight:700; font-style: normal;">{{ $registration->event->event_title }}</span>. 
                            This event was conducted by 
                            <strong>TechStrota</strong> on 
                            <strong>{{ \Carbon\Carbon::parse($registration->event->event_date)->format('dS F, Y') }}</strong>.
                        </div>

                        <div class="footer">
                            <div class="sig-box">
                                <div style="height: 15mm;"></div> <div class="sig-line"></div>
                                <span style="font-weight: 800; font-size: 12pt; color: #073c70; display: block; margin-bottom: 1mm;">JAMOD BADAL</span>
                                <span style="font-size: 10pt; color: #4a5568; text-transform: uppercase; letter-spacing: 1px;">Founder & CEO</span>
                            </div>

                            <div class="qr-section">
                                <div class="qr-wrapper">
                                    @php $certNum = $registration->certificate_number ?? $registration->generateCertificateNumber(); @endphp
                                    {!! QrCode::size(85)->margin(0)->generate(url('/verify-certificate/' . $certNum)) !!}
                                </div>
                                <span style="font-family: monospace; font-size: 9pt; font-weight: bold; color: #880E4F;">
                                    VERIFY ID: {{ $certNum }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>