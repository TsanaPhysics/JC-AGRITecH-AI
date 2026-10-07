# ข้อเสนอโครงการอบรมเชิงปฏิบัติการ (Workshop Proposal)

## **LEQs-xAI : ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม**

### **Digital Electronics • Smart IoT • Edge AI / TinyML • Computer Vision • Climate-Smart Agriculture • SDGs**

> **แหล่งงบประมาณสนับสนุน:** งบประมาณเงินรายได้อื่นๆ ประจำปีงบประมาณ พ.ศ. ๒๕๖๙  
> **ภายใต้โครงการหลัก:** **"โครงการเพิ่มศักยภาพครูให้มีสมรรถนะของครูยุคใหม่สำหรับการเรียนรู้ในศตวรรษที่ ๒๑"**  
> **หน่วยงานดำเนินงาน:** คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี ร่วมกับโรงเรียนประณีตวิทยาคม และภาคีเครือข่าย

**LEQs** = **Learning (การเรียนรู้เชิงรุก) • Environment (สิ่งแวดล้อมและสภาพอากาศ) • Quantitative Science (วิทยาศาสตร์เชิงปริมาณและดิจิทัลอิเล็กทรอนิกส์)**  
**xAI** = **Explainable & Edge Artificial Intelligence (ปัญญาประดิษฐ์อธิบายได้และสมองกลฝังตัวบนอุปกรณ์)**

> **“จากวงจรดิจิทัลสู่อินเทอร์เน็ตของสรรพสิ่ง จาก Edge AI สู่เกษตรอัจฉริยะ และจากสมรรถนะครูยุคใหม่สู่การสร้างนวัตกรเยาวชนและชุมชน”**

---

## 1. หลักการและเหตุผล

การเปลี่ยนผ่านสู่ยุคดิจิทัลและความท้าทายระดับโลกด้านความมั่นคงทางอาหาร วิกฤตสภาพภูมิอากาศแปรปรวน (Climate Crisis) และการเสื่อมถอยของทรัพยากรธรรมชาติ เป็นแรงขับเคลื่อนสำคัญที่ทำให้ภาคการศึกษาต้องเร่งพัฒนา **"ครูยุคใหม่"** ให้มีสมรรถนะแห่งศตวรรษที่ ๒๑ โดยเฉพาะความฉลาดรู้ด้านดิจิทัลอิเล็กทรอนิกส์ (Digital Electronics), ระบบอินเทอร์เน็ตของสรรพสิ่ง (IoT), คอมพิวเตอร์วิทัศน์ (Computer Vision) และปัญญาประดิษฐ์ระดับอุปกรณ์ฝังตัว (Edge AI / TinyML) เพื่อให้ครูสามารถจัดกระบวนการเรียนรู้เชิงรุก (Active Learning) แบบ **Learning by Doing** และสะเต็มศึกษา (STEM/STEAM) เชื่อมโยงบริบทปัญหาจริงในท้องถิ่นสู่ห้องเรียน

ในภาคการเกษตรและสิ่งแวดล้อม มีข้อมูลทางกายภาพจำนวนมหาศาล ทั้งภาพถ่ายใบพืช ค่าความชื้นในดิน ๒ ระดับความลึก อุณหภูมิและความชื้นสัมพัทธ์ในอากาศ (VPD) ความเข้มแสงสังเคราะห์ (PAR) ค่าความเป็นกรด-ด่าง (pH) และธาตุอาหาร NPK การประมวลผลข้อมูลเหล่านี้ในอดีตมักต้องส่งผ่านคลาวด์ ซึ่งมีข้อจำกัดด้านสัญญาณอินเทอร์เน็ตในพื้นที่ห่างไกลและค่าใช้จ่ายสูง การนำเทคโนโลยี **Edge AI และ TinyML** มาประมวลผลและตัดสินใจบนไมโครคอนโทรลเลอร์ ESP32-S3 โดยตรง (Offline & Real-time Inference) ร่วมกับวงจรดิจิทัลอิเล็กทรอนิกส์มาตรฐานอุตสาหกรรม (RS485 Modbus RTU, I2C, Optocoupler Relay Isolation) จึงเป็นคำตอบที่ทรงพลัง ปลอดภัย ประหยัดพลังงาน และตอบโจทย์เกษตรแม่นยำสูงอย่างแท้จริง

