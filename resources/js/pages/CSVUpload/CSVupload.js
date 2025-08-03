import axios from 'axios';
import Papa from 'papaparse';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
export function useCsvUploader() {
    const file = ref(null);
    const isUploading = ref(false);
    const uploadProgress = ref(0);
    const message = ref('');
    const messageClass = ref('');
    const summary = ref(null);
    const isDragging = ref(false);
    const previewData = ref([]);
    const previewHeaders = ref([]);
    const showPreviewModal = ref(false);
    const isParsing = ref(false);

    const handleFileChange = (event) => {
        const input = event.target;
        if (!input.files?.length) return;
        validateAndSetFile(input.files[0]);
    };

    const handleDrop = (event) => {
        event.preventDefault();
        isDragging.value = false;

        if (!event.dataTransfer?.files.length) return;
        validateAndSetFile(event.dataTransfer.files[0]);
    };

    const handleDragOver = (event) => {
        event.preventDefault();
        isDragging.value = true;
    };

    const handleDragLeave = () => {
        isDragging.value = false;
    };

    const validateAndSetFile = (selectedFile) => {
        file.value = null;
        //showMessage('', '');

        if (!selectedFile.name.match(/\.(csv)$/i)) {
            showMessage('Only CSV files are allowed', 'error');
            return;
        }

        if (selectedFile.size > 2 * 1024 * 1024) {
            showMessage('File size exceeds 2MB limit', 'error');
            return;
        }

        file.value = selectedFile;
        // showMessage(`${selectedFile.name} ready for upload`, 'success');
    };

    const parseCsv = (file) => {
        return new Promise((resolve, reject) => {
            isParsing.value = true;

            Papa.parse(file, {
                header: true,
                skipEmptyLines: true,
                complete: (results) => {
                    isParsing.value = false;
                    if (results.errors.length) {
                        const errorMsg = results.errors.map((e) => `Row ${e.row}: ${e.message}`).join('\n');
                        reject(new Error(errorMsg));
                    } else {
                        resolve(results);
                    }
                },
                error: (error) => {
                    isParsing.value = false;
                    reject(error);
                },
            });
        });
    };
    const openPreviewModal = async () => {
        if (!file.value || isParsing.value) return;

        try {
            const results = await parseCsv(file.value);
            previewHeaders.value = results.meta.fields;
            previewData.value = results.data;
            showPreviewModal.value = true;
        } catch (error) {
            showMessage(`CSV parsing failed: ${error.message}`, 'error');
        }
    };
    const closePreviewModal = () => {
        showPreviewModal.value = false;
    };
    const resetFileInput = () => {
        file.value = null;
        message.value = '';
        // Reset the file input element
        if (fileInput.value) {
            fileInput.value.value = '';
        }
    };
    const handleSubmit = async () => {
        summary.value = null;
        if (!file.value) return;

        isUploading.value = true;
        uploadProgress.value = 0;

        const formData = new FormData();
        formData.append('csv_file', file.value);

        try {
            const response = await axios.post('/csv-upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onUploadProgress: (progressEvent) => {
                    if (progressEvent.total) {
                        uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                },
            });

            if (response.data && typeof response.data === 'object') {
                summary.value = response.data;
                file.value = null;
                message.value = '';
                resetFileInput();
                showMessage('File uploaded successfully!', 'success');
            }
        } catch (error) {
            let errorMessage = 'An error occurred during upload';
            if (error.response) {
                errorMessage = error.response.data.message || errorMessage;
            }
            showMessage(errorMessage, 'error');
            summary.value = error.response.data;
        } finally {
            isUploading.value = false;
        }
    };

    const showMessage = (msg, type = 'default') => {
        const options = {
            position: 'top-right',
            autoClose: 3000,
        };

        switch (type) {
            case 'success':
                toast.success(msg, options);
                break;
            case 'error':
                toast.error(msg, options);
                break;
            case 'warning':
                toast.warning(msg, options);
                break;
            default:
                toast(msg, options);
        }
    };

    const clearForm = () => {
        file.value = null;
        message.value = '';
        summary.value = null;
        if (fileInput.value) {
            fileInput.value.value = '';
        }
    };

    return {
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
    };
}
