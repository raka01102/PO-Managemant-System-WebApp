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

window.Swal = Swal;

window.Alpine = Alpine;

Alpine.start();
