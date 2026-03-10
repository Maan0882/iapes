<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
    /* 1. Define physical page margins to prevent content overlap */
    @page {
        margin: 100px 50px 120px 50px; /* top, right, bottom, left */
    }

    body {
        font-family: 'Times New Roman', serif;
        font-size: 14px;
        line-height: 1.6;
    }

    /* 2. Style and fix the Header */
    header {
        position: fixed;
        top: -60px; /* Push into the top margin space */
        left: 0;
        right: 0;
        height: 50px;
        text-align: center;
        font-size: 13px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
    }

    /* 3. Style and fix the Footer */
    footer {
        position: fixed;
        bottom: -100px; /* Push into the bottom margin space */
        left: 0;
        right: 0;
        height: 80px;
        font-size: 12px;
        text-align: center;
        border-top: 1px solid #ccc;
        padding-top: 10px;
    }

    /* 4. Main content styles */
    .subject {
        margin-top: 20px;
    }
    .date-section {
        margin-bottom: 20px;
    }
    .address {
        margin-top: 20px;
    }
    .signature {
        margin-top: 40px;
    }
    .page-break {
        page-break-after: always;
    }
</style>
</head>

<body>

    <header>
        <strong>Email:</strong> info@techstrota.com &nbsp;&nbsp;
        <img src="" alt="TECHSTROTA">&nbsp;&nbsp;
        <strong>Tel:</strong> +91 81288 40055
    </header>

    <footer>
        Techstrota<br>
        www.techstrota.com<br><br>
        156, 1st Floor, K10 Atlantis, C Tower, Near Genda Circle, Opp Honest Restaurant,<br>
        Vadodara, Gujarat - 390007
    </footer>

    <main>
        @if(isset($offers))
            @foreach($offers as $offer)
                
                <div class="address">
                    To,<br>
                    <strong>{{ strtoupper($offer->application->name) }}</strong><br>
                    {{ $offer->application->college }}<br>
                    {{ $offer->application->university }}<br>
                    {{ $offer->application->address }}
                </div>
                
                <br>

                <div class="subject">
                    <strong>Subject: Intern Offer / Appointment Letter</strong>
                </div>
                
                <div class="date-section">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($offer->created_at ?? now())->format('d/m/Y') }}
                </div>

                <p>
                    Dear <strong>{{ strtoupper($offer->application->name) }}</strong>,
                </p>

                <p>
                    We are pleased to offer you an internship position at Techstrota for the role of {{ $offer->internship_role }}. This internship presents an excellent opportunity for you to gain valuable experience and enhance your skills in software development by working with a talented and dynamic team.
                </p>
                
                <p>
                    As an intern, you will be responsible for:
                </p>
                <ul>
                    <li>Developing, testing, and debugging software applications and features using various technologies and tools.</li>
                    <li>Collaborating with other developers, designers, and project managers to deliver high quality products and services.</li>
                    <li>Following best practices and standards for coding, documentation and quality assurance.</li>
                    <li>Learning new skills and technologies and applying them to your projects.</li>
                    <li>Contributing to the improvement and innovation of the software development process and culture.</li>
                </ul>
                
                <p>
                    The internship will commence on {{ \Carbon\Carbon::parse($offer->joining_date)->format('d F Y') }} and will conclude on {{ \Carbon\Carbon::parse($offer->completion_date)->format('d F Y') }}. You will be expected to work {{ $offer->working_hours }} hours per week, from Monday to Saturday, between 10:30 AM to 5:30 PM.
                </p>
                
                <p>
                    Upon successful completion of the internship, you will receive a
                    <strong>Certificate of Completion</strong> and a
                    <strong>Letter of Recommendation</strong>.
                </p>
                
                <p>
                    To accept this offer, please sign and return this letter before the joining date.
                    If you have any questions or concerns, please feel free to contact us.
                </p>

                <p>
                    We are excited to have you join our team and look forward to working with you.
                </p>

                <div class="signature">
                    For, <strong>TECHSTROTA</strong><br><br>
                    Yours Sincerely<br><br>
                    <strong>Jamod Badal</strong><br>
                    CEO
                </div>

                <div class="page-break"></div>

            @endforeach
        @endif
    </main>

</body>
</html>