<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Certificate - {{ $offer->application?->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,700;1,400&family=Noto+Sans+Gujarati:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* PDF/Print Reset */
        @page { size: landscape; margin: 0; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #e2e8f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Typography */
        .serif { font-family: 'Playfair Display', serif; }
        .gujarati { font-family: 'Noto Sans Gujarati', sans-serif; }

        /* Main Container */
        .certificate-container {
            position: relative;
            width: 1123px;
            height: 794px;
            background-color: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            overflow: hidden;
            margin: auto;
            /* Layout Border */
            border: 20px solid #f8fafc;
            outline: 2px solid #3b82f6;
            outline-offset: -12px;
        }

        /* Left Sidebar */
        .sidebar {
            width: 25%;
            background-color: #0f172a;
            color: white;
            padding: 50px 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            z-index: 10;
        }

        .logo-section .brand-text {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .accent-line {
            height: 4px;
            width: 60px;
            background-color: #3b82f6;
            margin-top: 12px;
            border-radius: 99px;
        }

        /* Right Content Area */
        .content-area {
            width: 75%;
            padding: 80px;
            display: flex;
            flex-direction: column;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        }

        .badge-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .badge-line { height: 1px; width: 48px; background-color: #bfdbfe; }
        .badge-text { font-size: 12px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 4px; }

        .main-title { font-size: 64px; color: #0f172a; margin: 0 0 8px 0; line-height: 1.1; }
        .title-accent { font-style: italic; color: #64748b; font-weight: 300; font-size: 52px; }
        .title-bar { height: 6px; width: 80px; background-color: #2563eb; margin-bottom: 48px; }

        .certify-text { font-size: 20px; color: #64748b; margin-bottom: 16px; }
        .intern-name { font-size: 52px; font-weight: 800; color: #0f172a; margin: 0 0 40px 0; letter-spacing: -1px; }

        .description { font-size: 20px; line-height: 1.6; color: #475569; max-width: 650px; }
        .highlight { font-weight: 700; color: #0f172a; text-decoration: underline; text-decoration-color: #3b82f6; text-underline-offset: 6px; }

        .performance-note {
            font-size: 16px;
            font-style: italic;
            color: #64748b;
            border-left: 4px solid #e2e8f0;
            padding-left: 24px;
            margin-top: 32px;
        }

        /* Footer & QR */
        .qr-box { background: white; padding: 10px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: inline-block; }
        .id-text { font-size: 11px; font-family: monospace; color: #60a5fa; font-weight: 700; margin-top: 16px; text-transform: uppercase; }

        .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; }
        .signature-name { font-size: 14px; font-weight: 700; color: #0f172a; text-transform: uppercase; margin: 4px 0; }
        .signature-title { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }

        .location-info { text-align: right; }
        .location-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .location-text { font-size: 14px; font-weight: 600; color: #334155; }
        .website { font-size: 14px; font-weight: 700; color: #2563eb; margin-top: 4px; }
    </style>
</head>
<body>

    @if(isset($offers))
        @foreach($offers as $offer)
        <div class="certificate-container">
            
            <div class="sidebar">
                <div class="logo-section">
                    <div class="brand-text">
                        <span>TECH</span>
                        <span class="gujarati" style="color: #60a5fa;">स्त्रोत</span>
                    </div> 
                    <div class="accent-line"></div>
                </div>

                <div>
                    <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;">Verified Document</div>
                    <div class="qr-box">
                        <img src="data:image/png;base64, {{ $qr_code ?? '' }}" alt="QR" style="width: 100px; height: 100px; display: block;">
                    </div>
                    <div class="id-text">
                        ID: {{ $offer->offer_letter_code ?? $offer->intern?->intern_code ?? 'TS25/WD/17' }}
                    </div>
                </div>

                <div style="font-size: 10px; color: #475569; font-style: italic;">
                    This certificate is issued to verify the successful completion of an internship at TechStrota.
                </div>
            </div>

            <div class="content-area">
                <div class="badge-header">
                    <div class="badge-line"></div>
                    <span class="badge-text">Official Certification</span>
                </div>

                <h1 class="serif main-title">
                    Certificate <span class="title-accent">of</span> Internship
                </h1>
                <div class="title-bar"></div>

                <p class="certify-text">This is to certify that</p>
                <h2 class="intern-name">{{ $offer->application?->name ?? 'Dharmik R Gajjar' }}</h2>

                <div class="description">
                    has successfully completed the <span class="highlight">{{ $offer->internship_role ?? 'Web Development' }} Internship</span> 
                    at <span style="font-weight: 800; color: #0f172a;">TechStrota</span> from 
                    <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($offer->joining_date ?? '2025-12-01')->format('jS F Y') }}</span> 
                    to <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($offer->completion_date ?? '2025-12-31')->format('jS F Y') }}</span>.
                </div>

                <p class="performance-note">
                    During the internship period, the intern actively contributed to learning and real-time development tasks and demonstrated 
                    excellent discipline, creativity, and problem-solving skills.
                </p>

                <div class="footer">
                    <div>
                        <div class="serif" style="font-size: 28px; color: #cbd5e1; font-style: italic; margin-bottom: -10px;">Jamod Badal</div>
                        <div style="width: 220px; height: 1px; background-color: #cbd5e1;"></div>
                        <p class="signature-name">Jamod Badal</p>
                        <p class="signature-title">Founder / CEO, TechStrota</p>
                    </div>
                    
                    <div class="location-info">
                        <p class="location-label">Issued At</p>
                        <p class="location-text">Vadodara, Gujarat, India</p>
                        <p class="website">www.techstrota.com</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
        @endforeach
    @endif

</body>
</html>