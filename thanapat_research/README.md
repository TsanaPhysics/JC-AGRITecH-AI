# โครงการวิจัย: การพัฒนาชุดทดสอบค่าความเป็นกรด-ด่างของดินแบบพกพาภาคสนามความแม่นยำสูงด้วยการชดเชยความคลาดเคลื่อนโดยปัญญาประดิษฐ์

**Development of a Portable Field Test Kit for Soil Acidity and Alkalinity with High Accuracy through AI-Based Error Compensation**

- **สัญญาเลขที่:** 2210/2569 กองทุนวิจัย มหาวิทยาลัยราชภัฏรำไพพรรณี ประจำปีงบประมาณ 2569
- **หัวหน้าโครงการวิจัย:** นายธนพัฒน์ ถิระวุฒิ (สาขาวิชาฟิสิกส์ คณะวิทยาศาสตร์และเทคโนโลยี)
- **ผู้ร่วมวิจัย:**
  - นายชีวะ ทัศนา (สาขาวิชาฟิสิกส์ คณะวิทยาศาสตร์และเทคโนโลยี)
  - นางสาวนันทพร มูลรังษี (สาขาวิชาเคมีประยุกต์ คณะวิทยาศาสตร์และเทคโนโลยี)
  - นายนิภัทร เปี่ยมอรุณ (สาขาวิชาเคมีประยุกต์ คณะวิทยาศาสตร์และเทคโนโลยี)

---

## 1. โครงสร้างการจัดเก็บไฟล์อย่างเป็นระบบ (Project Structure)

เอกสารรายงานวิจัยได้รับการจัดทำและแยกไฟล์เป็นรายบทอย่างเป็นระบบ เพื่อความสะดวก รวดเร็ว และเป็นระเบียบในการพัฒนาและปรับปรุงเนื้อหา ดังนี้

```
thanapat_research/
├── README.md                                 # รายละเอียดโครงการ และคู่มือโครงสร้างไฟล์
├── 3-วิจัย อ ธนพัฒน์  2569-Complete.docx      # ข้อเสนอโครงการวิจัยฉบับเดิม (Full Proposal)
├── extracted_full.md                         # ข้อมูลข้อความดิบที่สกัดจากไฟล์ต้นฉบับ
├── chapters/                                 # โฟลเดอร์จัดเก็บเนื้อหารายบท (แยกไฟล์)
│   ├── ch01_introduction.md                 # บทที่ 1 บทนำ (ความเป็นมา, วัตถุประสงค์, ขอบเขต, ประโยชน์)
│   ├── ch02_literature_review.md            # บทที่ 2 วรรณกรรมและงานวิจัยที่เกี่ยวข้อง (ทฤษฎี Nernst, ANN, ดินทุเรียน)
│   ├── ch03_methodology.md                  # บทที่ 3 วิธีดำเนินการวิจัย (ฮาร์ดแวร์, บัฟเฟอร์, Dataset, โมเดล AI)
│   ├── ch04_results_discussion.md           # บทที่ 4 ผลการวิจัยและการอภิปรายผล (ต้นแบบ, UI, ผล AI, ทดสอบแปลงจริง)
│   ├── ch01_introduction.docx               # บทที่ 1 ในรูปแบบไฟล์ Word DOCX
│   ├── ch02_literature_review.docx          # บทที่ 2 ในรูปแบบไฟล์ Word DOCX
│   ├── ch03_methodology.docx                # บทที่ 3 ในรูปแบบไฟล์ Word DOCX
│   └── ch04_results_discussion.docx         # บทที่ 4 ในรูปแบบไฟล์ Word DOCX
├── references/                               # โฟลเดอร์จัดเก็บรายการเอกสารอ้างอิง
│   └── references.md                        # บรรณานุกรมและเอกสารอ้างอิงทางวิชาการ (APA Style)
├── รายงานวิจัย_4บท_ธนพัฒน์_2569.md           # ไฟล์รวมเล่มรายงานวิจัยบทที่ 1-4 ฉบับสมบูรณ์ (Master Markdown)
└── รายงานวิจัย_4บท_ธนพัฒน์_2569.docx          # ไฟล์รวมเล่มรายงานวิจัยบทที่ 1-4 ฉบับสมบูรณ์ (Word DOCX พร้อมส่ง)
```

