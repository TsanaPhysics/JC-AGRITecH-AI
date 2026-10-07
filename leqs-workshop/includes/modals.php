    <!-- ========================================================================= -->
    <!-- 11. FLOATING AI ASSISTANT AVATAR ("น้อง SmartScience 🤖" - cmu_aiot STYLE)  -->
    <!-- ========================================================================= -->
    <div x-data="nongSmartScience()" class="fixed bottom-24 sm:bottom-6 right-3 sm:right-6 z-40 flex flex-col items-end">
        
        <!-- Chat Drawer / Modal -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-6 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-6 scale-95"
             class="mb-3 w-[calc(100vw-1.5rem)] max-w-sm sm:w-96 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-100 bg-white/95 backdrop-blur-xl overflow-hidden flex flex-col z-50 max-h-[72vh] sm:max-h-none"
             style="height: 480px; display: none;">
            
            <!-- Chat Header -->
            <div class="p-4 bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 text-white flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border-2 border-white/50 overflow-hidden bg-white/20">
                        <img src="assets/images/nong_smartscience.png" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-sm font-heading">น้อง SmartScience 🤖</h4>
                        <div class="text-[10px] text-emerald-100 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                            AI ผู้ช่วยวิจัยเกษตรอัจฉริยะ (พร้อมตอบ 24 ชม.)
                        </div>
                    </div>
                </div>
                <button @click="isOpen = false" class="text-white/80 hover:text-white p-1 rounded-lg">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Chat Messages Box -->
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 text-xs bg-slate-50/50">
                <!-- Welcome Message -->
                <div class="flex items-start gap-2.5">
                    <img src="assets/images/nong_smartscience.png" class="w-7 h-7 rounded-full object-cover border border-emerald-300 flex-shrink-0 mt-0.5">
                    <div class="p-3 rounded-2xl bg-white text-gray-800 shadow-sm border border-gray-100 max-w-[85%] leading-relaxed">
                        สวัสดีครับ! ผมน้อง <strong>SmartScience</strong> 🤖 ผู้ช่วยอัจฉริยะประจำโครงการ <strong>LEQs-xAI</strong> สอบถามข้อมูลหลักสูตร 7 โมดูล, โครงงาน Capstone, หรือการคำนวณเซนเซอร์ได้เลยครับ!
                    </div>
                </div>

                <!-- Suggested Quick Prompts -->
                <div class="flex flex-wrap gap-1.5 pt-1 pl-9">
                    <button @click="askQuick('หลักสูตร 7 โมดูลมีอะไรบ้าง?')" class="px-2.5 py-1 rounded-full bg-white hover:bg-emerald-50 text-[11px] text-emerald-700 border border-emerald-200 transition shadow-xs">
                        🌱 7 โมดูลเรียนรู้อะไรบ้าง?
                    </button>
                    <button @click="askQuick('VPD คืออะไร และส่งผลต่อทุเรียนอย่างไร?')" class="px-2.5 py-1 rounded-full bg-white hover:bg-cyan-50 text-[11px] text-cyan-700 border border-cyan-200 transition shadow-xs">
                        💨 VPD ส่งผลต่อทุเรียนอย่างไร?
                    </button>
                    <button @click="askQuick('สิ่งที่ได้รับจากการเข้าร่วมอบรมมีอะไรบ้าง?')" class="px-2.5 py-1 rounded-full bg-white hover:bg-amber-50 text-[11px] text-amber-700 border border-amber-200 transition shadow-xs">
                        🎁 สิ่งที่ได้รับจากการอบรมมีอะไรบ้าง?
                    </button>
                </div>

                <!-- Dynamic Chat Bubbles -->
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.isUser ? 'flex justify-end' : 'flex items-start gap-2.5'">
                        <template x-if="!msg.isUser">
                            <img src="assets/images/nong_smartscience.png" class="w-7 h-7 rounded-full object-cover border border-emerald-300 flex-shrink-0 mt-0.5">
                        </template>
                        <div :class="msg.isUser ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-sm' : 'bg-white text-gray-800 shadow-sm border border-gray-100'"
                             class="p-3 rounded-2xl max-w-[85%] leading-relaxed"
                             x-html="msg.text">
                        </div>
                    </div>
                </template>

                <!-- Loading Bubble -->
                <div x-show="isLoading" class="flex items-start gap-2.5">
                    <img src="assets/images/nong_smartscience.png" class="w-7 h-7 rounded-full object-cover border border-emerald-300 flex-shrink-0 mt-0.5">
                    <div class="p-3 rounded-2xl bg-white text-gray-500 shadow-sm border border-gray-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-bounce"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-bounce" style="animation-delay: 0.15s"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-bounce" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Chat Input Form -->
            <form @submit.prevent="sendMessage()" class="p-3 bg-white border-t border-gray-100 flex items-center gap-2">
                <input type="text" 
                       x-model="inputMessage" 
                       placeholder="พิมพ์คำถามที่นี่..." 
                       class="flex-1 bg-gray-50 border border-gray-200 rounded-full px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 focus:bg-white transition"
                       :disabled="isLoading">
                <button type="submit" 
                        class="w-8 h-8 rounded-full bg-gradient-to-r from-emerald-600 to-teal-500 text-white flex items-center justify-center hover:scale-105 disabled:opacity-50 transition shadow-md"
                        :disabled="!inputMessage.trim() || isLoading">
                    <i class="fa-solid fa-paper-plane text-[11px]"></i>
                </button>
            </form>
        </div>

        <!-- Floating Avatar Button (cmu_aiot style) -->
        <button @click="isOpen = !isOpen" 
                class="group relative w-14 h-14 sm:w-16 md:w-20 sm:h-16 md:h-20 focus:outline-none transform hover:scale-110 active:scale-95 transition duration-300">
            <div class="absolute inset-0 animate-bounce-slow rounded-full bg-gradient-to-tr from-emerald-100 to-cyan-100 border-2 sm:border-4 border-white shadow-2xl overflow-hidden p-0.5 sm:p-1">
                <img src="assets/images/nong_smartscience.png" 
                     alt="Nong SmartScience" 
                     class="w-full h-full object-cover">
            </div>
            
            <!-- Notification Badge -->
            <span class="absolute top-0 right-0 w-3.5 h-3.5 sm:w-4 sm:h-4 bg-red-500 rounded-full border-2 border-white animate-ping"></span>
            <span class="absolute top-0 right-0 w-3.5 h-3.5 sm:w-4 sm:h-4 bg-red-500 rounded-full border-2 border-white"></span>
            
            <!-- Tooltip -->
            <span class="hidden sm:block absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-xl shadow-xl text-xs font-bold text-gray-800 whitespace-nowrap opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none border border-gray-100">
                ถามน้อง SmartScience สิ! 🤖
                <span class="absolute right-[-6px] top-1/2 -translate-y-1/2 w-3 h-3 bg-white transform rotate-45 border-r border-t border-gray-100"></span>
            </span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- 12. SCREEN LIGHTBOX MODAL                                                 -->
    <!-- ========================================================================= -->
    <div id="screen-modal" class="fixed inset-0 z-50 bg-slate-950/85 backdrop-blur-md hidden items-center justify-center p-2 sm:p-4">
        <div class="relative max-w-4xl w-full bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-2xl border border-gray-100 max-h-[92vh] overflow-y-auto">
            <button onclick="closeScreenModal()" class="absolute top-3 right-3 sm:top-5 sm:right-5 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-800 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm sm:text-lg"></i>
            </button>
            <div class="space-y-3 sm:space-y-4">
                <div class="pr-10">
                    <h3 id="modal-screen-title" class="text-base sm:text-xl font-bold text-gray-900 font-heading"></h3>
                    <p id="modal-screen-desc" class="text-[11px] sm:text-xs text-gray-500 mt-0.5 sm:mt-1"></p>
                </div>
                <div class="relative rounded-xl sm:rounded-2xl overflow-hidden border border-gray-100 bg-slate-950 p-2 sm:p-4 flex items-center justify-center min-h-[260px] sm:min-h-[360px]">
                    <img id="modal-screen-bg" src="" alt="" class="absolute inset-0 w-full h-full object-cover blur-2xl opacity-40 scale-110 pointer-events-none">
                    <img id="modal-screen-img" src="" alt="Screen Preview" class="relative z-10 w-auto h-auto max-w-full max-h-[65vh] object-contain mx-auto drop-shadow-2xl rounded-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Alpine.js Helper for Nong SmartScience -->
    <script>
        function nongSmartScience() {
            return {
                isOpen: false,
                isLoading: false,
                inputMessage: '',
                messages: [],
                
                askQuick(text) {
                    this.inputMessage = text;
                    this.sendMessage();
                },

                async sendMessage() {
                    if (!this.inputMessage.trim()) return;
                    const userText = this.inputMessage;
                    this.messages.push({ id: Date.now(), text: userText, isUser: true });
                    this.inputMessage = '';
                    this.isLoading = true;
                    this.scrollToBottom();

                    try {
                        // Simulated intelligent response for LEQs-xAI knowledge base
                        await new Promise(r => setTimeout(r, 800));
                        let replyText = '';
                        const q = userText.toLowerCase();

                        if (q.includes('วัน') || q.includes('เวลา') || q.includes('เมื่อไหร่') || q.includes('กำหนดการ') || q.includes('สถานที่') || q.includes('ที่ไหน') || q.includes('จัดที่')) {
                            replyText = '📅 <strong>กำหนดการจัดกิจกรรมอบรม:</strong> วันที่ <strong>28 - 30 พฤศจิกายน 2569</strong> (ระยะเวลา 3 วัน 18 ชั่วโมง)<br>📍 <strong>สถานที่:</strong> ณ <strong>คณะวิทยาศาสตร์และเทคโนโลยี มหาวิทยาลัยราชภัฏรำไพพรรณี จันทบุรี</strong><br>เน้นลงมือปฏิบัติการจริงทุกขั้นตอน พร้อมรับชุดบอร์ดทดลอง ESP32-S3 ATD3.5 และวุฒิบัตรรับรองสมรรถนะครับ!';
                        } else if (q.includes('โมดูล') || q.includes('module') || q.includes('หลักสูตร')) {
                            replyText = 'หลักสูตร LEQs-xAI มี <strong>7 โมดูลเข้มข้น</strong> ครอบคลุม: <br>1. AIoT Overview<br>2. Precision Sensors &amp; VPD<br>3. Deep Learning &amp; CNN<br>4. Computer Vision (YOLOv8)<br>5. Edge AI TinyML บน ESP32-S3<br>6. Environmental AI<br>7. Capstone Mini Projects ครับ!';
                        } else if (q.includes('vpd')) {
                            replyText = '<strong>VPD (Vapor Pressure Deficit)</strong> คือความดันไอขาดดุลของอากาศครับ สภาวะที่เหมาะสมสำหรับทุเรียนคือ <strong>0.8 - 1.25 kPa</strong> ถ้าต่ำกว่า 0.4 kPa พืชไม่คายน้ำและเสี่ยงโรครา แต่ถ้าเกิน 1.60 kPa ปากใบจะปิดสนิทและเสี่ยงสลัดผลอ่อนครับ';
                        } else if (q.includes('สิ่งที่ได้รับ') || q.includes('อุปกรณ์') || q.includes('ประโยชน์')) {
                            replyText = 'สิ่งที่ผู้เข้าร่วมอบรมจะได้รับประกอบด้วย:<br>• <strong>ชุดทดลองบอร์ด ESP32-S3 ATD3.5 ทัชสกรีน</strong> พร้อมโพรบวัดดิน RS485 7-in-1 และกล้อง AI<br>• อาหารกลางวันและอาหารว่าง/เครื่องดื่มฟรีตลอดการจัดอบรม 3 วัน<br>• วุฒิบัตรอิเล็กทรอนิกส์ (E-Certificate) รับรองสมรรถนะ<br>• สื่อการสอน แผนการจัดการเรียนรู้ Active Learning และโค้ดตัวอย่างฉบับสมบูรณ์ครับ';
                        } else if (q.includes('ค่าใช้จ่าย') || q.includes('งบประมาณ') || q.includes('ค่าลงทะเบียน') || q.includes('เงิน')) {
                            replyText = 'การอบรมเชิงปฏิบัติการนี้ <strong>ไม่มีค่าใช้จ่ายในการลงทะเบียนตลอดหลักสูตร (เข้าร่วมฟรี)</strong> โดยได้รับการสนับสนุนการจัดกิจกรรมและชุดอุปกรณ์ฝึกปฏิบัติการจากมหาวิทยาลัยราชภัฏรำไพพรรณีครับ';
                        } else if (q.includes('สมัคร') || q.includes('ลงทะเบียน')) {
                            replyText = 'สามารถลงทะเบียนเข้าร่วมอบรมได้ทันทีที่ปุ่ม <strong><a href="pages/register.php" class="text-emerald-600 underline">ลงทะเบียนเข้าร่วมอบรม</a></strong> (จัดอบรม 28-30 พ.ย. 2569 ณ มรภ.รำไพพรรณี) รับจำนวนจำกัด 30 ท่าน (นักเรียน ครู และเกษตรกร) ฟรีตลอดหลักสูตรครับ!';
                        } else {
                            replyText = 'ยินดีต้อนรับสู่โครงการ LEQs-AgriEnvi-xAI ครับ! หากต้องการสอบถามข้อมูลเฉพาะด้าน สามารถติดต่อ ผศ.ดร.ชีวะ ทัศนา ทางอีเมล <a href="mailto:chewa.t@rbru.ac.th" class="text-emerald-600 underline">chewa.t@rbru.ac.th</a> หรือโทร <a href="tel:0937422654" class="text-emerald-600 underline font-bold">093 7422654</a> ได้ตลอดเวลาครับ 😊';
                        }

                        this.messages.push({ id: Date.now() + 1, text: replyText, isUser: false });
                    } catch (err) {
                        this.messages.push({ id: Date.now() + 1, text: 'ขออภัยครับ ระบบขัดข้องชั่วคราว', isUser: false });
                    } finally {
                        this.isLoading = false;
                        this.scrollToBottom();
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const box = document.getElementById('chat-messages');
                        if (box) box.scrollTop = box.scrollHeight;
                    });
                }
            };
        }
    </script>

</body>
</html>
