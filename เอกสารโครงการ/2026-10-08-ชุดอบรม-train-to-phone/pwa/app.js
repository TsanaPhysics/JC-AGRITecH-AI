'use strict';
const $ = id => document.getElementById(id);
const video = $('video'), photo = $('photo');
let model = null, cfg = null, stream = null;
const LOG_KEY = 'leaf_ai_log';

function loadLog() { try { return JSON.parse(localStorage.getItem(LOG_KEY) || '[]'); } catch (e) { return []; } }
function saveLog(a) { try { localStorage.setItem(LOG_KEY, JSON.stringify(a.slice(-200))); } catch (e) {} }

async function init() {
  try {
    cfg = await (await fetch('labels.json')).json();
    model = await tf.loadLayersModel('model/model.json');
    tf.tidy(() => model.predict(tf.zeros([1, cfg.input_size, cfg.input_size, 3])));
    $('status').textContent = 'โมเดลพร้อม (' + tf.getBackend() + ')';
    $('btnSnap').disabled = !stream && !photo.src;
  } catch (e) {
    $('status').textContent = 'โหลดโมเดลไม่สำเร็จ';
    $('result').innerHTML = '<p class="hint">' + e.message + '</p>';
  }
  renderLog();
  if ('serviceWorker' in navigator) navigator.serviceWorker.register('sw.js').catch(() => {});
}

async function openCam() {
  try {
    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } }, audio: false });
    video.srcObject = stream; photo.hidden = true; video.hidden = false;
    $('btnSnap').disabled = !model;
  } catch (e) {
    $('result').innerHTML = '<p class="hint">เปิดกล้องไม่ได้: ' + e.name + ' (ต้องใช้ HTTPS และอนุญาตกล้อง) ใช้ "เลือกรูป" แทนได้</p>';
  }
}

function predict(src) {
  const n = cfg.input_size;
  const probs = tf.tidy(() => {
    const t = tf.browser.fromPixels(src).resizeBilinear([n, n]).toFloat().expandDims(0);
    return model.predict(t).dataSync();
  });
  const arr = Array.from(probs).map((p, i) => ({ label: cfg.labels[i], p }));
  arr.sort((a, b) => b.p - a.p);
  show(arr);
}

function show(arr) {
  const best = arr[0], unsure = best.p < cfg.threshold;
  let h = '<div class="top ' + (unsure ? 'unsure' : 'ok') + '">' + (unsure ? 'ไม่แน่ใจ' : best.label) + ' ' + (best.p * 100).toFixed(0) + '%</div>';
  if (unsure) h += '<p class="hint">ความมั่นใจต่ำกว่า ' + (cfg.threshold * 100) + '% ลองถ่ายใหม่ในที่สว่างและให้ใบเต็มกรอบ</p>';
  arr.slice(0, 3).forEach(r => { h += '<div>' + r.label + ' <small>' + (r.p * 100).toFixed(1) + '%</small></div><div class="bar"><i style="width:' + (r.p * 100) + '%"></i></div>'; });
  $('result').innerHTML = h;
  const log = loadLog();
  log.push({ t: new Date().toISOString(), label: unsure ? 'ไม่แน่ใจ' : best.label, top1: best.label, p: +best.p.toFixed(3) });
  saveLog(log); renderLog();
}

function renderLog() {
  const log = loadLog();
  $('count').textContent = '(' + log.length + ')';
  $('log').innerHTML = log.slice(-10).reverse().map(r =>
    '<li><span>' + new Date(r.t).toLocaleTimeString('th-TH') + ' ' + r.label + '</span><span>' + (r.p * 100).toFixed(0) + '%</span></li>').join('');
}

function csv() {
  const rows = ['time,label,top1,confidence'].concat(loadLog().map(r => [r.t, r.label, r.top1, r.p].join(',')));
  const a = document.createElement('a');
  a.href = URL.createObjectURL(new Blob(['﻿' + rows.join('\n')], { type: 'text/csv' }));
  a.download = 'leaf_ai_log.csv'; a.click();
}

$('btnCam').onclick = openCam;
$('btnSnap').onclick = () => { if (model && video.videoWidth) predict(video); };
$('file').onchange = e => {
  const f = e.target.files[0]; if (!f || !model) return;
  photo.onload = () => { predict(photo); URL.revokeObjectURL(photo.src); };
  photo.src = URL.createObjectURL(f); photo.hidden = false; video.hidden = true;
};
$('btnCsv').onclick = csv;
$('btnClear').onclick = () => { saveLog([]); renderLog(); };
init();