โครงการนี้ได้รับการสนับสนุนจาก **งบรายได้อื่นๆ ภายใต้ "โครงการเพิ่มศักยภาพครูให้มีสมรรถนะของครูยุคใหม่สำหรับการเรียนรู้ในศตวรรษที่ ๒๑"** คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จึงได้ออกแบบหลักสูตรฝึกอบรมเชิงปฏิบัติการเข้มข้น มุ่งเน้นการบูรณาการ ๔ เสาหลักทางเทคโนโลยี:
1. **Digital Electronics:** วงจรดิจิทัลอิเล็กทรอนิกส์ การเชื่อมต่อบัส RS485 Modbus RTU, I2C, ADC Sampling และวงจรขับโหลดกำลังสูง
2. **Smart IoT & Environmental Telemetry:** แพลตฟอร์มเปิด HandySense, การตรวจวัดฟิสิกส์สิ่งแวดล้อม และระบบคลาวด์มอนิเตอร์
3. **Computer Vision & Deep Learning:** การจำแนกโรคพืชและสุขภาพหน้าดินด้วยโครงข่ายประสาทเทียม (CNN)
4. **Edge AI / TinyML & Explainable AI (xAI):** การนำโมเดล AI มารันบนชิปออฟไลน์ พร้อมระบบอธิบายเหตุผลการตัดสินใจทางฟิสิกส์

การดำเนินงานมุ่งสร้างระบบนิเวศแห่งการเรียนรู้ร่วมกัน ๓ ประสาน คือ **ครูและบุคลากรทางการศึกษา (กลุ่มเป้าหมายหลัก)**, **นักเรียนมัธยมศึกษาตอนปลาย (นวัตกรเยาวชน)** และ **เกษตรกร/ชุมชนท้องถิ่น** สอดรับกับเป้าหมายการพัฒนาที่ยั่งยืนแห่งสหประชาชาติ (UN SDGs) ได้แก่ SDG 2, SDG 4, SDG 9, SDG 12, SDG 13, SDG 15 และ SDG 17

---

## 2. ชื่อโครงการ

### ภาษาไทย
**โครงการอบรมเชิงปฏิบัติการ "ปัญญาประดิษฐ์ฝังตัวเพื่อเกษตรดิจิทัลและสิ่งแวดล้อม" (LEQs-xAI)**  
*(ภายใต้โครงการเพิ่มศักยภาพครูให้มีสมรรถนะของครูยุคใหม่สำหรับการเรียนรู้ในศตวรรษที่ ๒๑)*

### ภาษาอังกฤษ
**Hands-on Workshop on Embedded AI for Digital Agriculture and Environment (LEQs-xAI)**  
*(Under the Teacher Competency Enhancement Program for 21st-Century Learning)*

### คำโปรยเชิงยุทธศาสตร์
**Digital Electronics • Smart IoT • Deep Learning • Edge TinyML • Explainable AI • SDGs**

---

## 3. กลุ่มเป้าหมายและจำนวนผู้เข้าร่วม

โครงการรองรับผู้เข้าร่วมอบรมจำนวน **๓๐–๕๐ คนต่อรุ่น** แบ่งออกเป็น ๔ กลุ่มเป้าหมาย:
1. **ครูและบุคลากรทางการศึกษา (กลุ่มเป้าหมายหลักตามงบประมาณ):** ครูผู้สอนกลุ่มสาระวิทยาศาสตร์และเทคโนโลยี, คอมพิวเตอร์, ฟิสิกส์ และการงานอาชีพ เพื่อยกระดับสมรรถนะ TPACK และการสร้างแผน Active Learning
2. **นักเรียนระดับมัธยมศึกษาตอนปลาย:** เพื่อบ่มเพาะทักษะนวัตกร และจัดทำโครงงาน Capstone บรรจุลงในแฟ้มสะสมงาน TCAS Portfolio
3. **เกษตรกรและผู้ประกอบการด้านการเกษตร (Smart Farmers):** เพื่อการติดตั้งใช้งานจริงในแปลงปลูก ลดต้นทุนน้ำและปุ๋ย
4. **นักศึกษา นักวิจัย และผู้สนใจทั่วไป:** เพื่อการขยายผลองค์ความรู้สู่สังคมและชุมชนอย่างยั่งยืน

