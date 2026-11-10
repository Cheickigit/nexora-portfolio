@php
    $L = $locale;
    $other = $L === 'fr' ? 'en' : 'fr';
    $pick = fn ($m, $f) => $m->{$f.'_'.$L} ?: ($m->{$f.'_'.$other} ?? '');

    $accent = '#00d4ff'; // Cyan
    $dark = '#0a0c16';   // Fond sombre
    $grayText = '#475569';

    $cfg = $portfolio->profileConfig();
    $summary = $portfolio->{'summary_'.$L} ?: $portfolio->{'summary_'.$other};

    // Filigrane (logo en base64)
    $logoPath = public_path('images/nexora-mark.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    // Icônes SVG (data URI) : remplacent les glyphes manquants dans Helvetica
    $icon = function (string $paths) use ($accent) {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="'.$accent.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'.$paths.'</svg>';
        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    };

    $icons = [
        'mail'  => $icon('<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>'),
        'phone' => $icon('<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>'),
        'pin'   => $icon('<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>'),
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $L }}">
<head>
<meta charset="utf-8">
<title>CV - {{ $portfolio->displayName() }}</title>
<style>
    @page {
        margin: 27pt 0;
        size: A4;
    }
    body {
        margin: 0;
        padding: 0;
        font-family: 'Helvetica', Arial, sans-serif;
        font-size: 9pt;
        color: #333;
        line-height: 1.4;
        background: #ffffff;
    }

    /* Filigrane (répété sur chaque page) */
    .watermark {
        position: fixed;
        top: 25%;
        left: 15%;
        width: 600px;
        height: 600px;
        opacity: 0.03;
        z-index: -2;
    }

    /* Fond sombre de la colonne gauche, répété sur chaque page */
    .bg-sidebar {
        position: fixed;
        top: -27pt;
        left: 0;
        width: 190pt;
        height: 842pt;
        background-color: {{ $dark }};
        z-index: -1;
    }

    /* Colonne gauche : uniquement sur la page 1 */
    .sidebar {
        position: absolute;
        top: -27pt;
        left: 0;
        width: 154pt;
        padding: 50pt 18pt 0 18pt;
        color: white;
    }

    /* Contenu principal : s'écoule sur plusieurs pages */
    .main {
        margin-left: 190pt;
        padding: 3pt 30pt 0 28pt;
        background: transparent;
    }

    /* Photo */
    .photo-container { text-align: center; margin-bottom: 20px; }
    .photo { width: 110px; height: 110px; border-radius: 50%; border: 3px solid {{ $accent }}; object-fit: cover; }

    /* Titres */
    h1 { font-size: 26pt; margin: 0 0 5px 0; color: #111; line-height: 1.1; text-transform: uppercase; letter-spacing: -1px; }
    h2.job-title { font-size: 12pt; color: {{ $accent }}; margin: 0 0 15px 0; font-weight: bold; letter-spacing: 1px; }
    h3.section-title { font-size: 11pt; text-transform: uppercase; color: #111; border-bottom: 2px solid {{ $accent }}; padding-bottom: 4px; margin: 20px 0 10px 0; letter-spacing: 1px; page-break-after: avoid; }

    /* Sidebar */
    .sidebar h3 { font-size: 10pt; text-transform: uppercase; border-bottom: 1px solid #333; padding-bottom: 4px; margin: 15px 0 10px 0; color: {{ $accent }}; }
    .sidebar p { margin: 0 0 6px 0; color: #ccc; font-size: 8.5pt; }
    .sidebar .label { font-weight: bold; color: #fff; display: block; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 1px; }

    /* Contact (icônes SVG) */
    table.contact-item { border-collapse: collapse; margin-bottom: 10px; }
    table.contact-item td { vertical-align: top; color: #ccc; font-size: 8.5pt; padding: 0; }
    table.contact-item td.ico-cell { width: 18px; padding-top: 1px; }
    .ico { width: 11px; height: 11px; }

    /* Skills */
    .skill-bar-container { margin-bottom: 8px; }
    .skill-name { font-size: 8pt; color: #fff; margin-bottom: 3px; display: block; }
    .skill-bar-bg { background: #222; height: 4px; width: 100%; border-radius: 2px; }
    .skill-bar-fill { background: {{ $accent }}; height: 4px; border-radius: 2px; }

    /* Items */
    .item { margin-bottom: 12px; page-break-inside: avoid; }
    .item-title { font-weight: bold; font-size: 10pt; color: #111; margin: 0; }
    .item-subtitle { font-size: 9pt; color: {{ $grayText }}; margin: 2px 0 4px 0; font-style: italic; }
    .item-desc { margin: 0; font-size: 8.5pt; color: #444; }
</style>
</head>
<body>

@if($logoBase64)
    <img src="{{ $logoBase64 }}" class="watermark" alt="">
@endif

<div class="bg-sidebar"></div>

        {{-- ===================== SIDEBAR ===================== --}}
        <div class="sidebar">
            @if(!empty($photo))
                <div class="photo-container">
                    <img src="{{ $photo }}" class="photo" alt="Photo">
                </div>
            @endif

            <h3>CONTACT</h3>
            @foreach ([['mail', $portfolio->email], ['phone', $portfolio->phone], ['pin', $portfolio->location]] as [$key, $value])
                @if($value)
                    <table class="contact-item">
                        <tr>
                            <td class="ico-cell"><img src="{{ $icons[$key] }}" class="ico" alt=""></td>
                            <td>{{ $value }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach

            @if(count($portfolio->links ?? []))
                <h3>LIENS</h3>
                @foreach($portfolio->links as $link)
                    <p style="margin-bottom: 8px;">
                        <span class="label">{{ $link['label'] }}</span>
                        {{ preg_replace('#^https?://(www\.)?#', '', $link['url']) }}
                    </p>
                @endforeach
            @endif

            @if($skills->isNotEmpty())
                <h3>COMPÉTENCES</h3>
                @foreach($skills as $group)
                    <div class="skill-bar-container">
                        <span class="skill-name">{{ $pick($group, 'label') }}</span>
                        @foreach($group->items ?? [] as $skill)
                            <div style="margin-bottom: 4px;">
                                <span style="font-size: 7.5pt; color: #ccc;">{{ $skill }}</span>
                                <div class="skill-bar-bg">
                                    <div class="skill-bar-fill" style="width: 85%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif

            @if($languages->isNotEmpty())
                <h3>LANGUES</h3>
                @foreach($languages as $lang)
                    <p>
                        <span class="label">{{ $pick($lang, 'name') }}</span>
                        {{ $pick($lang, 'level') }}
                    </p>
                @endforeach
            @endif
        </div>

        {{-- ===================== CONTENU PRINCIPAL ===================== --}}
        <div class="main">
            <h1>{{ $portfolio->displayName() }}</h1>
            <h2 class="job-title">{{ $portfolio->title }}</h2>

            @if($summary)
                <h3 class="section-title">PROFIL</h3>
                @foreach (preg_split('/\R{2,}/', trim($summary)) as $para)
                    <p style="font-size: 9pt; color: #444; line-height: 1.5; text-align: justify; margin: 0 0 6px 0;">
                        {{ preg_replace('/\s*\R\s*/', ' ', $para) }}
                    </p>
                @endforeach
            @endif

            @if($experiences->isNotEmpty())
                <h3 class="section-title">EXPÉRIENCES PROFESSIONNELLES</h3>
                @foreach($experiences as $e)
                    <div class="item">
                        <p class="item-title">{{ $pick($e, 'role') }}</p>
                        <p class="item-subtitle">
                            <strong>{{ $e->company }}</strong>
                            @if($pick($e, 'period')) | {{ $pick($e, 'period') }} @endif
                        </p>
                        @if($pick($e, 'desc'))
                            <p class="item-desc">{!! nl2br(e($pick($e, 'desc'))) !!}</p>
                        @endif
                    </div>
                @endforeach
            @endif

            @if($educations->isNotEmpty())
                <h3 class="section-title">DIPLÔMES & FORMATIONS</h3>
                @foreach($educations as $ed)
                    <div class="item">
                        <p class="item-title">{{ $pick($ed, 'degree') }}</p>
                        <p class="item-subtitle">
                            <strong>{{ $ed->school }}</strong>
                            @if($ed->year) | {{ $ed->year }} @endif
                        </p>
                        @if($pick($ed, 'desc'))
                            <p class="item-desc">{!! nl2br(e($pick($ed, 'desc'))) !!}</p>
                        @endif
                    </div>
                @endforeach
            @endif

            @if($certifications->isNotEmpty())
                <h3 class="section-title">CERTIFICATIONS</h3>
                @foreach($certifications as $c)
                    <div class="item">
                        <p class="item-title">{{ $pick($c, 'title') }}</p>
                        <p class="item-subtitle">
                            {{ $c->issuer }}
                            @if($c->year) | {{ $c->year }} @endif
                        </p>
                    </div>
                @endforeach
            @endif

            @if($projects->isNotEmpty())
                <h3 class="section-title">{{ $cfg['projects_label'][$L] ?? 'PROJETS' }}</h3>
                @foreach($projects as $p)
                    <div class="item">
                        <p class="item-title">{{ $pick($p, 'title') }}</p>
                        @if($pick($p, 'context'))
                            <p class="item-desc">{{ \Illuminate\Support\Str::limit($pick($p, 'context'), 200) }}</p>
                        @endif
                        @if($p->stack)
                            <p style="font-size: 7.5pt; color: {{ $accent }}; margin-top: 2px; font-weight: bold;">{{ implode(' • ', $p->stack) }}</p>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

</body>
</html>