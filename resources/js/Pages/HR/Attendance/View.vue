<script setup>

</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Attendance Report</h1>

    <div class="flex justify-between mb-6">

        <input type="search" name="search" id="search" placeholder="Search by Employee ID" class="bg-gray-200 rounded-md px-4 py-2">
        
        <div class="space-x-4">
            <button class="bg-blue-300 px-5 py-2 rounded-xl">week</button>
        
            <button class="bg-blue-400 px-4 py-2 rounded-xl">month</button>
        
            <button class="bg-blue-500 px-6 py-2 rounded-xl">year</button>
        </div>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead>
            <tr class="bg-gray-400 text-black font-medium">
                <th class="p-6">Serial No.</th>
                <th class="p-6">Employee ID</th>
                <th class="p-6">Name</th>
                <th class="p-6">Date</th>
                <th class="p-6">Time In</th>
                <th class="p-6">Time Out</th>
                <th class="p-6">Status</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">1</td>
                <td class="p-4">11111</td>
                <td class="p-4">Kier</td>
                <td class="p-4">1/30/2026</td>
                <td class="p-4">8:00 AM</td>
                <td class="p-4">5:00 PM</td>
                <td class="p-4">
                    <button>Present</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">2</td>
                <td class="p-4">22222</td>
                <td class="p-4">Lucky Jade</td>
                <td class="p-4">1/30/2026</td>
                <td class="p-4">9:00 AM</td>
                <td class="p-4">5:00 PM</td>
                <td class="p-4">
                    <button>Late</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">3</td>
                <td class="p-4">33333</td>
                <td class="p-4">Mark</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">
                    <button>Absent</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">4</td>
                <td class="p-4">44444</td>
                <td class="p-4">Khorvin</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">
                    <button>On Leave</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">5</td>
                <td class="p-4">55555</td>
                <td class="p-4">Nico</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">
                    <button>On Leave</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>