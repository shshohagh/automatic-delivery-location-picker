{{-- 
    Delivery Location Picker Component
    Usage: Include this in your product page blade file
    Requirements: Bootstrap 5, Leaflet.js (free OpenStreetMap - no API key needed)
    
    @include('components.delivery-location-picker')
--}}

{{-- ========== PRODUCT PAGE - LOCATION TRIGGER ========== --}}
{{-- Paste this where your location/delivery info shows --}}

<div class="delivery-info-section">
    <div class="delivery-row">
        <span class="delivery-icon">🚚</span>
        <span class="delivery-label"><strong>Delivery:</strong> 1-2 hours</span>
    </div>
    <div class="delivery-row location-trigger-row">
        <span class="location-pin-icon">📍</span>
        <span class="location-label"><strong>Location:</strong></span>
        <button 
            type="button" 
            class="btn-location-trigger" 
            data-bs-toggle="modal" 
            data-bs-target="#locationPickerModal"
            id="locationTriggerBtn"
        >
            <span id="selectedLocationText">House 40, Road 7, Block F, Banani, Dhaka</span>
            <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
    </div>
</div>


{{-- ========== LOCATION PICKER MODAL ========== --}}

<div class="modal fade" id="locationPickerModal" tabindex="-1" aria-labelledby="locationPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered location-modal-dialog">
        <div class="modal-content location-modal-content">
            
            {{-- Modal Header --}}
            <div class="modal-header location-modal-header">
                <div class="modal-title-group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="title-location-icon"><circle cx="12" cy="10" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.4 7.05 11.5 7.35 11.76a1 1 0 0 0 1.3 0C12.95 21.5 20 15.4 20 10a8 8 0 0 0-8-8z"/></svg>
                    <h5 class="modal-title" id="locationPickerModalLabel">Delivery Location</h5>
                </div>
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            {{-- Search Bar --}}
            <div class="location-search-wrapper">
                <div class="location-search-bar">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input 
                        type="text" 
                        id="locationSearchInput" 
                        class="location-search-input" 
                        placeholder="Search your area..." 
                        autocomplete="off"
                    />
                    <button type="button" id="clearSearchBtn" class="btn-clear-search" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                    <button type="button" id="gpsLocateBtn" class="btn-gps" title="Use my current location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
                    </button>
                    <button type="button" id="searchGoBtn" class="btn-go">GO</button>
                </div>
                
                {{-- Search Suggestions Dropdown --}}
                <div id="searchSuggestions" class="search-suggestions" style="display:none;"></div>
            </div>

            {{-- Map Container --}}
            <div class="map-container">
                <div id="locationMap"></div>
                {{-- Center pin overlay --}}
                <div class="map-center-pin" id="mapCenterPin">
                    <div class="pin-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#E53935" stroke="white" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a8 8 0 0 0-8 8c0 5.4 7.05 11.5 7.35 11.76a1 1 0 0 0 1.3 0C12.95 21.5 20 15.4 20 10a8 8 0 0 0-8-8z"/><circle cx="12" cy="10" r="3" fill="white" stroke="none"/></svg>
                    </div>
                    <div class="pin-shadow"></div>
                </div>
                {{-- Map zoom controls --}}
                <div class="map-zoom-controls">
                    <button type="button" id="zoomInBtn" class="btn-zoom">+</button>
                    <button type="button" id="zoomOutBtn" class="btn-zoom">−</button>
                    <button type="button" id="compassBtn" class="btn-zoom compass-btn">▲</button>
                </div>
            </div>

            {{-- Current Address Display --}}
            <div class="current-address-bar" id="currentAddressBar">
                <div class="address-loading" id="addressLoading">
                    <div class="loading-spinner"></div>
                    <span>Getting address...</span>
                </div>
                <div class="address-text" id="addressText" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#E53935" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.4 7.05 11.5 7.35 11.76a1 1 0 0 0 1.3 0C12.95 21.5 20 15.4 20 10a8 8 0 0 0-8-8z"/></svg>
                    <span id="reversedAddress">Locating...</span>
                </div>
            </div>

            {{-- Modal Footer Buttons --}}
            <div class="modal-footer location-modal-footer">
                <button type="button" class="btn-manual-input" id="manualInputBtn" data-bs-toggle="collapse" data-bs-target="#manualAddressCollapse">
                    ✏️ Manual Input
                </button>
                <button type="button" class="btn-confirm-location" id="confirmLocationBtn">
                    Confirm Location
                </button>
            </div>

            {{-- Manual Input Collapse --}}
            <div class="collapse" id="manualAddressCollapse">
                <div class="manual-input-section">
                    <div class="row g-2">
                        <div class="col-12">
                            <input type="text" class="form-control manual-input" id="manualHouse" placeholder="House / Flat No." />
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control manual-input" id="manualRoad" placeholder="Road No." />
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control manual-input" id="manualBlock" placeholder="Block / Sector" />
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control manual-input" id="manualArea" placeholder="Area / Thana" />
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn-confirm-manual w-100" id="confirmManualBtn">
                                Use This Address
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- ========== STYLES ========== --}}
<style>
/* -- Google Font -- */
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600&family=DM+Sans:wght@400;500;600;700&display=swap');