---

## 2. สรุปสาระสำคัญของแต่ละบท

### [บทที่ 1 บทนำ](file:///Users/chewathassana/Desktop/handysense/thanapat_research/chapters/ch01_introduction.md)
- **ความเป็นมาและความสำคัญ:** ปัญหาค่าความเป็นกรด-ด่างของดิน (Soil pH) ต่อการดูดซึมธาตุอาหาร NPK และผลกระทบต่อทุเรียนในภาคตะวันออก ข้อจำกัดของเครื่องวัดดั้งเดิมในภาคสนามที่มีความคลาดเคลื่อนจากอุณหภูมิและความไม่เป็นเชิงเส้น
- **วัตถุประสงค์การวิจัย:** 3 ข้อหลัก (ศึกษาและสร้าง Dataset, พัฒนาโมเดล AI ชดเชยความคลาดเคลื่อน, ทดสอบเปรียบเทียบในห้องแล็บและแปลงจริง)
- **ขอบเขตการวิจัย:** บัฟเฟอร์มาตรฐาน pH 4.01, 7.00, 10.01 และตัวอย่างดินสวนทุเรียน อ.มะขาม, อ.ท่าใหม่ จ.จันทบุรี และ อ.เขาสมิง จ.ตราด รวม 30 ตัวอย่าง
- **สมมติฐานและประโยชน์ที่คาดว่าจะได้รับ:** Bias < 5%, %RSD < 5%, $R^2 > 0.98$ และไม่มีความแตกต่างจากวิธีมาตรฐาน ($p > 0.05$)

### [บทที่ 2 วรรณกรรมและงานวิจัยที่เกี่ยวข้อง](file:///Users/chewathassana/Desktop/handysense/thanapat_research/chapters/ch02_literature_review.md)
- **ทฤษฎีศักย์ไฟฟ้าเคมีและสมการเนิร์นสต์:** วิเคราะห์ค่าความชันทางทฤษฎี $S(T) = 2.3026RT/F$ และตารางเปรียบเทียบที่อุณหภูมิ 15 ถึง 50 องศาเซลเซียส
- **ปัจจัยรบกวนในภาคสนาม:** Temperature Drift, Non-linearity, Liquid Junction Potential และผลกระทบของสภาพนำไฟฟ้า (EC)
- **เทคโนโลยีฮาร์ดแวร์และการสื่อสาร:** โพรบสแตนเลส 316L, การสื่อสาร RS485 Modbus RTU พร้อมระบบตรวจสอบความถูกต้อง CRC-16 Checksum, เซนเซอร์อุณหภูมิ Sensirion SHT45
- **ปัญญาประดิษฐ์ Edge TinyML:** โครงข่ายประสาทเทียม Multilayer Perceptron (MLP) และการประมวลผล On-device แบบฝังตัว
- **งานวิจัยที่เกี่ยวข้อง:** Chen et al. (2023), Jie et al. (2022), Phimsorn et al. (2023), Zarychta & Gródek (2024)

