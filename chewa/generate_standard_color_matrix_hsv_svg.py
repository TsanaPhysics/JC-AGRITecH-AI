import colorsys

colors = [
    {
        "title": "1. ความเป็นกรด-ด่างของดิน (Soil pH) — Universal Indicator Scale (7 ระดับ)",
        "unit": "pH",
        "tiers": [
            ("กรดจัดรุนแรง", "< 4.5", "#e63946"),
            ("กรดจัด", "4.5 - 5.2", "#f4a261"),
            ("กรดปานกลาง", "5.3 - 6.0", "#e9c46a"),
            ("กรดเล็กน้อย", "6.1 - 6.8", "#a7c957"),
            ("เป็นกลาง (เหมาะสม)", "6.9 - 7.5", "#2a9d8f"),
            ("ด่างปานกลาง", "7.6 - 8.4", "#457b9d"),
            ("ด่างรุนแรง", "> 8.4", "#1d3557"),
        ]
    },
    {
        "title": "2. ไนโตรเจนที่เป็นประโยชน์ (Available N: NO3-N) — Griess Reaction Scale (5 ระดับ)",
        "unit": "mg/kg",
        "tiers": [
            ("ต่ำมาก", "< 10", "#fefae0"),
            ("ต่ำ", "10 - 25", "#f4a261"),
            ("ปานกลาง (เหมาะสม)", "26 - 50", "#e76f51"),
            ("สูง", "51 - 80", "#d62828"),
            ("สูงมาก", "> 80", "#7209b7"),
        ]
    },
    {
        "title": "3. ฟอสฟอรัสที่เป็นประโยชน์ (Available P) — Bray II / Molybdenum Blue Scale (5 ระดับ)",
        "unit": "mg/kg",
        "tiers": [
            ("ต่ำมาก", "< 5", "#faf0ca"),
            ("ต่ำ", "5 - 15", "#a2d2ff"),
            ("ปานกลาง (เหมาะสม)", "16 - 30", "#3a86ff"),
            ("สูง", "31 - 60", "#003049"),
            ("สูงมาก", "> 60", "#03045e"),
        ]
    },
    {
        "title": "4. โพแทสเซียมที่แลกเปลี่ยนได้ (Exchangeable K) — Cobaltinitrite Turbidity Scale (5 ระดับ)",
        "unit": "mg/kg",
        "tiers": [
            ("ต่ำมาก", "< 40", "#edf2f4"),
            ("ต่ำ", "40 - 80", "#ffd166"),
            ("ปานกลาง (เหมาะสม)", "81 - 150", "#f3722c"),
            ("สูง", "151 - 250", "#d90429"),
            ("สูงมาก", "> 250", "#6a040f"),
        ]
    }
]

def hex_to_rgb(h):
    h = h.lstrip('#')
    return tuple(int(h[i:i+2], 16) for i in (0, 2, 4))

def get_text_color(h):
    r, g, b = hex_to_rgb(h)
    lum = (0.299 * r + 0.587 * g + 0.114 * b) / 255.0
    return "#111827" if lum > 0.6 else "#ffffff"