:root {
    --brand-red: #E53935;
    --brand-yellow: #FFC107;
    --brand-dark: #1a1a2e;
    --gray-light: #f5f5f5;
    --gray-mid: #e0e0e0;
    --gray-text: #757575;
    --text-dark: #212121;
    --white: #ffffff;
    --shadow-soft: 0 4px 20px rgba(0,0,0,0.12);
    --shadow-modal: 0 20px 60px rgba(0,0,0,0.25);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
}

/* ---- TRIGGER SECTION ---- */
.delivery-info-section {
    padding: 12px 0;
    border-top: 1px solid var(--gray-mid);
    font-family: 'DM Sans', sans-serif;
}
.delivery-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 0;
}
.location-trigger-row {
    flex-wrap: wrap;
}
.btn-location-trigger {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #1565C0;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    transition: opacity 0.2s;
    text-decoration: none;
}
.btn-location-trigger:hover {
    opacity: 0.75;
    text-decoration: underline;
}
.chevron-icon {
    color: #1565C0;
    transition: transform 0.2s;
}

/* ---- MODAL DIALOG ---- */
.location-modal-dialog {
    max-width: 420px;
    margin: 0 auto;
}
.location-modal-content {
    border: none;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-modal);
    font-family: 'DM Sans', sans-serif;
}

/* ---- MODAL HEADER ---- */
.location-modal-header {
    background: var(--white);
    border-bottom: 1px solid var(--gray-mid);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modal-title-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.title-location-icon {
    color: var(--brand-red);
}
.modal-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    letter-spacing: -0.2px;
}
.btn-close-custom {
    background: var(--gray-light);
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--gray-text);
    transition: background 0.2s, color 0.2s;
    padding: 0;
}
.btn-close-custom:hover {
    background: var(--gray-mid);
    color: var(--text-dark);
}

