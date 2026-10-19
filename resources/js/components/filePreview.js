const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp']

export function filePreview() {
    return {
        selectedFile: null,
        selectedFileName: '',

        previewDokumen(filePath, fileName = '') {
            if (!filePath) return

            this.selectedFile = filePath
            this.selectedFileName = fileName

            this.$dispatch('open-modal','preview-file-modal')
        },

        closePreviewDokumen() {
            this.selectedFile = null
            this.selectedFileName = ''

            this.$dispatch('close-modal','preview-file-modal')
        },

        get selectedFileUrl() {
            if (!this.selectedFile) return ''

            return `/storage/${this.selectedFile}`
        },

        get fileName() {
            return this.selectedFileName
        },

        get selectedExtension() {
            if (!this.selectedFile) return ''

            return this.selectedFile.split('.').pop().toLowerCase()
        },

        get isImage() {
            return IMAGE_EXTENSIONS.includes(this.selectedExtension)
        },

        get isPdf() {
            return this.selectedExtension === 'pdf'
        },
    }
}