W, H = 2200, 1600
svg = [f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" width="100%" height="100%" style="background:#0b1121; font-family:system-ui, -apple-system, sans-serif;">']

# Header
svg.append(f'''
  <rect x="0" y="0" width="{W}" height="175" fill="#101830"/>
  <line x1="0" y1="175" x2="{W}" y2="175" stroke="#22d3ee" stroke-width="3"/>
  <text x="60" y="55" fill="#f0f9ff" font-size="34" font-weight="bold">แถบสีมาตรฐานอ้างอิงสำหรับการวิเคราะห์ดินด้วยการประมวลผลภาพถ่าย (RGB &amp; HSV Dual-Color Space)</text>
  <text x="60" y="102" fill="#94a3b8" font-size="22">อ้างอิงเกณฑ์มาตรฐานตารางที่ 2.3 และข้อกำหนดสีดิจิทัล (HEX / RGB / HSV) สำหรับการชดเชยแสงและปัญญาประดิษฐ์</text>
  <text x="60" y="142" fill="#38bdf8" font-size="22" font-weight="600">โครงการวิจัยระบบวิเคราะห์ดินดิจิทัลและตรวจวัดธาตุอาหารพืชภาคสนาม มหาวิทยาลัยราชภัฏรำไพพรรณี</text>
''')

start_y = 205
row_height = 320

for sec_idx, sec in enumerate(colors):
    y = start_y + sec_idx * row_height
    svg.append(f'''
    <g transform="translate(45, {y})">
      <rect x="0" y="0" width="{W-90}" height="{row_height - 25}" rx="16" fill="#131d38" stroke="#2a3c66" stroke-width="1.5"/>
      <rect x="25" y="20" width="8" height="34" rx="4" fill="#22d3ee"/>
      <text x="45" y="45" fill="#e0f2fe" font-size="26" font-weight="bold">{sec['title']}</text>
    ''')
    
    tiers = sec["tiers"]
    n = len(tiers)
    avail_w = W - 140
    gap = 16
    swatch_w = (avail_w - (n - 1) * gap) / n
    swatch_h = 195
    
    for i, (label, val_range, hex_c) in enumerate(tiers):
        sx = 25 + i * (swatch_w + gap)
        sy = 70
        textColor = get_text_color(hex_c)
        r, g, b = hex_to_rgb(hex_c)
        h, s, v = colorsys.rgb_to_hsv(r/255.0, g/255.0, b/255.0)
        h_deg = round(h * 360)
        s_pct = round(s * 100)
        v_pct = round(v * 100)
        
        svg.append(f'''
        <g transform="translate({sx}, {sy})">
          <rect x="0" y="0" width="{swatch_w}" height="{swatch_h}" rx="12" fill="{hex_c}" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
          <text x="{swatch_w/2}" y="32" fill="{textColor}" font-size="20" font-weight="bold" text-anchor="middle">{label}</text>
          <text x="{swatch_w/2}" y="65" fill="{textColor}" font-size="19" font-weight="600" text-anchor="middle">{val_range} {sec['unit']}</text>
          <line x1="15" y1="90" x2="{swatch_w-15}" y2="90" stroke="{textColor}" stroke-opacity="0.4" stroke-width="1"/>
          <text x="{swatch_w/2}" y="112" fill="{textColor}" font-size="17" font-weight="bold" font-family="monospace" text-anchor="middle">HEX: {hex_c.upper()}</text>
          <text x="{swatch_w/2}" y="137" fill="{textColor}" font-size="16" font-family="monospace" text-anchor="middle">RGB: {r}, {g}, {b}</text>
          <text x="{swatch_w/2}" y="162" fill="{textColor}" font-size="16" font-weight="600" font-family="monospace" text-anchor="middle">HSV: {h_deg}°, {s_pct}%, {v_pct}%</text>
        </g>
        ''')
    svg.append('</g>')

# Footer
svg.append(f'''
  <rect x="0" y="{H-75}" width="{W}" height="75" fill="#101830"/>
  <line x1="0" y1="{H-75}" x2="{W}" y2="{H-75}" stroke="#2a3c66" stroke-width="1"/>
  <text x="60" y="{H-32}" fill="#94a3b8" font-size="20">สถาปัตยกรรมการประมวลผลสี: การแปลงค่าจาก RGB เป็น HSV ช่วยขจัดอิทธิพลของความเข้มแสงแวดล้อม (V) โดยอาศัยค่าเนื้อสี (H) และความอิ่มตัวสี (S) ในการเทียบค่าเคมีของดิน</text>
</svg>
''')

content = '\n'.join(svg)
with open('/Users/chewathassana/Desktop/handysense/chewa/figures/fig_standard_color_matrix_rgb_hsv.svg', 'w', encoding='utf-8') as f:
    f.write(content)
with open('/Users/chewathassana/Desktop/handysense/thanapat_research/figures/fig_standard_color_matrix_rgb_hsv.svg', 'w', encoding='utf-8') as f:
    f.write(content)
print("SVG GENERATED")
