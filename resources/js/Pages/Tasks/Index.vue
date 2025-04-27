<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from 'axios';
import draggable from 'vuedraggable';

const props = defineProps({ tasks: Array, projects: Array })

const localTasks = ref([...props.tasks ?? []]);
const form = ref({
  id: null,
  title: '',
  body: '',
  status: 'pending',
  priority: null,
  project_id: null,
  due_date: null,
});

const errors = ref({});
const isLoading = ref(false);

// Create or Update Task
const submit = async () => {
  errors.value = {};
  isLoading.value = true;
  try {
    await axios.get('/sanctum/csrf-cookie');
    if (form.value.id) {
      await axios.post(`/tasks-update/${form.value.id}`, form.value);
      toast.success("Task updated successfully!", { autoClose: 3000, position: "bottom-right", theme: "dark" });
    } else {
      await axios.post('/tasks', form.value);
      toast.success("Task created successfully!", { autoClose: 3000, position: "bottom-right", theme: "dark" });
    }
    closeModalAndReset();
    router.visit('/tasks');
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      console.error('Unexpected error:', error);
    }
  } finally {
    isLoading.value = false;
  }
};

// Edit Task
const editTask = (task) => {
  form.value = {
    id: task.id,
    title: task.title,
    body: task.body,
    status: task.status,
    project_id: task.project_id,
    due_date: task.due_date,
  };
  const modal = new bootstrap.Modal(document.getElementById('addTaskModal'));
  modal.show();
};

// Delete Task
const deleteTask = async (id) => {
  if (confirm('Are you sure you want to delete this task?')) {
    isLoading.value = true;
    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.post(`/tasks/${id}`);
      toast.success("Task deleted successfully!", { autoClose: 3000, position: "bottom-right", theme: "dark" });
      router.visit('/tasks');
    } catch (error) {
      console.error('Delete failed:', error);
    } finally {
      isLoading.value = false;
    }
  }
};

// Mark Task as Completed
const markComplete = async (task) => {
  if (confirm('Are you sure you want to complete this task?')) {
    isLoading.value = true;
    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.post(`/tasks-complete/${task.id}`, { ...task, status: 'completed' });
      toast.success("Task marked as completed!", { autoClose: 3000, position: "bottom-right", theme: "dark" });
      router.visit('/tasks');
    } catch (error) {
      console.error('Failed to complete task:', error);
    } finally {
      isLoading.value = false;
    }
  }
};

// Reorder Tasks
const onDragEnd = async () => {
  const orderedIds = localTasks.value.map(task => task.id);
  isLoading.value = true;
  try {
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/tasks-reorder', orderedIds);
    toast.success("Tasks reordered successfully!", { autoClose: 2000, position: "bottom-right", theme: "dark" });
  } catch (error) {
    console.error('Reordering failed:', error);
  } finally {
    isLoading.value = false;
  }
};

// Close Modal and Reset Form
const closeModalAndReset = () => {
  const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
  modal?.hide();
  form.value = {
    id: null,
    title: '',
    body: '',
    status: 'pending',
    priority: null,
    project_id: null,
    due_date: null,
  };
  errors.value = {};
};

// Logout
const logout = async () => {
  isLoading.value = true;
  try {
    await axios.post('/logout');
    router.visit('/');
  } catch (error) {
    console.error('Logout failed:', error);
  } finally {
    isLoading.value = false;
  }
};

// Go to Project Management
const task = async () => {
  router.visit('/project');
};

</script>



<template>
    <div class="container py-4">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📋 Task Management</h2>

          <button class="btn btn-outline-primary" @click="task" :disabled="isLoading">
            Go To Project Management
          </button>

          <button class="btn btn-outline-danger" @click="logout" :disabled="isLoading">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </button>
      </div>

      <!-- Add Task Button -->
      <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTaskModal" :disabled="isLoading">
          <i class="bi bi-plus-circle me-1"></i> Add Task
        </button>
      </div>

      <!-- Task List -->
      <div v-if="localTasks.length" class="list-group">
        <draggable v-model="localTasks" @end="onDragEnd" item-key="id">
          <template #item="{ element: task }">
            <div class="list-group-item border-start-4 border-primary py-3" :key="task.id">
              <div class="d-flex justify-content-between">
                <div>
                  <h5 class="mb-1">
                    <i class="bi bi-check2-square me-2 text-primary"></i>{{ task.title }}
                  </h5>
                  <p class="mb-1 text-muted">{{ task.body }}</p>
                  <small class="text-secondary">
                    <i class="bi bi-clock me-1"></i>Due: {{ task.due_date }} |
                    <span :class="{
                      'text-success': task.status === 'completed',
                      'text-warning': task.status === 'in_progress',
                      'text-danger': task.status === 'pending',
                    }">
                      <i class="bi bi-flag me-1"></i>Status: {{ task.status }} |
                    </span>
                    <span class="text-primary">
                        <i class="bi bi-lightning-fill me-1"></i>Project: {{ task.project?.name }}
                    </span>
                  </small>
                </div>
                <div class="btn-group align-self-start">
                  <button v-if="task.status !== 'completed'" class="btn btn-sm btn-outline-success" @click="markComplete(task)" title="Mark as Completed" :disabled="isLoading">
                    <i class="bi bi-check-circle"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-primary" title="Edit The Task" @click="editTask(task)" :disabled="isLoading">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" title="Delete The Task" @click="deleteTask(task.id)" :disabled="isLoading">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </template>
        </draggable>
      </div>

      <p v-else class="text-muted text-center mt-5">
        <i class="bi bi-inbox me-2"></i>No tasks found.
      </p>

      <!-- Add/Edit Task Modal -->
      <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form @submit.prevent="submit" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addTaskModalLabel">{{ form.id ? 'Edit Task' : 'Add Task' }}</h5>
              <button type="button" class="btn-close" @click="closeModalAndReset" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <!-- Title -->
              <div class="mb-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input v-model="form.title" class="form-control" />
                <div v-if="errors.title" class="text-danger mt-1">{{ errors.title[0] }}</div>
              </div>

              <!-- Body -->
              <div class="mb-3">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <textarea v-model="form.body" class="form-control"></textarea>
                <div v-if="errors.body" class="text-danger mt-1">{{ errors.body[0] }}</div>
              </div>

              <div class="row">
                <!-- Project -->
                <div class="col-md-4 mb-3">
                  <label class="form-label">Project <span class="text-danger">*</span></label>
                  <select v-model="form.project_id" class="form-select">
                    <option v-for="project in projects" :key="project.id" :value="project.id">
                      {{ project.name }}
                    </option>
                  </select>
                  <div v-if="errors.project_id" class="text-danger mt-1">{{ errors.project_id[0] }}</div>
                </div>

                <!-- Status -->
                <div class="col-md-4 mb-3">
                  <label class="form-label">Status</label>
                  <select v-model="form.status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                  </select>
                  <div v-if="errors.status" class="text-danger mt-1">{{ errors.status[0] }}</div>
                </div>

                <!-- Due Date -->
                <div class="col-md-4 mb-3">
                  <label class="form-label">Due Date</label>
                  <input type="date" v-model="form.due_date" class="form-control" />
                  <div v-if="errors.due_date" class="text-danger mt-1">{{ errors.due_date[0] }}</div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModalAndReset" data-bs-dismiss="modal" :disabled="isLoading">Cancel</button>
              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ form.id ? 'Update Task' : 'Add Task' }}
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </template>

