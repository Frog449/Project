// JavaScript for Backend Admin Dashboard (Full CRUD Management & Interactive UI)
let cacheData = {
    courses: [],
    teachers: [],
    portfolios: [],
    users: [],
    contacts: [],
    news: [],
    info: null
};

document.addEventListener('DOMContentLoaded', () => {
    initToastContainer();
    fetchAllData();
    setupFormListeners();
    setupSearchListeners();
});

function initToastContainer() {
    if (!document.getElementById('toastContainer')) {
        const div = document.createElement('div');
        div.id = 'toastContainer';
        document.body.appendChild(div);
    }
}

function showToast(message, type = 'success') {
    initToastContainer();
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    let iconClass = 'fa-check-circle';
    if (type === 'error') iconClass = 'fa-circle-xmark';
    if (type === 'info') iconClass = 'fa-circle-info';
    
    toast.innerHTML = `
        <i class="fa-solid ${iconClass} toast-icon"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function fetchAllData() {
    fetchStats();
    fetchCoursesAdmin();
    fetchTeachersAdmin();
    fetchPortfoliosAdmin();
    fetchUsersAdmin();
    fetchContactsLog();
    fetchNewsAdmin();
    fetchInfoAdmin();
}

// Modal Helpers
function openAdminModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.remove('hidden');
}

function closeAdminModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('hidden');
}

// Stats
async function fetchStats() {
    try {
        const res = await fetch('/api/index.php?endpoint=stats');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            if (document.getElementById('statCourses')) document.getElementById('statCourses').innerText = json.data.courses_count || 0;
            if (document.getElementById('statTeachers')) document.getElementById('statTeachers').innerText = json.data.teachers_count || 0;
            if (document.getElementById('statPortfolios')) document.getElementById('statPortfolios').innerText = json.data.portfolios_count || 0;
            if (document.getElementById('statContacts')) document.getElementById('statContacts').innerText = json.data.contacts_count || 0;
            if (document.getElementById('statUsers')) document.getElementById('statUsers').innerText = json.data.users_count || 0;
            if (document.getElementById('statNews')) document.getElementById('statNews').innerText = json.data.news_count || 0;
        }
    } catch (e) { console.error('Fetch Stats Error:', e); }
}

// Render Helper Functions
function renderCoursesTable(items) {
    const tbody = document.getElementById('adminCoursesTable');
    if (!tbody) return;
    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลหลักสูตร</td></tr>`;
        return;
    }
    tbody.innerHTML = items.map(c => `
        <tr>
            <td>${c.id}</td>
            <td><span class="tag tag-green">${c.level}</span></td>
            <td><strong>${escapeHtml(c.title)}</strong></td>
            <td>${escapeHtml(c.duration)}</td>
            <td>${c.credits || 0} หน่วยกิต</td>
            <td>${escapeHtml(c.description || '-')}</td>
            <td>
                <div class="action-btns">
                    <button class="btn btn-warning btn-sm" onclick="editCourseModal(${c.id})"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteItem('courses_delete', ${c.id})"><i class="fa-solid fa-trash"></i> ลบ</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderTeachersTable(items) {
    const tbody = document.getElementById('adminTeachersTable');
    if (!tbody) return;
    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลคณาจารย์</td></tr>`;
        return;
    }
    tbody.innerHTML = items.map(t => `
        <tr>
            <td>${t.id}</td>
            <td><img src="${t.image}" alt="${escapeHtml(t.name)}" class="table-thumb" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&auto=format&fit=crop&q=80'"></td>
            <td><strong>${escapeHtml(t.name)}</strong></td>
            <td>${escapeHtml(t.position)}</td>
            <td>${escapeHtml(t.degree || '-')}</td>
            <td>${escapeHtml(t.email || '-')}</td>
            <td>
                <div class="action-btns">
                    <button class="btn btn-warning btn-sm" onclick="editTeacherModal(${t.id})"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteItem('teachers_delete', ${t.id})"><i class="fa-solid fa-trash"></i> ลบ</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderPortfoliosTable(items) {
    const tbody = document.getElementById('adminPortfoliosTable');
    if (!tbody) return;
    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลผลงานนักศึกษา</td></tr>`;
        return;
    }
    tbody.innerHTML = items.map(p => `
        <tr>
            <td>${p.id}</td>
            <td><img src="${p.image_url}" alt="${escapeHtml(p.title)}" class="table-thumb" onerror="this.src='https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80'"></td>
            <td><strong>${escapeHtml(p.title)}</strong></td>
            <td>${escapeHtml(p.student_name)}</td>
            <td><span class="tag tag-green">${p.level}</span></td>
            <td>${escapeHtml(p.category || '-')}</td>
            <td>
                <div class="action-btns">
                    <button class="btn btn-warning btn-sm" onclick="editPortfolioModal(${p.id})"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteItem('portfolios_delete', ${p.id})"><i class="fa-solid fa-trash"></i> ลบ</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderUsersTable(items) {
    const tbody = document.getElementById('adminUsersTable');
    if (!tbody) return;
    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลสมาชิก</td></tr>`;
        return;
    }
    tbody.innerHTML = items.map(u => `
        <tr>
            <td>${u.id}</td>
            <td><strong>@${escapeHtml(u.username)}</strong></td>
            <td>${escapeHtml(u.fullname)}</td>
            <td>${escapeHtml(u.email)}</td>
            <td>${escapeHtml(u.phone || '-')}</td>
            <td><span class="tag ${u.role === 'admin' ? 'tag-green' : 'tag-gray'}">${u.role}</span></td>
            <td>${u.created_at ? u.created_at.substring(0, 10) : '-'}</td>
            <td>
                <div class="action-btns">
                    <button class="btn btn-warning btn-sm" onclick="editUserModal(${u.id})"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                    ${u.username !== 'admin' ? `<button class="btn btn-danger btn-sm" onclick="deleteItem('users_delete', ${u.id})"><i class="fa-solid fa-trash"></i> ลบ</button>` : '<span class="tag tag-gray">สงวนไว้</span>'}
                </div>
            </td>
        </tr>
    `).join('');
}

