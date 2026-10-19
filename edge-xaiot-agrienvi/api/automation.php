<?php
/**
 * Edge xAI : 4DAgriEnvi Automation Add-on (ส่วนเสริมของ api.php)
 * ---------------------------------------------------------------
 * - API Key (ไม่บังคับ): ถ้ามีไฟล์ data/api_key.txt ที่ไม่ว่าง การสั่งงาน/แก้ไขต้องแนบคีย์
 *   ผ่านหัว X-Api-Key หรือพารามิเตอร์ key=  (ต่างจาก DEVICE_TOKEN ซึ่งใช้กับบอร์ด)
 * - ตัวจับเวลาปิดอัตโนมัติ (timers) และตารางเวลา (schedules) ประมวลผลตอนบอร์ดเรียก get_commands
 * - บันทึกกิจกรรม (audit log), โปรไฟล์พืช (profiles) และการแจ้งเตือน (alerts)
 *
 * ไฟล์ข้อมูล (ในโฟลเดอร์ data/): api_key.txt, schedules.json, audit_log.json, profiles.json
 * ไม่แก้ไขหรือลบ endpoint เดิมของ api.php
 */

if (!function_exists('auto_tz')) {

    function auto_tz() {
        return new DateTimeZone('Asia/Bangkok');
    }

    function auto_now() {
        return new DateTime('now', auto_tz());
    }

    function auto_read_json($file, $default) {
        if (!is_file($file)) return $default;
        $j = json_decode((string)@file_get_contents($file), true);
        return is_array($j) ? $j : $default;
    }

    function auto_write_json($file, $data) {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $tmp = $file . '.' . getmypid() . '.tmp';
        if (@file_put_contents($tmp, $json) !== false && @rename($tmp, $file)) return true;
        @unlink($tmp);
        return @file_put_contents($file, $json) !== false;
    }

    // ---------------- API Key ----------------
    function auto_key_required($data_dir) {
        $f = $data_dir . '/api_key.txt';
        return is_file($f) && trim((string)@file_get_contents($f)) !== '';
    }

    function auto_require_key($data_dir, $input_json = []) {
        $f = $data_dir . '/api_key.txt';
        $expected = is_file($f) ? trim((string)@file_get_contents($f)) : '';
        if ($expected === '') return true;
        $given = $_SERVER['HTTP_X_API_KEY'] ?? ($_GET['key'] ?? ($_POST['key'] ?? ($input_json['key'] ?? '')));
        if (is_string($given) && hash_equals($expected, $given)) return true;
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'ต้องใช้ API Key ที่ถูกต้อง (Invalid or missing API key)'], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // ---------------- Audit log ----------------
    function auto_audit($data_dir, $type, $detail, $src = 'web') {
        $f = $data_dir . '/audit_log.json';
        $fp = @fopen($f . '.lock', 'c');
        if ($fp) @flock($fp, LOCK_EX);
        $log = auto_read_json($f, []);
        array_unshift($log, [
            'time' => auto_now()->format('Y-m-d H:i:s'),
            'type' => $type,
            'detail' => $detail,
            'src' => $src,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);
        if (count($log) > 300) $log = array_slice($log, 0, 300);
        auto_write_json($f, $log);
        if ($fp) { @flock($fp, LOCK_UN); @fclose($fp); }
    }

    function auto_src($input_json = []) {
        $s = $_GET['src'] ?? ($_POST['src'] ?? ($input_json['src'] ?? 'web'));
        $s = is_string($s) ? preg_replace('/[^a-z0-9_\-]/i', '', $s) : 'web';
        return substr($s ?: 'web', 0, 16);
    }

    // ---------------- Relay helpers ----------------
    function auto_relay_name($state, $rid) {
        return $state['relays'][$rid]['name'] ?? ('Relay ' . $rid);
    }

    function auto_mark_relay(&$state, $rid, $new) {
        $old = intval($state['relays'][$rid]['state'] ?? 0);
        if ($old !== intval($new) || !isset($state['relay_since'][$rid])) {
            $state['relay_since'][$rid] = time();
        }
    }

    /** ตั้งคำสั่งรีเลย์แบบรอบอร์ดยืนยัน (ใช้กลไก pending เดียวกับ control_relay) */
    function auto_set_relay(&$state, $rid, $val) {
        $rid = strval($rid);
        if (!isset($state['relays'][$rid])) return false;
        auto_mark_relay($state, $rid, $val ? 1 : 0);
        $state['relays'][$rid]['state'] = $val ? 1 : 0;
        $state['control_mode'] = 'manual';
        $state['auto_mode'] = false;
        $state['pending_relays'][$rid] = ['state' => $val ? 1 : 0, 'ts' => time()];
        $state['pending_mode'] = ['mode' => 'manual', 'ts' => time()];
        return true;
    }

    // ---------------- Schedules ----------------
    function auto_get_schedules($data_dir) {
        $s = auto_read_json($data_dir . '/schedules.json', []);
        return array_values(array_filter($s, 'is_array'));
    }

    function auto_clean_schedule($in, $existing_id = null) {
        $time = isset($in['time']) ? trim(strval($in['time'])) : '';
        if (!preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $time, $m)) return [null, 'รูปแบบเวลาไม่ถูกต้อง (ต้องเป็น HH:MM)'];
        $time = sprintf('%02d:%02d', intval($m[1]), intval($m[2]));
        $relay = intval($in['relay'] ?? 0);
        if ($relay < 1 || $relay > 4) return [null, 'หมายเลขรีเลย์ต้องเป็น 1-4'];
        $days = $in['days'] ?? [0, 1, 2, 3, 4, 5, 6];
        if (is_string($days)) $days = array_filter(explode(',', $days), 'strlen');
        $days = array_values(array_unique(array_map('intval', (array)$days)));
        $days = array_values(array_filter($days, function ($d) { return $d >= 0 && $d <= 6; }));
        if (!$days) return [null, 'ต้องเลือกอย่างน้อย 1 วัน'];
        sort($days);
        $dur = intval($in['duration'] ?? ($in['duration_min'] ?? 0));
        if ($dur < 0) $dur = 0;
        if ($dur > 720) $dur = 720;
        $action = (($in['action'] ?? 'on') === 'off') ? 'off' : 'on';
        $en = $in['enabled'] ?? 1;
        $enabled = ($en === true || $en === 1 || $en === '1' || $en === 'true') ? 1 : 0;
        return [[
            'id' => $existing_id ?: ('s' . substr(md5(uniqid('', true)), 0, 8)),
            'relay' => $relay,
            'time' => $time,
            'days' => $days,
            'duration' => $dur,
            'action' => $action,
            'enabled' => $enabled,
            'name' => mb_substr(trim(strval($in['name'] ?? '')), 0, 60),
        ], null];
    }

    // ---------------- Profiles ----------------
    function auto_default_profiles() {
        // ค่าเริ่มต้นเพื่อใช้เป็นจุดตั้งต้น ควรปรับตามคำแนะนำของผู้เชี่ยวชาญ/ผลทดลองจริงของแปลง
        return [
            'general' => [
                'id' => 'general', 'name' => 'ทั่วไป',
                'note' => 'ค่าเริ่มต้นเดียวกับแดชบอร์ดเว็บ',
                'thresholds' => [
                    'temp' => ['min' => 20, 'max' => 35],
                    'humidity' => ['min' => 40, 'max' => 90],
                    'soil_moisture' => ['min' => 40, 'max' => 65],
                    'vpd' => ['min' => 0.8, 'max' => 1.2],
                    'soil_ph' => ['min' => 5.5, 'max' => 7.5],
                ],
            ],
            'durian' => [
                'id' => 'durian', 'name' => 'ทุเรียน',
                'note' => 'ค่าตั้งต้นสำหรับทุเรียน ปรับตามช่วงการเจริญเติบโตและคำแนะนำของผู้เชี่ยวชาญ',
                'thresholds' => [
                    'temp' => ['min' => 22, 'max' => 34],
                    'humidity' => ['min' => 60, 'max' => 90],
                    'soil_moisture' => ['min' => 40, 'max' => 60],
                    'vpd' => ['min' => 0.6, 'max' => 1.2],
                    'soil_ph' => ['min' => 5.5, 'max' => 6.5],
                ],
            ],
            'vegetable' => [
                'id' => 'vegetable', 'name' => 'ผักใบ',
                'note' => 'ค่าตั้งต้นสำหรับผักใบ ปรับตามชนิดพืช',
                'thresholds' => [
                    'temp' => ['min' => 18, 'max' => 32],
                    'humidity' => ['min' => 50, 'max' => 85],
                    'soil_moisture' => ['min' => 50, 'max' => 75],
                    'vpd' => ['min' => 0.6, 'max' => 1.1],
                    'soil_ph' => ['min' => 6.0, 'max' => 7.0],
                ],
            ],
        ];
    }

    function auto_get_profiles($data_dir) {
        $custom = auto_read_json($data_dir . '/profiles.json', []);
        $profiles = auto_default_profiles();
        if (isset($custom['profiles']) && is_array($custom['profiles'])) {
            foreach ($custom['profiles'] as $id => $p) {
                if (is_array($p) && isset($p['thresholds'])) $profiles[$id] = array_replace_recursive($profiles[$id] ?? [], $p);
            }
        }
        $active = $custom['active'] ?? 'general';
        if (!isset($profiles[$active])) $active = 'general';
        return [$profiles, $active];
    }

    function auto_save_profiles($data_dir, $profiles, $active) {
        $default_ids = array_keys(auto_default_profiles());
        auto_write_json($data_dir . '/profiles.json', ['active' => $active, 'profiles' => $profiles]);
    }

    // ---------------- Alerts ----------------
    function auto_alerts($state, $profile) {
        $alerts = [];
        $s = $state['sensors'] ?? [];
        $t = $profile['thresholds'] ?? [];
        $add = function ($level, $key, $title, $detail) use (&$alerts) {
            $alerts[] = ['level' => $level, 'key' => $key, 'title' => $title, 'detail' => $detail];
        };

        $last = intval($state['board']['last_direct_post_time'] ?? 0);
        $age = $last > 0 ? (time() - $last) : null;
        if ($age === null || $age > 60) {
            $add('critical', 'board_offline', 'บอร์ดออฟไลน์', $age === null ? 'ยังไม่เคยได้รับข้อมูลจากบอร์ด' : "ไม่ได้รับข้อมูลจากบอร์ดมา {$age} วินาที ค่าที่แสดงอาจเป็นค่าเก่า");
            return $alerts;
        }

        $chk = function ($key, $val, $tkey, $label, $unit, $lowMsg, $highMsg) use ($t, $add) {
            if (!isset($t[$tkey]) || $val === null) return;
            $mn = $t[$tkey]['min'] ?? null;
            $mx = $t[$tkey]['max'] ?? null;
            if ($mn !== null && $val < $mn) $add('warning', $key . '_low', $label . 'ต่ำ', "{$label} {$val}{$unit} ต่ำกว่าเกณฑ์ {$mn}{$unit} — {$lowMsg}");
            if ($mx !== null && $val > $mx) $add('warning', $key . '_high', $label . 'สูง', "{$label} {$val}{$unit} สูงกว่าเกณฑ์ {$mx}{$unit} — {$highMsg}");
        };
        $num = function ($k) use ($s) { return isset($s[$k]) && is_numeric($s[$k]) ? round(floatval($s[$k]), 2) : null; };

        $chk('temp', $num('temperature'), 'temp', 'อุณหภูมิอากาศ', ' °C', 'ระวังพืชชะงักการเจริญเติบโต', 'พิจารณาพ่นหมอก/ให้ร่มเงา');
        $chk('humidity', $num('humidity'), 'humidity', 'ความชื้นสัมพัทธ์', ' %', 'อากาศแห้ง พิจารณาพ่นหมอก', 'เสี่ยงโรคเชื้อรา ควรระบายอากาศ');
        $chk('soil_moisture', $num('soil_moisture'), 'soil_moisture', 'ความชื้นดิน', ' %', 'พิจารณารดน้ำ', 'ดินแฉะเกินไป งดให้น้ำ');
        $chk('vpd', $num('vpd'), 'vpd', 'VPD', ' kPa', 'ชื้นมาก เสี่ยงโรคเชื้อรา', 'อากาศแห้ง พืชคายน้ำมากกว่าปกติ');
        $chk('soil_ph', $num('soil_ph'), 'soil_ph', 'pH ดิน', '', 'ดินเป็นกรด พิจารณาปรับสภาพดิน', 'ดินเป็นด่าง พิจารณาปรับสภาพดิน');

        foreach (['1', '2', '3', '4'] as $rid) {
            if (intval($state['relays'][$rid]['state'] ?? 0) === 1) {
                $since = intval($state['relay_since'][$rid] ?? 0);
                $has_timer = isset($state['timers'][$rid]);
                if ($since > 0 && !$has_timer && (time() - $since) > 3600) {
                    $mins = intval((time() - $since) / 60);
                    $add('warning', 'relay_long_' . $rid, auto_relay_name($state, $rid) . ' เปิดค้างนาน', "เปิดต่อเนื่องมาแล้วประมาณ {$mins} นาที และไม่ได้ตั้งตัวจับเวลาปิด");
                }
            }
        }
        return $alerts;
    }

    // ---------------- Tick (timers + schedules) ----------------
    function auto_needs_tick($state, $data_dir) {
        $now = time();
        foreach (($state['timers'] ?? []) as $rid => $tm) {
            if (intval($tm['end'] ?? 0) <= $now) return true;
        }
        $minute = auto_now()->format('YmdHi');
        if (($state['auto_last_min'] ?? '') !== $minute) {
            $sch = auto_get_schedules($data_dir);
            foreach ($sch as $s) { if (!empty($s['enabled'])) return true; }
        }
        return false;
    }

    /** คืน true ถ้ามีการเปลี่ยนสถานะ (ต้องบันทึก) ต้องเรียกภายใต้ล็อกสถานะ */
    function auto_tick(&$state, $data_dir) {
        $changed = false;
        $now = time();

        foreach (($state['timers'] ?? []) as $rid => $tm) {
            if (intval($tm['end'] ?? 0) <= $now) {
                unset($state['timers'][$rid]);
                if (intval($state['relays'][$rid]['state'] ?? 0) === 1) {
                    auto_set_relay($state, $rid, 0);
                    auto_audit($data_dir, 'timer_off', auto_relay_name($state, $rid) . ' ปิดอัตโนมัติเมื่อครบเวลา', 'server');
                }
                $changed = true;
            }
        }

        $dt = auto_now();
        $minute = $dt->format('YmdHi');
        if (($state['auto_last_min'] ?? '') !== $minute) {
            $state['auto_last_min'] = $minute;
            $changed = true;
            $hm = $dt->format('H:i');
            $dow = intval($dt->format('w'));
            foreach (auto_get_schedules($data_dir) as $s) {
                if (empty($s['enabled']) || ($s['time'] ?? '') !== $hm) continue;
                if (!in_array($dow, array_map('intval', $s['days'] ?? []), true)) continue;
                $rid = strval($s['relay'] ?? 0);
                if (!isset($state['relays'][$rid])) continue;
                $on = (($s['action'] ?? 'on') === 'on');
                auto_set_relay($state, $rid, $on);
                if ($on && intval($s['duration'] ?? 0) > 0) {
                    $state['timers'][$rid] = ['end' => $now + intval($s['duration']) * 60];
                } else {
                    unset($state['timers'][$rid]);
                }
                $label = ($s['name'] ?? '') !== '' ? $s['name'] : ($hm);
                auto_audit($data_dir, 'schedule', 'ตารางเวลา "' . $label . '": ' . auto_relay_name($state, $rid) . ($on ? ' เปิด' . (intval($s['duration']) > 0 ? ' ' . intval($s['duration']) . ' นาที' : '') : ' ปิด'), 'schedule');
            }
        }
        return $changed;
    }
}
