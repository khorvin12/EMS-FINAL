<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <main>
       <!-- Outer Container -->
<div class="flex justify-center items-center">

  <!-- Blue Review Card -->
  <div class="relative bg-sky-400 rounded-lg p-6 w-full max-w-4xl shadow-lg">

    <!-- Header -->
    <div class="flex justify-between mb-4">
      <h1 class="text-2xl font-bold">
        006 <span class="font-normal">Khorvin Impuesto</span>
      </h1>

      <!-- Close -->
        <Link href="/manageleaves">
      <button class="text-2xl font-bold hover:text-red-600 transition">
        x
      </button>
      </Link>
    </div>

    <!-- Inner White Card -->
    <div class="bg-white border rounded-lg p-6">

      <!-- From / To -->
      <div class="flex flex-col md:flex-row gap-6 mb-6">

        <!-- From -->
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1">From</label>
          <input
            type="text"
            value="1/1/2001"
            class="w-full border rounded-md px-3 py-2 text-lg"
            readonly
          />
        </div>

        <!-- To -->
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1">To</label>
          <input
            type="text"
            value="1/1/2002"
            class="w-full border rounded-md px-3 py-2 text-lg"
            readonly
          />
        </div>

      </div>

      <!-- Reason -->
      <div>
        <label class="block text-sm font-medium mb-1">Reason</label>

        <!-- Reason Title -->
        <input
          type="text"
          value="Magic Chess"
          class="w-full border rounded-md px-3 py-2 text-lg font-semibold mb-3"
          readonly
        />

        <!-- Reason Description -->
        <textarea
          rows="4"
          class="w-full border rounded-md px-3 py-2 text-lg resize-none"
          readonly
        >I want to play Magic Chess all day</textarea>
      </div>

    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between mt-6 px-6">
    
        <Link href="/manageleaves">
      <button
        class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded shadow"
      >
        Approve
      </button>
      </Link>
      
        <Link href="/manageleaves">
      <button
        class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded shadow"
      >
        Reject
      </button>
      </Link>
    </div>

  </div>
</div>
    </main>
</template>
