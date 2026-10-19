import './bootstrap'
import './pages/customer'
import './pages/product'
import './pages/purchase-orders'
import './pages/layout'
import './pages/purchase-order-confirm'
import './pages/purchase-order-edit'
import './pages/purchase-order-show'
import './pages/purchase-order-payment'

import { filePreview } from './components/filePreview'
import { fileUploadPreview } from './components/fileUploadPreview'

import Alpine from 'alpinejs'
import Swal from 'sweetalert2'
import '@fortawesome/fontawesome-free/css/all.min.css'
import '@fontsource/inter'
import collapse from '@alpinejs/collapse'

Alpine.plugin(collapse)

window.Swal = Swal

Alpine.data('filePreview', filePreview)
Alpine.data('fileUploadPreview', fileUploadPreview)

window.Alpine = Alpine

Alpine.start()