---

## 4. วัตถุประสงค์

เมื่อสิ้นสุดการอบรม ผู้เข้าร่วมสามารถ

1. อธิบายแนวคิดพื้นฐานของ AI, Machine Learning และ Deep Learning ได้
2. เข้าใจแนวคิด Digital Agriculture และ Smart Agriculture
3. ใช้ข้อมูลจาก Sensors และ IoT เพื่อสร้างข้อมูลสำหรับ AI ได้
4. เข้าใจหลักการของ Computer Vision สำหรับวิเคราะห์ภาพทางการเกษตร
5. สร้างและทดลองโมเดล Deep Learning เบื้องต้นได้
6. เข้าใจหลักการนำโมเดล AI ไปทำงานบนอุปกรณ์ Edge
7. สร้างต้นแบบ Edge AI สำหรับงานเกษตรหรือสิ่งแวดล้อมได้
8. วิเคราะห์ข้อมูลสิ่งแวดล้อม เช่น อุณหภูมิ ความชื้น แสง และความชื้นดินได้
9. ประยุกต์ AI เพื่อแก้ปัญหาจากบริบทพื้นที่จริงได้
10. พัฒนาผลงานต้นแบบหรือ Mini Project ของตนเองได้

---

## 5. แนวคิดหลักของการอบรม

### **LEQs Learning Model**

```
   [ L — Learn ]
   เรียนรู้แนวคิด AI และวิทยาศาสตร์
          │
          ▼
   [ E — Explore ]
   สำรวจข้อมูลจากพืช ดิน อากาศ และสิ่งแวดล้อม
          │
          ▼
   [ Q — Quantify ]
   วัด เก็บ และวิเคราะห์ข้อมูลด้วย Sensors และ IoT
          │
          ▼
   [ xAI — Build & Deploy ]
   สร้างโมเดล AI และนำไปใช้งานจริงบน Edge Device
```

---

## 6. โครงสร้างเนื้อหาการอบรม

### Module 1 — AI for Digital Agriculture
#### “รู้จัก AI ในโลกการเกษตร”
* **หัวข้อบรรยาย**
  * AI คืออะไร?
  * Machine Learning และ Deep Learning
  * AI กับ Smart Agriculture
  * Data → Model → Prediction
  * ตัวอย่าง AI ในสวนและแปลงเกษตร
  * ปัญหาทางการเกษตรที่สามารถใช้ AI แก้ไขได้
* **Workshop: Agri-AI Problem Discovery**
  * ให้ผู้เรียนเลือกปัญหาจริง เช่น ตรวจโรคพืช, จำแนกชนิดพืช, ประเมินความสมบูรณ์ของใบ, ตรวจความชื้นดิน, ตรวจสภาพอากาศ, ประเมินความเครียดของพืช

---

### Module 2 — Digital Agriculture & IoT
#### “เปลี่ยนแปลงพื้นที่เกษตรให้เป็นข้อมูล”
* **หัวข้อบรรยาย**
  * IoT คืออะไร?
  * Sensor → Microcontroller → AI
  * อุณหภูมิและความชื้นสัมพัทธ์ในอากาศ
  * ความชื้นและอุณหภูมิดิน
  * ความเข้มแสง ค่า pH และสภาพอากาศ
* **Workshop: Smart Farm Sensor Lab**
  * ผู้เรียนสร้างระบบ: **Sensor → Edge Device → Data → Dashboard**

```text
        SENSOR
           │
           ▼
    Microcontroller
           │
           ▼
      Edge Device
           │
           ▼
       AI Model
           │
           ▼
    Decision / Alert
```

---

### Module 3 — Computer Vision for Agriculture
#### “ให้ AI มองเห็นพืช”
* **หัวข้อบรรยาย**
  * Digital Image พื้นฐาน
  * Image Classification & Object Detection
  * Image Dataset & Image Annotation
  * Data Augmentation
  * Convolutional Neural Networks (CNN) & Transfer Learning
