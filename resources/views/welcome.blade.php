@extends('layouts.landing')

@section('content')
<!-- HERO -->
<section class="relative min-h-[85vh] flex items-center isolate">
  <!-- BG image -->
  <img
      src="{{ asset('landing/vestu-hero.png') }}"
      alt="Vestu hero"
      class="absolute inset-0 h-full w-full object-cover"
      loading="eager"
      fetchpriority="high"
  >

  <!-- Content -->
  <div class="relative z-10 w-11/12 max-w-7xl mx-auto">
      <div class="max-w-md bg-white/90 backdrop-blur rounded-2xl shadow-xl p-6 sm:p-8">
          <h2>Buy, sell & hire pre-loved dance clothing</h2>
          <h4>Vestu is the platform for pre-owned dance clothing that you will love!</h4>

          <div class="mt-6 space-y-3">
              <a href="#" class="w-full btn-primary download-btn">Download Now</a>
              <a href="#how-it-works" class="w-full btn-secondary smooth-scroll">Learn how it works</a>
          </div>
      </div>
  </div>

  <!-- Subtle right fade -->
  <div class="pointer-events-none absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-black/10 to-transparent"></div>
</section>

<!-- HOW IT WORKS -->
<section class="py-16 sm:py-20" id="how-it-works">
    <div class="w-11/12 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-12">

            {{-- Step 1 --}}
            <article class="flex flex-col">
                <img
                    src="{{ asset('landing/hire.png') }}"
                    alt="Sell dance clothing"
                    class="mb-5 w-full aspect-square object-cover rounded-2xl shadow-sm"
                    loading="lazy"
                >
                <h3><span class="font-bold">1.</span> Sell it</h3>
                <p>
                    List your items with clear photos, a solid description and a price. Publish and buyers can purchase directly.
                </p>
            </article>

            {{-- Step 2 --}}
            <article class="flex flex-col">
                <img
                    src="{{ asset('landing/sell.png') }}"
                    alt="Hire dance clothing"
                    class="mb-5 w-full aspect-square object-cover rounded-2xl shadow-sm"
                    loading="lazy"
                >
                <h3><span class="font-bold">2.</span> Hire it</h3>
                <p>
                    Turn items into rentals. Set terms and rates, publish, and get bookings. Earn each time your item is hired.
                </p>
            </article>

            {{-- Step 3 --}}
            <article class="flex flex-col">
                <img
                    src="{{ asset('landing/payday.png') }}"
                    alt="Sell or hire dance clothing"
                    class="mb-5 w-full aspect-square object-cover rounded-2xl shadow-sm"
                    loading="lazy"
                >
                <h3><span class="font-bold">3.</span> Get paid!</h3>
                <p>
                    After a sale or hire, your earnings are released to you. Secure payouts—quick and safe, every time.
                </p>
            </article>

        </div>
    </div>
</section>

<!-- SUSTAINABLE -->
<section class="py-24 sm:py-32 bg-indigo-50 dark:bg-gray-900">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-16">

      <!-- Text content -->
      <div>
        <h2>Sustainable style, stage-ready looks</h2>
        <h4 class="mt-4">
          Give your dance costumes a second life by passing them on to other performers — together we can keep beautiful pieces in use and out of landfill, while reducing fashion waste.
        </h4>
        <div class="mt-10">
          <a href="" class="btn-primary download-btn">Get started</a>
        </div>
      </div>

      <!-- Image -->
      <div>
        <img 
          src="{{ asset('landing/2.jpg') }}" 
          alt="Dancers in costume"
          class="w-full rounded-2xl object-cover shadow-lg outline outline-1 -outline-offset-1 outline-black/5 dark:outline-white/10"
        />
      </div>

    </div>
  </div>
</section>



<!-- HIRE SECTION -->
<section>
  <div class="relative isolate">
    <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
      <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75 dark:opacity-20"></div>
    </div>
    <div class="py-24 sm:py-32 lg:pb-40">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
          <h2>Find the perfect dance outfit for every performance</h2>
          <h4>Why buy when you can hire? Access stunning costumes, shoes, and accessories for a fraction of the cost. Whether it’s for a competition, showcase, or rehearsal, Vestu makes it easy to rent what you need, when you need it — saving you money while keeping fashion sustainable.</h4>
          <div class="mt-10 flex items-center justify-center gap-x-4">
            <a href="" class="btn-primary download-btn">Get started</a>
            <a href="#how-it-works" class="btn-secondary smooth-scroll">Learn more</a>
        </div>
        </div>

        <div class="mt-10 xl:mx-auto xl:max-w-7xl xl:px-8">
          <img src="{{ asset('landing/section.jpg') }}" alt="" class="w-full object-cover outline-1 -outline-offset-1 outline-black/5 xl:rounded-3xl dark:outline-white/10" />
        </div>

      </div>
    </div>
    <div aria-hidden="true" class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
      <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-288.75 dark:opacity-20"></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="relative isolate bg-white">
  <div class="mb-10 mx-auto max-w-2xl text-center">
      <h2>Buy for less. Sell for free.</h2>
      <h4>Find high-quality dancewear at prices that won’t break the bank.</h4>

      <div class="mt-10 flex items-center justify-center gap-x-4">
          <a href="" class="btn-primary download-btn">Get started</a>
          <a href="#how-it-works" class="btn-secondary smooth-scroll">Learn more →</a>
      </div>
  </div>
</section>

@endsection

<script>
  document.addEventListener("DOMContentLoaded", function () {
      // Store URLs
      const iosUrl = "https://apps.apple.com/app/your-app-id";
      const androidUrl = "https://play.google.com/store/apps/details?id=your.app.id";
      const fallbackUrl = "/";

      const userAgent = navigator.userAgent || navigator.vendor || window.opera;
      let targetUrl = fallbackUrl;
  
      if (/android/i.test(userAgent)) {
          targetUrl = androidUrl;
      } else if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
          targetUrl = iosUrl;
      }
  
      // Apply to all download buttons
      document.querySelectorAll('.download-btn').forEach(btn => {
          btn.href = targetUrl;
      });
  
      // Smooth scroll for "Learn how it works"
      document.querySelectorAll('.smooth-scroll').forEach(link => {
          link.addEventListener("click", function (e) {
              e.preventDefault();
              const target = document.querySelector(this.getAttribute("href"));
              if (target) {
                  target.scrollIntoView({ behavior: "smooth" });
              }
          });
      });
  });
  </script>