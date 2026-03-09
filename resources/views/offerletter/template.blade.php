<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
    font-family: Times New Roman, serif;
    font-size: 14px;
    line-height: 1.6;
}

.header{
    text-align:center;
    margin-bottom:20px;
}

.company-info{
    text-align:center;
    font-size:13px;
}

.subject{
    margin-top:20px;
}

.address{
    margin-top:20px;
}

.signature{
    margin-top:40px;
}

.footer{
    margin-top:40px;
    font-size:12px;
    text-align:center;
}

</style>
</head>

<body>
@if(isset($offers))
    @foreach($offers as $offer)


        <div class="company-info">
        <strong>Email:</strong> info@techstrota.com &nbsp;&nbsp;
        <strong>Tel:</strong> +91 81288 40055
        </div>

        <br>
        <div class="address">
        To,<br>
        <strong>{{ strtoupper($offer->application->name) }}</strong><br>

        {{ $offer->application->college }}<br>

        {{ $offer->application->university }}<br>

        {{ $offer->application->address }}

        </div>
        <br>
        <div>
        <strong>Date:</strong> {{ now()->format('d/m/Y') }}
        </div>

        <div class="subject">
        <strong>Subject: Intern Offer / Appointment Letter</strong>
        </div>

        <br>

        <p>
        Dear <strong>{{ strtoupper($offer->application->name) }}</strong>,
        </p>

        <p>
        We are pleased to offer you an internship position at
        <strong>Techstrota</strong> for the role of
        <strong>{{ $offer->internship_role }}</strong>.
        This internship presents an excellent opportunity for you to gain valuable
        experience and enhance your skills in software development by working with a
        talented and dynamic team.
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
        <br>
        <p>
        The internship will commence on
        <strong>{{ \Carbon\Carbon::parse($offer->joining_date)->format('d F Y') }}</strong>
        and will conclude on
        <strong>{{ \Carbon\Carbon::parse($offer->completion_date)->format('d F Y') }}</strong>.
        </p>
        <p>
        You will be expected to work
        <strong>{{ $offer->working_hours }}</strong>
        hours per week</strong>, from <strong>Monday to Saturday</strong>, between <strong>10:30 AM to 5:30 PM</strong>.
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

        For, <strong>TECHSTROTA</strong>

        <br><br>

        Yours Sincerely

        <br><br>

        <strong>Jamod Badal</strong><br>
        CEO

        </div>

        <div class="footer">

        <br>

        Techstrota<br>

        www.techstrota.com

        <br><br>

        156, 1st Floor, K10 Atlantis, C Tower, Near Genda Circle, Opp Honest Restaurant,<br>

        Vadodara, Gujarat - 390007

        </div>

    <div style="page-break-after: always;"></div>
    @endforeach
@endif
</body>
</html>