library;

/// Tropical Fruit Pomology & Phenological Stage Definitions
/// Designed for Durian and high-value tropical tree crop management.

enum LeafAgeStage {
  flush(
    id: 'flush',
    nameTh: 'ใบอ่อน / ยอดเพิ่งคลี่',
    nameEn: 'Flush Leaf',
    desc: 'ใบสีเขียวตองอ่อนหรือทองแดง SPAD ต่ำตามธรรมชาติ (18-35) พืชยังไม่สังเคราะห์แสงเต็มที่',
  ),
  youngMature(
    id: 'young_mature',
    nameTh: 'ใบเพสลาด (มาตรฐานวิจัย)',
    nameEn: 'Young Mature Leaf',
    desc: 'ใบเพสลาดตำแหน่งที่ 3-4 จากปลายยอด มาตรฐานสากลในการวินิจฉัยธาตุอาหาร (SPAD 40-55)',
  ),
  mature(
    id: 'mature',
    nameTh: 'ใบแก่ / โคนกิ่ง',
    nameEn: 'Mature / Base Leaf',
    desc: 'ใบแก่เต็มที่ สังเกตการเคลื่อนย้ายของธาตุอาหาร Mobile (N, P, K, Mg) ได้ชัดเจนที่สุด',
  );

  final String id;
  final String nameTh;
  final String nameEn;
  final String desc;

  const LeafAgeStage({
    required this.id,
    required this.nameTh,
    required this.nameEn,
    required this.desc,
  });

  static LeafAgeStage fromId(String? id) {
    return LeafAgeStage.values.firstWhere(
      (e) => e.id == id,
      orElse: () => LeafAgeStage.youngMature,
    );
  }
}

enum TreeCropStage {
  flushRecovery(
    id: 'flush_recovery',
    nameTh: 'ฟื้นต้น / ทำชุดใบ',
    nameEn: 'Vegetative Flush Recovery',
    focusNutrients: 'N, Mg, S',
    desc: 'ระยะสร้างทรงพุ่มและพื้นที่ใบ ต้องการไนโตรเจนและแมกนีเซียมสูงเพื่อสร้างคลอโรฟิลล์',
  ),
  floralInduction(
    id: 'floral_induction',
    nameTh: 'สะสมอาหารรอออกดอก',
    nameEn: 'Floral Induction (C:N)',
    focusNutrients: 'P, K, B, Zn (งด N)',
    desc: 'งดปุ๋ยไนโตรเจนเด็ดขาด คุม C:N ratio สูง เสริมฟอสฟอรัส-โพแทสเซียมและโบรอนเพื่อเปิดตาดอก',
  ),
  bloomToAnthesis(
    id: 'bloom_anthesis',
    nameTh: 'ดอกบาน / หางแย้ / ผลอ่อน',
    nameEn: 'Bloom & Fruitlet Set',
    focusNutrients: 'Ca, B, Cu',
    desc: 'ระยะวิกฤต เสริมแคลเซียม-โบรอน ป้องกันดอกหลุดร่วง ควบคุมการให้น้ำสเปรย์รอบทรงพุ่ม',
  ),
  fruitExpansion(
    id: 'fruit_expansion',
    nameTh: 'ขยายผล / สร้างพูและเนื้อ',
    nameEn: 'Fruit Expansion',
    focusNutrients: 'K, Ca, Mg, B',
    desc: 'ต้องการโพแทสเซียมและแคลเซียมสูง เพื่อขยายขนาดผล เพิ่มน้ำหนัก และความสมบูรณ์ของเนื้อ',
  ),
  preHarvest(
    id: 'pre_harvest',
    nameTh: 'บ่มหวานก่อนเก็บเกี่ยว',
    nameEn: 'Pre-Harvest Ripening',
    focusNutrients: 'K (ซัลเฟต)',
    desc: 'พ่นโพแทสเซียมซัลเฟตทางใบ ควบคุมน้ำให้พอเหมาะ เร่งการเปลี่ยนแป้งเป็นน้ำตาล ป้องกันเนื้อแกน',
  );

  final String id;
  final String nameTh;
  final String nameEn;
  final String focusNutrients;
  final String desc;

  const TreeCropStage({
    required this.id,
    required this.nameTh,
    required this.nameEn,
    required this.focusNutrients,
    required this.desc,
  });

  static TreeCropStage fromId(String? id) {
    return TreeCropStage.values.firstWhere(
      (e) => e.id == id,
      orElse: () => TreeCropStage.flushRecovery,
    );
  }
}
