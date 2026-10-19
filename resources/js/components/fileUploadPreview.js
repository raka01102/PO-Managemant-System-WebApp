const MAX_FILE_SIZE = 2 * 1024 * 1024 // 2 MB

export function fileUploadPreview() {
    return {
        fileName: '',
        fileSize: '',
        previewUrl: '',
        isImage: false,
        isPdf: false,
        errorMessage: '',

        handleChange(event) {
            const input = event.target
            const file = input.files?.[0]

            this.errorMessage = ''

            if (!file) {
                this.removeFile()
                return
            }

            if (file.size > MAX_FILE_SIZE) {
                this.clearPreview()
                this.fileName = ''
                this.fileSize = ''
                this.isImage = false
                this.isPdf = false
                input.value = ''

                this.errorMessage = 'Ukuran file maksimal 2 MB'
                return
            }

            this.clearPreview()

            this.fileName = file.name
            this.fileSize = this.formatFileSize(file.size)
            this.isImage = file.type.startsWith('image/')
            this.isPdf = file.type === 'application/pdf'

            if (this.isImage) {
                this.previewUrl = URL.createObjectURL(file)
            }
        },

        removeFile() {
            const input = this.$root.querySelector('input[type="file"]')

            this.clearPreview()

            this.fileName = ''
            this.fileSize = ''
            this.isImage = false
            this.isPdf = false
            this.errorMessage = ''

            if (input) {
                input.value = ''
            }
        },

        clearPreview() {
            if (this.previewUrl) {
                URL.revokeObjectURL(this.previewUrl)
                this.previewUrl = ''
            }
        },

        formatFileSize(bytes) {
            if (bytes < 1024) {
                return `${bytes} B`
            }

            if (bytes < 1024 * 1024) {
                return `${(bytes / 1024).toFixed(1)} KB`
            }

            return `${(bytes / (1024 * 1024)).toFixed(2)} MB`
        },
    };
}
