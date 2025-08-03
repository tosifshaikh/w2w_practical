<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useCsvUploader } from '@/pages/CSVUpload/CSVupload';
import CsvPreviewModal from '@/pages/CSVUpload/CsvPreviewModal.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'CSV Upload',
        href: '/csv-upload',
    },
];
const {
    file,
    isUploading,
    uploadProgress,
    message,
    messageClass,
    summary,
    isDragging,
    previewData,
    previewHeaders,
    showPreviewModal,
    isParsing,
    handleFileChange,
    handleDrop,
    handleDragOver,
    handleDragLeave,
    handleSubmit,
    clearForm,
    openPreviewModal,
    closePreviewModal,
} = useCsvUploader();
</script>

<template>
    <Head title="CSV Upload" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full rounded-lg bg-white p-6 shadow-md">
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Drag & Drop Zone -->
                <div
                    class="rounded-xl border-2 border-dashed p-8 text-center transition-colors"
                    @drop.prevent="handleDrop"
                    @dragover.prevent="handleDragOver"
                    @dragleave.prevent="handleDragLeave"
                    @click="$refs.fileInput.click()"
                    :class="{
                        'border-blue-500 bg-blue-50': isDragging,
                        'border-gray-300 bg-gray-50': !isDragging,
                    }"
                >
                    <div class="flex flex-col items-center justify-center space-y-3">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                            ></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium text-blue-600 hover:cursor-pointer">Click to upload</span> or drag and drop
                            </p>
                            <p class="mt-1 text-xs text-gray-500">CSV files (MAX. 2MB)</p>
                        </div>
                        <input
                            id="dropzone-file"
                            type="file"
                            class="hidden"
                            ref="fileInput"
                            accept=".csv"
                            @change="handleFileChange"
                            :disabled="isUploading"
                        />
                    </div>
                </div>

                <!-- Selected File & Messages -->
                <div class="space-y-2">
                    <p v-if="file" class="text-sm font-medium text-gray-700">
                        Selected file: <span class="text-blue-600">{{ file.name }}</span>
                    </p>
                    <p v-if="message" class="text-sm" :class="messageClass">
                        {{ message }}
                    </p>
                </div>

                <!-- Progress Bar -->
                <div v-if="isUploading" class="space-y-2">
                    <div class="h-2.5 w-full rounded-full bg-gray-200">
                        <div class="h-2.5 rounded-full bg-blue-600 transition-all duration-300" :style="{ width: uploadProgress + '%' }"></div>
                    </div>
                    <p class="text-right text-sm text-gray-600">{{ uploadProgress }}% uploaded</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button
                        type="button"
                        @click="
                            file = null;
                            ((message = ''), (summary = null));
                        "
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                        :disabled="isUploading"
                    >
                        Clear
                    </button>
                    <button
                        type="button"
                        @click="openPreviewModal"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                        :disabled="!file || isUploading"
                    >
                        Preview
                    </button>
                    <button
                        type="submit"
                        class="rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:cursor-pointer hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                        :disabled="!file || isUploading"
                        :class="{
                            'cursor-not-allowed opacity-50': !file || isUploading,
                        }"
                    >
                        <span v-if="isUploading"> Uploading... </span>
                        <span v-else>Upload File</span>
                    </button>
                </div>
            </form>

            <!-- Upload Summary -->
            <div v-if="summary" class="mt-8 border-t pt-6">
                <h2 class="mb-4 text-lg font-medium text-gray-900">Upload Summary</h2>
                <div class="rounded-lg bg-gray-50 p-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Total Processed</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ summary.processed }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Duplicates</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ summary.duplicates }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Invalid Records</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ summary.invalid }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            <CsvPreviewModal
                :show="showPreviewModal"
                :headers="previewHeaders"
                :data="previewData"
                :isParsing="isParsing"
                @close="closePreviewModal"
            />
        </div>
    </AppLayout>
</template>
