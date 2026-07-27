<livewire:product-analytics-screen
    :product-name="$screen['product']['name']"
    :emoji="$screen['product']['emoji']"
    :site-id="$screen['product']['site_id']"
    :screen-name="$screen['name']"
    :key="'product-analytics-'.$screen['product']['site_id']"
/>