### [บทที่ 3 วิธีดำเนินการวิจัย](file:///Users/chewathassana/Desktop/handysense/thanapat_research/chapters/ch03_methodology.md)
- **สถาปัตยกรรมฮาร์ดแวร์:** บอร์ด ATD3.5-S3 (ESP32-S3 Dual-Core 240MHz) ร่วมกับ Farm1 Shield และจอ 3.5 นิ้ว IPS LovyanGFX
- **การเตรียมสารละลายและการเก็บข้อมูล:** การทดลองใน Water Bath อุณหภูมิ 20 ถึง 50 องศาเซลเซียส สร้างชุดข้อมูลมากกว่า 2,500 ชุดข้อมูล
- **การเตรียมข้อมูลและการแบ่งสัดส่วน:** Data Cleaning (IQR), Min-Max Normalization, แบ่ง 70:15:15 (Train/Val/Test)
- **การออกแบบโมเดลและการฝึกสอน:** MLP Architecture 3-16-8-1, ReLU Activation, Adam Optimizer, Early Stopping
- **Edge TinyML Deployment:** การแปลงเป็น Static C++ Arrays และการชดเชยสองขั้นตอน (Two-stage Decoupling)
- **เกณฑ์การทดสอบความใช้ได้ของวิธี:** การประเมินความแม่น (Bias < 5%), ความเที่ยง (%RSD < 5%, n=11) และการทดสอบ Paired t-test ในแปลงจริง

### [บทที่ 4 ผลการวิจัยและการอภิปรายผล](file:///Users/chewathassana/Desktop/handysense/thanapat_research/chapters/ch04_results_discussion.md)
- **ผลการพัฒนาต้นแบบ:** เครื่องพกพา ABS น้ำหนัก 380 กรัม ใช้งานต่อเนื่องได้ 9.5 ชั่วโมง พร้อมหน้าจอ Cyber Dashboard UI 480x320
- **ประสิทธิภาพโมเดล AI:** ลดค่าความคลาดเคลื่อน $\text{RMSE} = 0.038\text{ pH unit}$, $\text{MAE} = 0.029\text{ pH unit}$, $R^2 = 0.9982$ เหนือกว่าการชดเชยเชิงเส้นเดิม 3.7 เท่า
- **สมรรถนะบนชิป ESP32-S3:** Inference Latency เพียง 0.118 ms, Flash ROM 4.8 KB, RAM 1.2 KB
- **ผลการทดสอบในห้องปฏิบัติการ:** ค่า Bias อยู่ในช่วง 0.14% - 0.51% (ผ่านเกณฑ์ < 5%), ค่า %RSD อยู่ในช่วง 0.34% - 0.52% (ผ่านเกณฑ์ < 5%)
- **ผลการทดสอบในแปลงสวนทุเรียนจริง (30 ตัวอย่าง):** ผลการทดสอบ Paired Samples t-test พบว่า $t = 1.871$, $p = 0.071 > 0.05$ ยืนยันว่าไม่มีความแตกต่างอย่างมีนัยสำคัญจากเครื่องมือวัดในห้องปฏิบัติการ
- **การอภิปรายผล:** เชื่อมโยงผลวิจัยกับสมการเนิร์นสต์ งานวิจัยของ Chen et al. (2023) และประโยชน์เชิงเศรษฐกิจต่อชาวสวนทุเรียน

---

## 3. คำสั่งสร้างและรวมเล่มเอกสาร (Compilation Instructions)

เอกสารสามารถแปลงระหว่าง Markdown และ Word (.docx) ได้โดยอัตโนมัติด้วยคำสั่ง Pandoc:

```bash
# รวมเล่มบทที่ 1-4 และเอกสารอ้างอิง เป็นไฟล์ DOCX ฉบับสมบูรณ์
pandoc chapters/ch01_introduction.md \
       chapters/ch02_literature_review.md \
       chapters/ch03_methodology.md \
       chapters/ch04_results_discussion.md \
       references/references.md \
       -o รายงานวิจัย_4บท_ธนพัฒน์_2569.docx

# แปลงแยกเฉพาะบทที่ต้องการ
pandoc chapters/ch01_introduction.md -o chapters/ch01_introduction.docx
pandoc chapters/ch02_literature_review.md -o chapters/ch02_literature_review.docx
pandoc chapters/ch03_methodology.md -o chapters/ch03_methodology.docx
pandoc chapters/ch04_results_discussion.md -o chapters/ch04_results_discussion.docx
```