function renderContactsTable(items) {
    const tbody = document.getElementById('contactsTable');
    if (!tbody) return;
    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่มีข้อความสอบถามในขณะนี้</td></tr>`;
        return;
    }
    tbody.innerHTML = items.map(c => `
        <tr>
            <td>${c.id}</td>
            <td><strong>${escapeHtml(c.name)}</strong></td>
            <td>${escapeHtml(c.email)}</td>
            <td>${escapeHtml(c.subject || '-')}</td>
            <td>${escapeHtml(c.message)}</td>
            <td>${c.created_at ? c.created_at.substring(0, 10) : '-'}</td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="deleteItem('contacts_delete', ${c.id})"><i class="fa-solid fa-trash"></i> ลบ</button>
            </td>
        </tr>
    `).join('');
}

function renderNewsTable(items) {
    const tbody = document.getElementById('adminNewsTable');
    if (!tbody) return;
    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color: var(--text-muted); padding:30px;">ไม่พบข้อมูลข่าวสาร</td></tr>`;
        return;
    }
    tbody.innerHTML = items.map(n => `
        <tr>
            <td>${n.id}</td>
            <td><img src="${n.image_url}" alt="${escapeHtml(n.title)}" class="table-thumb" onerror="this.src='https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop&q=80'"></td>
            <td><strong>${escapeHtml(n.title)}</strong></td>
            <td><span class="tag tag-green">${escapeHtml(n.category || 'ข่าวสาร')}</span></td>
            <td>${escapeHtml(n.author || 'ฝ่ายประชาสัมพันธ์')}</td>
            <td>${n.post_date || '-'}</td>
            <td>
                <div class="action-btns">
                    <button class="btn btn-warning btn-sm" onclick="editNewsModal(${n.id})"><i class="fa-solid fa-pen-to-square"></i> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteItem('news_delete', ${n.id})"><i class="fa-solid fa-trash"></i> ลบ</button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Fetch Functions
async function fetchCoursesAdmin() {
    try {
        const res = await fetch('/api/index.php?endpoint=courses');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            cacheData.courses = json.data;
            renderCoursesTable(cacheData.courses);
        }
    } catch (e) { console.error('Fetch Courses Error:', e); }
}

async function fetchTeachersAdmin() {
    try {
        const res = await fetch('/api/index.php?endpoint=teachers');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            cacheData.teachers = json.data;
            renderTeachersTable(cacheData.teachers);
        }
    } catch (e) { console.error('Fetch Teachers Error:', e); }
}

async function fetchPortfoliosAdmin() {
    try {
        const res = await fetch('/api/index.php?endpoint=portfolios');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            cacheData.portfolios = json.data;
            renderPortfoliosTable(cacheData.portfolios);
        }
    } catch (e) { console.error('Fetch Portfolios Error:', e); }
}

async function fetchUsersAdmin() {
    try {
        const res = await fetch('/api/index.php?endpoint=users');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            cacheData.users = json.data;
            renderUsersTable(cacheData.users);
        }
    } catch (e) { console.error('Fetch Users Error:', e); }
}

async function fetchContactsLog() {
    try {
        const res = await fetch('/api/index.php?endpoint=contacts');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            cacheData.contacts = json.data;
            renderContactsTable(cacheData.contacts);
        }
    } catch (e) { console.error('Fetch Contacts Error:', e); }
}

async function fetchNewsAdmin() {
    try {
        const res = await fetch('/api/index.php?endpoint=news');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            cacheData.news = json.data;
            renderNewsTable(cacheData.news);
        }
    } catch (e) { console.error('Fetch News Error:', e); }
}

async function fetchInfoAdmin() {
    try {
        const res = await fetch('/api/index.php?endpoint=info');
        const json = await res.json();
        if (json.status === 'success' && json.data) {
            cacheData.info = json.data;
            populateInfoForm(json.data);
        }
    } catch (e) { console.error('Fetch Info Error:', e); }
}

function populateInfoForm(info) {
    if (!info) return;
    if (document.getElementById('infoNameTh')) document.getElementById('infoNameTh').value = info.name_th || '';
    if (document.getElementById('infoNameEn')) document.getElementById('infoNameEn').value = info.name_en || '';
    if (document.getElementById('infoCollegeTh')) document.getElementById('infoCollegeTh').value = info.college_th || '';
    if (document.getElementById('infoCollegeEn')) document.getElementById('infoCollegeEn').value = info.college_en || '';
    if (document.getElementById('infoSlogan')) document.getElementById('infoSlogan').value = info.slogan || '';
    if (document.getElementById('infoHistory')) document.getElementById('infoHistory').value = info.history || '';
    if (document.getElementById('infoVision')) document.getElementById('infoVision').value = info.vision || '';
    if (document.getElementById('infoMission')) document.getElementById('infoMission').value = info.mission || '';
    if (document.getElementById('infoEstYear')) document.getElementById('infoEstYear').value = info.established_year || 2537;
}

// Delete Handler
async function deleteItem(endpoint, id) {
    if (!confirm(`คุณต้องการลบรายการ ID ${id} ใช่หรือไม่?`)) return;
    try {
        const res = await fetch(`/api/index.php?endpoint=${endpoint}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const json = await res.json();
        if (json.status === 'success') {
            showToast(json.message, 'success');
            fetchAllData();
        } else {
            showToast(json.message || 'เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
        }
    } catch (e) {
        showToast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
    }
}

