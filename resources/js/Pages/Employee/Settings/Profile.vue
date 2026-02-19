<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

// Get current user data from Inertia props to show existing photo
const user = usePage().props.auth.user;
const previewUrl = ref(user.profile_path ? `/storage/${user.profile_path}` : null);

const form = useForm({
    profile_picture: null,
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.profile_picture = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const saveImage = () => {
    // We use the direct string path instead of the route() helper
    form.post('/profile/update-picture', {
        forceFormData: true,
        onSuccess: () => {
            alert("Success!");
            // The previewUrl is already set by handleFileUpload
        },
        onError: (errors) => {
            console.log("Server Validation Errors:", errors);
        }
    });
};
</script>

<template>
  <div class="p-8 bg-white min-h-screen">
    <div class="max-w-md mx-auto">
      <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Profile Picture</h2>

      <div class="flex flex-col items-center gap-6">
        <div class="relative group w-40 h-40 overflow-hidden rounded-full bg-slate-100 border-4 border-gray-200 shadow-md flex items-center justify-center cursor-pointer">
          
          <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover" />
          
          <div v-else class="text-gray-400 text-center flex flex-col items-center">
            <span class="text-xs font-semibold">NO IMAGE</span>
          </div>

          <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="text-white text-xs font-bold uppercase">Change Photo</span>
          </div>

          <input 
            type="file" 
            @change="handleFileUpload" 
            accept="image/*"
            class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
          >
        </div>

        <p v-if="form.errors.profile_picture" class="text-red-500 text-sm italic">
          {{ form.errors.profile_picture }}
        </p>

        <div class="flex gap-4">
           <button 
            @click="saveImage" 
            :disabled="form.processing || !form.profile_picture"
            class="bg-green-600 hover:bg-green-700 text-white px-8 py-2 rounded-lg font-semibold transition disabled:bg-gray-400"
          >
            {{ form.processing ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>