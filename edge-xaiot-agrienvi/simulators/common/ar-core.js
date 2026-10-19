/**
 * ============================================================================
 * Edge xAIoT : 4D-AgriEnvi — Central 3D AR MediaPipe Core Engine
 * Faculty of Science & Technology, Rambhai Barni Rajabhat University
 * Author: Dr. Chewa Thassana & LEQs Team
 * ============================================================================
 * Features:
 * 1. Zero-GC loop pre-allocation
 * 2. MediaPipe Hands 21-joint spatial tracking & glowing canvas overlay
 * 3. Multi-modal Gesture Classification (Pinch, Point, Fist, Palm, Hand Tilt)
 * 4. Real-time Web Audio API Acoustic Synthesizer (Relay Clicks, Probes, Hums)
 * 5. Dual-Input Architecture: Camera AI Tracking + Mouse/Touch Fallback
 * ============================================================================
 */

class ARCoreEngine {
  constructor(options = {}) {
    this.labId = options.labId || 'generic-lab';
    this.themeColor = options.themeColor || '#00f0ff';
    this.onFrameCallback = options.onFrame || null;
    this.onPinchStart = options.onPinchStart || null;
    this.onPinchEnd = options.onPinchEnd || null;
    this.onPinchMove = options.onPinchMove || null;

    // Camera & Canvas elements
    this.videoEl = document.getElementById('camera-feed');
    this.canvasEl = document.getElementById('tracking-canvas');
    this.canvasCtx = this.canvasEl ? this.canvasEl.getContext('2d') : null;

    // MediaPipe state
    this.cameraInstance = null;
    this.handsInstance = null;
    this.isCameraActive = false;
    this.isTrackingHand = false;

    // Gesture State
    this.isPinching = false;
    this.pinchDist = 1.0;
    this.pinchPos = { x: 0.5, y: 0.5, z: 0 };
    this.indexPos = { x: 0.5, y: 0.5, z: 0 };
    this.tiltDeg = 0;
    this.currentGesture = 'none'; // 'pinch' | 'point' | 'fist' | 'palm' | 'none'

    // Scene Positioning
    this.scenePosY = options.initialPosY || 0.95;
    this.sceneRotY = 0;
    this.apparatusPivot = document.getElementById('apparatus-pivot');

    // Pre-allocated Zero-GC Vector Math
    this._dx = 0;
    this._dy = 0;
    this._dz = 0;

    // Audio Context (Synthesizer)
    this.audioCtx = null;
    this.humOsc = null;
    this.humGain = null;

    this.currentMode = '3d';
    this.initAudio();
    this.initControls();
    this.updateModeUI();
  }

  // ==========================================================================
  // 1. WEB AUDIO API REAL-TIME ACOUSTIC SYNTHESIZER
  // ==========================================================================
  initAudio() {
    try {
      const AudioContextClass = window.AudioContext || window.webkitAudioContext;
      if (AudioContextClass) {
        this.audioCtx = new AudioContextClass();
      }
    } catch (e) {
      console.warn('[ARCore] Web Audio API not supported:', e);
    }
  }

  resumeAudio() {
    if (this.audioCtx && this.audioCtx.state === 'suspended') {
      this.audioCtx.resume();
    }
  }

  /**
   * Snappy mechanical relay switch click sound
   * (Noise burst + damped sine ping mimicking mechanical armature snap)
   */
  playRelayClick(state = true) {
    this.resumeAudio();
    if (!this.audioCtx) return;
    const now = this.audioCtx.currentTime;

    // Sine Ping
    const osc = this.audioCtx.createOscillator();
    const gain = this.audioCtx.createGain();
    osc.type = state ? 'triangle' : 'sine';
    osc.frequency.setValueAtTime(state ? 1600 : 900, now);
    osc.frequency.exponentialRampToValueAtTime(120, now + 0.045);

    gain.gain.setValueAtTime(0.35, now);
    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.045);

    osc.connect(gain);
    gain.connect(this.audioCtx.destination);
    osc.start(now);
    osc.stop(now + 0.05);

    // Filtered Noise Click
    const bufferSize = this.audioCtx.sampleRate * 0.025;
    const buffer = this.audioCtx.createBuffer(1, bufferSize, this.audioCtx.sampleRate);
    const data = buffer.getChannelData(0);
    for (let i = 0; i < bufferSize; i++) {
      data[i] = (Math.random() * 2 - 1) * Math.exp(-i / (bufferSize * 0.3));
    }
    const noise = this.audioCtx.createBufferSource();
    noise.buffer = buffer;
    const filter = this.audioCtx.createBiquadFilter();
    filter.type = 'highpass';
    filter.frequency.setValueAtTime(state ? 2500 : 1800, now);

