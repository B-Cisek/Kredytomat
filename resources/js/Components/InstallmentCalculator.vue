<script setup></script>
<template>
  <section class="flex gap-5">
    <div>
      <h3 class="font-semibold text-black">Kwota kredytu</h3>
      <div class="form-control w-full max-w-xs">
        <input
          type="text"
          value="231321"
          class="input input-bordered w-full max-w-xs bg-gray-300 text-black"
        />
        <label class="label">
          <span class="label-text-alt">50 000 zł</span>
          <span class="label-text-alt">2 000 000 zł</span>
        </label>
        <input type="range" min="0" max="100" value="40" class="range range-primary" />
      </div>
    </div>
    <div>
      <h3 class="font-semibold text-black">Okres spłaty</h3>
      <div class="form-control w-full max-w-xs">
        <input
          type="text"
          value="231321"
          class="input input-bordered w-full max-w-xs bg-gray-300 text-black"
        />
        <label class="label">
          <span class="label-text-alt">5 lat</span>
          <span class="label-text-alt">35 lat</span>
        </label>
        <input type="range" min="0" max="100" value="40" class="range range-primary" />
      </div>
    </div>
    <div>
      <h3 class="font-semibold text-black">Oprocentowanie</h3>
      <div class="form-control w-full max-w-xs">
        <input
          type="text"
          value="231321"
          class="input input-bordered w-full max-w-xs bg-gray-300 text-black"
        />
        <label class="label">
          <span class="label-text-alt">0,01%</span>
          <span class="label-text-alt">15%</span>
        </label>
        <input type="range" min="0" max="100" value="40" class="range range-primary" />
      </div>
    </div>
    <div>
      <h3 class="font-semibold text-black">Prowizja</h3>
      <div class="form-control w-full max-w-xs">
        <input
          type="text"
          value="231321"
          class="input input-bordered w-full max-w-xs bg-gray-300 text-black"
        />
        <label class="label">
          <span class="label-text-alt">50 000 zł</span>
          <span class="label-text-alt">2 000 000 zł</span>
        </label>
        <input type="range" min="0" max="100" value="40" class="range range-primary" />
      </div>
    </div>
  </section>
  <button class="btn btn-primary mt-10">Oblicz ratę i koszt</button>
</template>