* **Workshop: Plant Vision AI**
  * ผู้เรียนสร้าง Dataset สำหรับจำแนกภาพ เช่น:

```text
Healthy Leaf ──► Nutrient Stress ──► Disease ──► Other
```
  * จากนั้นฝึกโมเดล Deep Learning เพื่อจำแนกภาพจริง

---

### Module 4 — Deep Learning
#### “สร้างสมอง AI”
* **หัวข้อบรรยาย**
  * Neural Network & CNN
  * Training / Validation / Testing
  * Epoch, Batch, Loss & Accuracy
  * การเกิด Overfitting และแนวทางป้องกัน
  * Model Evaluation
* **Workshop: Train Your First Agriculture AI**
  * กระบวนการปฏิบัติการ:

```text
Collect Data ──► Prepare Dataset ──► Train Model ──► Evaluate ──► Improve ──► Export Model
```
  * ผู้เรียนทดลองใช้ Dataset ด้านพืช ใบไม้ โรคพืช ดิน และสิ่งแวดล้อม

---

### Module 5 — Edge AI
#### “นำ AI ออกจาก Cloud สู่พื้นที่จริง”
* **หัวใจสำคัญของโครงการ**
  * ผู้เรียนเรียนรู้ว่า Edge AI สามารถทำให้ระบบ AI ทำงานใกล้กับแหล่งกำเนิดข้อมูลได้อย่างไร:

```text
[ Cloud AI ]
Camera ──► Internet ──► Cloud ──► AI

[ Edge AI ]
Camera ──► Edge Device ──► AI Model ──► Prediction (Real-time & Offline)
```

* **หัวข้อบรรยาย**
  * Edge Computing & Edge AI
  * Model Compression & Lightweight Model
  * Real-time & Offline Inference on Device
* **Workshop: Edge AI Vision**
  * สร้างต้นแบบระบบ: **กล้อง → Edge Device → Deep Learning → Prediction**
  * ตัวอย่างการประยุกต์: ตรวจใบพืช, จำแนกโรคพืช, ตรวจผลผลิต, ตรวจวัตถุ, ตรวจสภาพแวดล้อม

---

### Module 6 — Environmental AI
#### “AI นักสืบสิ่งแวดล้อม”
* **หัวข้อบรรยาย**
  * ประยุกต์ AI กับข้อมูลอุณหภูมิ, ความชื้น, แสง, คุณภาพดิน, คุณภาพน้ำ, สภาพอากาศ, Carbon / Climate, และ Biodiversity
* **Workshop: Environmental Data Intelligence**
  * ผู้เรียนสร้างระบบ:

```text
Environmental Sensors ──► IoT Data ──► Data Analysis ──► AI ──► Prediction / Alert
```

---

### Module 7 — AI + Agriculture + Environment
#### “Agri-Environmental Intelligence”
* บูรณาการข้อมูลจาก: **ภาพ + Sensor + IoT + Weather + AI**

```text
             CAMERA
                │
                ▼
         Computer Vision
                │
                ▼
               AI
                ▲
                │
Sensor ──► Edge Device ◄── IoT
                ▲
                │
       Environmental Data
```

* เพื่อสร้างระบบช่วยตัดสินใจ เช่น
  * พืชต้องการน้ำหรือไม่?
  * ใบพืชมีความผิดปกติหรือไม่?
  * สภาพแวดล้อมเหมาะสมหรือไม่?
  * ความชื้นดินต่ำหรือไม่?
  * มีความเสี่ยงต่อโรคหรือไม่?

---

## 7. LEQs-xAI Mini Project

ผู้เข้าอบรมเลือกพัฒนาโครงงาน 1 เรื่อง จาก 6 แทร็ก (Tracks):

* **Track A — Smart Agriculture:** AI Smart Farm
* **Track B — Plant Vision:** AI Plant Disease / Plant Health Detection
* **Track C — Smart Soil:** AI Soil Monitoring
* **Track D — Environmental AI:** AI Environmental Monitoring
* **Track E — Edge AI:** Offline Edge AI Agriculture
* **Track F — School AI:** AI Science Project for High School

---

