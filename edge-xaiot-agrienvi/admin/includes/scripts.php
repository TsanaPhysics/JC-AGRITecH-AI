    <!-- ========================================================================= -->
    <!-- JAVASCRIPT: CRUD ACTIONS & FILTERING -->
    <!-- ========================================================================= -->
    <script>
        const participantsData = <?php echo json_encode($participants, JSON_UNESCAPED_UNICODE); ?>;

        // Table Filtering
        function filterAdminTable() {
            const query = (document.getElementById('adminSearchInput').value || '').toLowerCase().trim();
            const groupFilter = (document.getElementById('groupFilter').value || '').toLowerCase().trim();
            const trackFilter = (document.getElementById('trackFilter').value || '').toLowerCase().trim();
            const statusFilter = (document.getElementById('statusFilter').value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.participant-row');

            rows.forEach(row => {
                const name = (row.getAttribute('data-name') || '').toLowerCase();
                const phone = (row.getAttribute('data-phone') || '').toLowerCase();
                const org = (row.getAttribute('data-org') || '').toLowerCase();
                const group = (row.getAttribute('data-group') || '').toLowerCase();
                const track = (row.getAttribute('data-track') || '').toLowerCase();
                const status = (row.getAttribute('data-status') || '').toLowerCase();

                const matchQuery = !query || name.includes(query) || phone.includes(query) || org.includes(query) || group.includes(query) || track.includes(query);
                const matchGroup = !groupFilter || group.includes(groupFilter);
                const matchTrack = !trackFilter || track.includes(trackFilter);
                const matchStatus = !statusFilter || status === statusFilter;

                if (matchQuery && matchGroup && matchTrack && matchStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // CSV Export
        function exportCSV() {
            if (!participantsData || !participantsData.length) {
                Swal.fire('ไม่มีข้อมูล', 'ยังไม่มีรายชื่อผู้เข้าร่วมสำหรับส่งออก', 'info');
                return;
            }
            let csv = "\uFEFF"; // UTF-8 BOM
            csv += 'ID,คำนำหน้า,ชื่อ-นามสกุล,กลุ่มเป้าหมาย,สังกัด/สถาบัน,เบอร์โทร,อีเมล,แทร็กโครงงาน,คะแนน Pre,คะแนน Post,สถานะ\n';
            participantsData.forEach(r => {
                csv += `"${r.id}","${r.prefix || ''}","${r.fullname || ''}","${r.target_group || ''}","${r.organization || ''}","${r.phone || ''}","${r.email || ''}","${r.project_track || ''}","${r.pre_score || 0}","${r.post_score || 0}","${r.status || 'registered'}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `LEQs_xAI_Participants_${Date.now()}.csv`;
            link.click();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'ส่งออกไฟล์ CSV สำเร็จ',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // --- PARTICIPANT MODALS & ACTIONS ---
        function openAddParticipantModal() {
            document.getElementById('addParticipantForm').reset();
            document.getElementById('addParticipantModal').classList.remove('hidden');
        }
        function closeAddParticipantModal() {
            document.getElementById('addParticipantModal').classList.add('hidden');
        }

        async function submitAddParticipant(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitAddP');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...';

            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'add_participant');

            try {
                const res = await fetch('../api/api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มข้อมูลสำเร็จ!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่สามารถบันทึกได้', 'error');
                }
            } catch (err) {
                Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> บันทึกข้อมูล';
            }
        }

        function openEditParticipantModal(p) {
            document.getElementById('edit_id').value = p.id;
            document.getElementById('editParticipantSubtitle').textContent = `รหัสผู้เข้าร่วม #${p.id}`;
            document.getElementById('edit_prefix').value = p.prefix || 'นาย';
            document.getElementById('edit_fullname').value = p.fullname || '';
            document.getElementById('edit_target_group').value = p.target_group || 'นักเรียนมัธยมศึกษาตอนปลาย';
            document.getElementById('edit_organization').value = p.organization || '';
            document.getElementById('edit_phone').value = p.phone || '';
            document.getElementById('edit_email').value = p.email || '';
            document.getElementById('edit_project_track').value = p.project_track || 'Track A — Smart Agriculture & Sensor Hub';
            document.getElementById('edit_pre_score').value = p.pre_score || 0;
            document.getElementById('edit_post_score').value = p.post_score || 0;
            document.getElementById('edit_status').value = p.status || 'registered';

            document.getElementById('editParticipantModal').classList.remove('hidden');
        }
        function closeEditParticipantModal() {
            document.getElementById('editParticipantModal').classList.add('hidden');
        }

        async function submitEditParticipant(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitEditP');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...';

            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'update_participant');

            try {
                const res = await fetch('../api/api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขสำเร็จ!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่สามารถแก้ไขได้', 'error');
                }
            } catch (err) {
                Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> บันทึกการแก้ไข';
            }
        }

        async function confirmDeleteParticipant(id, name) {
            const result = await Swal.fire({
                title: 'ยืนยันการลบรายชื่อ?',
                html: `ต้องการลบ <b>${name}</b> (รหัส #${id}) ออกจากระบบหรือไม่?<br><span class="text-xs text-rose-400">การกระทำนี้จะลบออกจากฐานข้อมูลและไฟล์ JSON ทันที</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#334155',
                confirmButtonText: 'ลบรายชื่อนี้',
                cancelButtonText: 'ยกเลิก'
            });

            if (result.isConfirmed) {
                try {
                    const res = await fetch(`../api/api.php?action=delete_participant&id=${id}`, {
                        method: 'POST'
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ',
                            text: data.message,
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            const row = document.getElementById(`participant-row-${id}`);
                            if (row) row.remove();
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด', data.message, 'error');
                    }
                } catch (err) {
                    Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
                }
            }
        }

        async function toggleCheckIn(id, name) {
            try {
                const res = await fetch(`../api/api.php?action=toggle_checkin&id=${id}`, {
                    method: 'POST'
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `${name}: ${data.message}`,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('ผิดพลาด', data.message, 'error');
                }
            } catch (err) {
                Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
            }
        }

        async function confirmResetParticipants() {
            const result = await Swal.fire({
                title: 'รีเซ็ตข้อมูลตัวอย่าง?',
                text: 'ระบบจะคืนค่ารายชื่อผู้เข้าร่วม 10 ท่านแรกตามโครงการเพื่อใช้ทดสอบหรือสาธิต',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#334155',
                confirmButtonText: 'รีเซ็ตข้อมูล',
                cancelButtonText: 'ยกเลิก'
            });

            if (result.isConfirmed) {
                try {
                    const res = await fetch('../api/api.php?action=reset_participants', {
                        method: 'POST'
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'รีเซ็ตสำเร็จ',
                            text: data.message,
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                } catch (err) {
                    Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
                }
            }
        }

        async function confirmClearAllParticipants() {
            const result = await Swal.fire({
                title: 'ล้างข้อมูลผู้ลงทะเบียนทั้งหมด?',
                text: 'รายชื่อผู้สมัครทั้งหมดจะถูกล้างเป็น 0 รายการเพื่อเตรียมพร้อมรับการเปิดรับสมัครจริง (การดำเนินการนี้ไม่สามารถยกเลิกได้)',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'ล้างข้อมูลทั้งหมด',
                cancelButtonText: 'ยกเลิก'
            });

            if (result.isConfirmed) {
                try {
                    const res = await fetch('../api/api.php?action=clear_all_participants', {
                        method: 'POST'
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ล้างข้อมูลสำเร็จ',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่สามารถล้างข้อมูลได้', 'error');
                    }
                } catch (err) {
                    Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
                }
            }
        }

        // --- ANNOUNCEMENT MODALS & ACTIONS ---
        function openAddAnnouncementModal() {
            document.getElementById('addAnnouncementForm').reset();
            document.getElementById('addAnnouncementModal').classList.remove('hidden');
        }
        function closeAddAnnouncementModal() {
            document.getElementById('addAnnouncementModal').classList.add('hidden');
        }

        async function submitAddAnnouncement(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitAddAnn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...';

            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'add_announcement');
            if (!form.is_active.checked) {
                formData.set('is_active', '0');
            }

            try {
                const res = await fetch('../api/api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มประกาศสำเร็จ!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่สามารถบันทึกได้', 'error');
                }
            } catch (err) {
                Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plus"></i> บันทึกประกาศ';
            }
        }

        function openEditAnnouncementModal(ann) {
            document.getElementById('edit_ann_id').value = ann.id;
            document.getElementById('editAnnSubtitle').textContent = `รหัสประกาศ #${ann.id}`;
            document.getElementById('edit_ann_title').value = ann.title || '';
            document.getElementById('edit_ann_content').value = ann.content || '';
            document.getElementById('edit_ann_badge').value = ann.badge || 'ประชาสัมพันธ์';
            document.getElementById('edit_ann_badge_color').value = ann.badge_color || 'cyan';
            document.getElementById('edit_ann_is_active').checked = (ann.is_active == 1);

            document.getElementById('editAnnouncementModal').classList.remove('hidden');
        }
        function closeEditAnnouncementModal() {
            document.getElementById('editAnnouncementModal').classList.add('hidden');
        }

        async function submitEditAnnouncement(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitEditAnn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...';

            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'update_announcement');
            formData.set('is_active', form.is_active.checked ? '1' : '0');

            try {
                const res = await fetch('../api/api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขประกาศสำเร็จ!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่สามารถแก้ไขได้', 'error');
                }
            } catch (err) {
                Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> บันทึกการแก้ไข';
            }
        }

        async function confirmDeleteAnnouncement(id, title) {
            const result = await Swal.fire({
                title: 'ยืนยันการลบประกาศ?',
                html: `ต้องการลบประกาศ <b>"${title}"</b> (รหัส #${id}) ออกจากระบบหรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#334155',
                confirmButtonText: 'ลบประกาศ',
                cancelButtonText: 'ยกเลิก'
            });

            if (result.isConfirmed) {
                try {
                    const res = await fetch(`../api/api.php?action=delete_announcement&id=${id}`, {
                        method: 'POST'
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบประกาศสำเร็จ',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด', data.message, 'error');
                    }
                } catch (err) {
                    Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
                }
            }
        }

        async function toggleAnnouncementActive(id) {
            try {
                const res = await fetch(`../api/api.php?action=toggle_announcement&id=${id}`, {
                    method: 'POST'
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1200
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('ผิดพลาด', data.message, 'error');
                }
            } catch (err) {
                Swal.fire('เชื่อมต่อล้มเหลว', err.message, 'error');
            }
        }

        // =========================================================================
        // GOOGLE SHEETS CLOUD SYNC JS CONTROLLER
        // =========================================================================
        async function openGoogleSheetsModal() {
            document.getElementById('googleSheetsModal').classList.remove('hidden');
            await loadGoogleSheetsConfig();
        }

        function closeGoogleSheetsModal() {
            document.getElementById('googleSheetsModal').classList.add('hidden');
        }

        async function loadGoogleSheetsConfig() {
            try {
                const res = await fetch('../api/api.php?action=get_google_sheets_config');
                const data = await res.json();
                if (data.status === 'success' && data.config) {
                    const cfg = data.config;
                    document.getElementById('gs_enabled').checked = !!cfg.enabled;
                    document.getElementById('gs_webhook_url').value = cfg.webhook_url || '';
                    document.getElementById('gs_sheet_url').value = cfg.sheet_url || '';
                    document.getElementById('gs_auto_sync').checked = cfg.auto_sync_on_register !== false;
                    
                    document.getElementById('gs_stat_last_sync').innerText = cfg.last_sync || 'ยังไม่เคยซิงก์';
                    document.getElementById('gs_stat_count').innerText = (cfg.last_sync_count || 0) + ' ท่าน';

                    const pill = document.getElementById('gs_status_pill');
                    const statStatus = document.getElementById('gs_stat_status');
                    if (cfg.enabled && cfg.webhook_url) {
                        pill.className = 'text-[10px] font-mono px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 font-bold';
                        pill.innerText = 'ONLINE (เชื่อมต่อแล้ว)';
                        statStatus.className = 'text-xs font-bold text-emerald-400 mt-1';
                        statStatus.innerText = 'เชื่อมต่อสำเร็จ';
                    } else if (cfg.webhook_url) {
                        pill.className = 'text-[10px] font-mono px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/40 font-bold';
                        pill.innerText = 'PAUSED (ปิดชั่วคราว)';
                        statStatus.className = 'text-xs font-bold text-amber-400 mt-1';
                        statStatus.innerText = 'ปิดการทำงาน';
                    } else {
                        pill.className = 'text-[10px] font-mono px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-400 border border-slate-700';
                        pill.innerText = 'STANDBY (รอตั้งค่า)';
                        statStatus.className = 'text-xs font-bold text-slate-400 mt-1';
                        statStatus.innerText = 'ยังไม่ได้ตั้งค่า';
                    }
                }
            } catch (err) {
                console.error("Failed to load Google Sheets config", err);
            }
        }

        async function saveGoogleSheetsConfig() {
            const btn = document.getElementById('btnSaveGS');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...';

            const payload = {
                enabled: document.getElementById('gs_enabled').checked,
                webhook_url: document.getElementById('gs_webhook_url').value.trim(),
                sheet_url: document.getElementById('gs_sheet_url').value.trim(),
                auto_sync_on_register: document.getElementById('gs_auto_sync').checked
            };

            try {
                const res = await fetch('../api/api.php?action=save_google_sheets_config', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'บันทึกการตั้งค่า Google Sheets สำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    await loadGoogleSheetsConfig();
                } else {
                    Swal.fire('ผิดพลาด', data.message, 'error');
                }
            } catch (err) {
                Swal.fire('ผิดพลาด', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> บันทึกการตั้งค่า';
            }
        }

        async function testGoogleSheetsWebhook() {
            const url = document.getElementById('gs_webhook_url').value.trim();
            if (!url) {
                Swal.fire('กรุณาระบุ URL', 'โปรดวาง Google Apps Script Web App URL ก่อนทดสอบ', 'warning');
                return;
            }

            const btn = document.getElementById('btnTestGS');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังทดสอบ...';

            try {
                const res = await fetch('../api/api.php?action=test_google_sheets_webhook', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ webhook_url: url })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'ติดต่อ Google Apps Script สำเร็จ!',
                        text: data.message,
                        confirmButtonColor: '#059669'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ทดสอบไม่สำเร็จ',
                        text: data.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            } catch (err) {
                Swal.fire('เกิดข้อผิดพลาด', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-plug text-cyan-400"></i> ทดสอบการเชื่อมต่อ';
            }
        }

        async function syncAllToGoogleSheets() {
            const url = document.getElementById('gs_webhook_url').value.trim();
            if (!url) {
                Swal.fire('ยังไม่ได้ระบุ URL', 'กรุณาระบุ Google Apps Script Web App URL และบันทึกการตั้งค่าก่อนทำการ Sync', 'warning');
                return;
            }

            const result = await Swal.fire({
                title: 'ยืนยันการ Sync ข้อมูลทั้งหมด?',
                text: 'ระบบจะส่งรายชื่อผู้สมัครทั้งหมด (' + participantsData.length + ' ท่าน) ขึ้นไปยัง Google Sheets ทันที',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#475569',
                confirmButtonText: '<i class="fa-solid fa-cloud-arrow-up mr-1"></i> ยืนยันการ Sync',
                cancelButtonText: 'ยกเลิก'
            });

            if (!result.isConfirmed) return;

            const btn = document.getElementById('btnSyncAllGS');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังส่งข้อมูล...';

            try {
                const res = await fetch('../api/api.php?action=sync_all_to_google_sheets', {
                    method: 'POST'
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sync สำเร็จเรียบร้อย!',
                        text: `อัปเดตข้อมูลขึ้น Google Sheets แล้วทั้งหมด ${data.synced_count} ท่าน`,
                        confirmButtonColor: '#059669'
                    });
                    await loadGoogleSheetsConfig();
                } else {
                    Swal.fire('ผิดพลาด', data.message, 'error');
                }
            } catch (err) {
                Swal.fire('เกิดข้อผิดพลาด', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Sync ข้อมูลทั้งหมดขึ้น Google Sheet';
            }
        }

        function openTargetSheet() {
            const url = document.getElementById('gs_sheet_url').value.trim();
            if (url) {
                window.open(url, '_blank');
            } else {
                Swal.fire('ยังไม่มีลิงก์ชีต', 'กรุณาวางลิงก์ไฟล์ Google Sheet ในช่องก่อนกดเปิด', 'info');
            }
        }

        function toggleGSGuide() {
            const content = document.getElementById('gsGuideContent');
            const chevron = document.getElementById('gsGuideChevron');
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        async function copyAppsScriptCode() {
            try {
                const res = await fetch('../google_sheets_script/Code.gs');
                const text = await res.text();
                await navigator.clipboard.writeText(text);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'คัดลอกโค้ด Code.gs สำเร็จ พร้อมนำไปวางใน Apps Script',
                    showConfirmButton: false,
                    timer: 2000
                });
            } catch (err) {
                Swal.fire('คัดลอกไม่สำเร็จ', 'ท่านสามารถเปิดไฟล์ ../google_sheets_script/Code.gs เพื่อคัดลอกด้วยตนเองได้ครับ', 'info');
            }
        }

        async function triggerLineTestNotify(btn) {
            const originalHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังส่งไปยัง LINE...';
            }

            try {
                const res = await fetch('../api/api.php?action=test_line_notify');
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'ส่งแจ้งเตือน LINE สำเร็จ!',
                        text: 'ระบบส่งข้อความทดสอบผ่าน LINE Messaging API เรียบร้อยแล้ว (HTTP 200) กรุณาตรวจสอบในโทรศัพท์มือถือของท่าน',
                        confirmButtonColor: '#10b981'
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ส่งไม่สำเร็จ',
                        text: data.message || 'โปรดตรวจสอบ Channel Access Token หรือการตั้งค่าใน LINE Developers',
                        confirmButtonColor: '#f59e0b'
                    });
                }
            } catch (err) {
                Swal.fire('เกิดข้อผิดพลาด', err.message, 'error');
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            }
        }

        // =====================================================================
        // LIVE PAGE CONTENT CMS LOGIC
        // =====================================================================
        function switchCmsTab(tabId) {
            document.querySelectorAll('.cms-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.cms-tab-btn').forEach(btn => {
                btn.className = 'cms-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5';
            });

            const activeTab = document.getElementById(tabId);
            if (activeTab) activeTab.classList.remove('hidden');

            const activeBtn = document.getElementById('tabBtn_' + tabId);
            if (activeBtn) {
                activeBtn.className = 'cms-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-brand/20 text-brand-light border border-brand/30';
            }
        }

        function addNewModuleRow() {
            const container = document.getElementById('modulesContainer');
            const count = container.querySelectorAll('.module-item').length + 1;
            const code = 'MOD-' + String(count).padStart(2, '0');
            const div = document.createElement('div');
            div.className = 'module-item p-4 rounded-2xl bg-white/[0.02] border border-white/10 relative space-y-3';
            div.innerHTML = `
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-brand/20 text-brand text-xs font-bold flex items-center justify-center">${count}</span>
                        <input type="text" class="module-code px-3 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-xs text-brand font-mono font-bold w-24" value="${code}">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" class="module-hours px-3 py-1.5 rounded-lg bg-slate-900 border border-white/10 text-xs text-amber-400 font-bold w-24 text-right" placeholder="เช่น 2.5 ชม." value="2.5 ชม.">
                        <button type="button" onclick="this.closest('.module-item').remove()" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition">
                            <i class="fas fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <input type="text" class="module-name w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-xs font-bold text-white mb-2" placeholder="ชื่อโมดูลการเรียนรู้">
                    <textarea class="module-desc w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-xs text-slate-300 resize-y" rows="2" placeholder="คำอธิบายรายละเอียดหัวข้อย่อย"></textarea>
                </div>
            `;
            container.appendChild(div);
        }

        async function loadSiteContentFromBackend() {
            try {
                const res = await fetch('../api/api.php?action=get_site_content');
                const result = await res.json();
                if (result.status === 'success' && result.data) {
                    const d = result.data;
                    if (document.getElementById('cms_project_title')) document.getElementById('cms_project_title').value = d.project_title || '';
                    if (document.getElementById('cms_project_subtitle')) document.getElementById('cms_project_subtitle').value = d.project_subtitle || '';
                    if (document.getElementById('cms_full_title')) document.getElementById('cms_full_title').value = d.full_title || '';
                    if (document.getElementById('cms_institution')) document.getElementById('cms_institution').value = d.institution || '';
                    if (document.getElementById('cms_coordinator_name')) document.getElementById('cms_coordinator_name').value = d.coordinator_name || '';
                    if (document.getElementById('cms_coordinator_title')) document.getElementById('cms_coordinator_title').value = d.coordinator_title || '';
                    if (document.getElementById('cms_coordinator_phone')) document.getElementById('cms_coordinator_phone').value = d.coordinator_phone || '';
                    if (document.getElementById('cms_coordinator_email')) document.getElementById('cms_coordinator_email').value = d.coordinator_email || '';
                    if (document.getElementById('cms_workshop_dates')) document.getElementById('cms_workshop_dates').value = d.workshop_dates || '';
                    if (document.getElementById('cms_workshop_duration')) document.getElementById('cms_workshop_duration').value = d.workshop_duration || '';
                    if (document.getElementById('cms_workshop_venue')) document.getElementById('cms_workshop_venue').value = d.workshop_venue || '';
                    if (document.getElementById('cms_target_participants')) document.getElementById('cms_target_participants').value = d.target_participants || 40;
                    if (document.getElementById('cms_fee_note')) document.getElementById('cms_fee_note').value = d.fee_note || '';
                    if (document.getElementById('cms_line_oa_id')) document.getElementById('cms_line_oa_id').value = d.line_oa_id || '';
                    if (document.getElementById('cms_line_oa_name')) document.getElementById('cms_line_oa_name').value = d.line_oa_name || '';
                    if (document.getElementById('cms_hero_alert_text')) document.getElementById('cms_hero_alert_text').value = d.hero_alert_text || '';

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'โหลดเนื้อหาหน้าเว็บล่าสุดสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            } catch (err) {
                Swal.fire('ข้อผิดพลาด', err.message, 'error');
            }
        }

        async function saveSiteContentToBackend() {
            const btn = document.getElementById('btnSaveSiteContent');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...';

            // Extract modules
            const moduleItems = document.querySelectorAll('#modulesContainer .module-item');
            const modules = [];
            moduleItems.forEach((m, idx) => {
                modules.push({
                    id: idx + 1,
                    code: m.querySelector('.module-code')?.value || ('MOD-0' + (idx + 1)),
                    name: m.querySelector('.module-name')?.value || '',
                    desc: m.querySelector('.module-desc')?.value || '',
                    hours: m.querySelector('.module-hours')?.value || '2.5 ชม.'
                });
            });

            // Extract tracks
            const trackItems = document.querySelectorAll('#tracksContainer .track-item');
            const tracks = [];
            trackItems.forEach((t, idx) => {
                tracks.push({
                    code: t.querySelector('.track-code')?.value || ('Track ' + String.fromCharCode(65 + idx)),
                    name: t.querySelector('.track-name')?.value || '',
                    desc: t.querySelector('.track-desc')?.value || '',
                    icon: t.querySelector('.track-icon')?.value || 'fa-solid fa-microchip',
                    badge: t.querySelector('.track-badge')?.value || ''
                });
            });

            const payload = {
                project_title: document.getElementById('cms_project_title')?.value || 'Edge xAI : 4DAgriEnvi',
                project_subtitle: document.getElementById('cms_project_subtitle')?.value || '',
                full_title: document.getElementById('cms_full_title')?.value || '',
                institution: document.getElementById('cms_institution')?.value || '',
                coordinator_name: document.getElementById('cms_coordinator_name')?.value || '',
                coordinator_title: document.getElementById('cms_coordinator_title')?.value || '',
                coordinator_phone: document.getElementById('cms_coordinator_phone')?.value || '',
                coordinator_email: document.getElementById('cms_coordinator_email')?.value || '',
                workshop_dates: document.getElementById('cms_workshop_dates')?.value || '',
                workshop_duration: document.getElementById('cms_workshop_duration')?.value || '',
                workshop_venue: document.getElementById('cms_workshop_venue')?.value || '',
                target_participants: parseInt(document.getElementById('cms_target_participants')?.value || 40, 10),
                fee_note: document.getElementById('cms_fee_note')?.value || '',
                line_oa_id: document.getElementById('cms_line_oa_id')?.value || '',
                line_oa_name: document.getElementById('cms_line_oa_name')?.value || '',
                hero_alert_text: document.getElementById('cms_hero_alert_text')?.value || '',
                modules: modules,
                tracks: tracks
            };

            try {
                const res = await fetch('../api/api.php?action=save_site_content', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกเนื้อหาสำเร็จ!',
                        text: 'ข้อมูลหน้าเว็บไซต์และ RAG Mascot Bot ได้รับการอัปเดตเรียบร้อยแล้ว',
                        confirmButtonColor: '#10b981'
                    });
                } else {
                    Swal.fire('บันทึกไม่สำเร็จ', data.message || 'เกิดข้อผิดพลาด', 'error');
                }
            } catch (err) {
                Swal.fire('ข้อผิดพลาด', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }

        // =====================================================================
        // DATABASE CRUD ENGINE JAVASCRIPT LOGIC
        // =====================================================================
        let activeDbTable = 'participants';
        let dbTablesMeta = {};
        let dbCurrentPage = 1;
        let dbLimit = 50;
        let dbSearchQuery = '';
        let currentTableCols = [];
        let currentTableRows = [];
        let currentTotalRows = 0;
        let searchDebounceTimer = null;

        async function loadDbTablesMeta() {
            try {
                const res = await fetch('../api/api.php?action=db_get_tables');
                const data = await res.json();
                if (data.status === 'success' && data.tables) {
                    dbTablesMeta = data.tables;
                    for (const tbl in dbTablesMeta) {
                        const badge = document.getElementById('badge_count_' + tbl);
                        if (badge) {
                            badge.textContent = Number(dbTablesMeta[tbl].row_count || 0).toLocaleString();
                        }
                    }
                }
            } catch (err) {
                console.error('loadDbTablesMeta error:', err);
            }
        }

        function selectDbTable(tblName) {
            activeDbTable = tblName;
            dbCurrentPage = 1;
            dbSearchQuery = '';
            const searchInput = document.getElementById('dbSearchInput');
            if (searchInput) searchInput.value = '';

            // Update tab styles
            document.querySelectorAll('.db-tab-btn').forEach(btn => {
                btn.className = 'db-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-white/5 text-slate-400 hover:text-white border border-white/5 flex items-center gap-2';
            });
            const activeBtn = document.getElementById('dbTab_' + tblName);
            if (activeBtn) {
                activeBtn.className = 'db-tab-btn px-4 py-2 rounded-xl text-xs font-bold transition bg-blue-500/20 text-blue-300 border border-blue-500/30 flex items-center gap-2';
            }

            loadDbTableRows();
        }

        function refreshCurrentTable() {
            loadDbTablesMeta();
            loadDbTableRows();
        }

        async function loadDbTableRows() {
            const tableBody = document.getElementById('dbTableBody');
            const statusText = document.getElementById('dbTableStatusText');
            if (statusText) statusText.textContent = 'กำลังโหลด ' + activeDbTable + '...';

            if (tableBody) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="15" class="p-8 text-center text-slate-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i> กำลังดึงข้อมูลจากตาราง ${activeDbTable}...
                        </td>
                    </tr>
                `;
            }

            const offset = (dbCurrentPage - 1) * dbLimit;
            const url = `../api/api.php?action=db_get_table_rows&table=${encodeURIComponent(activeDbTable)}&search=${encodeURIComponent(dbSearchQuery)}&limit=${dbLimit}&offset=${offset}&order_by=id&order_dir=DESC`;

            try {
                const res = await fetch(url);
                const data = await res.json();
                if (data.status === 'success') {
                    currentTableCols = data.columns || [];
                    currentTableRows = data.rows || [];
                    currentTotalRows = data.total_count || 0;
                    renderDbTable(data);
                } else {
                    if (tableBody) {
                        tableBody.innerHTML = `<tr><td colspan="15" class="p-8 text-center text-rose-400">${data.message || 'เกิดข้อผิดพลาดในการโหลด'}</td></tr>`;
                    }
                }
            } catch (err) {
                if (tableBody) {
                    tableBody.innerHTML = `<tr><td colspan="15" class="p-8 text-center text-rose-400">${err.message}</td></tr>`;
                }
            }
        }

        function renderDbTable(data) {
            const thead = document.getElementById('dbTableHead');
            const tbody = document.getElementById('dbTableBody');
            const cols = data.columns || [];
            const rows = data.rows || [];

            // 1. Build Thead
            let headHtml = `<tr>
                <th class="p-3 text-center w-24 sticky left-0 bg-[#0c1220] z-20 border-r border-white/10">จัดการ</th>`;
            cols.forEach(c => {
                headHtml += `<th class="p-3 font-mono text-[11px] whitespace-nowrap text-slate-300">
                    ${c.name}
                    <span class="text-[9px] text-slate-500 font-normal">(${c.type})</span>
                </th>`;
            });
            headHtml += `</tr>`;
            if (thead) thead.innerHTML = headHtml;

            // 2. Build Tbody
            if (rows.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${cols.length + 1}" class="p-8 text-center text-slate-500">ไม่มีข้อมูลในตารางนี้ตามเงื่อนไขที่ระบุ</td></tr>`;
            } else {
                let bodyHtml = '';
                rows.forEach((r, idx) => {
                    bodyHtml += `<tr class="hover:bg-white/[0.03] transition-colors border-b border-white/5">`;
                    // Sticky actions column
                    bodyHtml += `<td class="p-3 text-center sticky left-0 bg-[#0c1220] z-10 border-r border-white/10 whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5">
                            <button type="button" onclick="openEditRowModal(${idx})" title="แก้ไขระเบียนนี้" class="p-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 transition">
                                <i class="fas fa-pencil text-xs"></i>
                            </button>
                            <button type="button" onclick="confirmDeleteDbRow(${r.id})" title="ลบระเบียนนี้" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition">
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </td>`;

                    // Columns
                    cols.forEach(c => {
                        let val = r[c.name];
                        if (val === null || val === undefined) {
                            val = '<span class="text-slate-600 italic">NULL</span>';
                        } else if (typeof val === 'string' && val.length > 50) {
                            val = `<span title="${escapeHtml(val)}">${escapeHtml(val.substring(0, 50))}...</span>`;
                        } else {
                            val = escapeHtml(String(val));
                        }

                        // Special color for ID
                        if (c.name === 'id') {
                            bodyHtml += `<td class="p-3 font-mono font-bold text-blue-400 whitespace-nowrap">#${val}</td>`;
                        } else {
                            bodyHtml += `<td class="p-3 whitespace-nowrap text-slate-300 font-sans">${val}</td>`;
                        }
                    });

                    bodyHtml += `</tr>`;
                });
                tbody.innerHTML = bodyHtml;
            }

            // 3. Update Pagination Text
            const start = currentTotalRows === 0 ? 0 : (data.offset + 1);
            const end = Math.min(data.offset + data.limit, currentTotalRows);
            const pagInfo = document.getElementById('dbPaginationInfo');
            if (pagInfo) {
                pagInfo.textContent = `แสดง ${start.toLocaleString()} ถึง ${end.toLocaleString()} จากทั้งหมด ${currentTotalRows.toLocaleString()} รายการ`;
            }

            const pageText = document.getElementById('dbCurrentPageText');
            if (pageText) pageText.textContent = dbCurrentPage;

            const btnPrev = document.getElementById('btnDbPrevPage');
            const btnNext = document.getElementById('btnDbNextPage');
            if (btnPrev) btnPrev.disabled = (dbCurrentPage <= 1);
            if (btnNext) btnNext.disabled = (end >= currentTotalRows);

            const statusText = document.getElementById('dbTableStatusText');
            if (statusText) statusText.textContent = `${activeDbTable} (${currentTotalRows} รายการ)`;
        }

        function handleDbSearch(e) {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                dbSearchQuery = (document.getElementById('dbSearchInput')?.value || '').trim();
                dbCurrentPage = 1;
                loadDbTableRows();
            }, 350);
        }

        function changeDbLimit(val) {
            dbLimit = parseInt(val, 10) || 50;
            dbCurrentPage = 1;
            loadDbTableRows();
        }

        function navigateDbPage(dir) {
            dbCurrentPage += dir;
            if (dbCurrentPage < 1) dbCurrentPage = 1;
            loadDbTableRows();
        }

        // --- DYNAMIC ADD / EDIT MODAL ---
        function openAddRowModal() {
            document.getElementById('modalTableBadge').textContent = activeDbTable.toUpperCase();
            document.getElementById('modalRowTitle').textContent = `เพิ่มระเบียนใหม่ในตาราง ${activeDbTable}`;
            const container = document.getElementById('dbModalFormFields');
            container.innerHTML = '';

            currentTableCols.forEach(c => {
                const isId = (c.name === 'id');
                const div = document.createElement('div');
                div.className = isId ? 'md:col-span-2' : '';
                
                let inputHtml = '';
                if (isId) {
                    inputHtml = `
                        <label class="block text-xs font-bold text-slate-400 mb-1">
                            ${c.name} <span class="text-[10px] text-blue-400">(Auto-Increment / เว้นว่างไว้สำหรับระเบียนใหม่)</span>
                        </label>
                        <input type="text" name="${c.name}" placeholder="(เว้นว่างไว้เพื่อสร้าง ID อัตโนมัติ)" class="w-full px-3 py-2 rounded-xl bg-slate-900/60 border border-white/10 text-slate-400 text-xs font-mono">
                    `;
                } else if (c.type.includes('TEXT') && c.name.includes('desc') || c.name.includes('content') || c.name.includes('comment')) {
                    inputHtml = `
                        <label class="block text-xs font-bold text-slate-300 mb-1">${c.name} <span class="text-[10px] text-slate-500">(${c.type})</span></label>
                        <textarea name="${c.name}" rows="3" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-blue-400 focus:outline-none"></textarea>
                    `;
                } else {
                    inputHtml = `
                        <label class="block text-xs font-bold text-slate-300 mb-1">${c.name} <span class="text-[10px] text-slate-500">(${c.type})</span></label>
                        <input type="text" name="${c.name}" value="${c.dflt_value ? c.dflt_value.replace(/'/g, '') : ''}" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-blue-400 focus:outline-none">
                    `;
                }

                div.innerHTML = inputHtml;
                container.appendChild(div);
            });

            document.getElementById('dbRowModal').classList.remove('hidden');
        }

        function openEditRowModal(rowIndex) {
            const row = currentTableRows[rowIndex];
            if (!row) return;

            document.getElementById('modalTableBadge').textContent = activeDbTable.toUpperCase();
            document.getElementById('modalRowTitle').textContent = `แก้ไขข้อมูลระเบียน ID #${row.id} (${activeDbTable})`;
            const container = document.getElementById('dbModalFormFields');
            container.innerHTML = '';

            currentTableCols.forEach(c => {
                const isId = (c.name === 'id');
                const val = (row[c.name] !== null && row[c.name] !== undefined) ? row[c.name] : '';
                const div = document.createElement('div');
                div.className = isId ? 'md:col-span-2' : '';
                
                let inputHtml = '';
                if (isId) {
                    inputHtml = `
                        <label class="block text-xs font-bold text-slate-400 mb-1">
                            ${c.name} <span class="text-[10px] text-blue-400">(Primary Key ห้ามเปลี่ยน)</span>
                        </label>
                        <input type="text" name="${c.name}" value="${val}" readonly class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-white/10 text-blue-400 font-mono font-bold text-xs cursor-not-allowed">
                    `;
                } else if ((c.type.includes('TEXT')) && (c.name.includes('desc') || c.name.includes('content') || c.name.includes('comment'))) {
                    inputHtml = `
                        <label class="block text-xs font-bold text-slate-300 mb-1">${c.name} <span class="text-[10px] text-slate-500">(${c.type})</span></label>
                        <textarea name="${c.name}" rows="3" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-blue-400 focus:outline-none">${escapeHtml(String(val))}</textarea>
                    `;
                } else {
                    inputHtml = `
                        <label class="block text-xs font-bold text-slate-300 mb-1">${c.name} <span class="text-[10px] text-slate-500">(${c.type})</span></label>
                        <input type="text" name="${c.name}" value="${escapeHtml(String(val))}" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:border-blue-400 focus:outline-none">
                    `;
                }

                div.innerHTML = inputHtml;
                container.appendChild(div);
            });

            document.getElementById('dbRowModal').classList.remove('hidden');
        }

        function closeDbRowModal() {
            document.getElementById('dbRowModal').classList.add('hidden');
        }

        async function submitDbRowForm(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const rowData = {};
            formData.forEach((value, key) => {
                rowData[key] = value;
            });

            const btn = document.getElementById('btnSaveDbRow');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...';

            try {
                const res = await fetch('../api/api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'db_save_row',
                        table: activeDbTable,
                        data: rowData
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    closeDbRowModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadDbTablesMeta();
                    loadDbTableRows();
                } else {
                    Swal.fire('บันทึกไม่สำเร็จ', data.message || 'เกิดข้อผิดพลาด', 'error');
                }
            } catch (err) {
                Swal.fire('ข้อผิดพลาด', err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }

        function confirmDeleteDbRow(id) {
            Swal.fire({
                title: 'ยืนยันการลบระเบียน?',
                text: `คุณต้องการลบข้อมูล ID #${id} จากตาราง ${activeDbTable} หรือไม่? (การดำเนินการนี้ไม่สามารถยกเลิกได้)`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#4b5563',
                confirmButtonText: '<i class="fas fa-trash-can mr-1"></i> ใช่, ลบข้อมูลทันที',
                cancelButtonText: 'ยกเลิก'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch('../api/api.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                action: 'db_delete_row',
                                table: activeDbTable,
                                id: id
                            })
                        });
                        const data = await res.json();
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบข้อมูลสำเร็จ!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadDbTablesMeta();
                            loadDbTableRows();
                        } else {
                            Swal.fire('ลบไม่สำเร็จ', data.message || 'เกิดข้อผิดพลาด', 'error');
                        }
                    } catch (err) {
                        Swal.fire('ข้อผิดพลาด', err.message, 'error');
                    }
                }
            });
        }

        // --- SQL PLAYGROUND ---
        function toggleSqlPlayground() {
            const el = document.getElementById('sqlPlaygroundConsole');
            if (el) el.classList.toggle('hidden');
        }

        async function runCustomSqlQuery() {
            const sql = (document.getElementById('sqlCustomQueryInput')?.value || '').trim();
            if (!sql) return;

            const resBox = document.getElementById('sqlQueryResultBox');
            resBox.classList.remove('hidden');
            resBox.textContent = 'กำลังประมวลผลคำสั่ง SQL...';

            try {
                const res = await fetch('../api/api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'db_execute_sql',
                        sql: sql
                    })
                });
                const data = await res.json();
                resBox.textContent = JSON.stringify(data, null, 2);
                if (data.status === 'success') {
                    loadDbTablesMeta();
                    loadDbTableRows();
                }
            } catch (err) {
                resBox.textContent = 'Error: ' + err.message;
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Auto-initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            loadDbTablesMeta();
            loadDbTableRows();
        });
    </script>