/* ---- SEARCH BAR ---- */
.location-search-wrapper {
    padding: 12px 16px 8px;
    background: var(--white);
    position: relative;
    z-index: 10;
}
.location-search-bar {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--gray-light);
    border: 1.5px solid var(--gray-mid);
    border-radius: var(--radius-sm);
    padding: 8px 10px;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.location-search-bar:focus-within {
    border-color: var(--brand-red);
    box-shadow: 0 0 0 3px rgba(229,57,53,0.1);
    background: var(--white);
}
.search-icon {
    color: var(--gray-text);
    flex-shrink: 0;
}
.location-search-input {
    flex: 1;
    border: none;
    background: transparent;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--text-dark);
    outline: none;
    min-width: 0;
}
.location-search-input::placeholder {
    color: #bdbdbd;
}
.btn-clear-search {
    background: none;
    border: none;
    padding: 2px;
    cursor: pointer;
    color: var(--gray-text);
    display: flex;
    align-items: center;
    flex-shrink: 0;
}
.btn-gps {
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    color: #1565C0;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    transition: color 0.2s;
}
.btn-gps:hover { color: var(--brand-red); }
.btn-gps.locating {
    animation: spin 1s linear infinite;
    color: var(--brand-red);
}
.btn-go {
    background: var(--brand-yellow);
    border: none;
    border-radius: 6px;
    padding: 4px 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-dark);
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s, transform 0.1s;
}
.btn-go:hover { background: #FFB300; }
.btn-go:active { transform: scale(0.96); }

/* ---- SEARCH SUGGESTIONS ---- */
.search-suggestions {
    position: absolute;
    left: 16px;
    right: 16px;
    top: calc(100% - 8px);
    background: var(--white);
    border: 1px solid var(--gray-mid);
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow-soft);
    z-index: 999;
    max-height: 200px;
    overflow-y: auto;
}
.suggestion-item {
    padding: 10px 14px;
    cursor: pointer;
    font-size: 13px;
    color: var(--text-dark);
    border-bottom: 1px solid var(--gray-light);
    display: flex;
    align-items: flex-start;
    gap: 8px;
    transition: background 0.15s;
}
.suggestion-item:last-child { border-bottom: none; }
.suggestion-item:hover { background: var(--gray-light); }
.suggestion-item .sug-icon { color: var(--gray-text); margin-top: 1px; flex-shrink: 0; }
.suggestion-item .sug-name { font-weight: 600; display: block; }
.suggestion-item .sug-sub { color: var(--gray-text); font-size: 12px; display: block; }

/* ---- MAP ---- */
.map-container {
    position: relative;
    height: 280px;
    background: #e8e0d8;
}
#locationMap {
    width: 100%;
    height: 100%;
}

/* Center pin */
.map-center-pin {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -100%);
    pointer-events: none;
    z-index: 500;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.15s ease;
}
.map-center-pin.dragging {
    transform: translate(-50%, -110%) scale(1.1);
}
.pin-icon {
    filter: drop-shadow(0 3px 6px rgba(0,0,0,0.35));
}
.pin-shadow {
    width: 12px;
    height: 4px;
    background: rgba(0,0,0,0.25);
    border-radius: 50%;
    margin-top: -4px;
    filter: blur(2px);
}

/* Map zoom controls */
.map-zoom-controls {
    position: absolute;
    right: 12px;
    bottom: 40px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    z-index: 500;
}
.btn-zoom {
    width: 32px;
    height: 32px;
    background: var(--white);
    border: 1px solid var(--gray-mid);
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    transition: background 0.15s;
}
.btn-zoom:hover { background: var(--gray-light); }
.compass-btn { font-size: 12px; color: var(--gray-text); }

/* ---- ADDRESS BAR ---- */
.current-address-bar {
    background: var(--white);
    border-top: 1px solid var(--gray-mid);
    padding: 10px 16px;
    min-height: 44px;
    display: flex;
    align-items: center;
}
.address-loading {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray-text);
    font-size: 13px;
}
.loading-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid var(--gray-mid);
    border-top-color: var(--brand-red);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    flex-shrink: 0;
}
.address-text {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    font-size: 13px;
    color: var(--text-dark);
    line-height: 1.4;
}
.address-text svg { flex-shrink: 0; margin-top: 1px; }
#reversedAddress { flex: 1; }

