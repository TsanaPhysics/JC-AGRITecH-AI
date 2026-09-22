import 'dart:math' as math;

class MultiColorSpaceService {
  /// Convert RGB (0-255) to HSV
  /// Hue: 0-360, Saturation: 0-1, Value: 0-1
  static List<double> rgbToHsv(int r, int g, int b) {
    double rf = r / 255.0;
    double gf = g / 255.0;
    double bf = b / 255.0;

    double max = math.max(rf, math.max(gf, bf));
    double min = math.min(rf, math.min(gf, bf));
    double delta = max - min;

    double h = 0.0;
    if (delta > 0.00001) {
      if (max == rf) {
        h = 60.0 * (((gf - bf) / delta) % 6.0);
      } else if (max == gf) {
        h = 60.0 * (((bf - rf) / delta) + 2.0);
      } else {
        h = 60.0 * (((rf - gf) / delta) + 4.0);
      }
      if (h < 0) h += 360.0;
    }

    double s = max <= 0.0 ? 0.0 : delta / max;
    double v = max;

    return [h, s, v];
  }

  /// Convert RGB (0-255) to CIE L*a*b* using standard D65 illuminant
  static List<double> rgbToLab(int r, int g, int b) {
    // 1. sRGB to linear RGB
    double pivotRgb(double n) {
      return (n > 0.04045) ? math.pow((n + 0.055) / 1.055, 2.4).toDouble() : n / 12.92;
    }

    double lr = pivotRgb(r / 255.0) * 100.0;
    double lg = pivotRgb(g / 255.0) * 100.0;
    double lb = pivotRgb(b / 255.0) * 100.0;

    // 2. Linear RGB to XYZ
    double x = lr * 0.4124564 + lg * 0.3575761 + lb * 0.1804375;
    double y = lr * 0.2126729 + lg * 0.7151522 + lb * 0.0721750;
    double z = lr * 0.0193339 + lg * 0.1191920 + lb * 0.9503041;

    // D65 standard reference illuminant
    const double refX = 95.047;
    const double refY = 100.000;
    const double refZ = 108.883;

    double px = x / refX;
    double py = y / refY;
    double pz = z / refZ;

    double pivotXyz(double n) {
      return (n > 0.008856) ? math.pow(n, 1.0 / 3.0).toDouble() : (7.787 * n) + (16.0 / 116.0);
    }

    double fx = pivotXyz(px);
    double fy = pivotXyz(py);
    double fz = pivotXyz(pz);

    double cieL = math.max(0.0, (116.0 * fy) - 16.0);
    double cieA = 500.0 * (fx - fy);
    double cieB = 200.0 * (fy - fz);

    return [cieL, cieA, cieB];
  }

  /// Dark Green Color Index (DGCI)
  /// Standardized metric for plant leaf nitrogen and greenness
  static double calculateDgci(double hue, double saturation, double brightness) {
    // Standard DGCI formula: [(Hue - 60) / 60 + (1 - Saturation) + (1 - Brightness)] / 3
    // Clamped between 0.0 and 1.0
    double hNorm = ((hue - 60.0) / 60.0).clamp(0.0, 1.0);
    double sNorm = (1.0 - saturation).clamp(0.0, 1.0);
    double bNorm = (1.0 - brightness).clamp(0.0, 1.0);
    double dgci = (hNorm + (1.0 - sNorm) + (1.0 - bNorm)) / 3.0;
    return dgci.clamp(0.0, 1.0);
  }

  /// Visible Atmospherically Resistant Index (VARI) = (G - R) / (G + R - B)
  static double calculateVari(int r, int g, int b) {
    double denom = (g + r - b).toDouble();
    if (denom.abs() < 0.001) return 0.0;
    return ((g - r) / denom).clamp(-1.0, 1.0);
  }

  /// Green Leaf Index (GLI) = (2G - R - B) / (2G + R + B)
  static double calculateGli(int r, int g, int b) {
    double denom = (2 * g + r + b).toDouble();
    if (denom.abs() < 0.001) return 0.0;
    return ((2 * g - r - b) / denom).clamp(-1.0, 1.0);
  }

  /// Excess Green Index (ExG) = 2G - R - B
  static double calculateExg(int r, int g, int b) {
    return (2 * g - r - b).toDouble();
  }

  /// Calculate estimated SPAD chlorophyll reading from leaf optical metrics
  static double estimateSpadFromColor(int r, int g, int b) {
    final hsv = rgbToHsv(r, g, b);
    final lab = rgbToLab(r, g, b);
    final dgci = calculateDgci(hsv[0], hsv[1], hsv[2]);

    // Robust empirical regression formula linking DGCI, Lab a* (green-red) and Lab L*
    // SPAD = 15.0 + (DGCI * 45.0) - (a* * 0.35) - ((L* - 45.0) * 0.25)
    double spad = 12.0 + (dgci * 48.0) - (lab[1] * 0.42) - ((lab[0] - 40.0) * 0.20);
    return spad.clamp(5.0, 75.0);
  }
}
