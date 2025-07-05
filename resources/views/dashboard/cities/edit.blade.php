@extends('dashboard.index')

@section('title', 'تعديل مدينة')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('cities.index') }}">المدن</a></li>
    <li class="breadcrumb-item">تعديل مدينة</li>
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
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Form Start --}}
                    <form action="{{ route('cities.update', $city->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="col-sm-10">
                            {{-- City Name --}}
                            <div class="row mb-3 mt-3">
                                <label for="city-input" class="col-sm-2 col-form-label fw-bold">اسم المدينة</label>
                                <div class="col-sm-10">
                                    <x-form.input type="text" id="city-input" name="name_ar"
                                                  value="{{ old('name_ar', $city->name_ar) }}"/>
                                </div>
                            </div>

                            {{-- إحداثيات الزاوية الجنوبية الغربية --}}
                            {{--                            <span>الزاوية الجنوبية الغربية (SouthWest):</span>--}}
                            <div class="row mb-3 mt-3">
                                {{--                                <label class="col-sm-2 col-form-label fw-bold">خط الطول</label>--}}
                                <div class="col-sm-10">
                                    <x-form.input type="hidden" id="longitude" name="longitude"
                                                  value="{{ old('longitude', $city->longitude) }}"/>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                {{--                                <label class="col-sm-2 col-form-label fw-bold">خط العرض</label>--}}
                                <div class="col-sm-10">
                                    <x-form.input type="hidden" id="latitude" name="latitude"
                                                  value="{{ old('latitude', $city->latitude) }}"/>
                                </div>
                            </div>

                            {{-- إحداثيات الزاوية الشمالية الشرقية --}}
                            {{--                            <span>الزاوية الشمالية الشرقية (NorthEast):</span>--}}
                            <div class="row mb-3 mt-3">
                                {{--                                <label class="col-sm-2 col-form-label fw-bold">نهاية خط الطول</label>--}}
                                <div class="col-sm-10">
                                    <x-form.input type="hidden" id="end_longitude" name="end_longitude"
                                                  value="{{ old('end_longitude', $city->end_longitude) }}"/>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                {{--                                <label class="col-sm-2 col-form-label fw-bold">نهاية خط العرض</label>--}}
                                <div class="col-sm-10">
                                    <x-form.input type="hidden" id="end_latitude" name="end_latitude"
                                                  value="{{ old('end_latitude', $city->end_latitude) }}"/>
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">
                                <label class="col-sm-2 col-form-label fw-bold"></label>
                                <div class="col-sm-10">
                                    <x-form.input type="hidden" id="polygon_coordinates" name="polygon_coordinates"
                                                  value="{{ old('polygon_coordinates', $city->polygon_coordinates) }}"/>
                                </div>
                            </div>

                            {{--                            <input type="hidden" name="polygon_coordinates" id="polygon_coordinates"--}}
                            {{--                                   value="{{ old('polygon_coordinates', $city->polygon_coordinates) }}">--}}

                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">الحالة</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="status" aria-label="Default select example">
                                        <option hidden disabled>اختر حالة المدينة</option>
                                        <option value="used" {{ $city->status == 'used' ? 'selected' : '' }}>مفعل
                                        </option>
                                        <option value="not_used" {{ $city->status == 'not_used' ? 'selected' : '' }}>غير
                                            مفعل
                                        </option>
                                    </select>
                                    @error('status')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <button class="btn btn-primary" type="submit">حفظ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuK5dAqp6_Fs7d58Qs-5SvJeyHECUJAoM&libraries=drawing,places">
    </script>

    <script>
        let map, drawingManager, selectedShape;

        function initMap() {
            let swLat = parseFloat("{{ $city->latitude }}");
            let swLng = parseFloat("{{ $city->longitude }}");
            let neLat = parseFloat("{{ $city->end_latitude }}");
            let neLng = parseFloat("{{ $city->end_longitude }}");

            let cityCenter = {
                lat: (swLat + neLat) / 2,
                lng: (swLng + neLng) / 2
            };

            map = new google.maps.Map(document.getElementById("map"), {
                center: cityCenter,
                zoom: 12
            });

            // استرجاع المضلع المخزن من قاعدة البيانات
            let polygonCoordinates = {!! $city->polygon_coordinates ?? 'null' !!};

            if (polygonCoordinates && polygonCoordinates.length) {
                selectedShape = new google.maps.Polygon({
                    paths: polygonCoordinates,
                    editable: true,
                    draggable: true,
                    map: map
                });

                extractBounds(selectedShape);

                // تحديث الإحداثيات عند تعديل المضلع
                google.maps.event.addListener(selectedShape.getPath(), 'set_at', function () {
                    extractBounds(selectedShape);
                });

                google.maps.event.addListener(selectedShape.getPath(), 'insert_at', function () {
                    extractBounds(selectedShape);
                });
            }

            // البحث عن مدينة
            const searchInput = document.getElementById("search-input");
            const searchBox = new google.maps.places.SearchBox(searchInput);

            searchBox.addListener("places_changed", function () {
                const places = searchBox.getPlaces();
                if (places.length === 0) return;

                const place = places[0];
                if (!place.geometry || !place.geometry.location) return;

                map.setCenter(place.geometry.location);
                map.setZoom(12);

                removeExistingShape();

                selectedShape = new google.maps.Polygon({
                    paths: [
                        {
                            lat: place.geometry.viewport.getSouthWest().lat(),
                            lng: place.geometry.viewport.getSouthWest().lng()
                        },
                        {
                            lat: place.geometry.viewport.getNorthEast().lat(),
                            lng: place.geometry.viewport.getSouthWest().lng()
                        },
                        {
                            lat: place.geometry.viewport.getNorthEast().lat(),
                            lng: place.geometry.viewport.getNorthEast().lng()
                        },
                        {
                            lat: place.geometry.viewport.getSouthWest().lat(),
                            lng: place.geometry.viewport.getNorthEast().lng()
                        }
                    ],
                    editable: true,
                    draggable: true,
                    map: map
                });

                extractBounds(selectedShape);
            });

            // مدير الرسم للسماح برسم مضلع جديد
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
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
                    removeExistingShape();
                }

                selectedShape = event.overlay;
                selectedShape.setEditable(true);
                selectedShape.setDraggable(true);

                extractBounds(selectedShape);
                drawingManager.setDrawingMode(null);
            });
        }


        function extractBounds(polygon) {
            let bounds = new google.maps.LatLngBounds();
            let coordinates = [];

            polygon.getPath().forEach(function (point) {
                bounds.extend(point);
                coordinates.push({
                    lat: point.lat(),
                    lng: point.lng()
                });
            });

            document.getElementById('longitude').value = bounds.getSouthWest().lng();
            document.getElementById('latitude').value = bounds.getSouthWest().lat();
            document.getElementById('end_longitude').value = bounds.getNorthEast().lng();
            document.getElementById('end_latitude').value = bounds.getNorthEast().lat();
            document.getElementById('polygon_coordinates').value = JSON.stringify(coordinates);
        }


        function removeExistingShape() {
            if (selectedShape) {
                selectedShape.setMap(null);
                selectedShape = null;
            }
        }

        google.maps.event.addDomListener(window, 'load', initMap);
    </script>

@endsection
