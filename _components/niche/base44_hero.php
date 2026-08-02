<?php /** Base44 hero visual */ ?>
<div class="relative">
    <div class="absolute inset-0 bg-gradient-to-tr from-brand-400 to-secondary-400 rounded-[2.5rem] blur-2xl opacity-40 -z-10 translate-y-4"></div>
    <img src="/assets/hero.webp" alt="Base44 app converted to Android" width="405" height="868" decoding="async"
         class="w-full h-auto rounded-[2.5rem] border-8 border-white shadow-soft relative">
    <div class="absolute -left-4 top-10 hidden sm:block bg-white rounded-2xl border-2 border-gray-100 shadow-float p-4 w-48">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-icons text-brand-500" aria-hidden="true">auto_awesome</span>
            <span class="text-xs font-bold text-gray-900">Base44 project</span>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="h-10 bg-brand-50 rounded-lg border border-brand-100"></div>
            <div class="h-10 bg-secondary-50 rounded-lg border border-secondary-100"></div>
            <div class="col-span-2 h-8 bg-gray-100 rounded-lg"></div>
        </div>
    </div>
    <div class="absolute -right-2 bottom-20 bg-secondary-50 rounded-2xl border-2 border-secondary-200 shadow-float px-4 py-3 flex items-center gap-2">
        <span class="material-icons text-secondary-600 text-xl" aria-hidden="true">share</span>
        <span class="text-sm font-bold text-gray-900">Client APK deliverable</span>
    </div>
</div>
