<template>
    <div class="flex items-center justify-center h-full bg-gray-100">

        <!-- OUTER MODAL -->
        <div class="w-full max-w-2xl bg-sky-400 rounded-lg shadow-lg border-4 border-blue-500">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-4 py-3 bg-sky-400 rounded-t-md">
                <h1 class="text-xl font-bold text-black">
                    006 Khorvin Impuesto
                </h1>

                <button class="text-xl font-bold text-black hover:text-red-600">
                    ✕
                </button>
            </div>

            <!-- INNER CONTENT -->
            <div class="m-4 bg-white border-2 border-gray-400 rounded-md p-6">

                <!-- DATE RANGE -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">From</label>
                        <input
                            type="date"
                            class="p-2 border border-gray-500 rounded-md"
                        />
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">To</label>
                        <input
                            type="date"
                            class="p-2 border border-gray-500 rounded-md"
                        />
                    </div>
                </div>

                <!-- REASON -->
                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium">Reason</label>
                    <input
                        type="text"
                        value="Magic Chess"
                        class="w-full p-2 border border-gray-500 rounded-md font-semibold"
                    />
                </div>

                <!-- DESCRIPTION -->
                <div>
                    <textarea
                        rows="6"
                        class="w-full p-2 border border-gray-500 rounded-md resize-none"
                    >I want to play Magic Chess all day</textarea>
                </div>
            </div>

            <!-- FOOTER BUTTONS -->
            <div class="flex justify-between px-12 pb-6">
                <button
                    class="bg-green-500 hover:bg-green-400 text-black font-bold px-6 py-2 rounded-md border border-black"
                >
                    Submit
                </button>

                <button
                    class="bg-red-500 hover:bg-red-400 text-black font-bold px-6 py-2 rounded-md border border-black"
                >
                    Reject
                </button>
            </div>
        </div>
    </div>
</template>