    const noiseGain = this.audioCtx.createGain();
    noiseGain.gain.setValueAtTime(0.4, now);
    noiseGain.gain.exponentialRampToValueAtTime(0.01, now + 0.025);

    noise.connect(filter);
    filter.connect(noiseGain);
    noiseGain.connect(this.audioCtx.destination);
    noise.start(now);
  }

  /**
   * Sensor probe detection ping (High-tech audio indicator)
   */
  playProbeBeep(freq = 880, duration = 0.08) {
    this.resumeAudio();
    if (!this.audioCtx) return;
    const now = this.audioCtx.currentTime;

    const osc = this.audioCtx.createOscillator();
    const gain = this.audioCtx.createGain();
    osc.type = 'sine';
    osc.frequency.setValueAtTime(freq, now);
    osc.frequency.exponentialRampToValueAtTime(freq * 1.25, now + duration);

    gain.gain.setValueAtTime(0.2, now);
    gain.gain.exponentialRampToValueAtTime(0.001, now + duration);

    osc.connect(gain);
    gain.connect(this.audioCtx.destination);
    osc.start(now);
    osc.stop(now + duration);
  }

  /**
   * Water aeration bubbling sound
   */
  playWaterBubble() {
    this.resumeAudio();
    if (!this.audioCtx) return;
    const now = this.audioCtx.currentTime;

    const osc = this.audioCtx.createOscillator();
    const gain = this.audioCtx.createGain();
    const f0 = 400 + Math.random() * 600;
    osc.type = 'sine';
    osc.frequency.setValueAtTime(f0, now);
    osc.frequency.exponentialRampToValueAtTime(f0 + 350, now + 0.06);

    gain.gain.setValueAtTime(0.12, now);
    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.06);

    osc.connect(gain);
    gain.connect(this.audioCtx.destination);
    osc.start(now);
    osc.stop(now + 0.065);
  }

  /**
   * Start or update continuous motor / inverter hum
   */
  setHumFrequency(freq = 60, targetGain = 0.05) {
    this.resumeAudio();
    if (!this.audioCtx) return;
    const now = this.audioCtx.currentTime;

    if (!this.humOsc) {
      this.humOsc = this.audioCtx.createOscillator();
      this.humGain = this.audioCtx.createGain();
      this.humOsc.type = 'triangle';
      this.humOsc.frequency.setValueAtTime(freq, now);
      this.humGain.gain.setValueAtTime(0.001, now);
      this.humOsc.connect(this.humGain);
      this.humGain.connect(this.audioCtx.destination);
      this.humOsc.start(now);
    }

    this.humOsc.frequency.linearRampToValueAtTime(Math.max(20, Math.min(400, freq)), now + 0.1);
    this.humGain.gain.linearRampToValueAtTime(Math.max(0, Math.min(0.2, targetGain)), now + 0.1);
  }

  stopHum() {
    if (this.humGain && this.audioCtx) {
      this.humGain.gain.linearRampToValueAtTime(0.0001, this.audioCtx.currentTime + 0.1);
    }
  }

  // ==========================================================================
  // 2. MEDIAPIPE HANDS INITIALIZATION & SKELETON RENDERER
  // ==========================================================================
  async startCamera() {
    if (this.isCameraActive) return;

    if (!this.videoEl) {
      console.warn('[ARCore] video element not found.');
      return;
    }

    try {
      const hands = new Hands({
        locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
      });

      hands.setOptions({
        maxNumHands: 1,
        modelComplexity: 1,
        minDetectionConfidence: 0.65,
        minTrackingConfidence: 0.65
      });

      hands.onResults((results) => this.onHandResults(results));
      this.handsInstance = hands;

      const camera = new Camera(this.videoEl, {
        onFrame: async () => {
          if (this.isCameraActive && this.handsInstance) {
            await this.handsInstance.send({ image: this.videoEl });
          }
        },
        width: 320,
        height: 240
      });

      await camera.start();
      this.cameraInstance = camera;
      this.isCameraActive = true;

      const statusEl = document.getElementById('cam-status-text');
      if (statusEl) statusEl.innerText = 'AI Active';

      this.showToast('✅ เปิดระบบตรวจจับท่าทางมือ MediaPipe 3D สำเร็จ');
      this.playProbeBeep(1200, 0.1);
    } catch (err) {
      console.warn('[ARCore] Camera start failed or denied:', err);
      this.isCameraActive = false;
      const statusEl = document.getElementById('cam-status-text');
      if (statusEl) statusEl.innerText = 'Mouse Mode';
      this.showToast('⚠️ ไม่สามารถเข้าถึงกล้อง — สลับใช้เมาส์/สัมผัสอัตโนมัติ');
    }
  }

  stopCamera() {
    if (this.cameraInstance) {
      try { this.cameraInstance.stop(); } catch (e) {}
      this.cameraInstance = null;
    }
    this.isCameraActive = false;
    this.isTrackingHand = false;
    if (this.canvasCtx && this.canvasEl) {
      this.canvasCtx.clearRect(0, 0, this.canvasEl.width, this.canvasEl.height);
    }
    const statusEl = document.getElementById('cam-status-text');
    if (statusEl) statusEl.innerText = 'Cam Off';
    this.showToast('ℹ️ ปิดการใช้งานกล้อง — ควบคุมผ่านเมาส์/ทัชสกรีน');
  }

  async toggleArMode() {
    if (this.currentMode === 'ar') {
      this.stopCamera();
      this.currentMode = '3d';
      this.updateModeUI();
      this.showToast('🖥️ สลับเป็นโหมด 3D Realtime Simulation ปกติ');
    } else {
      this.showToast('📷 กำลังเชื่อมต่อระบบ 3D AR MediaPipe Hands...');
      await this.startCamera();
      if (this.isCameraActive) {
        this.currentMode = 'ar';
        this.updateModeUI();
        this.showToast('🚀 เปิดใช้งานโหมด 3D AR MediaPipe Gesture AI สำเร็จ');
      }
    }
  }

  updateModeUI() {
    const camBox = document.getElementById('cam-box');
    const btnAr = document.getElementById('btn-toggle-ar-mode');
    const pill = document.getElementById('mode-indicator-pill');
    const guide = document.getElementById('gesture-guide-text');

    if (this.currentMode === 'ar') {
      if (camBox) camBox.classList.remove('hidden');
      if (btnAr) {
        btnAr.classList.add('active');
        btnAr.innerHTML = '<i class="fa-solid fa-desktop"></i> สลับเป็นโหมด 3D ปกติ (ปิดกล้อง)';
      }
      if (pill) {
        pill.className = 'mode-indicator-pill ar-active';
        pill.innerHTML = '<span class="dot-pulse"></span> 3D AR MediaPipe Active';
      }
      if (guide) {
        guide.innerHTML = '🖐️ <strong>โหมด 3D AR Active:</strong> จีบนิ้ว (🤏 Pinch) หรือชี้นิ้ว (☝️ Point) หน้ากล้องเพื่อปรับค่าและสั่งการ';
      }
    } else {
      if (camBox) camBox.classList.add('hidden');
      if (btnAr) {
        btnAr.classList.remove('active');
        btnAr.innerHTML = '<i class="fa-solid fa-camera"></i> เปิดใช้งานโหมด 3D AR';
      }
      if (pill) {
        pill.className = 'mode-indicator-pill';
        pill.innerHTML = '<i class="fa-solid fa-cube text-cyan-400"></i> โหมด 3D Simulation (เมาส์/สัมผัส)';
      }
      if (guide) {
        guide.innerHTML = '🖱️ <strong>โหมด 3D Realtime Simulation:</strong> คลิก/ลากวัตถุ 3D หรือเลื่อนสไลเดอร์เพื่อทดลอง (กดปุ่ม <strong>"เปิดใช้งานโหมด 3D AR"</strong> เพื่อเปิดกล้องตรวจจับท่าทางมือ)';
      }
    }
  }

  toggleCamera() {
    this.toggleArMode();
  }

  // ==========================================================================
  // 3. HAND LANDMARKS & GESTURE RECOGNITION PIPELINE
  // ==========================================================================
  onHandResults(results) {
    if (!this.canvasCtx || !this.canvasEl) return;
    const ctx = this.canvasCtx;
    const w = this.canvasEl.width;
    const h = this.canvasEl.height;
    ctx.clearRect(0, 0, w, h);

    if (!results.multiHandLandmarks || results.multiHandLandmarks.length === 0) {
      this.isTrackingHand = false;
      if (this.isPinching) {
        this.isPinching = false;
        if (this.onPinchEnd) this.onPinchEnd();
      }
      this.updateGestureGuide('กรุณาวางมือในกรอบกล้อง (🖐️)');
      return;
    }

    this.isTrackingHand = true;
    const lm = results.multiHandLandmarks[0];

    // Landmark 4: Thumb Tip, Landmark 8: Index Tip, Landmark 0: Wrist, Landmark 9: Middle MCP
    const thumb = lm[4];
    const index = lm[8];
    const middle = lm[12];
    const ring = lm[16];
    const pinky = lm[20];
    const wrist = lm[0];
    const middleKnuckle = lm[9];

    // Compute Pinch Distance (Euclidean)
    this._dx = thumb.x - index.x;
    this._dy = thumb.y - index.y;
    this._dz = thumb.z - index.z;
    this.pinchDist = Math.sqrt(this._dx * this._dx + this._dy * this._dy + this._dz * this._dz);

    // Compute Hand Tilt
    const dxTilt = middleKnuckle.x - wrist.x;
    const dyTilt = middleKnuckle.y - wrist.y;
    this.tiltDeg = Math.atan2(dxTilt, -dyTilt) * (180 / Math.PI);

    // Normalized Pinch Position
    this.pinchPos.x = (thumb.x + index.x) * 0.5;
    this.pinchPos.y = (thumb.y + index.y) * 0.5;
    this.pinchPos.z = (thumb.z + index.z) * 0.5;

    this.indexPos.x = index.x;
    this.indexPos.y = index.y;
    this.indexPos.z = index.z;

    // Gesture State machine
    const PINCH_THRESHOLD = 0.082;
    const wasPinching = this.isPinching;

    if (this.pinchDist < PINCH_THRESHOLD) {
      this.currentGesture = 'pinch';
      this.isPinching = true;
      if (!wasPinching) {
        this.playProbeBeep(880, 0.05);
        if (this.onPinchStart) this.onPinchStart(this.pinchPos);
      } else {
        if (this.onPinchMove) this.onPinchMove(this.pinchPos);
      }
      this.updateGestureGuide('🤏 <strong>กำลังหนีบ (Pinch):</strong> จับ/ปรับระดับความลึกหรืออุปกรณ์');
    } else {
      this.isPinching = false;
      if (wasPinching && this.onPinchEnd) {
        this.onPinchEnd();
      }

      // Check Point vs Palm vs Fist
      const isIndexExtended = index.y < lm[6].y;
      const isMiddleExtended = middle.y < lm[10].y;
      const isRingExtended = ring.y < lm[14].y;
      const isPinkyExtended = pinky.y < lm[18].y;

      if (isIndexExtended && !isMiddleExtended && !isRingExtended && !isPinkyExtended) {
        this.currentGesture = 'point';
        this.updateGestureGuide('☝️ <strong>ชี้เลือก (Point):</strong> เล็งเป้าหมายที่ต้องการเปิด/ปิด');
      } else if (isIndexExtended && isMiddleExtended && isRingExtended && isPinkyExtended) {
        this.currentGesture = 'palm';
        this.updateGestureGuide('🖐️ <strong>กางฝ่ามือ (Palm):</strong> เอียงมือเพื่อหมุนมุมมอง 360°');
        // Tilt scene if tilted
        if (Math.abs(this.tiltDeg) > 25) {
          this.sceneRotY += this.tiltDeg > 0 ? 0.8 : -0.8;
          this.applyApparatusTransform();
        }
      } else if (!isIndexExtended && !isMiddleExtended && !isRingExtended && !isPinkyExtended) {
        this.currentGesture = 'fist';
        this.updateGestureGuide('✊ <strong>กำปั้น (Fist):</strong> ล็อกการแสดงผล');
      } else {
        this.currentGesture = 'none';
        this.updateGestureGuide('🖐️ จีบนิ้ว (🤏) หรือชี้นิ้ว (☝️) เพื่อสั่งการ');
      }
    }

    // Render 21-Joint Cyber Skeleton onto Canvas
    this.renderSkeleton(ctx, lm, w, h);

    // Trigger Per-Frame Lab Hook
    if (this.onFrameCallback) {
      this.onFrameCallback({
        gesture: this.currentGesture,
        isPinching: this.isPinching,
        pinchPos: this.pinchPos,
        indexPos: this.indexPos,
        tiltDeg: this.tiltDeg,
        landmarks: lm
      });
    }
  }

  renderSkeleton(ctx, lm, w, h) {
    const CONNECTIONS = [
      [0,1],[1,2],[2,3],[3,4],        // Thumb
      [0,5],[5,6],[6,7],[7,8],        // Index
      [0,9],[9,10],[10,11],[11,12],   // Middle
      [0,13],[13,14],[14,15],[15,16], // Ring
      [0,17],[17,18],[18,19],[19,20], // Pinky
      [5,9],[9,13],[13,17]           // Palm base
    ];

    // Draw Bones
    ctx.lineWidth = 2;
    ctx.strokeStyle = this.isPinching ? 'rgba(250, 204, 21, 0.7)' : 'rgba(0, 240, 255, 0.5)';
    for (let i = 0; i < CONNECTIONS.length; i++) {
      const p1 = lm[CONNECTIONS[i][0]];
      const p2 = lm[CONNECTIONS[i][1]];
      ctx.beginPath();
      ctx.moveTo(p1.x * w, p1.y * h);
      ctx.lineTo(p2.x * w, p2.y * h);
      ctx.stroke();
    }

    // Draw Joints
    for (let i = 0; i < lm.length; i++) {
      const p = lm[i];
      const px = p.x * w;
      const py = p.y * h;
      const isTip = (i === 4 || i === 8 || i === 12 || i === 16 || i === 20);

      ctx.beginPath();
      ctx.arc(px, py, isTip ? 4 : 2.5, 0, 2 * Math.PI);
      if (i === 4 || i === 8) {
        ctx.fillStyle = this.isPinching ? '#facc15' : '#00f0ff';
        ctx.shadowColor = ctx.fillStyle;
        ctx.shadowBlur = 8;
      } else {
        ctx.fillStyle = '#34d399';
        ctx.shadowBlur = 0;
      }
      ctx.fill();
    }

    // Draw Pinch Indicator Circle
    if (this.isPinching) {
      ctx.beginPath();
      ctx.arc(this.pinchPos.x * w, this.pinchPos.y * h, 14, 0, 2 * Math.PI);
      ctx.strokeStyle = '#facc15';
      ctx.lineWidth = 2.5;
      ctx.stroke();
    }
  }

  // ==========================================================================
  // 4. UI HELPERS & SCENE POSITIONING
  // ==========================================================================
  initControls() {
    // Elevate / Lower Scene buttons
    const btnUp = document.getElementById('btn-elevate-up');
    const btnDown = document.getElementById('btn-elevate-down');
    const btnReset = document.getElementById('btn-reset-view');

    if (btnUp) {
      btnUp.addEventListener('click', () => {
        this.scenePosY += 0.08;
        this.applyApparatusTransform();
        this.playProbeBeep(600, 0.04);
      });
    }

    if (btnDown) {
      btnDown.addEventListener('click', () => {
        this.scenePosY -= 0.08;
        this.applyApparatusTransform();
        this.playProbeBeep(500, 0.04);
      });
    }

    if (btnReset) {
      btnReset.addEventListener('click', () => {
        this.scenePosY = 0.95;
        this.sceneRotY = 0;
        this.applyApparatusTransform();
        this.playProbeBeep(800, 0.05);
        this.showToast('🔄 รีเซ็ตตำแหน่งมุมมอง 3D เรียบร้อย');
      });
    }

    // Toggle AR Mode Button
    const btnToggleAr = document.getElementById('btn-toggle-ar-mode');
    if (btnToggleAr) {
      btnToggleAr.addEventListener('click', () => {
        this.toggleArMode();
      });
    }

    // Mouse drag fallback for 3D rotation
    let isMouseDown = false;
    let lastMouseX = 0;
    const sceneEl = document.querySelector('a-scene');
    if (sceneEl) {
      window.addEventListener('mousedown', (e) => {
        if (e.target.closest('.ui-overlay') || e.target.closest('.cam-box') || e.target.closest('.hud-bar')) return;
        isMouseDown = true;
        lastMouseX = e.clientX;
      });
      window.addEventListener('mouseup', () => { isMouseDown = false; });
      window.addEventListener('mousemove', (e) => {
        if (!isMouseDown) return;
        const deltaX = e.clientX - lastMouseX;
        lastMouseX = e.clientX;
        this.sceneRotY += deltaX * 0.4;
        this.applyApparatusTransform();
      });
    }
  }

  applyApparatusTransform() {
    if (!this.apparatusPivot) {
      this.apparatusPivot = document.getElementById('apparatus-pivot');
    }
    if (this.apparatusPivot) {
      this.apparatusPivot.setAttribute('position', `0 ${this.scenePosY.toFixed(3)} -0.2`);
      this.apparatusPivot.setAttribute('rotation', `0 ${this.sceneRotY.toFixed(1)} 0`);
    }
  }

  updateGestureGuide(htmlContent) {
    const el = document.getElementById('gesture-guide-text');
    if (el) el.innerHTML = htmlContent;
  }

  showToast(msg, duration = 3000) {
    let toast = document.getElementById('toast-msg');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toast-msg';
      toast.className = 'toast-msg';
      document.body.appendChild(toast);
    }
    toast.innerHTML = msg;
    toast.classList.add('show');
    clearTimeout(this._toastTimer);
    this._toastTimer = setTimeout(() => {
      toast.classList.remove('show');
    }, duration);
  }
}

// Global expose
window.ARCoreEngine = ARCoreEngine;
