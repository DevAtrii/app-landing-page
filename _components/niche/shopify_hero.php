<?php /** Shopify hero visual */ ?>
<div class="relative">
    <div class="absolute inset-0 bg-gradient-to-tr from-secondary-400 to-brand-400 rounded-[2.5rem] blur-2xl opacity-40 -z-10 translate-y-4"></div>
    <img src="/assets/hero.webp" alt="Shopify store converted to Android app" width="405" height="868" decoding="async"
         class="w-full h-auto rounded-[2.5rem] border-8 border-white shadow-soft relative">
    <div class="absolute -left-4 top-10 hidden sm:block bg-white rounded-2xl border-2 border-gray-100 shadow-float p-4 w-48">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-8 h-8 rounded-lg bg-secondary-500 text-white flex items-center justify-center">
                <span class="material-icons text-lg" aria-hidden="true">shopping_bag</span>
            </span>
            <span class="text-xs font-bold text-gray-900">Shopify Store</span>
        </div>
        <div class="flex gap-2">
            <div class="flex-1 h-14 bg-gray-100 rounded-lg"></div>
            <div class="flex-1 h-14 bg-gray-100 rounded-lg"></div>
        </div>
        <p class="mt-2 text-xs font-bold text-secondary-700">Cart · Checkout · Pay</p>
    </div>
    <div class="absolute -right-2 bottom-20 bg-accent-50 rounded-2xl border-2 border-accent-200 shadow-float px-4 py-3">
        <p class="text-xs font-bold text-accent-800 uppercase tracking-wide">Push alert</p>
        <p class="text-sm font-bold text-gray-900 mt-1">Flash sale live now</p>
    </div>
</div>
