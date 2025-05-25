/* helpers ----------------------------------------------------------*/
const $ = s => document.querySelector(s);
const save = data => localStorage.setItem('tasks', JSON.stringify(data));
const load = () => JSON.parse(localStorage.getItem('tasks') || '[]');
const toast = msg => {
  const t = $("#toast");
  t.textContent = msg;
  t.hidden = false;
  setTimeout(() => t.hidden = true, 2500);
};

/* state ------------------------------------------------------------*/
let tasks = load();
let editing = null;

/* elements ---------------------------------------------------------*/
const list = $("#taskList");
const modal = $("#taskModal");
const mForm = $("#modalForm");
const mTitle = $("#mTitle");
const mDesc = $("#mDesc");
const mPri = $("#mPriority");
const mStat = $("#mStatus");
const mDue = $("#mDue");
const mAssignee = $("#mAssignee");
const counter = $("#taskCounter");

/* render -----------------------------------------------------------*/
function render() {
  list.innerHTML = "";
  const q = $("#searchBox").value.trim().toLowerCase();
  const data = tasks.filter(t => t.title.toLowerCase().includes(q));
  counter.textContent = `${data.length} task${data.length !== 1 ? 's' : ""}`;
  if (!data.length) {
    list.innerHTML = `<p style="color:#888">No tasks.</p>`;
    return;
  }

  data.forEach(t => {
    const card = document.createElement('div');
    card.className = 'task-card';
    card.innerHTML =
      `<div class="task-info">
        <strong>${t.title}</strong>
        ${t.desc ? `<p>${t.desc}</p>` : ""}
        <div class="meta">
          <span class="tag ${t.priority.toLowerCase()}">${t.priority}</span>
          <span class="tag ${t.status.toLowerCase().replace("‑", "-")}">${t.status}</span>
          ${t.assignee ? `<span>👤 ${t.assignee}</span>` : ""}
        </div>
        <small>Last updated: ${new Date(t.updated).toLocaleString()}</small>
     </div>
     <div class="actions">
       <button class="card-btn" data-act="edit"   title="Edit">✎</button>
       <button class="card-btn" data-act="delete" title="Delete">🗑</button>
     </div>`;
    card.onclick = e => {
      const act = e.target.dataset.act;
      if (!act) return;
      if (act === 'edit') openModal(t.id);
      if (act === 'delete') del(t.id);
    };
    list.append(card);
  });
}

/* crud -------------------------------------------------------------*/
function saveTask(obj) {
  obj.updated = new Date().toISOString();
  if (editing) {
    tasks = tasks.map(t => t.id === editing ? obj : t);
    toast("Task updated");
  } else {
    tasks.unshift(obj);
    toast("Task created");
  }
  save(tasks);
  render();
}

function del(id) {
  if (!confirm("Delete task?")) return;
  tasks = tasks.filter(t => t.id !== id);
  save(tasks);
  render();
  toast("Task deleted");
}

/* modal ------------------------------------------------------------*/
function openModal(id) {
  editing = id || null;
  $("#modalTitle").textContent = editing ? "Edit Task" : "Add Task";
  $("#saveBtn").textContent = editing ? "Update Task" : "Create Task";
  if (editing) {
    const t = tasks.find(x => x.id === id);
    mTitle.value = t.title;
    mDesc.value = t.desc || "";
    mPri.value = t.priority;
    mStat.value = t.status;
    mDue.value = t.due || "";
    mAssignee.value = t.assignee || "";
  } else {
    mForm.reset();
  }
  modal.showModal();
}

$("#openNewTask").onclick = () => openModal();
$("#closeModal").onclick = $("#cancelBtn").onclick = () => modal.close();

/* save */
mForm.onsubmit = e => {
  e.preventDefault();
  const title = mTitle.value.trim();
  const desc = mDesc.value.trim();
  const priority = mPri.value;
  const status = mStat.value;
  const due = mDue.value;
  const assignee = mAssignee.value.trim();

  const isDuplicate = tasks.some(t =>
    t.title.toLowerCase() === title.toLowerCase() && t.id !== editing
  );
  if (isDuplicate) {
    toast("Task title already exists!");
    return;
  }

  const obj = {
    id: editing || crypto.randomUUID(),
    title,
    desc,
    priority,
    status,
    due,
    assignee,
    updated: new Date().toISOString()
  };

  modal.close();
  saveTask(obj);
  editing = null;
};

/* search */
$("#searchBox").oninput = render;

/* init */
render();
