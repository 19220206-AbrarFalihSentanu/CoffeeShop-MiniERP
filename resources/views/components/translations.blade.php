{{-- File: resources/views/components/translations.blade.php --}}
{{-- 
    This component provides translations to JavaScript
    Usage: @include('components.translations')
--}}
<script>
    window.translations = {
        locale: '{{ app()->getLocale() }}',
        general: {
            confirm: '{{ __('general.confirm') }}',
            cancel: '{{ __('general.cancel') }}',
            delete: '{{ __('general.delete') }}',
            save: '{{ __('general.save') }}',
            edit: '{{ __('general.edit') }}',
            close: '{{ __('general.close') }}',
            yes: '{{ __('general.yes') }}',
            no: '{{ __('general.no') }}',
            loading: '{{ __('general.loading') }}',
            processing: '{{ __('general.processing') }}',
            success: '{{ __('general.success') }}',
            error: '{{ __('general.error') }}',
            warning: '{{ __('general.warning') }}',
            info: '{{ __('general.info') }}',
            are_you_sure: '{{ __('general.are_you_sure') }}',
            confirm_delete: '{{ __('general.confirm_delete') }}',
            no_data: '{{ __('general.no_data') }}',
            search: '{{ __('general.search') }}',
        },
        cart: {
            item_added: '{{ __('cart.item_added') }}',
            item_removed: '{{ __('cart.item_removed') }}',
            cart_updated: '{{ __('cart.cart_updated') }}',
            cart_empty: '{{ __('cart.cart_empty') }}',
            add_to_cart: '{{ __('cart.add_to_cart') }}',
            checkout: '{{ __('cart.checkout') }}',
            insufficient_stock: '{{ __('cart.insufficient_stock') }}',
        },
        orders: {
            confirm_cancel_order: '{{ __('orders.confirm_cancel_order') }}',
            confirm_approve_order: '{{ __('orders.confirm_approve_order') }}',
            confirm_reject_order: '{{ __('orders.confirm_reject_order') }}',
            order_created: '{{ __('orders.order_created') }}',
        },
        payments: {
            confirm_verify: '{{ __('payments.confirm_verify') }}',
            confirm_reject: '{{ __('payments.confirm_reject') }}',
            payment_verified: '{{ __('payments.payment_verified') }}',
            payment_rejected: '{{ __('payments.payment_rejected') }}',
        }
    };

    // Helper function to get translation
    window.__ = function(key) {
        const keys = key.split('.');
        let value = window.translations;
        for (const k of keys) {
            value = value[k];
            if (value === undefined) return key;
        }
        return value;
    };
</script>

