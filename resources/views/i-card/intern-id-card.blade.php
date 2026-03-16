<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: 'Helvetica', sans-serif; }
        
        /* Container must be relative for positioning inside */
        .card-container {
            position: relative;
            width: 153pt; 
            height: 243pt;
            overflow: hidden;
        }

        /* Background image is placed ABSOLUTE at the front (higher z-index) */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10; /* Circuit is at the front */
        }

        .int-id{
            position: absolute;
            top: 45pt;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            color: #1E73BE; 
            text-transform: uppercase;
        }
        
        /* Photo container is placed ABSOLUTE and behind the background */
.photo-container {
    position: absolute;
    /* Manually position the photo BEHIND the hole */
    top: 83pt; /* Adjust vertically to align with the hole */
    left: 47%; /* Adjust horizontally to center under the hole */
    width: 60pt; /* Size of the intern photo slightly smaller than the hole */
    height: 60pt;
    margin-left: -30pt; /* Negative margin for perfect centering */
    z-index: 5; /* Intern photo is behind the background */
    display: flex; /* Use flexbox to center image within container */
    justify-content: center;
    align-items: center;
    overflow: hidden; /* Hide anything outside the photo container */
}

/* Make sure the photo fills its container */
.photo {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Cover the whole area without distortion */
    border-radius: 4px; /* Slight rounded corner on the photo itself */
}

        .name {
            position: absolute;
            top: 140pt;
            width: 100%;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            color: #1a2b4c;
        }

        .role {
            position: absolute;
            top: 158pt;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            color: #f39200; 
            text-transform: uppercase;
        }
        
        .date{
            position: absolute;
            top: 170pt;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            color: #1E73BE; 

        }
        .contact {
            position: absolute;
            bottom: 42pt; 
            left: 36pt;
            font-size: 7.5pt;
            font-weight: bold;
            color: #1a2b4c;
        }

        .address {
            position: absolute;
            bottom: 12pt; 
            left: 38pt;
            font-size: 5.5pt;
            color: #333;
            width: 100pt;
            line-height: 1.1;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <img src="{{ $base64Image }}" class="bg-image">

        <div class="photo-container">
           @if($internImageBase64)
                <img src="{{ $internImageBase64 }}" class="photo">
           @endif
        </div>

        <div class="name">{{ strtoupper($intern->application->name) }}</div>
        <div class="role">INTERN</div>
        <div class="int-id">{{ $intern->intern_code }}</div>
        <div class="date">
            [{{ \Carbon\Carbon::parse($intern->offer_letters->joining_date)->format('d M Y') }}
        -
        {{ $intern->offer_letters?->completion_date 
            ? \Carbon\Carbon::parse($intern->offer_letters->completion_date)->format('d M Y') 
            : '' }}]
        </div>
        <div class="contact">{{ $intern->application->phone }}</div>
        <div class="address">
            TechSrota, Alkapuri,<br>
            Vadodara, Gujarat - 390007
        </div>
    </div>
</body>
</html>