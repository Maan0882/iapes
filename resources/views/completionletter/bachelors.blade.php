<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page {
        margin: 40px 60px 40px 60px;
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 14px;
        line-height: 1.6;
        color: #000;
        margin: 0;
        padding: 0;
    }

    header {
        width: 100%;
        margin-bottom: 20px;
    }

    .header-logo {
        height: 70px;
    }

    .header-table {
        width: 100%;
        border-collapse: collapse;
    }

    .subject {
        margin: 40px 0 30px 0;
        text-align: center;
        font-size: 18px;
        letter-spacing: 1px;
    }

    .meta-section {
        margin-bottom: 25px;
        line-height: 1.4;
    }

    .content-p {
        margin-bottom: 15px;
        text-align: justify;
    }

    .tech-list {
        list-style-type: disc;
        margin-left: 40px;
        margin-bottom: 30px;
    }

    .tech-list li {
        margin-bottom: 5px;
    }

    .signature-section {
        margin-top: 50px;
    }

    .page-break {
        page-break-after: always;
    }

    strong {
        font-weight: bold;
    }
</style>
</head>

<body>
    @if(isset($offers))
        @foreach($offers as $offer)
            <header>
                <table class="header-table">
                    <tr>
                        <td style="text-align: left; width: 35%; font-size: 13px;">
                            <strong>Email:</strong> info@techstrota.com
                        </td>
                        <td style="text-align: center; width: 30%;">
                            <img src="{{ public_path('storage/images/TsLogo.png') }}" class="header-logo" alt="Logo">
                        </td>
                        <td style="text-align: right; width: 35%; font-size: 13px;">
                            <strong>Tel:</strong> +91 90334 76660
                        </td>
                    </tr>
                </table>
            </header>

            <main>
                <div class="subject">
                    <strong><u>INTERNSHIP COMPLETION CERTIFICATE</u></strong>
                </div>

                <div class="meta-section">
                    <strong>Issued on: {{ \Carbon\Carbon::parse($offer->completion_date ?? now())->format('d/m/Y') }}</strong><br>
                    <strong>Certificate ID No.: {{ $offer->intern_code }}</strong>
                </div>

                <div class="content-p">
                    This is to certify that <strong>Ms. {{ $offer->application->name }}</strong>, a student of 
                    <strong>{{ strtoupper($offer->application->degree) }}</strong>, has successfully completed the 
                    <strong>{{ $offer->duration_days ?? '27' }} Days ({{ $offer->total_hours ?? '135' }} Hours)</strong> 
                    with <strong>Grade {{ $offer->grade ?? 'A' }}</strong>. 
                    The internship was carried out for the course titled <strong>“Web Development”</strong>, 
                    conducted by <strong>Techstrota</strong> and facilitated by 
                    <strong>Shree P.M. Patel College of Computer Science & Technology, Anand.</strong> 
                    The internship duration was from 
                    <strong>{{ \Carbon\Carbon::parse($offer->joining_date)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($offer->completion_date)->format('d/m/Y') }}</strong> 
                    at <strong>Techstrota</strong>. 156, K-10 Atlantis, Near Genda Circle, Vadodara, Gujarat – 390007
                </div>

                <div class="content-p">
                    <strong>Project Work:</strong> As part of the internship, the student worked on a 
                    <strong>{{ $offer->project_name ?? 'Financial Service Intro Website project' }}</strong>, 
                    which involved developing the <strong>About</strong>, <strong>Service</strong> and <strong>Contact</strong> modules. 
                    This real-world assignment helped the student understand client requirements, gain exposure to industry-level workflows, 
                    and apply practical front-end development skills effectively. Also Built an <strong>Admin Backend for Blogs</strong> 
                    and <strong>Contacts</strong> with content management features.
                </div>

                <div class="content-p">
                    During the internship, the student worked under the guidance of <strong>{{ $offer->mentor_name ?? 'Ms. Vidhi Patel' }}</strong> 
                    and was trained in the following technologies:
                </div>

                <ul class="tech-list">
                    <li><strong>Language:</strong> JavaScript, PHP</li>
                    <li><strong>Framework:</strong> Laravel, Filament</li>
                    <li><strong>Library:</strong> React, React hook form, framer-motion</li>
                    <li><strong>Tools & Technology:</strong> Github, Xampp, Tailwind, bootstrap, API Key, VS Code</li>
                    <li><strong>Extra Activity:</strong> Git Collab, APP Tester (Play Console)</li>
                </ul>

                <div class="signature-section">
                    <strong>Techstrota ,</strong><br><br><br>
                    CEO/CTO<br>
                    <strong>Badal Jamod</strong>
                </div>
            </main>

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif
</body>
</html>