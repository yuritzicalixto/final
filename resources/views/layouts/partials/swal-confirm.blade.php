{{-- ═══════════════════════════════════════════════════════════════
     SweetAlert2: Intercepta formularios con clase .swal-confirm-form
     Lee la configuración del modal desde data-swal-* attributes.

     USO en cualquier formulario:
       class="swal-confirm-form"
       data-swal-title="¿Título?"
       data-swal-text="Descripción"
       data-swal-icon="warning|question|info|error"
       data-swal-confirm="Texto del botón confirmar"
       data-swal-cancel="Texto del botón cancelar"
       data-swal-color="#hex del botón confirmar"
     ═══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.swal-confirm-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const title        = this.dataset.swalTitle   || '¿Estás seguro?';
            const text         = this.dataset.swalText    || '';
            const icon         = this.dataset.swalIcon    || 'warning';
            const confirmText  = this.dataset.swalConfirm || 'Sí, confirmar';
            const cancelText   = this.dataset.swalCancel  || 'Cancelar';
            const confirmColor = this.dataset.swalColor   || '#dc2626';

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