// Edit Modal Openers
function editCourseModal(id) {
    const item = cacheData.courses.find(c => c.id == id);
    if (!item) return;
    document.getElementById('editCourseId').value = item.id;
    document.getElementById('editCourseLevel').value = item.level;
    document.getElementById('editCourseTitle').value = item.title;
    document.getElementById('editCourseDuration').value = item.duration;
    document.getElementById('editCourseCredits').value = item.credits || 0;
    document.getElementById('editCourseDesc').value = item.description || '';
    openAdminModal('editCourseModal');
}

function editTeacherModal(id) {
    const item = cacheData.teachers.find(t => t.id == id);
    if (!item) return;
    document.getElementById('editTeacherId').value = item.id;
    document.getElementById('editTeacherName').value = item.name;
    document.getElementById('editTeacherPos').value = item.position;
    document.getElementById('editTeacherDegree').value = item.degree || '';
    document.getElementById('editTeacherExpertise').value = item.expertise || '';
    document.getElementById('editTeacherEmail').value = item.email || '';
    document.getElementById('editTeacherImage').value = item.image || '';
    if (document.getElementById('editTeacherPreview')) {
        document.getElementById('editTeacherPreview').src = item.image || '';
    }
    openAdminModal('editTeacherModal');
}

function editPortfolioModal(id) {
    const item = cacheData.portfolios.find(p => p.id == id);
    if (!item) return;
    document.getElementById('editPortId').value = item.id;
    document.getElementById('editPortTitle').value = item.title;
    document.getElementById('editPortStudent').value = item.student_name;
    document.getElementById('editPortLevel').value = item.level;
    document.getElementById('editPortCategory').value = item.category || '';
    document.getElementById('editPortImage').value = item.image_url || '';
    document.getElementById('editPortDesc').value = item.description || '';
    if (document.getElementById('editPortPreview')) {
        document.getElementById('editPortPreview').src = item.image_url || '';
    }
    openAdminModal('editPortfolioModal');
}

