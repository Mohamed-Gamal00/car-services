@extends('dashboard.index')

@section('title', 'اضافة مدينة')
@section('css')
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('cities.index') }}">المدن</a></li>
    <li class="breadcrumb-item">اضافة مدينة</li>
@endsection

@section('section')
    <div>
        <h3>حدد المدينة على الخريطة</h3>
        <div class="row mb-3">
            <label for="search-input" class="col-sm-2 col-form-label fw-bold">البحث عن مدينة</label>
            <div class="col-sm-10">
                <input type="text" id="search-input" class="form-control" placeholder="ابحث عن مدينة...">
            </div>
        </div>
        <div id="map"></div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Form Start --}}
                    <form action="{{ route('cities.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="col-sm-10">
                            {{-- City Name with Autocomplete --}}
                            <div class="row mb-3 mt-3">
                                <label for="city-input" class="col-sm-2 col-form-label fw-bold">اسم المدينة</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" id="city-input" name="name_ar"
                                                  value="{{ old('name_ar') }}"/>
                                </div>
                            </div>

                            {{-- Longitude --}}
                            <span>الزاوية الجنوبية الغربية (SouthWest):</span>
                            <div class="row mb-3 mt-3">
                                <label for="longitude" class="col-sm-2 col-form-label fw-bold">خط الطول</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" id="sw-lng" name="longitude" readonly/>
                                </div>
                            </div>

                            {{-- Latitude --}}
                            <div class="row mb-3 mt-3">
                                <label for="latitude" class="col-sm-2 col-form-label fw-bold">خط العرض</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" id="sw-lat" name="latitude" readonly/>
                                </div>
                            </div>

                            <span>الزاوية الشمالية الشرقية (NorthEast):</span>
                            <div class="row mb-3 mt-3">
                                <label for="latitude" class="col-sm-2 col-form-label fw-bold">نهاية خط الطول</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" id="ne-lng" name="end_longitude" readonly/>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                <label for="latitude" class="col-sm-2 col-form-label fw-bold"> نهاية خط العرض</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" id="ne-lat" name="end_latitude" readonly/>
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الحالة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="status" aria-label="Default select example">
                                        <option hidden disabled>اختر حالة المدينة</option>
                                        <option value="used">مفعل</option>
                                        <option value="not_used">غير مفعل</option>
                                    </select>
                                    @error('status')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <button class="btn btn-primary" type="submit" id="submitButton">حفظ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuK5dAqp6_Fs7d58Qs-5SvJeyHECUJAoM&libraries=drawing,places"></script>
    <script>
        let map;
        let drawingManager;
        let selectedShape;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {lat: 24.7136, lng: 46.6753}, // الرياض كمثال
                zoom: 10
            });

            // تفعيل البحث التلقائي للمدن
            const searchInput = document.getElementById("search-input");
            const searchBox = new google.maps.places.SearchBox(searchInput);

            // عند اختيار مدينة من البحث، يتم نقل الخريطة إليها
            searchBox.addListener("places_changed", function () {
                const places = searchBox.getPlaces();
                if (places.length === 0) {
                    return;
                }

                const place = places[0];
                if (!place.geometry || !place.geometry.location) {
                    return;
                }

                map.setCenter(place.geometry.location);
                map.setZoom(12); // تقريب على المكان الجديد

                // تعيين القيم في الحقول
                if (place.geometry.viewport) {
                    document.getElementById('sw-lng').value = place.geometry.viewport.getSouthWest().lng();
                    document.getElementById('sw-lat').value = place.geometry.viewport.getSouthWest().lat();
                    document.getElementById('ne-lng').value = place.geometry.viewport.getNorthEast().lng();
                    document.getElementById('ne-lat').value = place.geometry.viewport.getNorthEast().lat();
                }
            });

            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON, // نسمح برسم مضلع فقط
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                polygonOptions: {
                    editable: true,
                    draggable: true
                }
            });

            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
                if (selectedShape) {
                    selectedShape.setMap(null); // إزالة الشكل السابق
                }
                selectedShape = event.overlay;
                extractBounds(selectedShape);

                google.maps.event.addListener(selectedShape.getPath(), 'set_at', function () {
                    extractBounds(selectedShape);
                });
                google.maps.event.addListener(selectedShape.getPath(), 'insert_at', function () {
                    extractBounds(selectedShape);
                });
            });
        }

        function extractBounds(polygon) {
            let bounds = new google.maps.LatLngBounds();

            polygon.getPath().forEach(function (point) {
                bounds.extend(point);
            });

            document.getElementById('sw-lng').value = bounds.getSouthWest().lng();
            document.getElementById('sw-lat').value = bounds.getSouthWest().lat();
            document.getElementById('ne-lng').value = bounds.getNorthEast().lng();
            document.getElementById('ne-lat').value = bounds.getNorthEast().lat();
        }

        google.maps.event.addDomListener(window, 'load', initMap);
    </script>
@endsection
