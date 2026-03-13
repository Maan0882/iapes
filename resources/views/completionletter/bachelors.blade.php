<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            /* Standard page margins */
            margin: 0.4in 0.5in 0.6in 0.5in;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #000000; 
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* --- Sophisticated Watermark --- */
        .watermark {
            position: fixed;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            z-index: -1000;
            opacity: 0.05;
        }

        header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #1a2a6c; 
            padding-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* --- Increased Main Content Margins --- */
        main {
            padding: 0 40px; /* Increased horizontal margin for main tag only */
            margin-top: 10px;
        }

        .subject {
            margin: 10px 0 25px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #000000;
            text-transform: uppercase;
        }

        .meta-section {
            margin-bottom: 20px;
            font-size: 13px;
            color: #000000;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .content-p {
            margin-bottom: 18px;
            text-align: justify;
        }

        /* --- Premium Competency Box --- */
        .tech-section {
            background: #f8f9fa; 
            border-left: 5px solid #f39200; 
            border-top: 1px solid #e1e1e1;
            border-right: 1px solid #e1e1e1;
            border-bottom: 1px solid #e1e1e1;
            border-radius: 0 8px 8px 0;
            padding: 18px 25px;
            margin: 25px 0;
        }

        .tech-title {
            color: #000000;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tech-list {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 13.5px;
        }

        .tech-list li {
            margin-bottom: 6px;
        }

        /* --- Refined Signature Area --- */
        .signature-container {
            margin-top: 50px;
            width: 100%;
        }

        .sig-block {
            text-align: center;
            width: 250px;
        }

        .sig-line {
            border-top: 2px solid #1a2a6c;
            margin-bottom: 8px;
        }

        /* --- Footer --- */
        footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            width: 100%;
            font-size: 10.5px;
            color: #000000;
            border-top: 1px solid #dee2e6;
            padding-top: 12px;
        }

        .system-remark {
            font-style: italic;
            font-size: 9px;
            color: #c0392b; 
            text-align: right;
        }

        strong {
            color: #000000;
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('images/TsLogo.png') }}" style="width: 100%;">
    </div>

    @if(isset($offers))
        @foreach($offers as $offer)
            <header>
                <table class="header-table">
                    <tr>
                        <td style="text-align: left; width: 33%; font-size: 12px; font-weight: bold;">
                            Email: info@techstrota.com
                        </td>
                        <td style="text-align: center; width: 34%;">
                            <img src="{{ public_path('images/TsLogo.png') }}" style="height: 60px;">
                        </td>
                        <td style="text-align: right; width: 33%; font-size: 12px; font-weight: bold;">
                            Tel: +91 90334 76660
                        </td>
                    </tr>
                </table>
            </header>

            <main>
                <div class="subject">
                    Internship Completion Certificate
                </div>

                <div class="meta-section">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: left;"><strong>Issued on:</strong> {{ \Carbon\Carbon::parse($offer->completion_date ?? '03/12/2025')->format('d/m/Y') }}</td>
                            <td style="text-align: right;"><strong>Certificate ID No:</strong> {{ $offer->intern?->intern_code ?? 'TS25/WD/01' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="content-p">
                    This is to certify that <strong>{{ $offer->application?->name ?? 'Ms. Kajalben Bharatbhai Vanazara' }}</strong>, a student of 
                    <strong>{{ strtoupper($offer->application?->degree ?? 'BCA') }}</strong>, has successfully completed the 
                    <strong>27 Days (135 Hours)</strong> intensive internship with an overall performance <strong>Grade A</strong>. 
                    The internship was carried out for the course titled <strong>"Web Development"</strong>, conducted by <strong>Techstrota</strong> 
                    and facilitated by <strong>Shree P.M. Patel College of Computer Science & Technology, Anand</strong>. 
                    The internship duration was from <strong>03/11/2025 to 03/12/2025</strong> at Techstrota.
                </div>

                <div class="content-p">
                    <strong>Project Work:</strong> As part of the internship, the student worked on a 
                    <strong>Financial Service Intro Website project</strong>, which involved developing the 
                    <strong>About, Service and Contact modules</strong>. This real-world assignment helped the student understand 
                    client requirements, gain exposure to industry-level workflows, and apply practical front-end development skills effectively. 
                    Also built an <strong>Admin Backend for Blogs and Contacts</strong> with content management features.
                </div>

                <div class="tech-section">
                    <div class="tech-title">Technical Competencies Acquired</div>
                    <ul class="tech-list">
                        <li><strong>Language & Framework:</strong> JavaScript, PHP, Laravel, Filament</li>
                        <li><strong>Library:</strong> React, React hook form, framer-motion</li>
                        <li><strong>Tools & Technology:</strong> Github, Xampp, Tailwind, bootstrap, API Key, VS Code</li>
                        <li><strong>Extra Activity:</strong> Git Collab, APP Tester (Play Console)</li>
                    </ul>
                </div>

                <div class="content-p">
                    During the internship, the student worked under the guidance of <strong>Ms. Vidhi Patel</strong>. 
                    We acknowledge their dedication and wish them the very best in their future professional endeavors.
                </div>

                <div class="signature-container">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">
                                <div class="sig-block">
                                    <div style="height: 60px;"></div> <div class="sig-line"></div>
                                    <strong>Badal Jamod</strong><br>
                                    <span style="font-size: 11px;">CEO/CTO, Techstrota</span>
                                </div>
                            </td>
                            <!-- <td style="width: 50%; text-align: right;">
                                <div class="sig-block" style="display: inline-block;">
                                    <div style="height: 60px;"></div>
                                    <div class="sig-line"></div>
                                    <strong>Candidate Signature</strong>
                                </div>
                            </td> -->
                        </tr>
                    </table>
                </div>
            </main>

            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @endif

    <footer>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 75%; text-align: left;">
                    <strong>Techstrota</strong> | <span style="color: #000000;">www.techstrota.com</span><br>
                    156, 1st Floor, K10 Atlantis, C tower, Near Genda Circle, Opp Honest Restaurant,<br>
                    Vadodara, Gujarat-390007 | CIN: GJ240114897
                </td>
                <td style="width: 25%; vertical-align: bottom;">
                    <div class="system-remark">This is a system-generated document.</div>
                </td>
            </tr>
        </table>
    </footer>
</body>
</html>