## 8. ตัวอย่างผลงานปลายทาง (Prototypes)

ผู้เรียนแต่ละกลุ่มควรมีผลงานต้นแบบอย่างน้อย 1 ชิ้น:

1. **Prototype 1 (Edge AI Plant Detector):** กล้อง → Edge AI → ตรวจพืช
2. **Prototype 2 (Smart Soil AI):** Soil Sensor → IoT → AI → วิเคราะห์ดิน
3. **Prototype 3 (Environmental AI Station):** Temperature + Humidity + Light → AI
4. **Prototype 4 (AI Plant Health):** ภาพใบพืช → Deep Learning → Health Classification
5. **Prototype 5 (AI Smart Farm Dashboard):** Sensor → IoT → Database → AI → Dashboard

---

## 9. รูปแบบการเรียนรู้

### **30% Theory + 70% Hands-on**
ผู้เข้าอบรมจะได้เรียนรู้ผ่าน
* Mini Lecture
* Demonstration
* Hands-on Workshop
* Team-based Learning
* Problem-based Learning
* AI Experiment
* Mini Project
* Project Presentation

---

## 10. ระยะเวลาอบรม (รูปแบบแนะนำ 2 วัน)

### **Day 1 — AI + Data + Digital Agriculture**

| เวลา | กิจกรรม |
| :---: | :--- |
| **08.30–09.00** | ลงทะเบียน / Pre-test |
| **09.00–09.30** | เปิดโครงการ LEQs-xAI |
| **09.30–10.30** | AI for Digital Agriculture |
| **10.45–12.00** | Digital Agriculture + IoT |
| **13.00–14.30** | Sensor & Smart Farm Lab |
| **14.45–16.00** | Computer Vision |
| **16.00–16.30** | AI Experiment |

### **Day 2 — Deep Learning + Edge AI + Environment**

| เวลา | กิจกรรม |
| :---: | :--- |
| **08.30–09.30** | Deep Learning |
| **09.30–10.30** | Train Agriculture AI |
| **10.45–12.00** | Edge AI |
| **13.00–14.00** | Edge AI Hands-on |
| **14.00–15.00** | Environmental AI |
| **15.00–16.00** | LEQs-xAI Mini Project |
| **16.00–16.30** | Project Presentation / Post-test |
| **16.30–17.00** | มอบประกาศนียบัตร |

---

## 11. อุปกรณ์สำหรับการอบรม

ออกแบบให้สามารถรองรับอุปกรณ์ได้หลายระดับ:

* **ระดับ Beginner:** Smartphone, Laptop, Web-based AI tools, Online Dataset
* **ระดับ IoT:** Microcontroller, Temperature/Humidity Sensor, Soil Moisture Sensor, Light Sensor, Environmental Sensor
* **ระดับ Edge AI:** Edge AI Device, Camera Module, Microcontroller / SBC, Lightweight Deep Learning Model

---

## 12. การแบ่งระดับผู้เรียน

เพื่อรองรับผู้เรียนที่มีพื้นฐานแตกต่างกัน แบ่งออกเป็น 3 ระดับ:

* 🟢 **LEQs-xAI Explorer (สำหรับนักเรียนมัธยมศึกษา):**  
  เน้น **AI + Computer Vision + Sensor + ทดลองสร้าง**
* 🔵 **LEQs-xAI Educator (สำหรับครูและบุคลากรทางการศึกษา):**  
  เน้น **AI + STEM + Project + การออกแบบกิจกรรมการเรียนรู้**
* 🟠 **LEQs-xAI AgriTech (สำหรับเกษตรกรและผู้สนใจ):**  
  เน้น **Sensor + IoT + AI + Smart Farm + การประยุกต์ใช้จริง**

---

## 13. ผลที่คาดว่าจะได้รับ

