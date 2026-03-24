@php $isPdf = $isPdf ?? false; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @if(!$isPdf)
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Internship Certificate - TechStrota</title>
    @endif
</head>
<body style="margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
<div id="certificate-wrapper">
    
    <div id="certificate-container">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Inter:wght@400;500;600;700&family=Outfit:wght@300;400;600&display=swap');

            @media screen {
                #certificate-wrapper {
                    background-color: #0f172a;
                    background-image:
                        radial-gradient(at 0% 0%, rgba(14, 114, 180, 0.25) 0px, transparent 50%),
                        radial-gradient(at 100% 0%, rgba(244, 162, 67, 0.2) 0px, transparent 50%),
                        radial-gradient(at 50% 50%, rgba(30, 41, 59, 0.5) 0px, transparent 100%),
                        linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
                    background-size: 100% 100%, 100% 100%, 100% 100%, 40px 40px, 40px 40px;
                    min-height: 100vh;
                    padding: 100px 0 60px 0;
                    font-family: 'Outfit', sans-serif;
                }
                .preview-header {
                    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
                    background: rgba(15, 23, 42, 0.8);
                    backdrop-filter: blur(12px);
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                    padding: 1rem 2rem;
                }
                .header-content {
                    max-width: 1200px; margin: 0 auto;
                    display: flex; justify-content: space-between; align-items: center;
                }
                .header-left h1 { color: #f8fafc; font-size: 1.25rem; font-weight: 600; margin: 0; letter-spacing: -0.02em; }
                .preview-badge {
                    display: inline-block; padding: 2px 8px; border-radius: 4px;
                    background: rgba(14, 114, 180, 0.2); color: #3b82f6;
                    font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
                    margin-bottom: 4px; border: 1px solid rgba(59, 130, 246, 0.2);
                }
                .print-btn {
                    background: linear-gradient(135deg, #0e72b4 0%, #0a4f7c 100%);
                    color: white; border: none; padding: 0.6rem 1.25rem;
                    border-radius: 8px; font-weight: 600; font-size: 0.9rem;
                    cursor: pointer; display: flex; align-items: center; gap: 0.5rem;
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                    box-shadow: 0 4px 12px rgba(14, 114, 180, 0.3);
                }
                .print-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(14, 114, 180, 0.4); filter: brightness(1.1); }
                .print-btn svg { width: 1.2rem; height: 1.2rem; }
                #certificate-container {
                    display: flex; flex-direction: column; align-items: center; gap: 60px;
                }
                .cert {
                    box-shadow: 0 30px 60px rgba(0,0,0,0.5);
                    transition: transform 0.4s ease;
                }
                .cert:hover { transform: scale(1.01); }

            }

            @media print {
                @page { size: A4 landscape; margin: 0; }
                body { margin: 0; padding: 0; background: white; }
                .no-print { display: none !important; }
                * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                body * { visibility: hidden !important; }
                #certificate-container, #certificate-container * { visibility: visible !important; }
                #certificate-container {
                    position: absolute !important; left: 0 !important; top: 0 !important;
                    width: 297mm !important; margin: 0 !important; padding: 0 !important;
                }
                html, body { background: white !important; margin: 0 !important; padding: 0 !important; }
                .cert { margin: 0 !important; page-break-after: always !important; display: block !important; box-shadow: none !important; }
            }

            .cert {
                position: relative; width: 297mm; height: 210mm;
                background: #ffffff !important; overflow: hidden;
                box-sizing: border-box; font-family: 'Inter', sans-serif; color: #2d3748;
            }
            .b-outer { position: absolute; top: 10mm; left: 10mm; right: 10mm; bottom: 10mm; border: 3pt solid #0e72b4; z-index: 5; }
            .b-inner { position: absolute; top: 13mm; left: 13mm; right: 13mm; bottom: 13mm; border: 1pt solid #f4a243; z-index: 5; }
            .wave { position: absolute; width: 55mm; height: 55mm; z-index: 2; pointer-events: none; opacity: 0.8; }
            .wave-tl-1 { top: -12mm; left: -12mm; fill: #0e72b4; }
            .wave-tl-2 { top: 8mm; left: -18mm; fill: #f4a243; opacity: 0.6; transform: rotate(15deg); }
            .wave-tr-1 { top: -10mm; right: -12mm; fill: #f4a243; transform: scaleX(-1); }
            .wave-tr-2 { top: -15mm; right: -5mm; fill: #0e72b4; opacity: 0.6; transform: scaleX(-1) rotate(45deg); }
            .wave-bl-1 { bottom: -10mm; left: -12mm; fill: #f4a243; transform: scaleY(-1); }
            .wave-bl-2 { bottom: -20mm; left: -5mm; fill: #0e72b4; opacity: 0.6; transform: scaleY(-1) rotate(-30deg); }
            .wave-br-1 { bottom: -12mm; right: -12mm; fill: #0e72b4; transform: scale(-1); }
            .wave-br-2 { bottom: 8mm; right: -18mm; fill: #f4a243; opacity: 0.6; transform: scale(-1) rotate(-15deg); }
            .wm-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none; }
            .content-wrapper {
                position: relative; z-index: 10; width: 100%; height: 100%;
                display: flex; flex-direction: column; align-items: center;
                padding: 25mm 40mm; text-align: center; box-sizing: border-box;
            }
            .main-title {
                font-family: 'Cormorant Garamond', serif;
                font-size: 36pt; font-weight: 700; color: #0e72b4;
                text-transform: uppercase; letter-spacing: 2pt; margin-top: 1mm;
            }
            .recipient {
                font-family: 'Cormorant Garamond', serif; font-size: 48pt; font-weight: 600;
                color: #1a202c; margin: 3mm 0 1mm; font-style: italic;
            }
            .underline { width: 80%; height: 2pt; background: linear-gradient(90deg, transparent, #f4a243, transparent); margin-bottom: 15mm; }
            .body-text { font-size: 15.5pt; line-height: 1.8; color: #000000; max-width: 95%; margin-top: -6mm; }
            .footer {
                position: absolute; bottom: 25mm; left: 35mm; right: 35mm;
                display: flex; justify-content: space-between; align-items: flex-end;
            }
            .sig-box { width: 60mm; text-align: center; }
            .line { border-top: 1.5pt solid #cbd5e0; margin-bottom: 3mm; }
            .outer-footer {
                position: absolute; bottom: 3mm; left: 10mm; right: 10mm; z-index: 10;
                display: flex; justify-content: space-between; align-items: center;
                font-size: 8pt; color: #070707ff; font-family: 'Inter', sans-serif;
            }
            .outer-footer .website {
                position: absolute; left: 50%; transform: translateX(-50%);
                font-weight: 500; letter-spacing: 0.5pt;
            }
            .outer-footer .system-gen { margin-left: auto; font-style: italic; color: maroon; opacity: 1; }

            /* Responsive Scaling for Mobile - MUST BE AT THE END TO OVERRIDE */
            @media screen and (max-width: 1150px) {
                #certificate-wrapper {
                    padding: 60px 0 20px 0 !important;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    overflow-x: hidden;
                    box-sizing: border-box;
                }
                #certificate-container {
                    width: 100vw !important;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 20px !important;
                }
                .cert-scale-wrapper {
                    width: 100vw;
                    display: flex;
                    justify-content: center;
                    /* height set by JS */
                }
                .cert {
                    width: 1122px !important;
                    height: 794px !important;
                    margin: 0 !important;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.4) !important;
                    transform-origin: top center !important;
                    flex-shrink: 0;
                }
                .preview-header {
                    padding: 0.4rem 1rem !important;
                }
                .header-content {
                    flex-direction: row;
                    font-size: 0.75rem;
                }
            }

            /* Landscape: tighter top padding since header is smaller */
            @media screen and (max-width: 1150px) and (orientation: landscape) {
                #certificate-wrapper {
                    padding: 50px 0 10px 0 !important;
                }
                #certificate-container {
                    gap: 10px !important;
                }
            }
        </style>

        @if(isset($offers) && $offers->isNotEmpty())
            @foreach($offers as $offer)
                <div class="cert-scale-wrapper">
                <div class="cert">
                    @php
                        mt_srand($offer->id ?? 0);
                        $blob1 = "M44.7,-76.4C58.3,-69.2,70.1,-59,78.5,-46.3C86.9,-33.5,92,-18.3,91.3,-3.3C90.7,11.7,84.3,26.5,75.2,39.5C66.1,52.5,54.2,63.7,40.5,71.2C26.9,78.7,11.5,82.5,-3.1,87.8C-17.7,93.1,-31.4,100,-44.1,96.4C-56.8,92.8,-68.5,78.7,-76.5,64.1C-84.5,49.5,-88.8,34.4,-91,19.3C-93.2,4.2,-93.3,-10.8,-88.9,-24.8C-84.5,-38.8,-75.6,-51.8,-63.9,-59.5C-52.2,-67.2,-37.7,-69.6,-24.7,-76.1C-11.7,-82.6,0,-93.2,12.7,-91.1C25.4,-89,31.1,-83.6,44.7,-76.4Z";
                        $blob2 = "M41.5,-71.4C53.3,-66.1,61.4,-50.2,67.9,-34.7C74.4,-19.2,79.2,-4.2,77.8,10.2C76.4,24.6,68.8,38.3,58.3,49.4C47.8,60.5,34.4,69.1,20.2,74.1C6,79.1,-9.1,80.5,-23.8,77.7C-38.4,74.9,-52.6,67.9,-63.9,57.5C-75.2,47.1,-83.6,33.4,-88.4,18.5C-93.2,3.6,-94.3,-12.4,-88.7,-25.9C-83.1,-39.3,-70.7,-50.3,-57.7,-57.5Z";
                    @endphp

                    <svg class="wave wave-tl-2" viewBox="0 0 200 200"><path d="{{ $blob2 }}" transform="translate(100 100)"/></svg>
                    <svg class="wave wave-tl-1" viewBox="0 0 200 200"><path d="{{ $blob1 }}" transform="translate(100 100)"/></svg>
                    <svg class="wave wave-tr-2" viewBox="0 0 200 200"><path d="{{ $blob1 }}" transform="translate(100 100)"/></svg>
                    <svg class="wave wave-tr-1" viewBox="0 0 200 200"><path d="{{ $blob2 }}" transform="translate(100 100)"/></svg>
                    <svg class="wave wave-bl-2" viewBox="0 0 200 200"><path d="{{ $blob1 }}" transform="translate(100 100)"/></svg>
                    <svg class="wave wave-bl-1" viewBox="0 0 200 200"><path d="{{ $blob2 }}" transform="translate(100 100)"/></svg>
                    <svg class="wave wave-br-2" viewBox="0 0 200 200"><path d="{{ $blob2 }}" transform="translate(100 100)"/></svg>
                    <svg class="wave wave-br-1" viewBox="0 0 200 200"><path d="{{ $blob1 }}" transform="translate(100 100)"/></svg>

                    <div class="b-outer"></div>
                    <div class="b-inner"></div>

                    @php
                        $icons = ['php', 'laravel', 'mysql', 'python', 'nodedotjs', 'react', 'git', 'tailwindcss', 'typescript', 'javascript', 'docker', 'html5', 'php', 'laravel', 'mysql', 'react', 'git', 'docker'];
                        shuffle($icons);
                        $slots = [];
                        for ($r = 0; $r < 4; $r++) { for ($c = 0; $c < 6; $c++) { $slots[] = ['r' => $r, 'c' => $c]; } }
                        shuffle($slots);
                    @endphp

                    <div class="wm-overlay">
                        @foreach($icons as $index => $icon)
                            @if(isset($slots[$index]))
                                @php
                                    $slot = $slots[$index];
                                    $top  = ($slot['r'] * 22) + 6 + mt_rand(0, 4);
                                    $left = ($slot['c'] * 15) + 5 + mt_rand(0, 4);
                                    $size = mt_rand(11, 16);
                                    $rot  = mt_rand(-30, 30);
                                @endphp
                                <img src="https://cdn.simpleicons.org/{{ $icon }}/0e72b4"

                                style="position:absolute;top:{{ $top }}%;left:{{ $left }}%;width:{{ $size }}mm;opacity:0.2;transform:rotate({{ $rot }}deg);">
                            @endif
                        @endforeach
                    </div>

                    <div class="content-wrapper">
                        <img src="{{ asset('images/TsLogo.png') }}" alt="TechStrota" style="height:80px;margin-bottom:6mm;margin-top:-7mm;">
                        <div class="main-title">Certificate of Internship</div>
                        <p style="font-size:14pt;color:#718096;margin:2mm 0;">This is to certify that</p>
                        <div class="recipient">{{ $offer->application?->name ?? 'Student Name' }}</div>
                        <div class="underline"></div>
                        <div class="body-text">
                            has successfully completed a <b>{{ $offer->internship_role ?? 'Software Development' }}</b> internship
                            at <b>TechStrota</b>. The internship was conducted from
                            <b>{!! !empty($offer->joining_date) ? \Carbon\Carbon::parse($offer->joining_date)->format('dS F Y') : '01 Dec 2025' !!}</b> to
                            <b>{!! !empty($offer->completion_date) ? \Carbon\Carbon::parse($offer->completion_date)->format('dS F Y') : '31 Dec 2025' !!}</b>.
                            During this tenure, the intern demonstrated exceptional professional conduct and technical proficiency.
                        </div>
                        <div class="footer">
                            <div class="sig-box">
                                <div class="line"></div>
                                <b style="color:#2d3748;font-size:12pt;font-weight:700;">Founder/CEO</b><br>
                                <span style="font-size:9.5pt;color:#718096;font-weight:500;">TechStrota</span>
                            </div>
                            <div class="stamp-box" style="width:35mm;height:35mm;">
                                
                            </div>
                            <div class="sig-box" style="display:flex;flex-direction:column;align-items:center;">
                                <div style="width:22mm;height:22mm;border:1pt solid #edf2f7;background:#f7fafc;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:7pt;color:#a0aec0;">
                                    {!! QrCode::size(150)->generate(route('certificate.verify',str_replace('/', '-',$offer->intern->intern_code))) !!}

                                </div>
                                <span style="font-size:8pt;color:#4a5568;margin-top:3mm;font-family:monospace;letter-spacing:0.5pt;">
                                    ID: {{ $offer->intern->intern_code ?? '000' }}
                                </span>
                            </div>
                        </div>
                        <div class="outer-footer">
                            <div class="website">
                                <a href="https://techstrota.com" style="color:inherit;text-decoration:none;">www.techstrota.com</a>
                            </div>
                            <div class="system-gen">This is a system generated certificate</div>
                        </div>
                    </div>
                </div>{{-- /.cert --}}
                </div>{{-- /.cert-scale-wrapper --}}
            @endforeach
        @endif
    </div>
</div>
</body>
</html>
@if(!$isPdf)
<script>
(function() {
    var CERT_W = 1122; // px — 297mm at 96dpi
    var CERT_H = 794;  // px — 210mm at 96dpi
    var HEADER_H = 48; // approximate fixed header height in px

    function scaleCerts() {
        if (window.innerWidth >= 1150) {
            // Desktop: remove any inline styles set by this script
            document.querySelectorAll('.cert-scale-wrapper').forEach(function(w) {
                w.style.height = '';
                var c = w.querySelector('.cert');
                if (c) c.style.transform = '';
            });
            return;
        }

        var vw = window.innerWidth;
        var vh = window.innerHeight;
        var isLandscape = vw > vh;

        // Available vertical space for one certificate (minus header + padding)
        var availableH = vh - HEADER_H - (isLandscape ? 20 : 40);

        // Scale to fit width first
        var scaleByW = vw / CERT_W;
        // Scale to fit height (so full cert is visible without vertical scroll in landscape)
        var scaleByH = availableH / CERT_H;

        // In landscape: use the smaller of the two so it fits both axes
        // In portrait: just scale to width (vertical scroll is fine)
        var scale = isLandscape ? Math.min(scaleByW, scaleByH) : scaleByW;

        document.querySelectorAll('.cert-scale-wrapper').forEach(function(wrapper) {
            var cert = wrapper.querySelector('.cert');
            if (!cert) return;
            cert.style.transform = 'scale(' + scale + ')';
            cert.style.transformOrigin = 'top center';
            // Set wrapper height to match actual rendered height so page flow is correct
            wrapper.style.height = Math.ceil(CERT_H * scale) + 'px';
        });
    }

    document.addEventListener('DOMContentLoaded', scaleCerts);
    window.addEventListener('resize', scaleCerts);
    // orientationchange fires before innerWidth/Height update — delay slightly
    window.addEventListener('orientationchange', function() {
        setTimeout(scaleCerts, 150);
    });
})();
</script>
@endif

@if(request()->query('print') === 'true' && auth()->user()?->canAccessPanel(filament()->getPanel('admin')))
<script>
    window.addEventListener('load', function() {
        setTimeout(function() { window.print(); }, 500);
    });
</script>
@endif
@if(!auth()->user()?->canAccessPanel(filament()->getPanel('admin')))
<script>
    window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            alert('Printing is disabled for this document.');
        }
    });
</script>
@endif