function editUserModal(id) {
    const item = cacheData.users.find(u => u.id == id);
    if (!item) return;
    document.getElementById('editUserId').value = item.id;
    document.getElementById('editUserUsername').value = item.username;
    document.getElementById('editUserFullname').value = item.fullname;
    document.getElementById('editUserEmail').value = item.email;
    document.getElementById('editUserPhone').value = item.phone || '';
    document.getElementById('editUserRole').value = item.role || 'user';
    document.getElementById('editUserPassword').value = '';
    openAdminModal('editUserModal');
}

function editNewsModal(id) {
    const item = cacheData.news.find(n => n.id == id);
    if (!item) return;
    document.getElementById('editNewsId').value = item.id;
    document.getElementById('editNewsTitle').value = item.title;
    document.getElementById('editNewsContent').value = item.content || '';
    document.getElementById('editNewsAuthor').value = item.author || 'ฝ่ายประชาสัมพันธ์';
    document.getElementById('editNewsCategory').value = item.category || 'ข่าวประชาสัมพันธ์';
    document.getElementById('editNewsDate').value = item.post_date || '';
    document.getElementById('editNewsImage').value = item.image_url || '';
    if (document.getElementById('editNewsPreview')) {
        document.getElementById('editNewsPreview').src = item.image_url || '';
    }
    openAdminModal('editNewsModal');
}

