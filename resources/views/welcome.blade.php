@extends('layouts.landing')

@section('title', 'Vestu | Buy, sell and hire dancewear')
@section('meta_description', 'Vestu is the marketplace for buying, selling and hiring dance clothing, costumes, shoes and accessories.')

@push('head')
<style>
    :root {
        --green: #409349;
        --green-strong: #51b35d;
        --ink: #070b0c;
        --panel: #101617;
        --panel-soft: #151d1f;
        --line: rgba(255, 255, 255, .11);
        --text: #f5f7f4;
        --muted: #aeb9ae;
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { margin: 0; background: var(--ink); color: var(--text); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
    a { color: inherit; text-decoration: none; }
    img { max-width: 100%; display: block; }

    .site-shell { overflow: hidden; background: radial-gradient(circle at 85% 0%, rgba(64, 147, 73, .28), transparent 28rem), #070b0c; }
    .container { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
    .nav { position: fixed; inset: 0 0 auto 0; z-index: 50; background: rgba(7, 11, 12, .76); border-bottom: 1px solid var(--line); backdrop-filter: blur(18px); }
    .nav-inner { height: 74px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
    .brand { display: inline-flex; align-items: center; gap: 12px; font-weight: 800; letter-spacing: .02em; }
    .brand-mark { width: 38px; height: 38px; border-radius: 999px; display: grid; place-items: center; background: linear-gradient(135deg, var(--green), #8be28f); color: #051006; font-weight: 900; }
    .nav-links { display: flex; align-items: center; gap: 24px; color: var(--muted); font-size: 14px; }
    .nav-links a:hover { color: #fff; }
    .button { display: inline-flex; align-items: center; justify-content: center; min-height: 46px; padding: 0 20px; border-radius: 999px; font-weight: 800; border: 1px solid transparent; transition: transform .2s ease, border-color .2s ease, background .2s ease; }
    .button:hover { transform: translateY(-1px); }
    .button-primary { background: var(--green); color: #fff; box-shadow: 0 18px 42px rgba(64, 147, 73, .34); }
    .button-primary:hover { background: var(--green-strong); }
    .button-secondary { border-color: rgba(255, 255, 255, .18); color: #fff; background: rgba(255, 255, 255, .06); }

    .hero { min-height: 100vh; padding: 118px 0 72px; position: relative; display: grid; align-items: center; isolation: isolate; background: radial-gradient(circle at 50% 20%, rgba(64, 147, 73, .48), transparent 26rem), linear-gradient(180deg, #0e2012 0%, #070b0c 78%); }
    .hero::before { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(7, 11, 12, .1) 0%, rgba(7, 11, 12, .36) 48%, #070b0c 100%); z-index: -1; }
    .hero-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .22; filter: saturate(.95) contrast(1.12); z-index: -2; }
    .hero-stage { position: relative; min-height: 640px; display: grid; place-items: center; text-align: center; }
    .hero-kicker { position: absolute; top: 0; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,.82); font-size: 14px; letter-spacing: .18em; text-transform: uppercase; white-space: nowrap; }
    .hero-side-copy { position: absolute; left: 0; top: 245px; max-width: 260px; text-align: left; color: #e8eee6; font-size: clamp(18px, 2.1vw, 24px); line-height: 1.28; font-weight: 700; }
    .hero-title { position: absolute; top: 70px; left: 50%; transform: translateX(-50%); margin: 0; font-size: clamp(82px, 19vw, 238px); line-height: .8; letter-spacing: -.08em; font-weight: 950; text-transform: uppercase; color: rgba(64, 147, 73, .98); text-shadow: 0 12px 28px rgba(0,0,0,.38), 0 0 64px rgba(81, 179, 93, .46); }
    .hero-title::after { content: "VESTU"; position: absolute; inset: 0; color: transparent; -webkit-text-stroke: 1px rgba(255,255,255,.12); transform: translateY(18px); filter: blur(.2px); }
    .hero-copy { position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); max-width: 640px; margin: 0; color: #d8ded6; font-size: clamp(17px, 2vw, 22px); line-height: 1.45; }
    .hero-actions { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 34px; }
    .hero-actions.hero-actions-centered { position: absolute; bottom: 86px; left: 50%; transform: translateX(-50%); justify-content: center; margin: 0; }
    .phone-card { position: relative; z-index: 3; width: min(280px, 62vw); aspect-ratio: 9 / 18.5; border-radius: 42px; padding: 13px; background: linear-gradient(145deg, #242b2d, #050707); box-shadow: 0 30px 100px rgba(0,0,0,.68), 0 0 0 1px rgba(255,255,255,.18); transform: translateY(42px); }
    .phone-screen { height: 100%; border-radius: 34px; background: radial-gradient(circle at 50% 12%, rgba(64,147,73,.6), transparent 32%), linear-gradient(180deg, #111718, #061008); border: 1px solid rgba(255,255,255,.13); overflow: hidden; position: relative; padding: 28px 24px; display: flex; flex-direction: column; justify-content: space-between; }
    .phone-screen::before { content: ""; position: absolute; top: 12px; left: 50%; width: 92px; height: 24px; border-radius: 999px; background: #020303; transform: translateX(-50%); }
    .phone-logo { margin-top: 54px; font-size: 32px; font-weight: 950; letter-spacing: -.05em; }
    .phone-tabs { display: grid; gap: 12px; }
    .phone-tab { display: flex; justify-content: space-between; align-items: center; padding: 16px; border-radius: 18px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1); color: #dce6da; }
    .silhouettes { position: absolute; left: 50%; bottom: 118px; width: min(980px, 100%); transform: translateX(-50%); display: flex; justify-content: space-between; align-items: end; opacity: .62; z-index: 2; pointer-events: none; }
    .silhouette { position: relative; width: 52px; height: 112px; filter: drop-shadow(0 18px 18px rgba(0,0,0,.52)); }
    .silhouette::before { content: ""; position: absolute; left: 18px; top: 8px; width: 18px; height: 18px; border-radius: 999px; background: #020403; }
    .silhouette::after { content: ""; position: absolute; left: 20px; top: 28px; width: 15px; height: 44px; border-radius: 999px 999px 10px 10px; background: #020403; transform: rotate(var(--tilt, 0deg)); transform-origin: top center; box-shadow: -25px 14px 0 -5px #020403, 25px 12px 0 -5px #020403, -14px 62px 0 -5px #020403, 18px 63px 0 -5px #020403; }
    .silhouette:nth-child(2n) { --tilt: -12deg; transform: translateY(-18px) rotate(-8deg); }
    .silhouette:nth-child(3n) { --tilt: 14deg; transform: translateY(8px) rotate(10deg); }

    .section { padding: 92px 0; position: relative; }
    .section-tight { padding-top: 52px; }
    .section-head { display: flex; align-items: end; justify-content: space-between; gap: 28px; margin-bottom: 34px; }
    .section-kicker { color: var(--green-strong); font-weight: 800; font-size: 13px; letter-spacing: .18em; text-transform: uppercase; margin-bottom: 12px; }
    .section-title { margin: 0; font-size: clamp(34px, 5vw, 62px); line-height: 1; letter-spacing: -.05em; max-width: 720px; }
    .section-text { color: var(--muted); font-size: 18px; line-height: 1.65; max-width: 620px; margin: 18px 0 0; }

    .feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    .feature-card { min-height: 288px; border: 1px solid var(--line); border-radius: 26px; padding: 20px; background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.03)); position: relative; overflow: hidden; }
    .feature-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 18px; filter: saturate(1.04); }
    .feature-card h3 { margin: 18px 0 8px; font-size: 22px; letter-spacing: -.03em; }
    .feature-card p { margin: 0; color: var(--muted); line-height: 1.55; }

    .showcase { border: 1px solid var(--line); border-radius: 34px; background: linear-gradient(135deg, rgba(64,147,73,.18), rgba(255,255,255,.04) 42%, rgba(255,255,255,.02)); padding: clamp(22px, 5vw, 54px); display: grid; grid-template-columns: .92fr 1.08fr; gap: 42px; align-items: center; overflow: hidden; }
    .showcase-image { border-radius: 28px; overflow: hidden; min-height: 420px; background: #111; }
    .showcase-image img { height: 100%; width: 100%; object-fit: cover; }
    .list { display: grid; gap: 18px; margin-top: 26px; }
    .list-item { display: grid; grid-template-columns: 42px 1fr; gap: 14px; align-items: start; }
    .list-icon { width: 42px; height: 42px; border-radius: 14px; background: rgba(64,147,73,.16); border: 1px solid rgba(64,147,73,.38); display: grid; place-items: center; color: var(--green-strong); font-weight: 900; }
    .list-item h3 { margin: 0 0 5px; font-size: 18px; }
    .list-item p { margin: 0; color: var(--muted); line-height: 1.55; }

    .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; counter-reset: steps; }
    .step { counter-increment: steps; min-height: 230px; border: 1px solid var(--line); border-radius: 24px; padding: 22px; background: var(--panel); }
    .step::before { content: "0" counter(steps); display: block; color: var(--green-strong); font-weight: 950; font-size: 44px; line-height: 1; margin-bottom: 24px; }
    .step h3 { margin: 0 0 10px; font-size: 20px; }
    .step p { margin: 0; color: var(--muted); line-height: 1.55; }

    .split { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .class-panel { min-height: 430px; border: 1px solid var(--line); border-radius: 30px; padding: 28px; background: var(--panel-soft); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; }
    .class-panel img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .62; }
    .class-panel::after { content: ""; position: absolute; inset: 0; background: linear-gradient(0deg, rgba(7,11,12,.94), rgba(7,11,12,.18)); }
    .class-panel-content { position: relative; z-index: 1; max-width: 430px; }
    .class-panel h3 { margin: 0 0 10px; font-size: 32px; letter-spacing: -.04em; }
    .class-panel p { margin: 0 0 20px; color: #d6ded5; line-height: 1.55; }

    .trust-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .trust-card { border: 1px solid var(--line); border-radius: 22px; padding: 22px; background: rgba(255,255,255,.05); }
    .trust-card h3 { margin: 0 0 8px; font-size: 19px; }
    .trust-card p { margin: 0; color: var(--muted); line-height: 1.55; }

    .cta { text-align: center; padding: 86px 24px; border: 1px solid rgba(64,147,73,.36); border-radius: 34px; background: radial-gradient(circle at 50% 0%, rgba(64,147,73,.26), transparent 24rem), rgba(255,255,255,.045); }
    .cta h2 { margin: 0 auto; max-width: 820px; font-size: clamp(38px, 6vw, 78px); line-height: .96; letter-spacing: -.06em; }
    .cta p { margin: 22px auto 0; max-width: 620px; color: var(--muted); font-size: 18px; line-height: 1.6; }
    .footer { border-top: 1px solid var(--line); padding: 36px 0; color: var(--muted); }
    .footer-inner { display: flex; justify-content: space-between; gap: 24px; align-items: center; flex-wrap: wrap; }
    .footer-links { display: flex; gap: 18px; flex-wrap: wrap; }
    .footer a:hover { color: #fff; }

    @media (max-width: 920px) {
        .nav-links { display: none; }
        .showcase, .split { grid-template-columns: 1fr; }
        .hero-stage { min-height: 630px; }
        .hero-side-copy { display: none; }
        .silhouettes { opacity: .36; }
        .feature-grid, .trust-grid { grid-template-columns: 1fr; }
        .steps { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 620px) {
        .container { width: min(100% - 28px, 1120px); }
        .hero { padding-top: 112px; min-height: 92vh; }
        .hero-stage { min-height: 580px; }
        .hero-title { top: 78px; font-size: clamp(72px, 24vw, 118px); }
        .phone-card { width: min(232px, 68vw); transform: translateY(28px); }
        .silhouettes { bottom: 128px; }
        .steps { grid-template-columns: 1fr; }
        .section-head { display: block; }
        .section { padding: 68px 0; }
        .button { width: 100%; }
        .hero-actions { width: 100%; }
        .hero-actions.hero-actions-centered { width: calc(100% - 28px); }
        .showcase-image { min-height: 300px; }
    }
</style>
@endpush

@section('content')
<div class="site-shell">
    <header class="nav" aria-label="Main navigation">
        <div class="container nav-inner">
            <a href="/" class="brand" aria-label="Vestu home">
                <span class="brand-mark">V</span>
                <span>Vestu</span>
            </a>
            <nav class="nav-links" aria-label="Primary">
                <a href="#marketplace">Marketplace</a>
                <a href="#how-it-works">How it works</a>
                <a href="#hire">Hire</a>
                <a href="#trust">Trust</a>
            </nav>
            <a class="button button-primary" href="#download">Download app</a>
        </div>
    </header>

    <section class="hero">
        <img class="hero-bg" src="{{ asset('landing/vestu-hero.png') }}" alt="Young dancers in a studio wearing dance clothing" loading="eager" fetchpriority="high">
        <div class="container hero-stage">
            <div class="hero-kicker">All-in-one dancewear marketplace</div>
            <h1 class="hero-title">VESTU</h1>
            <p class="hero-side-copy">Buy, sell and hire pre-loved dance clothing.</p>
            <div class="phone-card" aria-hidden="true">
                <div class="phone-screen">
                    <div>
                        <div class="phone-logo">Vestu</div>
                        <p style="color: var(--muted); margin: 8px 0 0;">Discover · List · Book</p>
                    </div>
                    <div class="phone-tabs">
                        <div class="phone-tab"><span>Buy performance-ready pieces</span><strong>→</strong></div>
                        <div class="phone-tab"><span>Hire for the exact dates</span><strong>→</strong></div>
                        <div class="phone-tab"><span>Sell what has been outgrown</span><strong>→</strong></div>
                    </div>
                </div>
            </div>
            <div class="silhouettes" aria-hidden="true">
                <span class="silhouette"></span>
                <span class="silhouette"></span>
                <span class="silhouette"></span>
                <span class="silhouette"></span>
                <span class="silhouette"></span>
                <span class="silhouette"></span>
                <span class="silhouette"></span>
            </div>
            <div class="hero-actions hero-actions-centered">
                <a class="button button-primary" href="#download">Get Vestu</a>
                <a class="button button-secondary" href="#marketplace">Explore the marketplace</a>
            </div>
            <p class="hero-copy">A dedicated app for dance families to discover outfits, list pieces they no longer need, and hire standout items for performances.</p>
        </div>
    </section>

    <main>
        <section class="section section-tight" id="marketplace">
            <div class="container">
                <div class="section-head">
                    <div>
                        <div class="section-kicker">Marketplace</div>
                        <h2 class="section-title">Everything dancers need, without everything needing to be new.</h2>
                    </div>
                    <a class="button button-secondary" href="#how-it-works">See how it works</a>
                </div>
                <div class="feature-grid">
                    <article class="feature-card">
                        <img src="{{ asset('landing/hire.png') }}" alt="Dancewear available to hire">
                        <h3>Hire for the moment</h3>
                        <p>Book outfits for competitions and shows with date ranges, deposits and unavailable dates handled in-app.</p>
                    </article>
                    <article class="feature-card">
                        <img src="{{ asset('landing/sell.png') }}" alt="Dance clothing listed for sale">
                        <h3>Sell what still has life</h3>
                        <p>List pre-loved dancewear, manage stock by variant and remove items automatically when they sell out.</p>
                    </article>
                    <article class="feature-card">
                        <img src="{{ asset('landing/payday.png') }}" alt="Secure marketplace payment">
                        <h3>Payments with structure</h3>
                        <p>Stripe Connect supports secure payments, seller payouts and deposits for hire orders.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container showcase">
                <div class="showcase-image">
                    <img src="{{ asset('landing/section.jpg') }}" alt="Two dancers performing in yellow costumes">
                </div>
                <div>
                    <div class="section-kicker">Purpose built</div>
                    <h2 class="section-title">Not a generic resale app. A dancewear marketplace.</h2>
                    <p class="section-text">Dance families need sizes, colours, stock, fulfilment options, hire periods and late return rules. Vestu is shaped around those details from the start.</p>
                    <div class="list">
                        <div class="list-item">
                            <div class="list-icon">✓</div>
                            <div><h3>Sale and hire listings</h3><p>Each product can be configured for a straightforward sale or date-based hire.</p></div>
                        </div>
                        <div class="list-item">
                            <div class="list-icon">✓</div>
                            <div><h3>Dance-specific variants</h3><p>Track size, colour and quantity so buyers know what is actually available.</p></div>
                        </div>
                        <div class="list-item">
                            <div class="list-icon">✓</div>
                            <div><h3>Delivery or collection</h3><p>Sellers can offer postage, collection and drop off, with delivery charges shown clearly at checkout.</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="how-it-works">
            <div class="container">
                <div class="section-kicker">How it works</div>
                <h2 class="section-title">From wardrobe to performance in four steps.</h2>
                <div class="steps" style="margin-top: 34px;">
                    <article class="step"><h3>List</h3><p>Add photos, brand, condition, size, colour, stock and pricing.</p></article>
                    <article class="step"><h3>Choose sale or hire</h3><p>Sell outright or set hire terms, deposits, unavailable dates and minimum hire days.</p></article>
                    <article class="step"><h3>Checkout safely</h3><p>Buyers see item totals, delivery charges and order details before paying.</p></article>
                    <article class="step"><h3>Complete the order</h3><p>Sellers manage fulfilment, returns, deposits and payout status from the app.</p></article>
                </div>
            </div>
        </section>

        <section class="section" id="hire">
            <div class="container">
                <div class="section-head">
                    <div>
                        <div class="section-kicker">For every dance style</div>
                        <h2 class="section-title">Buy for class. Hire for stage. Sell when it is time to move on.</h2>
                    </div>
                </div>
                <div class="split">
                    <article class="class-panel">
                        <img src="{{ asset('landing/1.jpg') }}" alt="Ballet dancewear and costume">
                        <div class="class-panel-content">
                            <h3>Ballet, tap and class wear</h3>
                            <p>Find the essentials families need again and again, from shoes and leotards to skirts and accessories.</p>
                            <a class="button button-primary" href="#download">Start browsing</a>
                        </div>
                    </article>
                    <article class="class-panel">
                        <img src="{{ asset('landing/3.jpg') }}" alt="Performance dancewear and costume">
                        <div class="class-panel-content">
                            <h3>Competition and performance</h3>
                            <p>Hire standout pieces for a specific event, then return them so another dancer can use them next.</p>
                            <a class="button button-secondary" href="#download">List an item</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="trust">
            <div class="container">
                <div class="section-kicker">Trust and standards</div>
                <h2 class="section-title">Built with marketplace basics covered.</h2>
                <p class="section-text">The website and app link to clear privacy and terms pages, payment handling is structured through Stripe Connect, and uploaded listing images are treated as public marketplace content.</p>
                <div class="trust-grid" style="margin-top: 34px;">
                    <article class="trust-card"><h3>Privacy notice</h3><p>Explains account data, order data, uploaded media, third-party providers and deletion rights.</p></article>
                    <article class="trust-card"><h3>Terms and policies</h3><p>Covers sale orders, hire orders, cancellations, deposits, late returns and disputes.</p></article>
                    <article class="trust-card"><h3>Secure payments</h3><p>Stripe handles card payments and seller onboarding. Full card numbers are not stored by Vestu.</p></article>
                </div>
            </div>
        </section>

        <section class="section" id="download">
            <div class="container">
                <div class="cta">
                    <div class="section-kicker">Join Vestu</div>
                    <h2>Give dancewear a longer life.</h2>
                    <p>Download the app to discover pre-loved dance clothing, list your own items, or hire what you need for the next performance.</p>
                    <div class="hero-actions" style="justify-content: center;">
                        <a class="button button-primary" href="/" aria-label="Download Vestu app">Download app</a>
                        <a class="button button-secondary" href="/terms">Read marketplace terms</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <div>© {{ date('Y') }} Vestu. Buy, sell and hire dancewear.</div>
            <div class="footer-links">
                <a href="/privacy">Privacy Policy</a>
                <a href="/terms">Terms & Policies</a>
                <a href="mailto:support@vestu.co.uk">Contact</a>
            </div>
        </div>
    </footer>
</div>
@endsection
