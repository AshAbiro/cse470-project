<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 h-screen flex items-center justify-center bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            <!-- Left Side: Controls -->
            <div class="flex flex-col space-y-8 animate-fade-in-up">
                <div class="text-center md:text-left">
                    <h1 class="text-4xl font-extrabold text-navy-900 mb-4 uppercase tracking-widest"
                        style="font-family: 'Playfair Display', serif;">
                        Where are we?
                    </h1>
                    <p class="text-gray-600 text-lg mb-8">Navigate your way to the adventure or find out how far you are
                        from the magic.</p>
                </div>

                <div class="flex flex-col space-y-6">
                    <!-- Button: My Current Location -->
                    <button id="btn-my-location"
                        class="group relative w-full flex items-center justify-center py-5 px-8 border border-transparent text-lg font-bold rounded-xl text-white bg-navy-600 hover:bg-navy-700 transition duration-300 shadow-lg transform hover:-translate-y-1 hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-navy-300">
                        <span
                            class="absolute left-6 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center group-hover:bg-opacity-30 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </span>
                        My Current Location
                    </button>

                    <!-- Button: Park Location -->
                    <button id="btn-park-location"
                        class="group relative w-full flex items-center justify-center py-5 px-8 border border-transparent text-lg font-bold rounded-xl text-navy-900 bg-gold-400 hover:bg-gold-500 transition duration-300 shadow-lg transform hover:-translate-y-1 hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-gold-200">
                        <span
                            class="absolute left-6 w-8 h-8 bg-black bg-opacity-10 rounded-full flex items-center justify-center group-hover:bg-opacity-20 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </span>
                        Park Location & Distance
                    </button>

                    <!-- Result Display -->
                    <div id="distance-info"
                        class="hidden p-4 bg-white border border-gray-200 rounded-lg shadow-sm text-center">
                        <p class="text-gray-500 text-sm uppercase font-bold tracking-wide">Distance from Park</p>
                        <p id="distance-value" class="text-3xl font-extrabold text-navy-800 mt-1">0 km</p>
                        <p id="time-estimate" class="text-xs text-gray-400 mt-1">Estimated travel time: -- mins</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Circular Map -->
            <div class="flex items-center justify-center relative">
                <!-- Decorative Ring -->
                <div class="absolute w-[52vh] h-[52vh] rounded-full border-4 border-gold-300 animate-pulse opacity-50">
                </div>
                <div class="absolute w-[55vh] h-[55vh] rounded-full border border-navy-100 opacity-30"></div>

                <!-- Map Container -->
                <div id="map"
                    class="w-[50vh] h-[50vh] rounded-full shadow-2xl border-4 border-white overflow-hidden z-10 relative">
                    <!-- Leaflet Map renders here -->
                </div>
            </div>
        </div>

        <!-- Sticky Back Button -->
        <div class="fixed bottom-6 right-6 z-[999]">
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-center bg-navy-600 hover:bg-navy-700 text-white font-bold rounded-full h-16 px-6 shadow-2xl transition transform hover:scale-110 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-navy-300 gap-2"
                title="Back to Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-lg">Back</span>
            </a>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Park Coordinates (Fantasy Kingdom, Dhaka approx as placeholder)
            const parkLat = 23.413671;
            const parkLng = 91.1305712;

            // Initialize Map
            const map = L.map('map', {
                zoomControl: false, // Hide default controls for cleaner look
                attributionControl: false
            }).setView([parkLat, parkLng], 13);

            // Add OpenStreetMap Tile Layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            // Park Marker (Custom Icon)
            const parkIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-icon-2x-gold.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const parkMarker = L.marker([parkLat, parkLng], { icon: parkIcon }).addTo(map)
                .bindPopup('<strong class="text-navy-900">The Amusement Park</strong><br>Come have fun!').openPopup();

            let userMarker = null;
            let routeLine = null;

            // My Current Location Button Logic
            document.getElementById('btn-my-location').addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser');
                    return;
                }

                this.innerHTML = '<span class="animate-spin inline-block mr-2">⟳</span> Locating...';

                navigator.geolocation.getCurrentPosition(position => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update Map View
                    map.setView([lat, lng], 15);

                    // Add/Move User Marker
                    if (userMarker) {
                        userMarker.setLatLng([lat, lng]);
                    } else {
                        const userBusIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/markers/marker-icon-blue.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });
                        userMarker = L.marker([lat, lng], { icon: userBusIcon }).addTo(map)
                            .bindPopup('<strong>You are here!</strong>').openPopup();
                    }

                    // Reset Button Text
                    this.innerHTML = '<span class="absolute left-6 w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span> My Current Location';

                }, () => {
                    alert('Unable to retrieve your location');
                    this.innerHTML = 'My Current Location';
                });
            });

            // Park Location & Distance Button Logic
            document.getElementById('btn-park-location').addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('Geolocation is needed to calculate distance.');
                    return;
                }

                navigator.geolocation.getCurrentPosition(position => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    // Calculate Distance (Haversine Formula)
                    const R = 6371; // Radius of the earth in km
                    const dLat = deg2rad(parkLat - userLat);
                    const dLon = deg2rad(parkLng - userLng);
                    const a =
                        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(deg2rad(userLat)) * Math.cos(deg2rad(parkLat)) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    const distance = R * c; // Distance in km

                    // Show Distance Info
                    const distInfo = document.getElementById('distance-info');
                    const distValue = document.getElementById('distance-value');
                    const timeEst = document.getElementById('time-estimate');

                    distInfo.classList.remove('hidden');
                    distValue.innerText = distance.toFixed(2) + ' km';

                    // Estimate Time (Assuming 40 km/h avg speed)
                    const timeInMins = Math.round((distance / 40) * 60);
                    timeEst.innerText = `Estimated travel time: ${timeInMins} mins (by car at 40km/h)`;

                    // Draw Line
                    if (routeLine) {
                        map.removeLayer(routeLine);
                    }
                    routeLine = L.polyline([[userLat, userLng], [parkLat, parkLng]], { color: 'red', weight: 4, opacity: 0.7, dashArray: '10, 10' }).addTo(map);

                    // Fit Bounds to show both points
                    map.fitBounds([[userLat, userLng], [parkLat, parkLng]], { padding: [50, 50] });

                });
            });

            function deg2rad(deg) {
                return deg * (Math.PI / 180)
            }
        });
    </script>
</x-app-layout>