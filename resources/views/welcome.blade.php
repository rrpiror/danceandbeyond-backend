@extends('layouts.landing')

@section('title', 'Vestu | Buy, sell and hire dancewear')
@section('meta_description', 'Vestu is the app for buying, selling and hiring dance clothing, costumes, shoes and accessories.')

@push('head')
<style>
    :root {
        --green: #409349;
        --green-soft: #c9f080;
        --mint: #f2faee;
        --ink: #172018;
        --muted: #667263;
        --line: rgba(23, 32, 24, .12);
        --white: #fff;
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { margin: 0; background: var(--mint); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
    a { color: inherit; text-decoration: none; }
    img { display: block; max-width: 100%; }

    .page {
        min-height: 100vh;
        overflow: hidden;
        background:
            radial-gradient(circle at 8% 88%, rgba(64, 147, 73, .12), transparent 120px),
            radial-gradient(circle at 93% 85%, rgba(64, 147, 73, .10), transparent 130px),
            var(--mint);
    }

    .hero {
        position: relative;
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 44px clamp(20px, 5vw, 72px) 34px;
        isolation: isolate;
    }

    .brand {
        position: absolute;
        top: 54px;
        left: clamp(24px, 5vw, 72px);
        z-index: 5;
        font-size: clamp(32px, 4vw, 46px);
        font-weight: 900;
        letter-spacing: -0.04em;
    }

    .leaf {
        position: absolute;
        z-index: -1;
        width: min(68vw, 980px);
        aspect-ratio: 1.05 / 1;
        border-radius: 58% 42% 52% 48% / 48% 55% 45% 52%;
        background: var(--green-soft);
        transform: rotate(8deg);
        opacity: .95;
    }

    .phone-wrap {
        position: relative;
        width: min(420px, 72vw);
        aspect-ratio: 430 / 872;
        margin-top: 20px;
        filter: drop-shadow(0 36px 38px rgba(23, 32, 24, .25));
    }

    .phone {
        position: absolute;
        inset: 0;
        border: 12px solid #070707;
        border-radius: 66px;
        background: #070707;
        overflow: hidden;
        box-shadow: inset 0 0 0 2px rgba(255,255,255,.08);
    }

    .phone::before {
        content: "";
        position: absolute;
        top: 18px;
        left: 50%;
        width: 142px;
        height: 42px;
        border-radius: 999px;
        background: #000;
        transform: translateX(-50%);
        z-index: 4;
    }

    .phone::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 12px;
        width: 118px;
        height: 5px;
        border-radius: 999px;
        background: rgba(0,0,0,.7);
        transform: translateX(-50%);
        z-index: 4;
    }

    .phone-screen {
        position: absolute;
        inset: 0;
        border-radius: 52px;
        overflow: hidden;
        background: #fff;
    }

    .phone-screen img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transform: scale(1.015);
        animation: rotateScreens 16s infinite;
    }

    .phone-screen img:nth-child(2) { animation-delay: 4s; }
    .phone-screen img:nth-child(3) { animation-delay: 8s; }
    .phone-screen img:nth-child(4) { animation-delay: 12s; }

    @keyframes rotateScreens {
        0%, 7% { opacity: 0; }
        10%, 30% { opacity: 1; }
        33%, 100% { opacity: 0; }
    }

    .store-card {
        position: absolute;
        right: clamp(20px, 5vw, 70px);
        top: 52px;
        display: grid;
        gap: 18px;
        z-index: 5;
    }

    .store-link {
        display: grid;
        place-items: center;
        min-width: 148px;
        min-height: 70px;
        border-radius: 18px;
        background: rgba(255,255,255,.58);
        border: 1px solid rgba(255,255,255,.68);
        color: var(--ink);
        font-weight: 800;
        box-shadow: 0 18px 40px rgba(23, 32, 24, .08);
        backdrop-filter: blur(14px);
    }

    .store-link span {
        display: block;
        color: var(--muted);
        font-size: 12px;
        font-weight: 700;
        margin-top: 2px;
        text-align: center;
    }

    .copy {
        position: absolute;
        left: clamp(24px, 5vw, 72px);
        bottom: 76px;
        max-width: 380px;
        z-index: 5;
    }

    .copy h1 {
        margin: 0 0 14px;
        font-size: clamp(42px, 6vw, 76px);
        line-height: .95;
        letter-spacing: -0.06em;
    }

    .copy p {
        margin: 0;
        color: var(--muted);
        font-size: clamp(18px, 2vw, 22px);
        line-height: 1.45;
    }

    .download-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 26px;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 54px;
        padding: 0 22px;
        border-radius: 999px;
        border: 1px solid var(--line);
        font-weight: 850;
        background: var(--white);
        box-shadow: 0 14px 28px rgba(23, 32, 24, .08);
    }

    .button-primary {
        background: var(--green);
        color: #fff;
        border-color: var(--green);
    }

    .floating-note {
        position: absolute;
        right: clamp(28px, 10vw, 220px);
        bottom: 110px;
        padding: 14px 18px;
        border-radius: 999px;
        background: rgba(255,255,255,.64);
        color: var(--green);
        font-weight: 900;
        box-shadow: 0 16px 34px rgba(23, 32, 24, .08);
        backdrop-filter: blur(14px);
    }

    .footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 0 clamp(20px, 5vw, 72px) 24px;
        color: rgba(23, 32, 24, .72);
        font-size: 14px;
    }

    .footer-links {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .footer a {
        font-weight: 750;
    }

    .footer a:hover {
        color: var(--green);
    }

    @media (max-width: 900px) {
        .hero {
            align-content: start;
            padding-top: 118px;
            padding-bottom: 160px;
        }

        .brand {
            top: 30px;
        }

        .store-card {
            top: 26px;
            right: 20px;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .store-link {
            min-width: 112px;
            min-height: 54px;
            font-size: 14px;
        }

        .leaf {
            width: 92vw;
            top: 160px;
        }

        .phone-wrap {
            width: min(340px, 72vw);
            margin-top: 40px;
        }

        .copy {
            position: static;
            order: 3;
            margin: 28px auto 0;
            text-align: center;
        }

        .download-actions {
            justify-content: center;
        }

        .floating-note {
            display: none;
        }

        .footer {
            display: grid;
            justify-items: center;
            text-align: center;
        }
    }

    @media (max-width: 560px) {
        .store-card {
            position: static;
            margin-top: 18px;
            order: 4;
        }

        .hero {
            padding-top: 94px;
        }

        .brand {
            font-size: 34px;
        }

        .phone-wrap {
            width: min(292px, 78vw);
        }

        .phone {
            border-width: 9px;
            border-radius: 52px;
        }

        .phone-screen {
            border-radius: 43px;
        }
    }
</style>
@endpush

@section('content')
<main class="page">
    <section class="hero" aria-labelledby="home-title">
        <a class="brand" href="/" aria-label="Vestu home">vestu.</a>

        <div class="leaf" aria-hidden="true"></div>

        <div class="phone-wrap" aria-label="Vestu app screenshots">
            <div class="phone">
                <div class="phone-screen">
                    <img src="{{ asset('landing/app-screen-1.png') }}" alt="Vestu discover products screen">
                    <img src="{{ asset('landing/app-screen-2.png') }}" alt="Vestu add an item screen">
                    <img src="{{ asset('landing/app-screen-3.png') }}" alt="Vestu profile screen">
                    <img src="{{ asset('landing/app-screen-4.png') }}" alt="Vestu product detail screen">
                </div>
            </div>
        </div>

        <div class="store-card" aria-label="Store links">
            <a class="store-link" href="#" aria-disabled="true">
                App Store
                <span>Coming soon</span>
            </a>
            <a class="store-link" href="#" aria-disabled="true">
                Google Play
                <span>Coming soon</span>
            </a>
        </div>

        <div class="copy">
            <h1 id="home-title">Buy, sell and hire dancewear.</h1>
            <p>Vestu is the dedicated marketplace for dance clothing, costumes, shoes and accessories.</p>
            <div class="download-actions">
                <a class="button button-primary" href="#" aria-disabled="true">App Store listing</a>
                <a class="button" href="#" aria-disabled="true">Google Play listing</a>
            </div>
        </div>

        <div class="floating-note" aria-hidden="true">Dance-ready listings</div>

        <footer class="footer">
            <span>© {{ date('Y') }} Dance and Beyond trading as Vestu</span>
            <nav class="footer-links" aria-label="Legal links">
                <a href="/privacy">Privacy Policy</a>
                <a href="/terms">Terms</a>
                <a href="/support">Support</a>
                <a href="mailto:hello@vestu.co.uk">hello@vestu.co.uk</a>
            </nav>
        </footer>
    </section>
</main>
@endsection
