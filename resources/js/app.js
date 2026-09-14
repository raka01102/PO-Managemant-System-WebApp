import './bootstrap';
import './pages/customer';
import './pages/product';
import './pages/purchase-orders';
import './pages/layout';
import './pages/purchase-order-confirm';
import './pages/purchase-order-edit';
import './pages/purchase-order-show';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fontsource/inter';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

window.Swal = Swal;

// Global Helper
window.formatRupiah = (number) => {
    if (number===null || number===undefined || isNaN(number)) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

window.parseNumber = (value) => parseFloat(value) || 0;

//Reusable Components
Alpine.data('filePreview', () => ({
    selectedFile: null,

    selectedFileName: null,

    previewDokumen(filePath, fileName) {
        this.selectedFile = filePath

        this.selectedFileName = fileName

        this.$dispatch('open-modal', 'preview-file-modal')
    },

    closePreviewDokumen() {
        this.selectedFile = null

        this.selectedFileName = null

        this.$dispatch('close-modal', 'preview-file-modal')
    },

    get selectedFileUrl() {
        if (!this.selectedFile) {
            return ''
        }

        return '/storage/' + this.selectedFile
    },

    get fileName() {
        if(!this.selectedFileName) {
            return ''
        }

        return this.selectedFileName
    },

    get selectedExtension() {
        if (!this.selectedFile) {
            return ''
        }

        return this.selectedFile.split('.').pop().toLowerCase()
    },

    get isImage() {
        return ['jpg', 'jpeg', 'png'].includes(this.selectedExtension)
    },

    get isPdf() {
        return this.selectedExtension === 'pdf'
    }
}))

window.Alpine = Alpine;

Alpine.start();
