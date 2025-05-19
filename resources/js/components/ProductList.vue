<template>
  <div class="container mx-auto p-4 min-h-screen bg-gray-100">
    <!-- Notification -->
    <div
      v-if="notification"
      class="fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white transition-opacity duration-300"
      :class="notification.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
    >
      {{ notification.message }}
    </div>

    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Product Master List</h1>

    <!-- Search and Upload Section -->
    <div class="flex flex-col sm:flex-row justify-between mb-6 gap-4">
      <!-- Search Input -->
      <div class="flex-1">
        <input
          v-model="search"
          @input="debouncedFetchProducts"
          type="text"
          placeholder="Search by Product ID..."
          class="w-full sm:w-64 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
        />
      </div>

      <!-- File Upload -->
      <div class="flex items-center gap-3">
        <input
          type="file"
          ref="fileInput"
          @change="handleFileUpload"
          accept=".xlsx"
          class="hidden"
        />
        <button
          @click="$refs.fileInput.click()"
          class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 hover:scale-105 transition transform duration-300"
        >
          Choose File
        </button>
        <button
          @click="uploadFile"
          :disabled="!file || isUploading"
          class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 hover:scale-105 transition transform duration-300 disabled:bg-gray-400 disabled:scale-100"
        >
          {{ isUploading ? 'Uploading...' : 'Upload' }}
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
      <table class="min-w-full bg-white">
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="px-6 py-3 text-left font-semibold">No.</th>
            <th class="px-6 py-3 text-left font-semibold">Product ID</th>
            <th class="px-6 py-3 text-left font-semibold">Type</th>
            <th class="px-6 py-3 text-left font-semibold">Brand</th>
            <th class="px-6 py-3 text-left font-semibold">Model</th>
            <th class="px-6 py-3 text-left font-semibold">Capacity</th>
            <th class="px-6 py-3 text-left font-semibold">Quantity</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading" class="border-b">
            <td colspan="7" class="px-6 py-4 text-center">
              <div class="flex justify-center items-center">
                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span class="ml-2">Loading...</span>
              </div>
            </td>
          </tr>
          <tr
            v-for="(product, index) in products.data"
            v-else
            :key="product.id"
            class="border-b hover:bg-gray-50 transition duration-200 animate-fade-in"
          >
            <td class="px-6 py-4">{{ index + 1 + (products.current_page - 1) * products.per_page }}</td>
            <td class="px-6 py-4">{{ product.product_id }}</td>
            <td class="px-6 py-4">{{ product.type }}</td>
            <td class="px-6 py-4">{{ product.brand }}</td>
            <td class="px-6 py-4">{{ product.model }}</td>
            <td class="px-6 py-4">{{ product.capacity }}</td>
            <td class="px-6 py-4">{{ product.quantity }}</td>
          </tr>
          <tr v-if="!loading && !products.data.length" class="border-b">
            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No products found.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
      <button
        @click="fetchProducts(products.current_page - 1)"
        :disabled="!products.prev_page_url || loading"
        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 hover:scale-105 transition transform duration-300 disabled:bg-gray-200 disabled:scale-100"
      >
        Previous
      </button>
      <div class="flex items-center gap-2">
        <span class="text-gray-600">Page </span>
        <select
          v-model.number="selectedPage"
          @change="fetchProducts(selectedPage)"
          :disabled="loading"
          class="px-2 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option v-for="page in totalPages" :key="page" :value="page">{{ page }}</option>
        </select>
        <span class="text-gray-600"> of {{ products.last_page }}</span>
      </div>
      <button
        @click="fetchProducts(products.current_page + 1)"
        :disabled="!products.next_page_url || loading"
        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 hover:scale-105 transition transform duration-300 disabled:bg-gray-200 disabled:scale-100"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import axios from 'axios';
import debounce from 'lodash/debounce';

export default {
  name: 'ProductList',
  setup() {
    const products = ref({ data: [], current_page: 1, last_page: 1 });
    const search = ref('');
    const file = ref(null);
    const isUploading = ref(false);
    const loading = ref(false);
    const notification = ref(null);
    const selectedPage = ref(1);
    const jobStatusCheckInterval = ref(null);
    const jobId = ref(null);

    const totalPages = computed(() => products.value.last_page);

    const showNotification = (message, type = 'success') => {
      notification.value = { message, type };
      setTimeout(() => {
        notification.value = null;
      }, 3000); // Notification disappears after 3 seconds
    };

    const fetchProducts = async (page = 1) => {
      loading.value = true;
      try {
        const response = await axios.get('/api/products', {
          params: {
            page,
            search: search.value,
            per_page: 5,
          },
        });
        products.value = response.data;
        selectedPage.value = page;
      } catch (error) {
        console.error('Error fetching products:', error);
        showNotification('Failed to fetch products.', 'error');
      } finally {
        loading.value = false;
      }
    };

    const debouncedFetchProducts = debounce(() => {
      fetchProducts(1); // Reset to page 1 on search
    }, 300);

    const handleFileUpload = (event) => {
      file.value = event.target.files[0];
    };

    const uploadFile = async () => {
      if (!file.value) return;

      isUploading.value = true;
      loading.value = true;
      const formData = new FormData();
      formData.append('file', file.value);

      try {
        const response = await axios.post('/api/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        jobId.value = response.data.jobId; // Assume API returns jobId
        showNotification('File uploaded, processing in queue...');
        await checkJobStatus();
      } catch (error) {
        console.error('Error uploading file:', error);
        showNotification('Failed to upload file.', 'error');
      } finally {
        isUploading.value = false;
      }
    };

    const checkJobStatus = async () => {
      if (!jobId.value) return;

      jobStatusCheckInterval.value = setInterval(async () => {
        try {
          const response = await axios.get(`/api/job-status/${jobId.value}`);
          if (response.data.status === 'completed') {
            clearInterval(jobStatusCheckInterval.value);
            jobStatusCheckInterval.value = null;
            jobId.value = null;
            await fetchProducts(selectedPage.value); // Refetch current page
            showNotification('Quantity updated successfully!');
          } else if (response.data.status === 'failed') {
            clearInterval(jobStatusCheckInterval.value);
            jobStatusCheckInterval.value = null;
            jobId.value = null;
            showNotification('Job failed to process.', 'error');
          }
        } catch (error) {
          console.error('Error checking job status:', error);
          clearInterval(jobStatusCheckInterval.value);
          jobStatusCheckInterval.value = null;
          jobId.value = null;
          showNotification('Error checking job status.', 'error');
        }
      }, 2000); // Poll every 2 seconds
    };

    onMounted(() => {
      fetchProducts();
    });

    onUnmounted(() => {
      if (jobStatusCheckInterval.value) {
        clearInterval(jobStatusCheckInterval.value);
      }
    });

    return {
      products,
      search,
      debouncedFetchProducts,
      fetchProducts,
      file,
      handleFileUpload,
      uploadFile,
      isUploading,
      loading,
      notification,
      selectedPage,
      totalPages,
    };
  },
};
</script>

<style>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out forwards;
}
</style>