ผู้เข้าร่วมโครงการจะได้รับ
1. มีความรู้พื้นฐานด้าน AI และ Deep Learning
2. เข้าใจการทำงานของ Edge AI
3. สามารถใช้ Computer Vision กับปัญหาด้านการเกษตรได้
4. สามารถเชื่อมต่อ Sensor และ IoT เพื่อสร้างข้อมูลได้
5. สามารถวิเคราะห์ข้อมูลด้านสิ่งแวดล้อมได้
6. สามารถสร้างต้นแบบ AI สำหรับการเกษตรได้
7. สามารถนำความรู้ไปประยุกต์ใช้ในโรงเรียน แปลงเกษตร หรือชุมชนได้
8. เกิดเครือข่าย **LEQs-xAI Community**
9. เกิด Mini Projects ที่สามารถพัฒนาต่อเป็นโครงงานวิทยาศาสตร์ งานวิจัย หรือนวัตกรรมได้

---

## 14. ตัวชี้วัดความสำเร็จ

| ตัวชี้วัด | เป้าหมาย |
| :--- | :---: |
| ผู้เข้าร่วมอบรม | 30–50 คน/รุ่น |
| ผ่านกิจกรรม Hands-on | $\ge 80\%$ |
| สามารถสร้าง AI Model เบื้องต้น | $\ge 70\%$ |
| สามารถสร้าง IoT Prototype | $\ge 70\%$ |
| สามารถทดลอง Edge AI | $\ge 70\%$ |
| Mini Project | $\ge 8\text{--}10$ ผลงาน |
| ความพึงพอใจ | $\ge 4.25 / 5.00$ |

---

## 15. แนวคิด Branding

### **LEQs-xAI**
* **LEQs:** Learning • Environment • Quantitative Science
* **xAI:** Extended / Explainable Artificial Intelligence

### Technology Stack
$$\text{Digital Agriculture} \longrightarrow \text{IoT \& Sensors} \longrightarrow \text{Computer Vision} \longrightarrow \text{Deep Learning} \longrightarrow \text{Edge AI} \longrightarrow \text{Environmental Intelligence}$$

---

### LEQs-xAI Ecosystem

```text
                         LEQs-xAI
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
   DIGITAL AGRI          SCIENCE           ENVIRONMENT
        │                   │                   │
   Smart Farm          Data Science       Climate AI
   Smart Soil          Physics + AI       Eco AI
   Plant AI            AgriPhysics        Biodiversity
        │                   │                   │
        └───────────────────┼───────────────────┘
                            │
                     AI TECHNOLOGY
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
       IoT            Deep Learning         Edge AI
        │                   │                   │
        └───────────────────┼───────────────────┘
                            │
                    COMPUTER VISION
                            │
                            ▼
                 REAL-WORLD APPLICATION
                            │
                ┌───────────┴───────────┐
                │                       │
             SCHOOL                  FARM
                │                       │
           STEM Project             Smart Farm
```

---

## 16. แนวคิดต่อยอดในระยะยาว

โครงการ **LEQs-xAI** สามารถพัฒนาเป็นระบบต่อเนื่องได้ 4 ระดับ:

$$\text{LEQs-xAI Workshop} \longrightarrow \text{LEQs-xAI Mini Project} \longrightarrow \text{LEQs-xAI Research Project} \longrightarrow \text{LEQs-xAI Innovation / Smart Farm}$$

และสามารถเชื่อมโยงกับงานด้าน **AI4D-AgriPhysics** โดยใช้แนวคิด:

> **Physics + Sensors + IoT + Deep Learning + Edge AI + Agriculture**

เป็นแกนการพัฒนานวัตกรรมเกษตรดิจิทัลและสิ่งแวดล้อม

---

## 17. Tagline ที่เสนอ

### **LEQs-xAI**
> **“เรียนรู้ AI สร้างนวัตกรรม เปลี่ยนข้อมูลให้เป็นปัญญา”**  
> *(From Data to Intelligence, From AI to Smart Agriculture)*

หรือสำหรับนักเรียนและเกษตรกรโดยเฉพาะ:
> **“AI ใกล้ตัว เกษตรใกล้เรา สิ่งแวดล้อมที่เราเปลี่ยนได้”**

### **แนวคิดหลักของโครงการ (Core Operating Loop)**
> **SEE → SENSE → LEARN → THINK → ACT**

* **SEE** — Computer Vision
* **SENSE** — IoT & Sensors
* **LEARN** — Deep Learning
* **THINK** — Edge AI
* **ACT** — Smart Agriculture & Environmental Action
