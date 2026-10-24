import 'dart:async';
import 'package:flutter/foundation.dart';
import 'package:geolocator/geolocator.dart';
import '../models/geo_location_data.dart';

class GeoLocationService {
  static GeoLocationData? _cachedLocation;

  static GeoLocationData get currentLocation => _cachedLocation ?? GeoLocationData.mockDefault();

  static Future<GeoLocationData> determinePosition() async {
    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        debugPrint('[GeoLocationService] Location services are disabled');
        _cachedLocation = GeoLocationData.mockDefault();
        return _cachedLocation!;
      }

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          debugPrint('[GeoLocationService] Location permissions are denied');
          _cachedLocation = GeoLocationData.mockDefault();
          return _cachedLocation!;
        }
      }

      if (permission == LocationPermission.deniedForever) {
        debugPrint('[GeoLocationService] Location permissions are permanently denied');
        _cachedLocation = GeoLocationData.mockDefault();
        return _cachedLocation!;
      }

      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
        timeLimit: const Duration(seconds: 4),
      );

      _cachedLocation = GeoLocationData(
        latitude: position.latitude,
        longitude: position.longitude,
        altitude: position.altitude,
        accuracy: position.accuracy,
        timestamp: position.timestamp,
        isAvailable: true,
      );
      return _cachedLocation!;
    } catch (e) {
      debugPrint('[GeoLocationService] Error fetching GPS position $e');
      _cachedLocation = GeoLocationData.mockDefault();
      return _cachedLocation!;
    }
  }

  static Stream<GeoLocationData> getPositionStream() {
    return Geolocator.getPositionStream(
      locationSettings: const LocationSettings(
        accuracy: LocationAccuracy.high,
        distanceFilter: 2,
      ),
    ).map((position) {
      final data = GeoLocationData(
        latitude: position.latitude,
        longitude: position.longitude,
        altitude: position.altitude,
        accuracy: position.accuracy,
        timestamp: position.timestamp,
        isAvailable: true,
      );
      _cachedLocation = data;
      return data;
    }).handleError((_) => GeoLocationData.mockDefault());
  }
}