/* ---- FOOTER BUTTONS ---- */
.location-modal-footer {
    padding: 12px 16px;
    border-top: 1px solid var(--gray-mid);
    display: flex;
    gap: 10px;
    background: var(--white);
}
.btn-manual-input {
    flex: 1;
    background: var(--white);
    border: 2px solid var(--brand-red);
    border-radius: var(--radius-sm);
    padding: 10px 16px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--brand-red);
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.btn-manual-input:hover {
    background: #fff5f5;
}
.btn-confirm-location {
    flex: 1.5;
    background: var(--brand-yellow);
    border: none;
    border-radius: var(--radius-sm);
    padding: 10px 20px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    cursor: pointer;
    transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(255,193,7,0.4);
}
.btn-confirm-location:hover {
    background: #FFB300;
    box-shadow: 0 4px 14px rgba(255,193,7,0.5);
}
.btn-confirm-location:active { transform: scale(0.98); }

/* ---- MANUAL INPUT ---- */
.manual-input-section {
    padding: 12px 16px 16px;
    background: var(--gray-light);
    border-top: 1px solid var(--gray-mid);
}
.manual-input {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    border: 1.5px solid var(--gray-mid);
    border-radius: var(--radius-sm);
    padding: 8px 12px;
    background: var(--white);
    transition: border-color 0.2s;
}
.manual-input:focus {
    border-color: var(--brand-red);
    box-shadow: 0 0 0 3px rgba(229,57,53,0.1);
}
.btn-confirm-manual {
    background: var(--brand-red);
    border: none;
    border-radius: var(--radius-sm);
    padding: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--white);
    cursor: pointer;
    transition: background 0.2s;
}
.btn-confirm-manual:hover { background: #C62828; }

/* ---- ANIMATIONS ---- */
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ---- SUCCESS TOAST ---- */
.location-toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%) translateY(80px);
    background: var(--text-dark);
    color: var(--white);
    padding: 12px 20px;
    border-radius: 50px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    z-index: 9999;
    box-shadow: 0 6px 24px rgba(0,0,0,0.25);
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s;
    opacity: 0;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
}
.location-toast.show {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}
.location-toast .toast-check {
    width: 20px;
    height: 20px;
    background: #4CAF50;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ---- MOBILE RESPONSIVE ---- */
@media (max-width: 480px) {
    .location-modal-dialog {
        max-width: 100%;
        margin: auto 0 0;
    }
    .location-modal-content {
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
    .modal.fade .modal-dialog {
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    .modal.show .modal-dialog {
        transform: translateY(0);
    }
    .map-container {
        height: 260px;
    }
}
</style>


{{-- ========== SCRIPTS ========== --}}

{{-- Leaflet CSS (Free OpenStreetMap - no API key needed) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function() {
    // ---- STATE ----
    let map = null;
    let mapInitialized = false;
    let selectedLat = 23.7937;  // Default: Banani, Dhaka
    let selectedLng = 90.4066;
    let selectedAddress = 'House 40, Road 7, Block F, Banani, Dhaka';
    let reverseGeocodeTimer = null;
    let isDragging = false;

    // ---- INIT MAP ON MODAL OPEN ----
    const modalEl = document.getElementById('locationPickerModal');
    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', function () {
            if (!mapInitialized) {
                initMap();
                mapInitialized = true;
            } else {
                map.invalidateSize();
            }
        });
    }

    function initMap() {
        map = L.map('locationMap', {
            center: [selectedLat, selectedLng],
            zoom: 16,
            zoomControl: false,   // We use custom zoom buttons
            attributionControl: false
        });

        // OpenStreetMap tiles (free, no API key)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        // Reverse geocode on initial load
        reverseGeocode(selectedLat, selectedLng);

        // When map moves, update pin and reverse geocode
        map.on('movestart', function() {
            isDragging = true;
            document.getElementById('mapCenterPin').classList.add('dragging');
            showAddressLoading();
        });

        map.on('moveend', function() {
            isDragging = false;
            document.getElementById('mapCenterPin').classList.remove('dragging');
            const center = map.getCenter();
            selectedLat = center.lat;
            selectedLng = center.lng;
            clearTimeout(reverseGeocodeTimer);
            reverseGeocodeTimer = setTimeout(function() {
                reverseGeocode(selectedLat, selectedLng);
            }, 600);
        });

        // Custom zoom buttons
        document.getElementById('zoomInBtn').addEventListener('click', function() {
            map.zoomIn();
        });
        document.getElementById('zoomOutBtn').addEventListener('click', function() {
            map.zoomOut();
        });
    }

    // ---- REVERSE GEOCODE (Nominatim - free) ----
    function reverseGeocode(lat, lng) {
        showAddressLoading();
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=en`, {
            headers: { 'Accept-Language': 'en' }
        })
        .then(r => r.json())
        .then(data => {
            const addr = data.address || {};
            const parts = [
                addr.road || addr.pedestrian || addr.footway,
                addr.suburb || addr.neighbourhood || addr.city_district,
                addr.city || addr.town || addr.village,
                addr.country
            ].filter(Boolean);
            const fullAddress = data.display_name
                ? parts.join(', ') || data.display_name
                : `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            selectedAddress = fullAddress;
            showAddress(fullAddress);
        })
        .catch(function() {
            showAddress(`${lat.toFixed(5)}, ${lng.toFixed(5)}`);
        });
    }

    function showAddressLoading() {
        document.getElementById('addressLoading').style.display = 'flex';
        document.getElementById('addressText').style.display = 'none';
    }
    function showAddress(text) {
        document.getElementById('addressLoading').style.display = 'none';
        document.getElementById('addressText').style.display = 'flex';
        document.getElementById('reversedAddress').textContent = text;
    }

    // ---- SEARCH ----
    const searchInput = document.getElementById('locationSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const suggestions = document.getElementById('searchSuggestions');
    let searchTimer = null;

    searchInput.addEventListener('input', function() {
        const val = this.value.trim();
        clearBtn.style.display = val ? 'flex' : 'none';
        clearTimeout(searchTimer);
        if (val.length < 3) { suggestions.style.display = 'none'; return; }
        searchTimer = setTimeout(function() { searchLocation(val); }, 400);
    });

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        clearBtn.style.display = 'none';
        suggestions.style.display = 'none';
        searchInput.focus();
    });

    document.getElementById('searchGoBtn').addEventListener('click', function() {
        const val = searchInput.value.trim();
        if (val) searchLocation(val, true);
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const val = this.value.trim();
            if (val) searchLocation(val, true);
        }
    });

    function searchLocation(query, goFirst) {
        const q = encodeURIComponent(query + ', Bangladesh');
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${q}&limit=5&accept-language=en`)
        .then(r => r.json())
        .then(results => {
            if (!results || results.length === 0) {
                suggestions.innerHTML = '<div class="suggestion-item"><span class="sug-name">No results found</span></div>';
                suggestions.style.display = 'block';
                return;
            }
            if (goFirst && results[0]) {
                selectSearchResult(results[0]);
                suggestions.style.display = 'none';
                return;
            }
            suggestions.innerHTML = '';
            results.forEach(function(item) {
                const parts = item.display_name.split(',');
                const name = parts[0] || item.display_name;
                const sub = parts.slice(1, 4).join(',').trim();
                const el = document.createElement('div');
                el.className = 'suggestion-item';
                el.innerHTML = `
                    <svg class="sug-icon" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.4 7.05 11.5 7.35 11.76a1 1 0 0 0 1.3 0C12.95 21.5 20 15.4 20 10a8 8 0 0 0-8-8z"/></svg>
                    <span><span class="sug-name">${name}</span><span class="sug-sub">${sub}</span></span>
                `;
                el.addEventListener('click', function() {
                    selectSearchResult(item);
                    suggestions.style.display = 'none';
                    searchInput.value = name;
                    clearBtn.style.display = 'flex';
                });
                suggestions.appendChild(el);
            });
            suggestions.style.display = 'block';
        })
        .catch(function() {
            suggestions.style.display = 'none';
        });
    }

    function selectSearchResult(item) {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lon);
        if (map) {
            map.flyTo([lat, lng], 17, { duration: 1.2 });
        }
        selectedLat = lat;
        selectedLng = lng;
        selectedAddress = item.display_name;
        showAddress(item.display_name);
    }

    // Close suggestions on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.location-search-wrapper')) {
            suggestions.style.display = 'none';
        }
    });

    // ---- GPS / Current Location ----
    document.getElementById('gpsLocateBtn').addEventListener('click', function() {
        if (!navigator.geolocation) { alert('Geolocation not supported.'); return; }
        const btn = this;
        btn.classList.add('locating');
        navigator.geolocation.getCurrentPosition(function(pos) {
            btn.classList.remove('locating');
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            if (map) map.flyTo([lat, lng], 17, { duration: 1.2 });
            selectedLat = lat;
            selectedLng = lng;
            reverseGeocode(lat, lng);
        }, function() {
            btn.classList.remove('locating');
            alert('Could not get your location. Please allow location access.');
        });
    });

    // ---- CONFIRM LOCATION ----
    document.getElementById('confirmLocationBtn').addEventListener('click', function() {
        // Update the trigger button text
        const displayText = selectedAddress.length > 45
            ? selectedAddress.substring(0, 45) + '...'
            : selectedAddress;
        document.getElementById('selectedLocationText').textContent = displayText;

        // Store in hidden field (for Laravel form submission)
        setHiddenFields(selectedLat, selectedLng, selectedAddress);

        // Close modal
        const bsModal = bootstrap.Modal.getInstance(document.getElementById('locationPickerModal'));
        if (bsModal) bsModal.hide();

        // Show toast
        showToast('📍 Delivery location updated!');
    });

    // ---- MANUAL INPUT CONFIRM ----
    document.getElementById('confirmManualBtn').addEventListener('click', function() {
        const house = document.getElementById('manualHouse').value.trim();
        const road  = document.getElementById('manualRoad').value.trim();
        const block = document.getElementById('manualBlock').value.trim();
        const area  = document.getElementById('manualArea').value.trim();

        const parts = [house, road && 'Road ' + road, block && 'Block ' + block, area].filter(Boolean);
        if (parts.length === 0) { alert('Please enter at least one field.'); return; }
        const manualAddress = parts.join(', ') + (area ? '' : ', Dhaka');

        selectedAddress = manualAddress;
        document.getElementById('selectedLocationText').textContent = manualAddress.length > 45
            ? manualAddress.substring(0, 45) + '...'
            : manualAddress;

        setHiddenFields(selectedLat, selectedLng, manualAddress);
        showAddress(manualAddress);

        // Collapse manual section
        const collapse = document.getElementById('manualAddressCollapse');
        if (collapse) {
            const bsCollapse = bootstrap.Collapse.getInstance(collapse);
            if (bsCollapse) bsCollapse.hide();
        }

        const bsModal = bootstrap.Modal.getInstance(document.getElementById('locationPickerModal'));
        if (bsModal) bsModal.hide();

        showToast('📍 Delivery location updated!');
    });

    // ---- HELPER: Set hidden inputs for Laravel form ----
    function setHiddenFields(lat, lng, address) {
        // Dynamically create/update hidden inputs in your order form
        updateOrCreateInput('delivery_lat', lat);
        updateOrCreateInput('delivery_lng', lng);
        updateOrCreateInput('delivery_address', address);

        // Dispatch custom event for any JS listeners
        document.dispatchEvent(new CustomEvent('deliveryLocationSelected', {
            detail: { lat: lat, lng: lng, address: address }
        }));
    }

    function updateOrCreateInput(name, value) {
        let el = document.querySelector(`input[name="${name}"]`);
        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = name;
            document.body.appendChild(el);
        }
        el.value = value;
    }

    // ---- TOAST ----
    function showToast(message) {
        let toast = document.querySelector('.location-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'location-toast';
            document.body.appendChild(toast);
        }
        toast.innerHTML = `
            <div class="toast-check">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            ${message}
        `;
        toast.classList.add('show');
        setTimeout(function() { toast.classList.remove('show'); }, 2800);
    }

})();
</script>
