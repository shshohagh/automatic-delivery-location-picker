# 📍 Delivery Location Picker

A Laravel + Bootstrap modal component that lets users pick their delivery location from an interactive map. Built with [Leaflet.js](https://leafletjs.com/) and OpenStreetMap — **no API key required**.

---

## Preview

Click the location text on the product page → a modal opens with a live map, search bar, GPS detection, and manual address input.

---

## Features

- 🗺️ **Interactive map** — drag to move the pin and auto-detect the address
- 🔍 **Live search** — type-ahead suggestions using Nominatim (free OpenStreetMap geocoding)
- 📡 **GPS button** — detects the user's current location (browser permission required)
- ✏️ **Manual input** — fallback form for house/road/block/area
- ✅ **Confirm button** — saves the address and updates the product page display
- 🔔 **Toast notification** — confirms the location was saved
- 📱 **Mobile responsive** — slides up as a bottom sheet on small screens
- 💸 **Zero cost** — uses OpenStreetMap tiles and Nominatim, no paid API needed

---

## Files

| File | Description |
|------|-------------|
| `delivery-location-picker.blade.php` | The main Blade component to include in your project |
| `location-picker-preview.html` | Standalone HTML preview (open in browser to test) |

---

## Installation

### 1. Copy the Blade file

Place the component in your views directory:

```
resources/views/components/delivery-location-picker.blade.php
```

### 2. Add dependencies

Make sure your layout includes Bootstrap 5. The component self-loads Leaflet from CDN — no extra steps needed.

```html
<!-- In your layout <head> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>

<!-- Before closing </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
```

### 3. Include in your product page

```blade
{{-- resources/views/product/show.blade.php --}}

@include('components.delivery-location-picker')
```

### 4. Add hidden inputs to your order form

The component automatically creates and populates these hidden fields on confirm:

```html
<input type="hidden" name="delivery_address" />
<input type="hidden" name="delivery_lat" />
<input type="hidden" name="delivery_lng" />
```

If your `<form>` is elsewhere on the page, make sure these inputs are inside it before submission.

---

## Usage in Controller

```php
// app/Http/Controllers/OrderController.php

public function store(Request $request)
{
    $request->validate([
        'delivery_address' => 'required|string|max:500',
        'delivery_lat'     => 'required|numeric',
        'delivery_lng'     => 'required|numeric',
    ]);

    $order = Order::create([
        'delivery_address' => $request->delivery_address,
        'delivery_lat'     => $request->delivery_lat,
        'delivery_lng'     => $request->delivery_lng,
        // ...other fields
    ]);
}
```

---

## Migration (if saving to database)

```php
Schema::table('orders', function (Blueprint $table) {
    $table->string('delivery_address')->nullable();
    $table->decimal('delivery_lat', 10, 7)->nullable();
    $table->decimal('delivery_lng', 10, 7)->nullable();
});
```

---

## Listening for Location Changes (JavaScript)

The component fires a custom DOM event when a location is confirmed. You can hook into it for live UI updates:

```javascript
document.addEventListener('deliveryLocationSelected', function(e) {
    console.log(e.detail.address); // "Road 7, Banani, Dhaka"
    console.log(e.detail.lat);     // 23.7937
    console.log(e.detail.lng);     // 90.4066
});
```

---

## Default Location

The map opens centered on **Banani, Dhaka** by default. To change it, edit these lines near the top of the `<script>` block:

```javascript
let selectedLat = 23.7937;  // ← your default latitude
let selectedLng = 90.4066;  // ← your default longitude
let selectedAddress = 'House 40, Road 7, Block F, Banani, Dhaka'; // ← display text
```

To center on the user's saved address (e.g. from a logged-in session), pass it via Blade:

```blade
{{-- In your product page --}}
@php
    $userLat = auth()->user()->delivery_lat ?? 23.7937;
    $userLng = auth()->user()->delivery_lng ?? 90.4066;
    $userAddress = auth()->user()->delivery_address ?? 'Dhaka, Bangladesh';
@endphp

<script>
    window.defaultDeliveryLocation = {
        lat: {{ $userLat }},
        lng: {{ $userLng }},
        address: "{{ $userAddress }}"
    };
</script>

@include('components.delivery-location-picker')
```

Then in the component script, replace the defaults with:
```javascript
let selectedLat = window.defaultDeliveryLocation?.lat ?? 23.7937;
let selectedLng = window.defaultDeliveryLocation?.lng ?? 90.4066;
let selectedAddress = window.defaultDeliveryLocation?.address ?? 'Dhaka, Bangladesh';
```

---

## Third-Party Services Used

| Service | Purpose | Cost |
|---------|---------|------|
| [OpenStreetMap](https://www.openstreetmap.org/) | Map tiles | Free |
| [Leaflet.js](https://leafletjs.com/) | Map rendering library | Free |
| [Nominatim](https://nominatim.openstreetmap.org/) | Search & reverse geocoding | Free |

> **Note:** Nominatim has a usage policy of max 1 request/second and requires a valid `User-Agent`. For high-traffic production sites, consider self-hosting Nominatim or switching to a paid geocoding API (Google Maps, MapBox, etc.).

---

## Browser Support

Works in all modern browsers. GPS detection requires HTTPS in production (browsers block geolocation on HTTP).
