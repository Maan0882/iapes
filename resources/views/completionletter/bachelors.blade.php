<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            /* Optimized margins for single-page fit */
            margin: 0.4in 0.5in 0.8in 0.5in;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14.5px; /* Slightly reduced for better spatial distribution */
            line-height: 1.5;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* --- Sophisticated Watermark --- */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 75%;
            z-index: -1000;
            opacity: 0.08;
        }

        .watermark img {
            width: 100%;
            height: auto;
        }

        header {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-container {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* --- Modern Title Design --- */
        .subject {
            margin: 15px 0 25px 0;
            text-align: center;
            font-size: 20px;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #000;
        }

        .meta-section {
            margin-bottom: 15px;
            line-height: 1.4;
            text-align: left;
        }

        .content-p {
            margin-bottom: 12px;
            text-align: justify;
            text-justify: inter-word;
        }

        /* --- Professional Tech Grid (Saves space) --- */
        .tech-section {
            background: #fcfcfc;
            border: 1px solid #f0f0f0;
            padding: 10px 15px;
            margin: 15px 0;
        }

        .tech-list {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 13.5px;
        }

        .tech-list li {
            margin-bottom: 4px;
        }

        /* --- Clean Signature Section --- */
        .signature-section {
            margin-top: 40px;
            text-align: left;
            width: 200px;
        }

        .sig-line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
            width: 100%;
        }

        /* --- Footer Alignment --- */
        footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            width: 100%;
            line-height: 1.3;
            font-size: 11px;
            color: #444;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }

        .msme-logo {
            height: 30px;
            margin-bottom: 5px;
        }

        .system-remark {
            margin-top: 5px;
            font-style: italic;
            font-size: 10px;
            color: #888;
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }

        strong {
            font-weight: bold;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('images/TsLogo.png') }}" alt="Watermark">
    </div>

    @if(isset($offers))
        @foreach($offers as $offer)
            <header>
                <table class="header-table">
                    <tr>
                        <td style="text-align: left; width: 35%; font-size: 13px;">
                            <strong>Email: info@techstrota.com</strong>
                        </td>
                        <td style="text-align: center; width: 30%;">
                            <img src="{{ public_path('images/TsLogo.png') }}" style="height: 55px;" alt="Logo">
                        </td>
                        <td style="text-align: right; width: 35%; font-size: 13px;">
                            <strong>Tel: +91 90334 76660</strong>
                        </td>
                    </tr>
                </table>
            </header>

            <main class="content-container">
                <div class="subject">
                    Internship Completion Certificate
                </div>

                <div class="meta-section">
                    <strong>Issued on:</strong> {{ \Carbon\Carbon::parse($offer->completion_date ?? now())->format('d/m/Y') }} [cite: 5]<br>
                    <strong>Certificate ID:</strong> {{ $offer->intern?->intern_code ?? 'TS25/WD/01' }} [cite: 6]
                </div>

                <div class="content-p">
                    This is to certify that <strong>Mr./Ms. {{ $offer->application?->name }}</strong>, a student of 
                    <strong>{{ strtoupper($offer->application?->degree) }}</strong>, has successfully completed a 
                    <strong>{{ $offer->duration_days ?? '27' }} Days ({{ $offer->total_hours ?? '135' }} Hours)</strong> 
                    intensive internship with an overall performance <strong>Grade of {{ $offer->grade ?? 'A' }}</strong>. 
                    The program was specialized in <strong>“{{ $offer->internship_role }}”</strong>, 
                    conducted by <strong>Techstrota</strong> and facilitated by 
                    <strong>{{ strtoupper($offer->application?->college) }}</strong>[cite: 7, 8]. 
                    The internship was held from 
                    <strong>{{ \Carbon\Carbon::parse($offer->joining_date)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($offer->completion_date)->format('d/m/Y') }}</strong> 
                    at our Vadodara office[cite: 9].
                </div>

                <div class="content-p">
                    <strong>Project Engagement:</strong> As a core part of the curriculum, the intern contributed to the 
                    <strong>{{ $offer->project_name ?? 'Financial Service Intro Website project' }}</strong>[cite: 10]. 
                    Responsibilities included the development of <strong>About</strong>, <strong>Service</strong>, and <strong>Contact</strong> modules[cite: 10]. 
                    This engagement focused on translating client requirements into technical workflows, applying practical front-end skills, and 
                    building a custom <strong>Admin Backend</strong> for content management[cite: 11, 12].
                </div>

                <div class="tech-section">
                    <div style="font-weight: bold; margin-bottom: 5px; font-size: 14px;">Technical Competencies Acquired:</div>
                    <ul class="tech-list">
                        <li><strong>Languages & Frameworks:</strong> JavaScript, PHP, Laravel, Filament [cite: 14]</li>
                        <li><strong>Libraries:</strong> React, React Hook Form, Framer-motion [cite: 15]</li>
                        <li><strong>Tools:</strong> GitHub, XAMPP, Tailwind CSS, Bootstrap, VS Code [cite: 16]</li>
                        <li><strong>Industry Exposure:</strong> Git Collaboration, App Testing (Play Console) [cite: 16]</li>
                    </ul>
                </div>

                <div class="content-p" style="font-size: 13.5px;">
                    The student demonstrated exceptional dedication under the mentorship of <strong>{{ $offer->mentor_name ?? 'Ms. Vidhi Patel' }}</strong>[cite: 13]. 
                    We wish them the very best in their future professional endeavors.
                </div>

                <div class="signature-section">
                    <br><br>
                    <div class="sig-line"></div>
                    <strong>Badal Jamod</strong> [cite: 19]<br>
                    CEO/CTO, Techstrota [cite: 18, 17]
                </div>
            </main>

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif

    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%;">
                    <img src="{{ public_path('images/msme_logo.png') }}" class="msme-logo" alt="MSME Logo"><br>
                    <strong>Techstrota</strong> [cite: 20] | <span style="color: #0000EE;">www.techstrota.com</span> [cite: 21]<br>
                    156, K10 Atlantis, C tower, Near Genda Circle, Vadodara, Gujarat - 390007 [cite: 22]
                </td>
                <td style="width: 30%; vertical-align: bottom;">
                    <div class="system-remark">This is a system generated document.</div>
                </td>
            </tr>
        </table>
    </footer>
</body>
</html>