// Form Listeners
function setupFormListeners() {
    // Add Course
    const courseForm = document.getElementById('addCourseForm');
    if (courseForm) {
        courseForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                level: document.getElementById('courseLevel').value,
                title: document.getElementById('courseTitle').value,
                duration: document.getElementById('courseDuration').value,
                credits: parseInt(document.getElementById('courseCredits').value) || 0,
                description: document.getElementById('courseDesc').value
            };
            await sendPostData('courses_add', data, 'addCourseModal', courseForm);
        });
    }

    // Edit Course
    const editCourseForm = document.getElementById('editCourseForm');
    if (editCourseForm) {
        editCourseForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                id: parseInt(document.getElementById('editCourseId').value),
                level: document.getElementById('editCourseLevel').value,
                title: document.getElementById('editCourseTitle').value,
                duration: document.getElementById('editCourseDuration').value,
                credits: parseInt(document.getElementById('editCourseCredits').value) || 0,
                description: document.getElementById('editCourseDesc').value
            };
            await sendPostData('courses_update', data, 'editCourseModal', editCourseForm);
        });
    }

    // Add Teacher
    const teacherForm = document.getElementById('addTeacherForm');
    if (teacherForm) {
        teacherForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                name: document.getElementById('teacherName').value,
                position: document.getElementById('teacherPos').value,
                degree: document.getElementById('teacherDegree').value,
                expertise: document.getElementById('teacherExpertise') ? document.getElementById('teacherExpertise').value : '',
                email: document.getElementById('teacherEmail').value,
                image: document.getElementById('teacherImage').value
            };
            await sendPostData('teachers_add', data, 'addTeacherModal', teacherForm);
        });
    }

    // Edit Teacher
    const editTeacherForm = document.getElementById('editTeacherForm');
    if (editTeacherForm) {
        editTeacherForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                id: parseInt(document.getElementById('editTeacherId').value),
                name: document.getElementById('editTeacherName').value,
                position: document.getElementById('editTeacherPos').value,
                degree: document.getElementById('editTeacherDegree').value,
                expertise: document.getElementById('editTeacherExpertise').value,
                email: document.getElementById('editTeacherEmail').value,
                image: document.getElementById('editTeacherImage').value
            };
            await sendPostData('teachers_update', data, 'editTeacherModal', editTeacherForm);
        });
    }

    // Add Portfolio
    const portForm = document.getElementById('addPortfolioForm');
    if (portForm) {
        portForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                title: document.getElementById('portTitle').value,
                student_name: document.getElementById('portStudent').value,
                level: document.getElementById('portLevel').value,
                category: document.getElementById('portCategory').value,
                image_url: document.getElementById('portImage').value,
                description: document.getElementById('portDesc').value
            };
            await sendPostData('portfolios_add', data, 'addPortfolioModal', portForm);
        });
    }

    // Edit Portfolio
    const editPortForm = document.getElementById('editPortfolioForm');
    if (editPortForm) {
        editPortForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                id: parseInt(document.getElementById('editPortId').value),
                title: document.getElementById('editPortTitle').value,
                student_name: document.getElementById('editPortStudent').value,
                level: document.getElementById('editPortLevel').value,
                category: document.getElementById('editPortCategory').value,
                image_url: document.getElementById('editPortImage').value,
                description: document.getElementById('editPortDesc').value
            };
            await sendPostData('portfolios_update', data, 'editPortfolioModal', editPortForm);
        });
    }

    // Add User
    const addUserForm = document.getElementById('addUserForm');
    if (addUserForm) {
        addUserForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                username: document.getElementById('userName').value,
                password: document.getElementById('userPassword').value,
                fullname: document.getElementById('userFullname').value,
                email: document.getElementById('userEmail').value,
                phone: document.getElementById('userPhone').value,
                role: document.getElementById('userRole').value
            };
            await sendPostData('users_add', data, 'addUserModal', addUserForm);
        });
    }

    // Edit User
    const editUserForm = document.getElementById('editUserForm');
    if (editUserForm) {
        editUserForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                id: parseInt(document.getElementById('editUserId').value),
                fullname: document.getElementById('editUserFullname').value,
                email: document.getElementById('editUserEmail').value,
                phone: document.getElementById('editUserPhone').value,
                role: document.getElementById('editUserRole').value,
                password: document.getElementById('editUserPassword').value
            };
            await sendPostData('users_update', data, 'editUserModal', editUserForm);
        });
    }

    // Add News
    const newsForm = document.getElementById('addNewsForm');
    if (newsForm) {
        newsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                title: document.getElementById('newsTitle').value,
                content: document.getElementById('newsContent').value,
                author: document.getElementById('newsAuthor').value,
                category: document.getElementById('newsCategory').value,
                post_date: document.getElementById('newsDate').value,
                image_url: document.getElementById('newsImage').value
            };
            await sendPostData('news_add', data, 'addNewsModal', newsForm);
        });
    }

    // Edit News
    const editNewsForm = document.getElementById('editNewsForm');
    if (editNewsForm) {
        editNewsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                id: parseInt(document.getElementById('editNewsId').value),
                title: document.getElementById('editNewsTitle').value,
                content: document.getElementById('editNewsContent').value,
                author: document.getElementById('editNewsAuthor').value,
                category: document.getElementById('editNewsCategory').value,
                post_date: document.getElementById('editNewsDate').value,
                image_url: document.getElementById('editNewsImage').value
            };
            await sendPostData('news_update', data, 'editNewsModal', editNewsForm);
        });
    }

    // Info Form
    const infoForm = document.getElementById('infoForm');
    if (infoForm) {
        infoForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                name_th: document.getElementById('infoNameTh').value,
                name_en: document.getElementById('infoNameEn').value,
                college_th: document.getElementById('infoCollegeTh').value,
                college_en: document.getElementById('infoCollegeEn').value,
                slogan: document.getElementById('infoSlogan').value,
                history: document.getElementById('infoHistory').value,
                vision: document.getElementById('infoVision').value,
                mission: document.getElementById('infoMission').value,
                established_year: parseInt(document.getElementById('infoEstYear').value) || 2537
            };
            try {
                const res = await fetch('/api/index.php?endpoint=info_update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const json = await res.json();
                if (json.status === 'success') {
                    showToast(json.message, 'success');
                    fetchInfoAdmin();
                } else {
                    showToast(json.message || 'เกิดข้อผิดพลาด', 'error');
                }
            } catch (err) {
                showToast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
            }
        });
    }
}

