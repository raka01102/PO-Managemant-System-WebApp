window.purchaseOrderShowManager = () => ({

    previewDokumen(filePath) {
        this.$dispatch(
            'open-modal',
            'preview-file-modal'
        )
    },

    closePreviewDokumen() {
        this.$dispatch(
            'close-modal',
            'preview-file-modal'
        )
    }
})
