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
    </script>