// Search & Filter Event Setup
function setupSearchListeners() {
    // Courses Search
    const searchCourse = document.getElementById('searchCourseInput');
    const filterLevel = document.getElementById('filterCourseLevel');
    if (searchCourse || filterLevel) {
        const handler = () => {
            const query = searchCourse ? searchCourse.value.toLowerCase() : '';
            const lvl = filterLevel ? filterLevel.value : 'all';
            const filtered = cacheData.courses.filter(c => {
                const matchQuery = c.title.toLowerCase().includes(query) || (c.description && c.description.toLowerCase().includes(query));
                const matchLvl = lvl === 'all' || c.level === lvl;
                return matchQuery && matchLvl;
            });
            renderCoursesTable(filtered);
        };
        if (searchCourse) searchCourse.addEventListener('input', handler);
        if (filterLevel) filterLevel.addEventListener('change', handler);
    }

    // Teachers Search
    const searchTeacher = document.getElementById('searchTeacherInput');
    if (searchTeacher) {
        searchTeacher.addEventListener('input', () => {
            const query = searchTeacher.value.toLowerCase();
            const filtered = cacheData.teachers.filter(t => {
                return t.name.toLowerCase().includes(query) || 
                       t.position.toLowerCase().includes(query) || 
                       (t.expertise && t.expertise.toLowerCase().includes(query));
            });
            renderTeachersTable(filtered);
        });
    }

    // Portfolios Search
    const searchPort = document.getElementById('searchPortInput');
    const filterPortLvl = document.getElementById('filterPortLevel');
    if (searchPort || filterPortLvl) {
        const handler = () => {
            const query = searchPort ? searchPort.value.toLowerCase() : '';
            const lvl = filterPortLvl ? filterPortLvl.value : 'all';
            const filtered = cacheData.portfolios.filter(p => {
                const matchQuery = p.title.toLowerCase().includes(query) || p.student_name.toLowerCase().includes(query);
                const matchLvl = lvl === 'all' || p.level === lvl;
                return matchQuery && matchLvl;
            });
            renderPortfoliosTable(filtered);
        };
        if (searchPort) searchPort.addEventListener('input', handler);
        if (filterPortLvl) filterPortLvl.addEventListener('change', handler);
    }

    // Users Search
    const searchUser = document.getElementById('searchUserInput');
    if (searchUser) {
        searchUser.addEventListener('input', () => {
            const query = searchUser.value.toLowerCase();
            const filtered = cacheData.users.filter(u => {
                return u.username.toLowerCase().includes(query) || 
                       u.fullname.toLowerCase().includes(query) || 
                       u.email.toLowerCase().includes(query);
            });
            renderUsersTable(filtered);
        });
    }

    // News Search
    const searchNews = document.getElementById('searchNewsInput');
    if (searchNews) {
        searchNews.addEventListener('input', () => {
            const query = searchNews.value.toLowerCase();
            const filtered = cacheData.news.filter(n => {
                return n.title.toLowerCase().includes(query) || 
                       (n.content && n.content.toLowerCase().includes(query)) ||
                       (n.category && n.category.toLowerCase().includes(query));
            });
            renderNewsTable(filtered);
        });
    }
}

async function sendPostData(endpoint, data, modalId, formEl) {
    try {
        const res = await fetch(`/api/index.php?endpoint=${endpoint}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.status === 'success') {
            showToast(json.message, 'success');
            closeAdminModal(modalId);
            formEl.reset();
            fetchAllData();
        } else {
            showToast(json.message || 'เกิดข้อผิดพลาด', 'error');
        }
    } catch (e) {
        showToast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
